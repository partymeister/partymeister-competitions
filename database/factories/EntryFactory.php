<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;

class EntryFactory extends Factory
{
    protected $model = Entry::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name(),
            'description' => '',
            'organizer_description' => '',
            'custom_option' => '',
            'sort_position' => $this->faker->numberBetween(0, 100),
            'status' => 0,
            'ip_address' => $this->faker->ipv4(),
        ];
    }

    public function qualified(): static
    {
        return $this->state(fn () => ['status' => 1]);
    }
}
