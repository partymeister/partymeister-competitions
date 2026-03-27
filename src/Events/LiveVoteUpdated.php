<?php

namespace Partymeister\Competitions\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Partymeister\Competitions\Models\LiveVote;

/**
 * Class LiveVoteUpdated
 */
class LiveVoteUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var LiveVote
     */
    public $liveVote;

    /**
     * Create a new event instance.
     *
     * LiveVoteUpdated constructor.
     */
    public function __construct(LiveVote $liveVote)
    {
        $this->liveVote = $liveVote;
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
