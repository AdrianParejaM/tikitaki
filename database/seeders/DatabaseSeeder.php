<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            ClubSeeder::class,
            LeagueSeeder::class,
            League_UserSeeder::class,
            LineUpSeeder::class,
            PlayerSeeder::class,
            User_PlayerSeeder::class
        ]);

    }
}
