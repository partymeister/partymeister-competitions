<?php

namespace Partymeister\Competitions\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Partymeister\Competitions\Models\Competition;

/**
 * Class CompetitionSaved
 */
class CompetitionSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Competition
     */
    public $competition;

    /**
     * Create a new event instance.
     *
     * CompetitionSaved constructor.
     *
     * @param  Competition  $competition
     */
    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
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
