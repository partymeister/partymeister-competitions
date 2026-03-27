<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\ManualVote;

class ManualVoteFactory extends Factory
{
    protected $model = ManualVote::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'entry_id' => Entry::factory(),
            'points' => $this->faker->numberBetween(1, 10),
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}
