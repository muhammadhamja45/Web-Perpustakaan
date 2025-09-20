<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@admin.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_approved' => true,
                'approved_at' => now(),
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin account created successfully!');
            $this->command->info('Email: admin@admin.com');
            $this->command->info('Password: password123');
        } else {
            $this->command->info('Admin account already exists.');
        }
    }
}