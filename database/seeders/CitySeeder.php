<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::factory()->createMany([
            [
                'name' => 'Kabupaten Bogor',
                'slug' => 'kabupaten-bogor',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Sukabumi',
                'slug' => 'kabupaten-sukabumi',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Cianjur',
                'slug' => 'kabupaten-cianjur',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Bandung',
                'slug' => 'kabupaten-bandung',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Garut',
                'slug' => 'kabupaten-garut',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Tasikmalaya',
                'slug' => 'kabupaten-tasikmalaya',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Ciamis',
                'slug' => 'kabupaten-ciamis',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Kuningan',
                'slug' => 'kabupaten-kuningan',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Cirebon',
                'slug' => 'kabupaten-cirebon',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Majalengka',
                'slug' => 'kabupaten-majalengka',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Sumedang',
                'slug' => 'kabupaten-sumedang',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Indramayu',
                'slug' => 'kabupaten-indramayu',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Subang',
                'slug' => 'kabupaten-subang',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Purwakarta',
                'slug' => 'kabupaten-purwakarta',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Karawang',
                'slug' => 'kabupaten-karawang',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Bekasi',
                'slug' => 'kabupaten-bekasi',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Bandung Barat',
                'slug' => 'kabupaten-bandung-barat',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Pangandaran',
                'slug' => 'kabupaten-pangandaran',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Bogor',
                'slug' => 'kota-bogor',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Sukabumi',
                'slug' => 'kota-sukabumi',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'id' => '01HZY6QJ8ZK8X1YQ2R7V4H9C5D',
                'name' => 'Kota Bandung',
                'slug' => 'kota-bandung',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Cirebon',
                'slug' => 'kota-cirebon',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Bekasi',
                'slug' => 'kota-bekasi',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Depok',
                'slug' => 'kota-depok',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Cimahi',
                'slug' => 'kota-cimahi',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Tasikmalaya',
                'slug' => 'kota-tasikmalaya',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kota Banjar',
                'slug' => 'kota-banjar',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5D',
            ],
            [
                'name' => 'Kabupaten Administrasi Kepulauan Seribu',
                'slug' => 'kabupaten-administrasi-kepulauan-seribu',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5E',
            ],
            [
                'name' => 'Kota Administrasi Jakarta Pusat',
                'slug' => 'kota-administrasi-jakarta-pusat',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5E',
            ],
            [
                'name' => 'Kota Administrasi Jakarta Utara',
                'slug' => 'kota-administrasi-jakarta-utara',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5E',
            ],
            [
                'name' => 'Kota Administrasi Jakarta Barat',
                'slug' => 'kota-administrasi-jakarta-barat',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5E',
            ],
            [
                'name' => 'Kota Administrasi Jakarta Selatan',
                'slug' => 'kota-administrasi-jakarta-selatan',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5E',
            ],
            [
                'name' => 'Kota Administrasi Jakarta Timur',
                'slug' => 'kota-administrasi-jakarta-timur',
                'id_province' => '01HZY6QJ8ZK8X1YQ2R7V4B9C5E',
            ],
        ]);
    }
}
