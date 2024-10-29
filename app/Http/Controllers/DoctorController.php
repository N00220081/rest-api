<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     *
     * This method retrieves all doctors from the database
     * and returns them in JSON format. Accessible by any authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Fetch all doctors for any authenticated user
        return response()->json(Doctor::all()); // Return the list of doctors as a JSON response
    }

    /**
     * Store a newly created doctor in storage.
     *
     * This method validates the incoming request data,
     * creates a new doctor record, and returns the created doctor.
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
            'specialty' => 'required|string|max:128', // Specialty is required and should not exceed 128 characters
            'email' => 'required|string|email|unique:doctors,email|max:128', // Email must be unique and a valid email format
            'phone' => 'required|string|unique:doctors,phone|max:16', // Phone must be unique and not exceed 16 characters
        ]);

        // Create the doctor record using the validated data
        $doctor = Doctor::create($request->all());

        return response()->json($doctor, 201); // Return the created doctor with a 201 status code
    }

    /**
     * Display the specified doctor.
     *
     * This method retrieves a specific doctor by their ID
     * and returns it in JSON format.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Retrieve the doctor by ID or fail with a 404 error
        $doctor = Doctor::findOrFail($id);
        return response()->json($doctor); // Return the doctor details as a JSON response
    }

    /**
     * Update the specified doctor in storage.
     *
     * This method validates the incoming request data
     * and updates the doctor specified by the ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Retrieve the doctor by ID or fail with a 404 error
        $doctor = Doctor::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'name' => 'sometimes|required|string|max:128', // Validate name if present
            'specialty' => 'sometimes|required|string|max:128', // Validate specialty if present
            'email' => 'sometimes|required|string|email|unique:doctors,email,' . $doctor->id . '|max:128', // Validate email if present
            'phone' => 'sometimes|required|string|unique:doctors,phone,' . $doctor->id . '|max:16', // Validate phone if present
        ]);

        // Update the doctor record using the validated data
        $doctor->update($request->all());

        return response()->json($doctor); // Return the updated doctor as a JSON response
    }

    /**
     * Remove the specified doctor from storage.
     *
     * This method deletes the doctor specified by the ID
     * and returns a success message.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Retrieve the doctor by ID or fail with a 404 error
        $doctor = Doctor::findOrFail($id);
        $doctor->delete(); // Delete the doctor record

        return response()->json(['message' => 'Doctor deleted successfully']); // Return success message
    }
}
