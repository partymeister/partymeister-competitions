<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Models\VoteCategory;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'entry_id' => Entry::factory(),
            'visitor_id' => null,
            'vote_category_id' => VoteCategory::factory(),
            'points' => $this->faker->numberBetween(1, 10),
            'special_vote' => false,
            'comment' => '',
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}
