<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::orderBy('created_at', 'desc')->get();
        foreach ($items as $item) {
            Image::factory()->count(2)->create([
                'id_item' => $item->id,
            ]);
        }
    }
}
