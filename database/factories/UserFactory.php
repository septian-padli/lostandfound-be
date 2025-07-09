<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Province;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = City::inRandomOrder()->first();
        $province = $city->province ?? Province::inRandomOrder()->first();
        return [
            'email' => fake()->unique()->safeEmail(),
            'googleId' => Str::random(16),
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'photoprofile' => fake()->imageUrl(),
            'token' => fake()->sha256,
            'isAdmin' => false,
            'email_verified_at' => now(),
            'id_city' => $city?->id ?? null,
            'id_province' => $province?->id ?? null,
            'password' => Hash::make('123'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
