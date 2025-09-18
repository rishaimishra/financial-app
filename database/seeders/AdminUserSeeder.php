<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create a super admin user
        AdminUser::create([
            'email' => 'admin@financetracker.com',
            'password_hash' => Hash::make('admin123'), // You should change this password
            'role' => 'superadmin',
        ]);

        // Create additional admin users if needed
        AdminUser::create([
            'email' => 'manager@financetracker.com',
            'password_hash' => Hash::make('manager123'),
            'role' => 'manager',
        ]);

        // You can add more admin users as needed
        AdminUser::create([
            'email' => 'support@financetracker.com',
            'password_hash' => Hash::make('support123'),
            'role' => 'support',
        ]);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: admin@financetracker.com');
        $this->command->info('Password: admin123');
    }
}