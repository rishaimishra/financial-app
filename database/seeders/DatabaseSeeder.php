<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User1',
            'email' => 'test@example1.com',
            'password' => Hash::make('user123'),
            'phone_number'=>'+917063821662'
        ]);

        $this->call([
            AdminUserSeeder::class,
            DefaultDataSeeder::class, // Your existing seeder
            // Add other seeders as needed
        ]);
    }
}
