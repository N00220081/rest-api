<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'doctor_id' => \App\Models\Doctor::factory(), // Automatically create a doctor
            'patient_id' => \App\Models\Patient::factory(), // Automatically create a patient
            'at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'cost' => $this->faker->randomFloat(2, 50, 300), // Random cost between 50 and 300
            'paid' => $this->faker->boolean,
        ];
    }
}
