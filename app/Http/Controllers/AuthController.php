<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Find the user by email
        $user = User::where('email', $request->email)->first();
    
        // If user does not exist, return unauthorized error
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Check if the password matches
        if (Hash::check($request->password, $user->password)) {
            
            // Return the user data and token
            return response()->json([
                'token' => $user->createToken('YourAppName')->plainTextToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    // Include other user fields as necessary
                ]
            ]);
        }
    
        // If password check fails, return unauthorized error
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    




   
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $user->createToken('YourAppName')->plainTextToken, // Optionally return a token
        ], 201);
    }

    
    // New Update Method
    public function update(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ignore the current user's email
            'password' => 'nullable|string|min:8|confirmed', // Password is optional
        ]);

        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password only if a new one is provided
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Save the user
        $user->save();

        // Return a success message
        return response()->json([
            'message' => 'Account updated successfully',
            'user' => $user,
        ]);
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
