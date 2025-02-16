<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\LineUp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LineUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LineUp::factory(50)->create();

    }
}
