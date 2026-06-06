<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') — Portal Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/student/auth/style.css') }}">

    <style>
        .auth-card-single {
            width: 100%;
            max-width: 480px;
            background: var(--c-surface);
            border-radius: var(--r-card);
            border: 1px solid var(--c-border);
            box-shadow: 0 40px 80px rgba(205, 205, 205, 0.22), 0 8px 24px rgba(0, 0, 0, 0.12);
            animation: cardIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
            padding: 52px 48px;
        }

        @media (max-width: 480px) {
            .auth-card-single {
                padding: 32px 24px;
            }
        }
    </style>

    @yield('styles')
</head>

<body style="display: flex; align-items: center; justify-content: center; padding: 28px 20px;">

    @yield('content')

    @yield('scripts')

</body>

</html>
