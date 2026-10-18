<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        County::factory()->count(3)
                ->has(City::factory()->count(8))
                ->create();
    }
}
