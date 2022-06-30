<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['name' => "string"])]
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
