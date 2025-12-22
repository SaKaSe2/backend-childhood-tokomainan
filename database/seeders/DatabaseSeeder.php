<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create ADMIN user
        User::factory()->create([
            'name' => 'Admin Childhood',
            'email' => 'admin@childhood.com',
            'password' => bcrypt('admin123'), // Password: admin123
            'role' => 'admin', // ROLE ADMIN
        ]);

        // Create regular USER
        User::factory()->create([
            'name' => 'User Test',
            'email' => 'user@childhood.com',
            'password' => bcrypt('user123'), // Password: user123
            'role' => 'user', // ROLE USER
        ]);

        // Create another regular user
        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        // Seed products and transactions
        $this->call([
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);

        $this->command->info('✅ Seeder completed!');
        $this->command->info('📧 Admin: admin@childhood.com | Password: admin123');
        $this->command->info('📧 User: user@childhood.com | Password: user123');
    }
}
