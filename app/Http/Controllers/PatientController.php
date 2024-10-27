<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Get all patients
    public function index()
    {
        return Patient::all();
    }

    // Create a new patient
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'insurance' => 'nullable|string|max:255',
            'email' => 'required|string|email|unique:patients,email|max:128',
            'phone' => 'required|string|unique:patients,phone|max:16',
            'doctor_id' => 'required|exists:doctors,id', // Doctor must exist
        ]);

        $patient = Patient::create($request->all());

        return response()->json($patient, 201);
    }

    // Show a specific patient
    public function show($id)
    {
        return Patient::findOrFail($id);
    }

    // Update a specific patient
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:128',
            'insurance' => 'nullable|string|max:255',
            'email' => 'sometimes|required|string|email|max:128',
            'phone' => 'sometimes|required|string|max:16',
            'doctor_id' => 'sometimes|required|exists:doctors,id', // Doctor must exist
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($request->all());

        return response()->json($patient, 200);
    }

    // Delete a specific patient
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted successfully'], 204);
    }
}
