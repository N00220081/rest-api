<?php

// app/Http/Controllers/AppointmentController.php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct()
    {
        // Apply authentication middleware for all methods
        $this->middleware('auth:sanctum');
    }

    /**
     * Create a new appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'at' => 'required|date',
            'cost' => 'required|numeric',
            'paid' => 'required|boolean',
        ]);

        // Check if the authenticated user is a doctor
        $doctor = Auth::user();
        if (!$doctor->hasRole('doctor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Create the appointment
        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $request->input('patient_id'),
            'at' => $request->input('at'),
            'cost' => $request->input('cost'),
            'paid' => $request->input('paid'),
        ]);

        return response()->json($appointment, 201);
    }

    /**
     * Get all appointments for the authenticated doctor.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $doctor = Auth::user();
        $appointments = Appointment::where('doctor_id', $doctor->id)->get();

        return response()->json($appointments);
    }

    /**
     * Get a specific appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $doctor = Auth::user();
        $appointment = Appointment::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();

        return response()->json($appointment);
    }

    /**
     * Update a specific appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $doctor = Auth::user();
        $appointment = Appointment::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();

        // Validate request data
        $request->validate([
            'patient_id' => 'sometimes|required|exists:patients,id',
            'at' => 'sometimes|required|date',
            'cost' => 'sometimes|required|numeric',
            'paid' => 'sometimes|required|boolean',
        ]);

        // Update appointment fields
        $appointment->update($request->only('patient_id', 'at', 'cost', 'paid'));

        return response()->json($appointment);
    }

    /**
     * Delete a specific appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $doctor = Auth::user();
        $appointment = Appointment::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();

        $appointment->delete();

        return response()->json(null, 204);
    }
}



