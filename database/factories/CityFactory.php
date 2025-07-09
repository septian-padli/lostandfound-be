<?php

namespace Database\Factories;

use App\Models\Province;
use Illuminate\Support\Str;
use Database\Factories\ProvinceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random city name and slug
        $cityName = fake()->city();
        return [
            'name' => $cityName,
            'slug' => Str::slug($cityName),
            'id_province' => null,
        ];
    }
}
