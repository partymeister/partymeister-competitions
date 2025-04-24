<?php

namespace Partymeister\Competitions\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Partymeister\Competitions\Events\CompetitionSaved' => [
            'Partymeister\Competitions\Listeners\SyncCompetition',
        ],
        'Partymeister\Competitions\Events\EntrySaved' => [
            'Partymeister\Competitions\Listeners\SyncEntry',
        ],
        'Partymeister\Competitions\Events\LiveVoteUpdated' => [
            'Partymeister\Competitions\Listeners\SyncLiveVote',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
