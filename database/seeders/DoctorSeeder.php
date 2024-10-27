<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create a doctor entry for the user created in UserSeeder
        Doctor::create([
            'name' => 'Dr. John Doe',
            'specialty' => 'Cardiology',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
        ]);
    }
}
