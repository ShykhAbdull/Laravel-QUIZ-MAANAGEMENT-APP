<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordSetupMail;
use App\Mail\StudentAcceptedMail;
use App\Mail\StudentRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Str;

class AdminController extends Controller
{
    

    public function addManager(Request $request){

        $validateData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255'
        ]);

        try{
            
        // Create a Manager user
        $manager = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'], 
            'password' => Hash::make('manager'), 
        ]);    

        $manager->assignRole('Manager');

// Send password setup email (valid for 24 hours)
    $token = Str::random(60); // Create a unique token
    $expiration = now()->addHours(24);

    $manager->update(
    ['password_reset_token' => $token,
    'password_reset_expires_at' => $expiration
    ]);

        // Queue email notification
        Mail::to($manager->email)->send(new PasswordSetupMail($manager, $token));

        return response()->json(['message' => 'Manager added and password setup email sent successfully.']);
    }  
    
    catch(\Exception $e){
        return response()->json(['error' => 'There was an issue with adding the Manager or sending the email.'], 500);

    }
}


public function approveStudent($studentID , $admin_login_token){

    // Retrieve the admin by checking the token
    $admin = User::whereHas('tokens', function ($query) use ($admin_login_token) {
        $query->where('token', hash('sha256', $admin_login_token)); // Token is hashed in the DB
    })->first();


    if (!$admin) {
        return response()->json(['message' => 'Unauthorized.'], 403);
    }

    // Find the student by ID
    $student = User::findOrFail($studentID);

    // Update the student's status to 'approved'
    $student->status = 'approved';
    $student->save();

    $admin = User::whereHas('roles', function($query) {
        $query->where('name', 'Admin');
    })->first();
    

    // Send password setup email (valid for 24 hours)
    $token = Str::random(60); // Create a unique token
    $expiration = now()->addHours(24);

    $student->update(
    ['password_reset_token' => $token,
    'password_reset_expires_at' => $expiration
    ]);

// Send success email to student
    Mail::to($student->email)->send(new StudentAcceptedMail($student, $token));

    return response()->json(['Message' => $student->name . ' approved successfully.']);
    
}


// ----------------- Rejected Student Logic-------------------

public function rejectStudent($studentID, $admin_login_token)
{
    // Similar logic for rejection
    $admin = User::whereHas('tokens', function ($query) use ($admin_login_token) {
        $query->where('token', hash('sha256', $admin_login_token)); // Token is hashed in the DB
    })->first();

    if (!$admin) {
        return response()->json(['message' => 'Unauthorized.'], 403);
    }

    // Find the student by ID
    $student = User::findOrFail($studentID);
    
    // Update the student's status to 'rejected'
    $student->status = 'rejected';
    $student->save();

    $admin = User::whereHas('roles', function($query) {
        $query->where('name', 'Admin');
    })->first();

    $adminToken = $admin->tokens()
    ->where('name', 'Admin_auth_Token') // Filter by token name
    ->latest() // Get the most recent token
    ->first()
    ->token ?? null;



    // Send rejection email to the student
    Mail::to($student->email)->send(new StudentRejectedMail($student, $adminToken));

    return response()->json(['message' => $student->name . ' rejected successfully.']);
}



}
