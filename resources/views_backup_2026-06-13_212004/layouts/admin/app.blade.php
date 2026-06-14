<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.admin.partials.head')

    @stack('styles')
</head>

<body class="admin-layout" data-sidebartype="full">
    <div class="page-wrapper">
        @include('layouts.admin.partials.sidebar')

        <div class="body-wrapper">
            <header class="app-header">
                @include('layouts.admin.partials.navbar')
            </header>

            <main id="main-content">
                @include('components.shared.alert')
                @include('components.admin.alert')

                @yield('admin-content')
            </main>

            <footer class="footer">
                @include('layouts.admin.partials.footer')
            </footer>
        </div>
    </div>

    @include('components.admin.delete-modal')

    <script defer src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script defer src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script defer src="{{ asset('assets/admin/js/sidebarmenu.js') }}"></script>
    <script defer src="{{ asset('assets/admin/js/app.min.js') }}"></script>
    <script defer src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
    <script defer src="{{ asset('assets/components/components.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saved = localStorage.getItem('sidebarType');
            if (saved === 'mini') {
                document.body.setAttribute('data-sidebartype', 'mini');
            }
        });
    </script>

    @stack('admin-scripts')
    @stack('scripts')
</body>
</html>