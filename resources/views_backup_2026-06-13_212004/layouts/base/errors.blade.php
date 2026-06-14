<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Unit Feedback</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind -->
    @vite('resources/css/app.css')

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8F7F5;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #f8773c 0%, #e0622a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (min-width: 768px) {
            .error-code {
                font-size: 12rem;
            }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-2xl mx-auto">
        <div class="error-code mb-4">@yield('code')</div>
        <h1 class="text-2xl md:text-3xl font-bold text-[#1A1A1A] mb-3">@yield('title')</h1>
        <p class="text-[#555] text-base md:text-lg mb-8">@yield('message')</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:history.back()"
                class="px-6 py-3 border border-[#ddd] text-[#555] rounded-xl hover:bg-white transition-all">
                Kembali
            </a>
            <a href="{{ route('home') }}"
                class="px-6 py-3 bg-[#f8773c] text-white rounded-xl hover:bg-[#e0622a] transition-all shadow-md">
                Ke Beranda
            </a>
        </div>

        @hasSection('action')
            <div class="mt-6">
                @yield('action')
            </div>
        @endif
    </div>
</body>

</html>