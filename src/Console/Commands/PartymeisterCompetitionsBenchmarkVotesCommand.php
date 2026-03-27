<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Services\VoteService;

class PartymeisterCompetitionsBenchmarkVotesCommand extends Command
{
    protected $signature = 'partymeister:competitions:benchmark-votes
                            {--runs=3 : Number of runs to average}
                            {--save : Save baseline results to JSON file}';

    protected $description = 'Benchmark vote counting queries and record performance baseline';

    public function handle(): int
    {
        $runs = (int) $this->option('runs');

        $this->info("=== Vote Counting Performance Benchmark ===\n");

        // --- Table stats ---
        $this->tableStats();

        // --- Index analysis ---
        $this->indexAnalysis();

        // --- Individual query benchmarks ---
        $benchmarks = [];

        $this->info("\n--- Query Benchmarks ({$runs} runs each) ---\n");

        // 1. Full getAllVotesByRank (the main slow method)
        $benchmarks['getAllVotesByRank'] = $this->benchmark('getAllVotesByRank()', $runs, function () {
            return VoteService::getAllVotesByRank();
        });

        // 2. Full getAllSpecialVotesByRank
        $benchmarks['getAllSpecialVotesByRank'] = $this->benchmark('getAllSpecialVotesByRank()', $runs, function () {
            return VoteService::getAllSpecialVotesByRank();
        });

        // 3. Combined (what the API endpoint does)
        $benchmarks['resultsEndpoint'] = $this->benchmark('Results endpoint (both combined)', $runs, function () {
            $results = VoteService::getAllVotesByRank();
            $special = VoteService::getAllSpecialVotesByRank();
            return [$results, $special];
        });

        // 4. Single entry vote calculation (getVotesAttribute)
        $sampleEntry = Entry::whereHas('competition', fn ($q) => $q->where('has_prizegiving', true))
            ->where('status', 1)
            ->first();

        if ($sampleEntry) {
            $benchmarks['singleEntryVotes'] = $this->benchmark(
                "Single entry getVotesAttribute (entry #{$sampleEntry->id}: {$sampleEntry->title})",
                $runs,
                function () use ($sampleEntry) {
                    // Force fresh query each time
                    $entry = Entry::find($sampleEntry->id);
                    return $entry->votes;
                }
            );

            $benchmarks['singleEntrySpecialVotes'] = $this->benchmark(
                "Single entry getSpecialVotesAttribute (entry #{$sampleEntry->id})",
                $runs,
                function () use ($sampleEntry) {
                    $entry = Entry::find($sampleEntry->id);
                    return $entry->special_votes;
                }
            );

            $benchmarks['singleEntryComments'] = $this->benchmark(
                "Single entry getVoteCommentsAttribute (entry #{$sampleEntry->id})",
                $runs,
                function () use ($sampleEntry) {
                    $entry = Entry::find($sampleEntry->id);
                    return $entry->vote_comments;
                }
            );
        }

        // 5. Raw bulk query benchmarks (what optimized version would look like)
        $entryIds = Entry::where('status', 1)
            ->whereHas('competition', fn ($q) => $q->where('has_prizegiving', true))
            ->pluck('id')
            ->toArray();

        if (count($entryIds) > 0) {
            $benchmarks['bulkVisitorVotes'] = $this->benchmark(
                "Bulk visitor vote totals (" . count($entryIds) . " entries, single query)",
                $runs,
                function () use ($entryIds) {
                    return DB::table(
                        DB::raw('(SELECT entry_id, visitor_id, SUM(points)/COUNT(id) as points_per_visitor FROM votes WHERE entry_id IN (' . implode(',', $entryIds) . ') GROUP BY entry_id, visitor_id) as sub')
                    )
                        ->select('entry_id', DB::raw('SUM(points_per_visitor) as total_points'))
                        ->groupBy('entry_id')
                        ->get();
                }
            );

            $benchmarks['bulkManualVotes'] = $this->benchmark(
                "Bulk manual vote totals (" . count($entryIds) . " entries, single query)",
                $runs,
                function () use ($entryIds) {
                    return DB::table('manual_votes')
                        ->select('entry_id', DB::raw('SUM(points) as total_points'))
                        ->whereIn('entry_id', $entryIds)
                        ->groupBy('entry_id')
                        ->get();
                }
            );

            $benchmarks['bulkSpecialVotes'] = $this->benchmark(
                "Bulk special vote totals (" . count($entryIds) . " entries, single query)",
                $runs,
                function () use ($entryIds) {
                    return DB::table('votes')
                        ->select('entry_id', DB::raw('SUM(special_vote) as special_votes'))
                        ->whereIn('entry_id', $entryIds)
                        ->groupBy('entry_id')
                        ->get();
                }
            );

            $benchmarks['bulkComments'] = $this->benchmark(
                "Bulk vote comments (" . count($entryIds) . " entries, single query)",
                $runs,
                function () use ($entryIds) {
                    return DB::table('votes')
                        ->select('entry_id', 'comment')
                        ->whereIn('entry_id', $entryIds)
                        ->where('comment', '!=', '')
                        ->get();
                }
            );
        }

        // 6. Query count measurement
        $this->info("\n--- Query Count for getAllVotesByRank() ---\n");
        DB::enableQueryLog();
        DB::flushQueryLog();
        $resultsData = VoteService::getAllVotesByRank();
        $queryLog = DB::getQueryLog();
        DB::disableQueryLog();

        $queryCount = count($queryLog);
        $totalQueryTime = array_sum(array_column($queryLog, 'time'));
        $benchmarks['queryCount'] = $queryCount;
        $benchmarks['totalQueryTimeMs'] = round($totalQueryTime, 2);

        $this->warn("  Total queries executed: {$queryCount}");
        $this->warn("  Total DB time (sum of all queries): {$totalQueryTime}ms");

        // Group queries by pattern
        $patterns = [];
        foreach ($queryLog as $q) {
            $sql = preg_replace('/\b\d+\b/', '?', $q['query']);
            $sql = preg_replace("/'.+?'/", '?', $sql);
            $key = substr($sql, 0, 80);
            if (!isset($patterns[$key])) {
                $patterns[$key] = ['count' => 0, 'total_time' => 0];
            }
            $patterns[$key]['count']++;
            $patterns[$key]['total_time'] += $q['time'];
        }

        arsort($patterns);
        $this->info("\n  Top query patterns:");
        $i = 0;
        foreach ($patterns as $pattern => $stats) {
            if ($i++ >= 10) break;
            $avgTime = round($stats['total_time'] / $stats['count'], 2);
            $this->line("    [{$stats['count']}x, avg {$avgTime}ms] {$pattern}...");
        }

        // --- Vote data integrity snapshot ---
        $this->info("\n--- Vote Data Snapshot ---\n");
        $snapshot = $this->voteDataSnapshot($resultsData);
        $benchmarks['snapshot'] = $snapshot;

        // --- Save results ---
        if ($this->option('save')) {
            $output = [
                'timestamp' => now()->toIso8601String(),
                'benchmarks' => $benchmarks,
            ];

            $path = storage_path('vote-benchmark-baseline.json');
            file_put_contents($path, json_encode($output, JSON_PRETTY_PRINT));
            $this->info("\nBaseline saved to: {$path}");
        }

        return 0;
    }

