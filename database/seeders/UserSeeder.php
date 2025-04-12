<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Bogdan Tester',
            'email' => 'bogdygewald@yahoo.de',
            'password' => Hash::make('supertest'),
        ]);

        User::factory()->count(10)->create();
    }
}
