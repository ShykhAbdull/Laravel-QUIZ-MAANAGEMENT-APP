<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\AdminStudentApprovalMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;
use Str;

class StudentController extends Controller
{
    public function addStudent(Request $request){

        $student_validateData = $request->validate([    
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048', 
        ]);

    // Store the uploaded CV in storage (you can store this wherever required)
    $cvPath = $request->file('cv')->store('Admin/Applied-CVs');

        // Create the student with 'pending' status
        $student = User::create([
            'name' => $student_validateData['name'],
            'email' => $student_validateData['email'],
            'cv' => $cvPath,
            'password' => Hash::make('student'), // Temporary password 'student'
            'status' => 'pending', 
        ]);

        $student->assignRole(roles: 'Student');


    // Send password setup email (valid for 24 hours)
    $token = Str::random(60); // Create a unique token
    $expiration = now()->addHours(24);

    $student->update(
    ['password_reset_token' => $token,
    'password_reset_expires_at' => $expiration
    ]);


    // Dynamically fetching the latest created token of the Admin Role
    $admin = User::whereHas('roles', function($query) {
        $query->where('name', 'Admin');
    })->first();


    $adminToken = $admin->tokens()
    ->where('name', 'Admin_auth_Token') // Filter by token name
    ->latest() // Get the most recent token
    ->first()
    ->token ?? null;



    Mail::to($admin->email)->send(new AdminStudentApprovalMail($student_validateData,$adminToken, $student->id ));

    return response()->json([
        'message' => 'Registration request sent to the admin for approval.'
    ]);
}
}
