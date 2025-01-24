<?php

namespace Database\Seeders;

use GuzzleHttp\Psr7\LazyOpenStream;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class League_UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('league_user')->insert([
                'user_id' => DB::table('users')->inRandomOrder()->firstOrFail('id')->id,
                'league_id' => DB::table('leagues')->inRandomOrder()->firstOrFail('id')->id,
                'role' => fake()->randomElement(['Admin', 'Player']),
                'union_date' => fake()->date(),
            ]);
        }
    }
}
