<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        // Get all appointments for the authenticated doctor
        $appointments = Appointment::whereHas('patient', function ($query) {
            $query->where('doctor_id', auth()->id());
        })->get();

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'at' => 'required|date',
            'cost' => 'required|numeric',
            'paid' => 'boolean',
        ]);

        // Create the appointment
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'at' => $request->at,
            'cost' => $request->cost,
            'paid' => $request->paid,
        ]);

        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        // Find the appointment by ID and ensure it's linked to the authenticated doctor's patients
        $appointment = Appointment::whereHas('patient', function ($query) {
            $query->where('doctor_id', auth()->id());
        })->findOrFail($id);

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        // Ensure only the authenticated doctor can delete the appointment
        $appointment = Appointment::whereHas('patient', function ($query) {
            $query->where('doctor_id', auth()->id());
        })->findOrFail($id);

        $appointment->delete();

        return response()->json(null, 204);
    }
}




