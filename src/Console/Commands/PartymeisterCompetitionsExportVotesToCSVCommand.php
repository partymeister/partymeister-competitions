<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Writer;
use Partymeister\Competitions\Services\VoteService;

/**
 * Class PartymeisterCompetitionsExportVotesToCSVCommand
 */
class PartymeisterCompetitionsExportVotesToCSVCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:competitions:export-votes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all entries';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $results = VoteService::getAllVotesByRank();

        $header = [
            'COMPETITION',
            'RANK',
            'POINTS',
            'TITLE',
            'AUTHOR',
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

        file_put_contents('votes.csv', $csv->toString());
    }
}
