<?php

namespace Partymeister\Competitions\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Partymeister\Competitions\Models\Entry;

/**
 * Class EntrySaved
 *
 * @package Partymeister\Competitions\Events
 */
class EntrySaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Entry
     */
    public $entry;

    /**
     * Create a new event instance.
     *
     * EntrySaved constructor.
     *
     * @param Entry $entry
     */
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
