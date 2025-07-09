<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_parent' => null,
            'id_item' => Item::inRandomOrder()->first()?->id,
            'id_user' => User::inRandomOrder()->first()?->id,
            'content' => $this->faker->sentences(2, true),
            'deleted_at' => null,
        ];
    }
}
