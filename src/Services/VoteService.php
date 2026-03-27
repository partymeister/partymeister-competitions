<?php

namespace Partymeister\Competitions\Services;

use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\ManualVote;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Models\VoteCategory;
use Partymeister\Core\Models\Visitor;
use Request;

/**
 * Class VoteService
 */
class VoteService extends BaseService
{
    /**
     * Submit a vote for an entry.
     *
     * @return array{success: bool, message: string, status?: int}
     */
    public static function submitVote(
        Visitor $visitor,
        mixed $entryId,
        mixed $voteCategoryId,
        int $points,
        string $comment = '',
        ?bool $specialVote = null,
        bool $isLive = false,
        ?string $ipAddress = null,
    ): array {
        $entry = Entry::find($entryId);
        $voteCategory = VoteCategory::find($voteCategoryId);

        if (is_null($entry) || is_null($voteCategory)) {
            return ['success' => false, 'message' => 'Entry or vote category not found', 'status' => 404];
        }

        $competition = $entry->competition;

        if (is_null($competition)) {
            return ['success' => false, 'message' => 'Competition not found', 'status' => 404];
        }

        // Check voting is enabled (skip for live voting)
        if (! $isLive && ! $competition->voting_enabled) {
            return ['success' => false, 'message' => 'Voting for this competition is not available yet!', 'status' => 403];
        }

        // Check deadline
        if (strtotime(config('partymeister-competitions-voting.deadline')) < time()) {
            return ['success' => false, 'message' => 'Voting deadline is over, sorry :/', 'status' => 403];
        }

        // Cap points to category max
        $points = min($points, $voteCategory->points);

        // Handle special vote — clear previous if setting a new one
        if ($specialVote) {
            foreach ($visitor->votes()->where('special_vote', true)->get() as $existingVote) {
                $existingVote->special_vote = false;
                $existingVote->save();
            }
        }

        // Find or create vote
        $vote = $visitor->votes()
            ->where('vote_category_id', $voteCategoryId)
            ->where('entry_id', $entryId)
            ->first();

        if (is_null($vote)) {
            $vote = new Vote();
            $vote->visitor_id = $visitor->id;
            $vote->competition_id = $competition->id;
            $vote->entry_id = $entryId;
            $vote->ip_address = $ipAddress;
        }

        $vote->points = $points;
        $vote->vote_category_id = $voteCategoryId;
        $vote->comment = $comment;

        if ($specialVote !== null) {
            $vote->special_vote = $specialVote;
        }

        $vote->save();

        return [
            'success' => true,
            'message' => 'You voted for '.$entry->title.' in the '.$competition->name.' competition!',
        ];
    }

    /**
     * @var string
     */
    protected string $model = Vote::class;

    /**
     * @param $request
     */
    public static function addVotes($request)
    {
        foreach ($request->get('entry') as $competitionId => $entries) {
            foreach ($entries as $entryId => $points) {
                if ((int) $points !== 0) {
                    $mv = new ManualVote();
                    $mv->competition_id = $competitionId;
                    $mv->entry_id = $entryId;
                    $mv->points = $points;
                    $mv->ip_address = Request::ip();
                    $mv->save();
                }
            }
        }
    }

    /**
     * @return \League\Csv\Writer
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     * @throws \League\Csv\InvalidArgument
     */
    public static function exportCSV()
    {
        $results = VoteService::getAllVotesByRank();

        $header = [
            'COMPETITION',
            'RANK',
            'POINTS',
            'TITLE',
            'AUTHOR',
            'REMOTE',
        ];

        $records = [];

        foreach ($results as $competition) {
            foreach ($competition['entries'] as $entry) {
                $record = [
                    $competition['name'],
                    $entry['rank'],
                    $entry['points'],
                    $entry['title'],
                    $entry['author'],
                    $entry['remote_type'],
                ];

                $records[] = $record;
            }
        }

        //load the CSV document from a string
        $csv = Writer::createFromString();
        $csv->setEnclosure('"');
        $csv->setDelimiter(';');

        //insert the header
        $csv->insertOne($header);

        //insert all the records
        $csv->insertAll($records);

        return $csv;
    }

