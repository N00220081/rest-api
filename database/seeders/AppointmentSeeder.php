<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create an appointment entry
        Appointment::create([
            'doctor_id' => 1, // ID of the doctor
            'patient_id' => 1, // ID of the patient
            'at' => now()->addDays(1), // Appointment date (1 day from now)
            'cost' => 50.00,
            'paid' => false,
        ]);
    }
}
