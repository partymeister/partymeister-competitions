<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\CompetitionType;

class CompetitionTypeFactory extends Factory
{
    protected $model = CompetitionType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
        ];
    }
}
