<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1)->create([
            'name' => 'Test User',
            'email' => 'm.septtianpadli@gmail.com',
            'password' => Hash::make('123'),
        ]);
    }
}
