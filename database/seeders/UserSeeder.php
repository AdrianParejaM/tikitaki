<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'nickname' => 'Calamardo',
            'name' => 'Adrian',
            'email' => 'adrian@gmail.com',
            'password' => Hash::make('123456789')
        ])->assignRole('Admin');

        User::factory()->create([
            'nickname' => 'ElZetas',
            'name' => 'Pepe',
            'email' => 'elzetas@gmail.com',
            'password' => Hash::make('987654321')
        ])->assignRole('Player');

        User::factory(50)->create()->each(function ($user){
            $user->assignRole('Player');
        });
    }
}
