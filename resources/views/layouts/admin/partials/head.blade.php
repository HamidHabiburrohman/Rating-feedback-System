<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">

<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}">

<title>@yield('title', 'Itenas Unit - Admin Panel')</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css" />

<link rel="stylesheet" href="{{ asset('assets/admin/css/styles.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/components/css/modals.css') }}">

@vite('resources/css/app.css')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 400;
    }

    body {
        background-color: #f8fafc;
    }

    body.admin-layout {
        background-color: #f8fafc;
        overflow-x: hidden;
    }

        .page-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

    .left-sidebar {
        width: 270px;
        background: white;
        border-right: 1px solid #edf2f7;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        transition: width 0.2s ease;
    }

    body[data-sidebartype="mini"] .left-sidebar {
        width: 80px;
    }

    .body-wrapper {
        margin-left: 270px;
        width: calc(100% - 270px);
        display: flex;
        flex-direction: column;
        background-color: #f8fafc;
        min-height: 100vh;
        transition: margin-left 0.2s ease, width 0.2s ease;
    }

    body[data-sidebartype="mini"] .body-wrapper {
        margin-left: 80px;
        width: calc(100% - 80px);
    }

    .app-header {
        position: sticky;
        top: 0;
        width: 100%;
        height: 70px;
        background: #ffffff;
        border-bottom: 1px solid #edf2f7;
        z-index: 99;
        display: flex;
        align-items: center;
        padding: 0;
        margin: 0;
    }

    #main-content {
        flex: 1;
        /* padding: 24px; */
        background-color: #f8fafc;
    }

    .footer {
        padding: 20px 24px;
        border-top: 1px solid #edf2f7;
        background-color: #ffffff;
    }

    body[data-sidebartype="mini"] .settings-nav-label,
    body[data-sidebartype="mini"] .nav-small-cap span:not(.nav-small-cap-icon) {
        display: none;
    }

    body[data-sidebartype="mini"] .brand-logo {
        justify-content: center;
        padding: 16px 0;
    }

    body[data-sidebartype="mini"] .close-btn {
        display: none;
    }

    @media (max-width: 1199.98px) {
        .left-sidebar {
            position: fixed;
            left: -270px;
            z-index: 1050;
            transition: left 0.2s ease;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
        }

        .left-sidebar.show {
            left: 0;
        }

        .body-wrapper {
            margin-left: 0;
            width: 100%;
        }

        body[data-sidebartype="mini"] .left-sidebar {
            left: -80px;
        }

        .app-header {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        #main-content {
            padding: 16px;
        }

        .footer {
            padding: 16px;
        }

        .app-header {
            padding: 0;
        }

        .left-sidebar {
            width: 270px;
        }

        body[data-sidebartype="mini"] .left-sidebar {
            width: 270px;
        }
    }

    .sidebar-container {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
    }

    .main-content {
        flex: 1;
        padding: 24px;
        background-color: #f8fafc;
    }
</style>
