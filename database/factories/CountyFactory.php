<?php

namespace Database\Factories;

use App\Models\County;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountyFactory extends Factory
{
    protected $model = County::class;

    public function definition(): array
    {
        return [
            'badge' => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSEuKM856QGF6rnISEmWNxjczTyukPGd2M9LYMGzmAGOYA4F6V-3CmuCWE&s=10",
            'name' => fake()->unique()->city(),
        ];
    }
}