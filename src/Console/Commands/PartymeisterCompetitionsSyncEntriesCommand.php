<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Partymeister\Competitions\Events\EntrySaved;
use Partymeister\Competitions\Models\Competition;

/**
 * Class PartymeisterCompetitionsSyncEntriesCommand
 *
 * @package Partymeister\Competitions\Console\Commands
 */
class PartymeisterCompetitionsSyncEntriesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:competitions:sync-entries';

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
        foreach (Competition::all() as $competition) {
            foreach ($competition->entries()
                                 ->get() as $entry) {
                event(new EntrySaved($entry));
            }
        }
    }
}