    private function tableStats(): void
    {
        $this->info("--- Table Statistics ---\n");

        $tables = [
            'votes' => 'Visitor votes',
            'manual_votes' => 'Manual/jury votes',
            'live_votes' => 'Live votes',
            'entries' => 'Entries (total)',
            'competitions' => 'Competitions',
            'visitors' => 'Visitors',
        ];

        foreach ($tables as $table => $label) {
            try {
                $count = DB::table($table)->count();
                $this->line("  {$label} ({$table}): {$count}");
            } catch (\Exception $e) {
                $this->line("  {$label} ({$table}): ERROR - " . $e->getMessage());
            }
        }

        // Qualified entries (status=1)
        $qualifiedCount = DB::table('entries')->where('status', 1)->count();
        $this->line("  Qualified entries (status=1): {$qualifiedCount}");

        // Competitions with prizegiving
        $pgCount = DB::table('competitions')->where('has_prizegiving', true)->count();
        $this->line("  Competitions with prizegiving: {$pgCount}");

        // Unique voters
        $uniqueVoters = DB::table('votes')->distinct('visitor_id')->count('visitor_id');
        $this->line("  Unique voters: {$uniqueVoters}");
    }

    private function indexAnalysis(): void
    {
        $this->info("\n--- Index Analysis ---\n");

        $missing = [];

        // Check votes table - the hot query groups by (entry_id, visitor_id)
        $votesIndexes = collect(DB::select('SHOW INDEX FROM votes'))->pluck('Column_name', 'Key_name')->toArray();
        $votesIndexNames = collect(DB::select('SHOW INDEX FROM votes'))->groupBy('Key_name');

        $hasEntryVisitorComposite = false;
        $hasEntrySpecialVote = false;
        $hasEntryComment = false;

        foreach ($votesIndexNames as $name => $columns) {
            $cols = $columns->pluck('Column_name')->toArray();
            if (in_array('entry_id', $cols) && in_array('visitor_id', $cols)) {
                $hasEntryVisitorComposite = true;
            }
            if (in_array('entry_id', $cols) && in_array('special_vote', $cols)) {
                $hasEntrySpecialVote = true;
            }
            if (in_array('entry_id', $cols) && in_array('comment', $cols)) {
                $hasEntryComment = true;
            }
        }

        if (!$hasEntryVisitorComposite) {
            $missing[] = [
                'table' => 'votes',
                'columns' => 'entry_id, visitor_id',
                'reason' => 'getVotesAttribute groups by (entry_id, visitor_id) - composite index covers the hot path',
                'sql' => 'CREATE INDEX idx_votes_entry_visitor ON votes (entry_id, visitor_id)',
            ];
        }

        if (!$hasEntrySpecialVote) {
            $missing[] = [
                'table' => 'votes',
                'columns' => 'entry_id, special_vote',
                'reason' => 'getSpecialVotesAttribute sums special_vote filtered by entry_id',
                'sql' => 'CREATE INDEX idx_votes_entry_special ON votes (entry_id, special_vote)',
            ];
        }

        // Check entries table for status + competition_id composite
        $entriesIndexNames = collect(DB::select('SHOW INDEX FROM entries'))->groupBy('Key_name');
        $hasStatusCompetition = false;
        foreach ($entriesIndexNames as $name => $columns) {
            $cols = $columns->pluck('Column_name')->toArray();
            if (in_array('status', $cols) && in_array('competition_id', $cols)) {
                $hasStatusCompetition = true;
            }
        }

        if (!$hasStatusCompetition) {
            $missing[] = [
                'table' => 'entries',
                'columns' => 'competition_id, status',
                'reason' => 'Entries are always filtered by competition_id + status=1',
                'sql' => 'CREATE INDEX idx_entries_competition_status ON entries (competition_id, status)',
            ];
        }

        // Check manual_votes - entry_id index exists but covering index may help
        // Already has single entry_id index, which is fine for the simple SUM query

        if (empty($missing)) {
            $this->info("  All recommended indexes are present.");
        } else {
            $this->warn("  Missing recommended indexes:\n");
            foreach ($missing as $idx) {
                $this->line("  Table: {$idx['table']}");
                $this->line("  Columns: {$idx['columns']}");
                $this->line("  Reason: {$idx['reason']}");
                $this->line("  SQL: {$idx['sql']}");
                $this->line("");
            }
        }

        // Show existing indexes for reference
        $this->info("  Existing indexes on votes:");
        foreach ($votesIndexNames as $name => $columns) {
            $cols = $columns->pluck('Column_name')->implode(', ');
            $this->line("    {$name}: ({$cols})");
        }

        $this->info("  Existing indexes on entries:");
        foreach ($entriesIndexNames as $name => $columns) {
            $cols = $columns->pluck('Column_name')->implode(', ');
            $this->line("    {$name}: ({$cols})");
        }

        $manualVotesIndexNames = collect(DB::select('SHOW INDEX FROM manual_votes'))->groupBy('Key_name');
        $this->info("  Existing indexes on manual_votes:");
        foreach ($manualVotesIndexNames as $name => $columns) {
            $cols = $columns->pluck('Column_name')->implode(', ');
            $this->line("    {$name}: ({$cols})");
        }
    }

