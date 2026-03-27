<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\VoteCategory;

class VoteCategoryFactory extends Factory
{
    protected $model = VoteCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'points' => $this->faker->numberBetween(1, 10),
            'has_negative' => false,
            'has_comment' => false,
            'has_special_vote' => false,
        ];
    }
}
