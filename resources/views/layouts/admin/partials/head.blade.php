<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Tailwind + Konfigurasi Desain -->
{{-- <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "on-tertiary-fixed": "#001c3b",
                    "primary": "#ad2b00",
                    "tertiary-fixed": "#d5e3ff",
                    "surface-bright": "#f7f9fb",
                    "on-secondary-container": "#681600",
                    "background": "#f7f9fb",
                    "primary-fixed": "#ffdbd1",
                    "secondary": "#a83818",
                    "on-surface": "#191c1e",
                    "tertiary": "#005cad",
                    "surface-dim": "#d8dadc",
                    "on-error": "#ffffff",
                    "on-primary": "#ffffff",
                    "on-secondary-fixed": "#3b0900",
                    "surface-container": "#eceef0",
                    "surface-container-highest": "#e0e3e5",
                    "primary-container": "#d93900",
                    "on-primary-fixed": "#3b0900",
                    "on-tertiary-fixed-variant": "#004787",
                    "primary-fixed-dim": "#ffb5a1",
                    "secondary-fixed": "#ffdbd1",
                    "surface-tint": "#b12d00",
                    "on-tertiary": "#ffffff",
                    "outline": "#926f66",
                    "on-secondary": "#ffffff",
                    "on-primary-fixed-variant": "#882000",
                    "surface-container-low": "#f2f4f6",
                    "surface-container-high": "#e6e8ea",
                    "error": "#ba1a1a",
                    "inverse-primary": "#ffb5a1",
                    "surface": "#f7f9fb",
                    "on-surface-variant": "#5d4038",
                    "tertiary-fixed-dim": "#a6c8ff",
                    "surface-variant": "#e0e3e5",
                    "error-container": "#ffdad6",
                    "on-secondary-fixed-variant": "#872101",
                    "inverse-surface": "#2d3133",
                    "on-error-container": "#93000a",
                    "tertiary-container": "#0075d8",
                    "on-background": "#191c1e",
                    "secondary-container": "#fc7550",
                    "inverse-on-surface": "#eff1f3",
                    "on-primary-container": "#fffbff",
                    "on-tertiary-container": "#fefcff",
                    "outline-variant": "#e7bdb2",
                    "surface-container-lowest": "#ffffff",
                    "secondary-fixed-dim": "#ffb5a1"
                },
                fontFamily: {
                    "headline": ["Manrope", "sans-serif"],
                    "body": ["Manrope", "sans-serif"],
                    "label": ["Manrope", "sans-serif"]
                },
                borderRadius: {
                    "DEFAULT": "1rem",
                    "lg": "2rem",
                    "xl": "3rem",
                    "full": "9999px"
                },
            },
        },
    }
</script> --}}

<link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet">


<title>@yield('title', 'Itenas Unit - Admin Panel')</title>

<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/admin/css/styles.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/icons/tabler-icons/tabler-icons.css') }}">

<!-- Admin CSS -->
<link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/components/css/modals.css') }}">
<!-- Vite CSS -->
@vite('resources/css/app.css')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body.admin-layout {
        background-color: #ffffff;
        overflow-x: hidden;
    }

    .page-wrapper {
        display: flex;
        min-height: 100vh;
        background-color: #ffffff;
    }

    /* Left Sidebar */
    .left-sidebar {
        width: 280px;
        background: white;
        border-right: 1px solid #edf2f7;
        height: 100vh;
        position: sticky;
        top: 0 !important;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    /* Body Wrapper - Main Content Area */
    .body-wrapper {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
        min-height: 100vh;
    }

    /* Navbar */
    .app-header {
        background-color: #ffffff;
        border-bottom: 1px solid #e6e6e6;
        height: 70px;
        display: flex;
        align-items: center;
        padding: 0 30px;
        position: sticky;
        top: 0;
        z-index: 10;
        width: 100%;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        padding: 30px;
        background-color: #ffffff;
    }

    /* Footer */
    .footer {
        padding: 20px 30px;
        border-top: 1px solid #edf2f7;
        background-color: #ffffff;
    }

    /* Sidebar Collapsed State */
    body[data-sidebartype="mini"] .left-sidebar {
        width: 80px;
    }

    body[data-sidebartype="mini"] .settings-nav-label,
    body[data-sidebartype="mini"] .nav-small-cap span:not(.nav-small-cap-icon) {
        display: none;
    }

    body[data-sidebartype="mini"] .brand-logo img {
        width: 40px;
        height: auto;
    }

    /* Responsive */
    @media (max-width: 1199.98px) {
        .left-sidebar {
            position: fixed;
            left: -280px;
            z-index: 1050;
            transition: left 0.2s ease;
        }

        .left-sidebar.show {
            left: 0;
        }

        .app-header {
            left: 0 !important;
            width: 100% !important;
        }
    }

    @media (max-width: 768px) {
        .app-header {
            padding: 0 20px;
        }

        .main-content {
            padding: 20px;
        }

        .footer {
            padding: 20px;
        }
    }
</style>