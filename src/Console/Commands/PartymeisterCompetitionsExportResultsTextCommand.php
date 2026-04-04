<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Partymeister\Competitions\Services\VoteService;

class PartymeisterCompetitionsExportResultsTextCommand extends Command
{
    protected $signature = 'partymeister:competitions:export-results-text
                            {--filter= : Filter competitions by name}';

    protected $description = 'Export competition results as formatted text';

    public function handle()
    {
        $results = VoteService::getAllVotesByRank();
        $filter = $this->option('filter');
        $output = '';

        foreach ($results as $competition) {
            if ($filter && stripos($competition['name'], $filter) === false) {
                continue;
            }

            $output .= $competition['name'] . ":\n";

            foreach ($competition['entries'] as $entry) {
                $rank = $entry['rank'] < 10 ? 'o' . $entry['rank'] : (string) $entry['rank'];
                $points = $entry['points'] . 'pts';
                $left = "{$rank}. {$points} {$entry['title']} ";
                $right = " {$entry['author']}";
                $dots = max(3, 60 - strlen($left) - strlen($right));
                $output .= $left . str_repeat('.', $dots) . $right . "\n";
            }

            $output .= "\n";
        }

        $filename = storage_path('app/results.txt');
        file_put_contents($filename, $output);

        $this->line($output);
        $this->info("Written to {$filename}");
    }
}
