<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/images/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" href="/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" href="/images/android-chrome-192x192.png" sizes="192x192" />
    <link rel="icon" href="/images/android-chrome-512x512.png" sizes="512x512" />
    <link rel="apple-touch-icon" href="/images/apple-touch-icon.png" />

    <title>Flight Ticket Booking</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <ticket-manager></ticket-manager>
    </div>
</body>
</html>