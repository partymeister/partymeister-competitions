<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\LiveVote;

class LiveVoteFactory extends Factory
{
    protected $model = LiveVote::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'entry_id' => Entry::factory(),
            'sort_position' => $this->faker->numberBetween(1, 100),
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name(),
        ];
    }
}
