<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
   /**
     * Display a list of appointments relevant to the authenticated user.
     * Doctors see all appointments; patients see only their appointments.
     */
    public function index()
    {
        $user = Auth::user();

        // Doctors see all appointments; patients see only their appointments
        if ($user->role === 'doctor') {
            // Retrieve all appointments for a doctor
            $appointments = Appointment::where('doctor_id', $user->id)->get();
        } else {
            // Retrieve only the appointments for the authenticated patient
            $appointments = Appointment::where('patient_id', $user->id)->get();
        }

        return response()->json($appointments);
    }

    /**
     * Store a new appointment in the database (Doctors Only).
     * Doctor assigns the appointment to a patient.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if the user is a doctor, as only doctors can create appointments
        if ($user->role !== 'doctor') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate request input to ensure all required fields are provided
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        // Create a new appointment and link it to the doctor and patient
        $appointment = Appointment::create([
            'doctor_id' => $user->id,
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
        ]);

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified appointment to the doctor or patient if they own it.
     */
    public function show($id)
    {
        $user = Auth::user();
        $appointment = Appointment::findOrFail($id);

        // Ensure only the doctor or patient related to this appointment can view it
        if ($user->id !== $appointment->doctor_id && $user->id !== $appointment->patient_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($appointment);
    }

    /**
     * Update the specified appointment details (Doctors Only).
     * Only doctors should be able to update appointments.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Check if the user is a doctor, as only doctors can update appointments
        if ($user->role !== 'doctor') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment = Appointment::findOrFail($id);

        // Validate request input
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        // Update appointment details
        $appointment->update([
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
        ]);

        return response()->json($appointment);
    }

    /**
     * Remove the specified appointment (Doctors Only).
     * Only doctors should be able to delete appointments.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        // Check if the user is a doctor, as only doctors can delete appointments
        if ($user->role !== 'doctor') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment = Appointment::findOrFail($id);

        // Only the doctor related to this appointment can delete it
        if ($user->id !== $appointment->doctor_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully.']);
    }
}




