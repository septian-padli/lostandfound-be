<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinces')->insert([
            [
                'id' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
                'name' => 'Jawa Barat',
                'slug' => 'jawa-barat',
            ],
            [
                'id' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5E',
                'name' => 'Daerah Khusus Ibukota Jakarta',
                'slug' => 'dki-jakarta',
            ],
        ]);
    }
}
