<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\OptionGroup;

class OptionGroupFactory extends Factory
{
    protected $model = OptionGroup::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'type' => $this->faker->randomElement(['single', 'multiple']),
        ];
    }
}