    private function benchmark(string $label, int $runs, callable $fn): array
    {
        $times = [];
        $result = null;

        for ($i = 0; $i < $runs; $i++) {
            $start = microtime(true);
            $result = $fn();
            $elapsed = (microtime(true) - $start) * 1000;
            $times[] = $elapsed;
        }

        $avg = round(array_sum($times) / count($times), 2);
        $min = round(min($times), 2);
        $max = round(max($times), 2);

        $this->line("  {$label}");
        $this->line("    avg: {$avg}ms | min: {$min}ms | max: {$max}ms");

        return [
            'avg_ms' => $avg,
            'min_ms' => $min,
            'max_ms' => $max,
            'runs' => $runs,
        ];
    }

    private function voteDataSnapshot(array $resultsData): array
    {
        $snapshot = [
            'competitions' => [],
            'totals' => [
                'competitions' => 0,
                'entries' => 0,
                'total_points' => 0,
                'total_votes_rows' => DB::table('votes')->count(),
                'total_manual_votes_rows' => DB::table('manual_votes')->count(),
                'total_special_votes' => DB::table('votes')->where('special_vote', true)->count(),
                'unique_voters' => DB::table('votes')->distinct('visitor_id')->count('visitor_id'),
            ],
        ];

        foreach ($resultsData as $compId => $comp) {
            $compSnapshot = [
                'id' => $comp['id'],
                'name' => $comp['name'],
                'entry_count' => count($comp['entries']),
                'entries' => [],
            ];

            $compTotalPoints = 0;
            foreach ($comp['entries'] as $entry) {
                $compSnapshot['entries'][] = [
                    'id' => $entry['id'],
                    'title' => $entry['title'],
                    'author' => $entry['author'],
                    'points' => $entry['points'],
                    'rank' => $entry['rank'],
                    'tie' => $entry['tie'],
                ];
                $compTotalPoints += $entry['points'];
            }

            $compSnapshot['total_points'] = $compTotalPoints;
            $snapshot['competitions'][] = $compSnapshot;
            $snapshot['totals']['competitions']++;
            $snapshot['totals']['entries'] += count($comp['entries']);
            $snapshot['totals']['total_points'] += $compTotalPoints;
        }

        $this->line("  Competitions with results: {$snapshot['totals']['competitions']}");
        $this->line("  Total entries scored: {$snapshot['totals']['entries']}");
        $this->line("  Total vote rows: {$snapshot['totals']['total_votes_rows']}");
        $this->line("  Total manual vote rows: {$snapshot['totals']['total_manual_votes_rows']}");
        $this->line("  Total special votes: {$snapshot['totals']['total_special_votes']}");
        $this->line("  Unique voters: {$snapshot['totals']['unique_voters']}");

        foreach ($snapshot['competitions'] as $comp) {
            $this->line("  [{$comp['name']}] {$comp['entry_count']} entries, {$comp['total_points']} total points");
        }

        return $snapshot;
    }
}