    /**
     * @param string $direction
     * @return array
     */
    public static function getAllVotesByRank($direction = 'DESC')
    {
        $results = [];

        // Eager load competitions with their relations in 2 queries instead of N+1
        $competitionsWithPG = Competition::where('has_prizegiving', true)
            ->with(['competition_type', 'vote_categories'])
            ->orderBy('prizegiving_sort_position', $direction)
            ->get();

        $competitionsWithOOCVoting = Competition::where('has_prizegiving', false)
            ->with(['competition_type', 'vote_categories'])
            ->get()
            ->filter(fn ($c) => $c->competition_type && $c->competition_type->has_out_of_competition_voting);

        $competitions = $competitionsWithPG->merge($competitionsWithOOCVoting);

        // Load all qualified entries for these competitions in one query, with visitor.access_key eager loaded
        $competitionIds = $competitions->pluck('id');
        $allEntries = Entry::whereIn('competition_id', $competitionIds)
            ->where('status', 1)
            ->with('visitor.access_key')
            ->get()
            ->groupBy('competition_id');

        // Collect all entry IDs for bulk vote queries
        $allEntryIds = $allEntries->flatten()->pluck('id')->all();

        if (count($allEntryIds) > 0) {
            // Bulk: visitor vote totals (SUM per visitor, then SUM across visitors)
            $voteTotals = DB::table(
                DB::raw('(SELECT entry_id, visitor_id, SUM(points)/COUNT(id) as points_per_visitor FROM votes WHERE entry_id IN (' . implode(',', $allEntryIds) . ') GROUP BY entry_id, visitor_id) as sub')
            )
                ->select('entry_id', DB::raw('SUM(points_per_visitor) as total_points'))
                ->groupBy('entry_id')
                ->pluck('total_points', 'entry_id');

            // Bulk: manual vote totals
            $manualVoteTotals = DB::table('manual_votes')
                ->select('entry_id', DB::raw('SUM(points) as total_points'))
                ->whereIn('entry_id', $allEntryIds)
                ->groupBy('entry_id')
                ->pluck('total_points', 'entry_id');

            // Bulk: vote comments
            $allComments = DB::table('votes')
                ->select('entry_id', 'comment')
                ->whereIn('entry_id', $allEntryIds)
                ->where('comment', '!=', '')
                ->get()
                ->groupBy('entry_id')
                ->map(fn ($rows) => $rows->pluck('comment')->toArray());
        } else {
            $voteTotals = collect();
            $manualVoteTotals = collect();
            $allComments = collect();
        }

        foreach ($competitions as $competition) {
            $results[$competition->id] = [
                'id'          => $competition->id,
                'name'        => $competition->name,
                'has_comment' => isset($competition->vote_categories[0]) ? (bool) $competition->vote_categories[0]->has_comment : false,
                'entries'     => [],
            ];
            $maxPoints = 0;
            $entries = $allEntries->get($competition->id, collect());

            foreach ($entries as $entry) {
                $points = ($voteTotals[$entry->id] ?? 0) + ($manualVoteTotals[$entry->id] ?? 0);
                $maxPoints = max($points, $maxPoints);

                $results[$competition->id]['entries'][$entry->id] = [
                    'id'                        => $entry->id,
                    'title'                     => $entry->title,
                    'author'                    => $entry->author,
                    'author_name'               => $entry->author_name,
                    'author_address'            => $entry->author_address,
                    'author_city'               => $entry->author_city,
                    'author_zip'                => $entry->author_zip,
                    'author_country_iso_3166_1' => $entry->author_country_iso_3166_1,
                    'author_email'              => $entry->author_email,
                    'author_phone'              => $entry->author_phone,
                    'remote_type'               => $entry->remote_type,

                    'points'   => $points,
                    'comments' => $allComments[$entry->id] ?? [],
                    'tie'      => false,
                ];
            }

            // Sort by points
            usort($results[$competition->id]['entries'], function ($item1, $item2) {
                return $item2['points'] <=> $item1['points'];
            });

            $uniquePoints = [];

            foreach ($results[$competition->id]['entries'] as $key => $entry) {
                $pointsKey = (string) $entry['points'];
                if (! array_key_exists($pointsKey, $uniquePoints)) {
                    $uniquePoints[$pointsKey] = 1;
                    $rank = array_sum($uniquePoints);
                } else {
                    $uniquePoints[$pointsKey]++;
                }

                $results[$competition->id]['entries'][$key]['max_points'] = $maxPoints;
                $results[$competition->id]['entries'][$key]['rank'] = $rank;

                // Identify ties
                if (isset($results[$competition->id]['entries'][$key - 1])) {
                    if ($results[$competition->id]['entries'][$key]['points'] == $results[$competition->id]['entries'][$key - 1]['points']) {
                        $results[$competition->id]['entries'][$key]['tie'] = true;
                        $results[$competition->id]['entries'][$key - 1]['tie'] = true;
                    }
                }
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    public static function getAllSpecialVotesByRank()
    {
        // Eager load competitions with relations
        $competitions = Competition::where('has_prizegiving', true)
            ->with(['competition_type', 'vote_categories'])
            ->orderBy('prizegiving_sort_position', 'DESC')
            ->get()
            ->filter(fn ($c) => isset($c->vote_categories[0]) && $c->vote_categories[0]->has_special_vote);

        // Load all qualified entries for these competitions
        $competitionIds = $competitions->pluck('id');
        $allEntries = Entry::whereIn('competition_id', $competitionIds)
            ->where('status', 1)
            ->with('visitor.access_key')
            ->get();

        $allEntryIds = $allEntries->pluck('id')->all();

        // Bulk: special vote totals
        if (count($allEntryIds) > 0) {
            $specialVoteTotals = DB::table('votes')
                ->select('entry_id', DB::raw('SUM(special_vote) as special_votes'))
                ->whereIn('entry_id', $allEntryIds)
                ->groupBy('entry_id')
                ->pluck('special_votes', 'entry_id');
        } else {
            $specialVoteTotals = collect();
        }

        // Build a competition name lookup for entries
        $competitionNames = $competitions->pluck('name', 'id');

        $results = [];
        $maxPoints = 0;

        foreach ($allEntries as $entry) {
            $specialVotes = (int) ($specialVoteTotals[$entry->id] ?? 0);
            $maxPoints = max($specialVotes, $maxPoints);
            if ($specialVotes > 0) {
                $results[] = [
                    'id'            => $entry->id,
                    'title'         => $entry->title,
                    'author'        => $entry->author,
                    'competition'   => $competitionNames[$entry->competition_id] ?? '',
                    'special_votes' => $specialVotes,
                    'points'        => $specialVotes,
                    'remote_type'   => $entry->remote_type,
                    'tie'           => false,
                ];
            }
        }

        // Sort by special votes descending
        usort($results, function ($item1, $item2) {
            return $item2['special_votes'] <=> $item1['special_votes'];
        });

        // Assign ranks and detect ties
        foreach ($results as $key => $entry) {
            $results[$key]['max_points'] = $maxPoints;
            $results[$key]['rank'] = $key + 1;

            if (isset($results[$key - 1]) && $results[$key]['points'] == $results[$key - 1]['points']) {
                $results[$key]['tie'] = true;
                $results[$key - 1]['tie'] = true;
                $results[$key]['rank'] = $results[$key - 1]['rank'];
            }
        }

        return $results;
    }
}
