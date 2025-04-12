<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
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
