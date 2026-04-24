<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet">

<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}">

<title>@yield('title', 'Itenas Unit - Admin Panel')</title>

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