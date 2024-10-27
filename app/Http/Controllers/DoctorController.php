<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    // Get all doctors (only accessible by admin or authenticated users)
    public function index()
    {
        // Optionally, you can restrict this to certain roles
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Doctor::all();
    }

    // Create a new doctor (only accessible by admin)
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:128',
            'specialty' => 'required|string|max:128',
            'email' => 'required|string|email|unique:doctors,email|max:128',
            'phone' => 'required|string|unique:doctors,phone|max:16',
        ]);

        $doctor = Doctor::create($request->all());

        return response()->json($doctor, 201);
    }

    // Other methods (show, update, destroy) can also check for user roles...
}
