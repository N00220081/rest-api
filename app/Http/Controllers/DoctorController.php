<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    // Display a listing of the doctors
    public function index()
    {
        $doctors = Doctor::all();
        return response()->json($doctors);
    }

    // Store a newly created doctor in storage
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:128',
            'specialty' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:doctors',
            'phone' => 'nullable|string|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $doctor = Doctor::create($request->all());
        return response()->json($doctor, 201);
    }

    // Display the specified doctor
    public function show($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor);
    }

    // Update the specified doctor in storage
    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:128',
            'specialty' => 'sometimes|required|string|max:128',
            'email' => 'sometimes|required|string|email|max:128|unique:doctors,email,' . $doctor->id,
            'phone' => 'nullable|string|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $doctor->update($request->all());
        return response()->json($doctor);
    }

    // Remove the specified doctor from storage
    public function destroy($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $doctor->delete();
        return response()->json(['message' => 'Doctor deleted successfully']);
    }
}
