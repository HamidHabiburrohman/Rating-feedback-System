<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notification</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #374151;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .email-header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .email-logo {
            max-height: 50px;
        }

        .email-content {
            padding: 30px 0;
        }

        .email-footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #f8773c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{ asset('assets/images/logos/logo1.svg') }}" alt="Logo" class="email-logo">
        </div>

        <div class="email-content">
            @yield('email-content')
        </div>

        <div class="email-footer">
            &copy; {{ date('Y') }} Unit Rating Feedback System. All rights reserved.
        </div>
    </div>
</body>

</html>