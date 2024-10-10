<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Update </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Resend Request</h1>
        </div>
        <p>Hi {{ $user->name }},</p>
        <p>We received a request to reset your password. Click the button below to set a new password:</p>
        
        <a href="{{ route('password.reset', ['token' => $token, 'name' => $user->name]) }}" class="button">Reset Password</a>
        
        <p>If you didn't request a password reset, you can safely ignore this email.</p>
        
        <p>Thank you,<br>Your Application Team</p>
    </div>
</body>
</html>
