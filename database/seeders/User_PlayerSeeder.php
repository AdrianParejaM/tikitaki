<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User_PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('user_player')->insert([
                'user_id' => DB::table('users')->inRandomOrder()->firstOrFail('id')->id,
                'player_id' => DB::table('players')->inRandomOrder()->firstOrFail('id')->id,
                'date_signing' => fake()->date(),
            ]);
        }
    }
}
