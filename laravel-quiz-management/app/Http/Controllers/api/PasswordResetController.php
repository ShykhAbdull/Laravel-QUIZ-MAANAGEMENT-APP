<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResendMail;
use App\Mail\PasswordSetupMail;
use App\Mail\PasswordUpdatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Mail;
use Str;

class PasswordResetController extends Controller
{

    public function showResetForm($token, $name)
    {
        return view('emails.user_password_set', [ 'token' => $token, 'name' => $name]);
    }

    public function resetPassword(Request $request, $token){


        $validateData = $request->validate([
            'password' => 'required|confirmed|min:5',
        ]);

// Only Fetch the User Data when data of reset_expires is greater than current token date
        $user = User::where('password_reset_token', $token)
        ->where('password_reset_expires_at', '>', now())
        ->first();


        if (!$user) {
            return response()->json(['error' => 'This password reset token is invalid or has expired.'],200);
        }
        
    
        // Check if the new password is the same as the old password
    if (Hash::check($validateData['password'], $user->password)) {
        return response()->json(['error' => 'The new password cannot be the same as the current password.'], 400);
    }

        // Update the user's password
        $user->password = Hash::make($validateData['password']);
        $user->password_reset_token = null; // Clear the token
        $user->password_reset_expires_at = null; // Clear the expiration time

        $user->save();

// Send email to notify user of password update
    Mail::to($user->email)->send(new PasswordUpdatedMail($user));  
        return response()->json(['Success' => $user->name ." password has been updated successfully"],200);

    }

    public function resendPasswordReset($email){
    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['error' => 'Email not found'], 404);
    }

    if ($user->password_reset_expires_at && $user->password_reset_expires_at > now()) {
        return response()->json(['message' => "Your token hasn't expired. You can still create your password using the Set Up Password link."], 200);
    }

    

// Create and Send password setup email (valid for 24 hours) in case of null token
$token = Str::random(60); // Create a unique token
$expiration = now()->addHours(24);

$user->update(
['password_reset_token' => $token,
'password_reset_expires_at' => $expiration
]);

    try {
        Mail::to($user->email)->send(new PasswordResendMail($user, $token));
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to send email. Please try again later.'], 500);
    }


    return response()->json(['message' => 'Hi ' . $user->name . ' we have resent the password setup email ']);

}



}
