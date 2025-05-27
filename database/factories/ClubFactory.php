<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_club' => fake()->city().' FC',
            'city' => fake()->city(),
            'foundation' => fake()->date(),
            'api_id' => fake()->unique()->randomNumber(5),
            'image' => fake()->imageUrl(200, 200, 'sports'),
            'venue_name' => fake()->company().' Stadium'
        ];
    }
}
