<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Add your CSS links here, or Laravel's mix() if needed -->
</head>
<body>

    <div class="container">
        <!-- This is where the content from your view will be injected -->
        @yield('content')
    </div>

    <!-- Add your JavaScript files here if needed -->
</body>
</html>
