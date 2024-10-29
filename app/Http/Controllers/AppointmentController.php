<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index()
    {
        $appointments = Appointment::all(); // Show all appointments for any authenticated user
        return response()->json($appointments);
    }
    
    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'at' => 'required|date',
            'cost' => 'required|numeric',
            'paid' => 'boolean'
        ]);

        // Create the appointment
        $appointment = Appointment::create([
            'doctor_id' => $user->id,
            'patient_id' => $validatedData['patient_id'],
            'at' => $validatedData['at'],
            'cost' => $validatedData['cost'],
            'paid' => $validatedData['paid'] ?? false,
        ]);

        return response()->json($appointment, 201); // Return 201 on success
    }

    /**
     * Display the specified appointment.
     */
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return response()->json($appointment);
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'at' => 'sometimes|date',
            'cost' => 'sometimes|numeric',
            'paid' => 'sometimes|boolean',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($validatedData);

        return response()->json($appointment);
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}
