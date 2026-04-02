<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Itenas – Rating & feedback platform')</title>
    
    <!-- Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Buildify is a modern AI agents template by PrebuiltUI. Build, launch and scale intelligent AI agents with production-ready components and workflows.')">
    <meta name="author" content="PrebuiltUI">
    <meta name="keywords" content="AI agents,AI agent builder,AI SaaS template,PrebuiltUI,Next.js AI template,Tailwind CSS UI,AI workflow automation">
    
    <!-- Manifest & Icons -->
    <link rel="manifest" href="{{ asset('landing_assets/manifest.json') }}">
    <link rel="icon" href="{{ asset('assets/images/logos/favicon.png') }}" sizes="48x48" type="image/x-icon">
    <link rel="icon" href="{{ asset('landing_assets/icon0.svg') }}" sizes="any" type="image/svg+xml">
    <link rel="icon" href="{{ asset('landing_assets/icon1.png') }}" sizes="96x96" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('landing_assets/apple-icon.png') }}" sizes="180x180" type="image/png">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', 'Buildify – Build, Launch & Scale with AI Agents')">
    <meta property="og:description" content="@yield('og_description', 'A production-ready AI agents template built with PrebuiltUI. Launch faster with scalable workflows and modern UI.')">
    <meta property="og:image" content="{{ asset('landing_assets/opengraph-image.png') }}">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="640">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Buildify – Build, Launch & Scale with AI Agents')">
    <meta name="twitter:description" content="@yield('twitter_description', 'A modern AI agents template by PrebuiltUI to build, launch and scale faster.')">
    <meta name="twitter:image" content="{{ asset('landing_assets/opengraph-image.png') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/app.css') }}">
    
    <!-- CSS -->
    @vite(['resources/css/landing/input.css'])
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <!-- Scripts -->
    <script src="{{ asset('landing/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>