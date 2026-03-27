<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionType;

class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'competition_type_id' => CompetitionType::factory(),
            'sort_position' => $this->faker->numberBetween(0, 100),
            'prizegiving_sort_position' => $this->faker->numberBetween(0, 100),
            'has_prizegiving' => false,
            'upload_enabled' => false,
            'voting_enabled' => false,
        ];
    }
}
