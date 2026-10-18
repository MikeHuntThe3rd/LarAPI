<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\County;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'zip_code' => fake()->numberBetween(1000, 99999),
            'city' => fake()->unique()->city(),
            'population' => fake()->numberBetween(500, 2_000_000),
            'id_county' => County::factory(),
        ];
    }
}