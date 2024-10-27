<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // Get all appointments for the authenticated user
    public function index()
    {
        $user = Auth::user();

        // Get appointments based on role
        if ($user->role === 'doctor') {
            $appointments = Appointment::where('doctor_id', $user->id)->get();
        } else {
            $appointments = Appointment::where('patient_id', $user->id)->get();
        }

        return response()->json($appointments);
    }

    // Create a new appointment (for doctors)
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'at' => 'required|date',
            'cost' => 'required|numeric',
            'paid' => 'boolean',
        ]);

        $appointment = Appointment::create(array_merge($request->all(), ['doctor_id' => $user->id]));

        return response()->json($appointment, 201);
    }

    // Other methods (show, update, destroy) can also check for user roles...
}
