<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAPIController extends Controller
{
    public function login(Request $request)
    {
        
        // Validate the request
        $validateData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);
    
        // Attempt to authenticate the user with the provided credentials
        $check = Auth::attempt($validateData);
        
        if ($check) {
            $user = Auth::user();

            $user_role = $user->getRoleNames()->first();

// Generate a new token for the user based on their role
        if ($user->hasRole('Manager')) {
            $token = $user->createToken('Manager_auth_Token')->plainTextToken;
        } elseif ($user->hasRole('Admin')) {
            $token = $user->createToken('Admin_auth_Token')->plainTextToken;
        } elseif ($user->hasRole('Student')) {
            $token = $user->createToken('Student_auth_Token')->plainTextToken;
        }


        // Return a successful response with the token and role
        return response()->json([
            'message' => $user_role  . " Logged In successfully",
            'role' => $user_role,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'cv' => $user->cv,
                'status' => $user->status,
            ],
            'token' => $token,
        ], 200);
    }
                
        return response()->json(['error' => 'Invalid credentials'], 401);

    }

    public function logout(Request $request){
        
        $user = Auth::user();
        $user_role = $user->getRoleNames()->first();

        // Revoke the token
            $request->user()->currentAccessToken()->delete();
    
        
        return response(['message' => $user_role . ' Successfully Logged Out'],200);
        
    }


}
