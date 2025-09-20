<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // For production demo, use DemoDataSeeder
        // For development, use individual seeders
        if (app()->environment('production')) {
            $this->call([
                DemoDataSeeder::class,
            ]);
        } else {
            $this->call([
                AdminSeeder::class,
                DemoDataSeeder::class,
            ]);
        }
    }
}
