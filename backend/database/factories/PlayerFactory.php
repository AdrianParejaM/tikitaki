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
            'name_player' => fake()->userName(),
            'position' => fake()->randomElement(['Goalkeeper', 'Defender', 'Midfielder', 'Forward']),
            'market_value' => fake()->randomNumber(),
            'club_id'=> DB::table('clubs')->inRandomOrder()->firstOrFail('id')->id
        ];
    }
}
