<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use App\Models\County;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'smegma',
            'email' => 'user@example.com',
            'password' => 'asd',
        ]);

        County::factory()->count(3)
                ->has(City::factory()->count(8))
                ->create();

    }
}
