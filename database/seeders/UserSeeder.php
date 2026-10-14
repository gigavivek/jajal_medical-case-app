<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Admin User
        User::firstOrCreate(
            ['email' => 'admin@jajalmedical.com'],
            [
                'name' => 'Jajal Medical Admin',
                'password' => Hash::make('Admin@12345'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Default Viewer User
        User::firstOrCreate(
            ['email' => 'viewer@jajalmedical.com'],
            [
                'name' => 'Jajal Medical Viewer',
                'password' => Hash::make('Viewer@12345'),
                'role' => User::ROLE_VIEWER,
                'email_verified_at' => now(),
            ]
        );
    }
}
