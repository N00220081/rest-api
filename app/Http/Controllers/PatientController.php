<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index(): JsonResponse
    {
        $patients = Patient::all();
        return response()->json($patients);
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:128',
            'insurance' => 'nullable|string|max:255',
            'email' => 'required|email|max:128',
            'phone' => 'nullable|string|max:16',
            'doctor_id' => 'required|exists:doctor,id',
        ]);

        $patient = Patient::create($validatedData);
        return response()->json($patient, 201);
    }

    /**
     * Display the specified patient.
     */
    public function show($id): JsonResponse
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:128',
            'insurance' => 'nullable|string|max:255',
            'email' => 'sometimes|required|email|max:128',
            'phone' => 'nullable|string|max:16',
            'doctor_id' => 'sometimes|required|exists,id',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($validatedData);
        return response()->json($patient);
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy($id): JsonResponse
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return response()->json(null, 204);
    }
}
