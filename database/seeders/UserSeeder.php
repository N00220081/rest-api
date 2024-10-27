<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        // Create a doctor
        User::create([
            'name' => 'Dr. John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'), // Use a hashed password
            'role' => 'doctor',
        ]);

        // Create a patient
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => bcrypt('password123'), // Use a hashed password
            'role' => 'patient',
        ]);

        // You can add more users here if needed
    }
}
