<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Setup</title>
</head>
<body>
    <h1>Hello, {{ $student->name }} (Student)</h1>
    <p>To set your password, please click the link below:</p>
    <p><a href="{{ route('password.reset', ['token' => $token,'name' => $student->name]) }}">Set Password</a></p>
    <p>This link is valid for 24 hours.</p>
    <p>Thank you!</p>


        <!-- Resend Password Link -->
        <p>If the link has expired, click the button below to resend the password reset link:</p>
    <p><a href="{{ route('password.resend', ['email' => $student->email]) }}">Resend Password</a></p>
</body>
</html>
