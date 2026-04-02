<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Reset Password') — Portal Akademik</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/student/auth/style.css') }}">

    @yield('styles')
</head>

<body>

    @yield('content')

    @yield('scripts')

</body>

</html>