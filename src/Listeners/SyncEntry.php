<?php

namespace Partymeister\Competitions\Listeners;

use Partymeister\Competitions\Events\EntrySaved;

/**
 * Class SyncEntry
 *
 * @package Partymeister\Competitions\Listeners
 */
class SyncEntry
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param EntrySaved $event
     */
    public function handle(EntrySaved $event)
    {
        \Partymeister\Competitions\Jobs\SyncEntry::dispatch($event->entry);
    }
}
