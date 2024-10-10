@extends('layouts.app')

@section('content')
<style>
    /* Basic reset for margin and padding */
    body, html {
        margin: 0;
        padding: 0;
        height: 100%; /* Make sure body takes full height */
    }

    .container {
        display: flex;
        flex-direction: column; /* Arrange children in a column */
        justify-content: center; /* Center vertically */
        align-items: center; /* Center horizontally */
        height: 100vh; /* Full viewport height */
        text-align: center; /* Center text */
    }

    /* Optional styles for form inputs and button */
    input {
        margin: 5px 0; /* Space between inputs */
        padding: 10px;
        width: 300px; /* Set a width for the input fields */
    }

    button {
        padding: 10px 15px; /* Button padding */
    }
</style>

<body>
    <div class="container">
        <h1>Hello {{$name}}, Please reset your Password</h1>
        <form action="{{ route('password.update', $token) }}" method="POST">
            @csrf
            <div>
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <br>
            <div>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
            <br>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
@endsection
