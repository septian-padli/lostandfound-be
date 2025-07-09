<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProvinceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            ImageSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
