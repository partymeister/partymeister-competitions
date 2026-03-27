<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;

class CompetitionPrizeFactory extends Factory
{
    protected $model = CompetitionPrize::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'amount' => $this->faker->randomElement(['100', '50', '25']),
            'additional' => $this->faker->optional()->sentence(),
            'rank' => $this->faker->numberBetween(1, 3),
        ];
    }
}
