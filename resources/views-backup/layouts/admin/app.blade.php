<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.admin.partials.head')

    @stack('styles')
</head>

<body >

    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        
        @include('layouts.admin.partials.sidebar')

        <div class="body-wrapper">
                <div class="container-fluid">

                    @include('components.admin.alert')

                    <header class="app-header rounded-pill">
                        @include('layouts.admin.partials.navbar')
                    </header>

                    <div id="main-content">
                        @yield('admin-content')
                    </div>

                    @include('layouts.admin.partials.footer')

                </div>
            </div>
        </div>
    </div>

    <x-admin.logout-modal />

    {{-- SEMUA SCRIPT DI SATUKAN DI SINI DENGAN URUTAN YANG BENAR --}}
    <script defer src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script defer src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    
    {{-- Script yang butuh jQuery --}}
    <script defer src="{{ asset('assets/admin/js/sidebarmenu.js') }}"></script>
    <script defer src="{{ asset('assets/admin/js/app.min.js') }}"></script>
    <script defer src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
    <script defer src="{{ asset('assets/components/components.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Hapus @include('layouts.admin.partials.scripts') karena kita pindahin ke sini --}}

    @stack('admin-scripts')
    @stack('scripts')

</body>

</html>