<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['PC', 'PS5', 'Xbox', 'Switch'] as $name) {
            Platform::create(['name' => $name]);
        }
    }
}
