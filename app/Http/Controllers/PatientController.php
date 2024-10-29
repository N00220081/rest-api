<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     *
     * This method retrieves all patients from the database
     * and returns them in JSON format. Accessible by any authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Fetch all patients for any authenticated user
        return response()->json(Patient::all()); // Return the list of patients as a JSON response
    }

    /**
     * Store a newly created patient in storage.
     *
     * This method validates the incoming request data,
     * creates a new patient record, and returns the created patient.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 403); // Return unauthorized response if not authenticated
        }

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:128', // Name is required and should not exceed 128 characters
            'insurance' => 'nullable|string|max:255', // Insurance is optional and can be up to 255 characters
            'email' => 'required|string|email|unique:patients,email|max:128', // Email must be unique and a valid email format
            'phone' => 'required|string|unique:patients,phone|max:16', // Phone must be unique and not exceed 16 characters
            'doctor_id' => 'required|exists:doctors,id', // Doctor must exist in the database
        ]);

        // Create the patient record using the validated data
        $patient = Patient::create($request->all());

        return response()->json($patient, 201); // Return the created patient with a 201 status code
    }

    /**
     * Display the specified patient.
     *
     * This method retrieves a specific patient by their ID
     * and returns it in JSON format.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Retrieve the patient by ID or fail with a 404 error
        $patient = Patient::findOrFail($id);
        return response()->json($patient); // Return the patient details as a JSON response
    }

    /**
     * Update the specified patient in storage.
     *
     * This method validates the incoming request data
     * and updates the patient specified by the ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'sometimes|required|string|max:128', // Validate name if present
            'insurance' => 'nullable|string|max:255', // Validate insurance if present
            'email' => 'sometimes|required|string|email|max:128|unique:patients,email,' . $id, // Validate email if present
            'phone' => 'sometimes|required|string|max:16|unique:patients,phone,' . $id, // Validate phone if present
            'doctor_id' => 'sometimes|required|exists:doctors,id', // Doctor must exist if provided
        ]);

        // Find the patient by ID or fail with a 404 error
        $patient = Patient::findOrFail($id);
        $patient->update($request->all()); // Update the patient record using the validated data

        return response()->json($patient, 200); // Return the updated patient as a JSON response
    }

    /**
     * Remove the specified patient from storage.
     *
     * This method deletes the patient specified by the ID
     * and returns a success message.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Retrieve the patient by ID or fail with a 404 error
        $patient = Patient::findOrFail($id);
        $patient->delete(); // Delete the patient record

        return response()->json(['message' => 'Patient deleted successfully'], 204); // Return 204 No Content on success
    }
}
