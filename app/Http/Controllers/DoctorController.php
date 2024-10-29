<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    // Get all doctors (accessible by any authenticated user)
    public function index()
    {
        // Return all doctors for any authenticated user
        return response()->json(Doctor::all());
    }

    // Create a new doctor (accessible by any authenticated user)
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
            'specialty' => 'required|string|max:128',
            'email' => 'required|string|email|unique:doctors,email|max:128',
            'phone' => 'required|string|unique:doctors,phone|max:16',
        ]);

        // Create the doctor record
        $doctor = Doctor::create($request->all());

        return response()->json($doctor, 201); // Return the created doctor with a 201 status
    }

    // Show a specific doctor
    public function show($id)
    {
        $doctor = Doctor::findOrFail($id);
        return response()->json($doctor);
    }

    // Update an existing doctor
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'sometimes|required|string|max:128',
            'specialty' => 'sometimes|required|string|max:128',
            'email' => 'sometimes|required|string|email|unique:doctors,email,' . $doctor->id . '|max:128',
            'phone' => 'sometimes|required|string|unique:doctors,phone,' . $doctor->id . '|max:16',
        ]);

        $doctor->update($request->all());

        return response()->json($doctor);
    }

    // Delete a specific doctor
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted successfully']);
    }
}
