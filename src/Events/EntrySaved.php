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
