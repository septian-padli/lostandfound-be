<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\User;
use App\Models\Category;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'id_category' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'id_city' => City::inRandomOrder()->first()?->id ?? City::factory(),
            'id_province' => Province::inRandomOrder()->first()?->id ?? Province::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'is_found' => $isFound = $this->faker->boolean(30),
            'found_at' => $isFound ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
            'is_active' => $this->faker->boolean(90),
            'count_comment' => $this->faker->numberBetween(0, 20),
        ];
    }
}
