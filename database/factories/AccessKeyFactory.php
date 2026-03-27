<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\AccessKey;

class AccessKeyFactory extends Factory
{
    protected $model = AccessKey::class;

    public function definition(): array
    {
        return [
            'access_key' => strtoupper($this->faker->unique()->bothify('????-????')),
            'ip_address' => $this->faker->ipv4(),
            'is_remote' => false,
            'is_satellite' => false,
            'is_prepaid' => false,
        ];
    }
}
