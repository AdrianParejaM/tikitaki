<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_player' => fake()->firstName().' '.fake()->lastName(),
            'position' => fake()->randomElement(['Goalkeeper', 'Defender', 'Midfielder', 'Forward']),
            'market_value' => fake()->numberBetween(100000, 5000000),
            'club_id' => \App\Models\Club::factory(),
            'api_id' => fake()->unique()->randomNumber(6),
            'nationality' => fake()->countryCode(),
            'image' => fake()->imageUrl(200, 200, 'people')
        ];
    }
}
