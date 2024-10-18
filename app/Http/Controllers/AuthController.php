<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user instance
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password before storing
        ]);

        // Return a response indicating success
        return response()->json(['message' => 'User registered successfully.'], 201);
    }

    /**
     * Log in the user and return a token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a token for the authenticated user
        $token = $user->createToken('MyAppToken')->plainTextToken;

        // Return the token and user information
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    /**
     * Log out the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the user
        $request->user()->currentAccessToken()->delete();

        // Return a response indicating success
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}