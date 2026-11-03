<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Ce qui est référencé (catégories, plateformes) avant ce qui référence (jeux)
        $this->call([
            CategorySeeder::class,
            PlatformSeeder::class,
            GameSeeder::class,
        ]);
    }
}
