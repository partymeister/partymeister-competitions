<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
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
        $csv = VoteService::exportCSV(false);

        file_put_contents('votes.csv', $csv->toString());
    }
}
