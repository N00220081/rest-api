<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    // Get all patients (accessible by any authenticated user)
    public function index()
    {
        return response()->json(Patient::all());
    }

    // Create a new patient (accessible by any authenticated user)
    public function store(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:128',
            'insurance' => 'nullable|string|max:255',
            'email' => 'required|string|email|unique:patients,email|max:128',
            'phone' => 'required|string|unique:patients,phone|max:16',
            'doctor_id' => 'required|exists:doctors,id', // Doctor must exist
        ]);

        // Create the patient record
        $patient = Patient::create($request->all());

        return response()->json($patient, 201); // Return the created patient with a 201 status
    }

    // Show a specific patient
    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    // Update a specific patient
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'sometimes|required|string|max:128',
            'insurance' => 'nullable|string|max:255',
            'email' => 'sometimes|required|string|email|max:128|unique:patients,email,' . $id,
            'phone' => 'sometimes|required|string|max:16|unique:patients,phone,' . $id,
            'doctor_id' => 'sometimes|required|exists:doctors,id', // Doctor must exist
        ]);

        // Find and update the patient
        $patient = Patient::findOrFail($id);
        $patient->update($request->all());

        return response()->json($patient, 200); // Return the updated patient
    }

    // Delete a specific patient
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted successfully'], 204); // Return 204 No Content on success
    }
}
