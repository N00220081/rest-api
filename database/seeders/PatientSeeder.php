<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create a patient entry and associate with a doctor (doctor_id must match an existing doctor)
        Patient::create([
            'name' => 'Jane Smith',
            'insurance' => 'Health Insurance Co.',
            'email' => 'jane.smith@example.com',
            'phone' => '0987654321',
            'doctor_id' => 1, // Assuming this is the ID of the doctor created in DoctorSeeder
        ]);
    }
}
