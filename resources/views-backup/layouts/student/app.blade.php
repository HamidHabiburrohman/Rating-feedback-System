<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    @include('layouts.student.partials.head')
    @vite(['resources/css/app.css'])
</head>

<body class="font-body bg-surface text-on-surface antialiased">

    @include('layouts.student.partials.navbar')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('layouts.student.partials.footer')

    <x-student.auth-modal />
    
    @include('layouts.student.partials.scripts')

    @stack('scripts')
</body>

</html>