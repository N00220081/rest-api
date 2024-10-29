<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create 10 doctors
        Doctor::factory()->count(10)->create();

        // Create 30 patients
        Patient::factory()->count(30)->create();

        // Create 50 appointments
        Appointment::factory()->count(50)->create();
    }
}
