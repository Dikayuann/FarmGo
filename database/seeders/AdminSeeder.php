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
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@farmgo.com'],
            [
                'name' => 'Admin FarmGo',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@farmgo.com');
        $this->command->info('Password: admin123');
    }
}
