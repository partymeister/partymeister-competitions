<?php

namespace Partymeister\Competitions\Console\Commands;

use Illuminate\Console\Command;
use Partymeister\Competitions\Events\LiveVoteUpdated;
use Partymeister\Competitions\Models\LiveVote;

/**
 * Class PartymeisterCompetitionsSyncLiveVotingCommand
 *
 * @package Partymeister\Competitions\Console\Commands
 */
class PartymeisterCompetitionsSyncLiveVotingCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:competitions:sync-livevoting';

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
        event(new LiveVoteUpdated(LiveVote::first()));
    }
}
