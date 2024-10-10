<!DOCTYPE html>
<html>
<head>
    <title>Password Updated</title>
</head>
<body>
    <h1>Password Updated Successfully</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Your password has been updated successfully.</p>
    <p>If you didn't request this change or want to change the password again, please click on the link below.</p>
    
        <!-- Resend Password Link -->
        <p>Password reset link:</p>
    <p><a href="{{ route('password.resend', ['email' => $user->email]) }}">Resend Password</a></p>
</body>
</html>
