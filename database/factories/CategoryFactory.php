<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = [
            'Kunci',
            'Dompet',
            'Handphone',
            'Laptop',
            'Tas',
            'Buku',
            'Kacamata',
            'Jam Tangan',
            'Payung',
            'Topi',
            'Sepatu',
            'Jaket',
            'Pulpen',
            'Cincin',
            'Gelang',
            'Kartu Identitas',
            'STNK',
            'SIM',
            'Kartu ATM',
            'Charger',
            'Earphone',
            'Flashdisk',
            'Baju',
            'Botol Minum'
        ];
        $name = $this->faker->randomElement($items);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'item_count' => 0,
        ];
    }
}
