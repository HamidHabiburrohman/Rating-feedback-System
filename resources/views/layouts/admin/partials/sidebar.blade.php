<aside class="left-sidebar" id="leftSidebar">
    <div class="sidebar-inner">
        <div class="brand-logo">
            <a href="{{ route('admin.dashboard') }}" class="logo-anchor">
                <div class="logo-mark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                </div>
                <span class="logo-text">Itenas Unit</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section mt-3">
                <div class="nav-header">Dashboard</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <i class="ti ti-layout-dashboard"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-header">Master Data</div>
                <a href="{{ route('admin.unit-types.index') }}"
                    class="nav-item {{ request()->routeIs('admin.unit-types.*') ? 'active' : '' }}">
                    <i class="ti ti-tags"></i>
                    <span class="nav-label">Unit Types</span>
                </a>
                <a href="{{ route('admin.unit-departments.index') }}"
                    class="nav-item {{ request()->routeIs('admin.unit-departments.*') ? 'active' : '' }}">
                    <i class="ti ti-sitemap"></i>
                    <span class="nav-label">Departments</span>
                </a>
                <a href="{{ route('admin.facilities.index') }}"
                    class="nav-item {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                    <i class="ti ti-tools"></i>
                    <span class="nav-label">Facilities</span>
                </a>
                <a href="{{ route('admin.rating-categories.index') }}"
                    class="nav-item {{ request()->routeIs('admin.rating-categories.*') ? 'active' : '' }}">
                    <i class="ti ti-stars"></i>
                    <span class="nav-label">Rate Categories</span>
                </a>
                <a href="{{ route('admin.report-categories.index') }}"
                    class="nav-item {{ request()->routeIs('admin.report-categories.*') ? 'active' : '' }}">
                    <i class="ti ti-message-report"></i>
                    <span class="nav-label">Report Categories</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-header">Unit Management</div>
                <a href="{{ route('admin.units.index') }}"
                    class="nav-item {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                    <i class="ti ti-building"></i>
                    <span class="nav-label">Units</span>
                </a>

                <a href="{{  route('admin.qr-codes.index') }}"
                    class="nav-item {{ request()->routeIs('admin.qr-codes.*') ? 'active' : '' }}">
                    <i class="ti ti-qrcode"></i>
                    <span class="nav-label">QR Codes</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-header">HR Management</div>
                <a href="{{ route('admin.employees.index') }}"
                    class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <i class="ti ti-users"></i>
                    <span class="nav-label">Employees</span>
                </a>
                <a href="{{ route('admin.employees.index') }}"
                    class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <i class="ti ti-clipboard-list"></i>
                    <span class="nav-label">Assigments</span>
                </a>
                <a href="{{ route('admin.conversations.index') }}"
                    class="nav-item {{ request()->routeIs('admin.conversations.*') ? 'active' : '' }}">
                    <i class="ti ti-messages"></i>
                    <span class="nav-label">Conversations</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-header">Feedback</div>
                <a href="{{ route('admin.ratings.index') }}"
                    class="nav-item {{ request()->routeIs('admin.ratings.*') ? 'active' : '' }}">
                    <i class="ti ti-star"></i>
                    <span class="nav-label">Ratings</span>
                </a>
                <a href="{{ route('admin.reports.index') }}"
                    class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="ti ti-alert-triangle"></i>
                    <span class="nav-label">Reports</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-header">System</div>
                <a href="{{ route('admin.moderation-logs.index') }}"
                    class="nav-item {{ request()->routeIs('admin.moderation-logs.*') ? 'active' : '' }}">
                    <i class="ti ti-shield-check"></i>
                    <span class="nav-label">Moderation Logs</span>
                </a>
                <a href="{{ route('admin.exports.index') }}"
                    class="nav-item {{ request()->routeIs('admin.exports.*') ? 'active' : '' }}">
                    <i class="ti ti-file-export"></i>
                    <span class="nav-label">Export Data</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="ti ti-settings"></i>
                    <span class="nav-label">Settings</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

<style>
    .left-sidebar {
        position: fixed !important;
        top: 0;
        left: 0;
        bottom: 0;
        width: 270px;
        background: #ffffff;
        border-right: 1px solid rgba(15, 23, 42, 0.06);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0;
        overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .sidebar-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .brand-logo {
        height: 70px;
        padding: 20 24px;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        transition: padding 0.3s ease;
    }

    .logo-anchor {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        width: 100%;
        overflow: hidden;
    }

    .logo-mark {
        width: 36px;
        height: 36px;
        background: #fff5f0;
        color: #f8773c;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .logo-text {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
        white-space: nowrap;
        opacity: 1;
        transition: opacity 0.2s ease, width 0.2s ease;
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 16px 12px;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(15, 23, 42, 0.08);
        border-radius: 4px;
    }

    .nav-section {
        margin-bottom: 20px;
    }

    .nav-header {
        padding: 14px 16px 6px;
        font-size: 13px;
        font-weight: 600;
        color: #c2cad6;
        letter-spacing: 0.06em;
        white-space: nowrap;
        overflow: hidden;
        transition: opacity 0.2s ease, height 0.2s ease, padding 0.2s ease;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 12px 16px;
        margin: 6px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        border-radius: 20px;
        transition: all 0.2s ease;
        position: relative;
        white-space: nowrap;
    }

    .nav-item i {
        font-size: 20px;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        stroke-width: 1.5;
    }

    .nav-item:hover {
        background: #fef6f0;
        color: #f8773c;
    }

    .nav-item.active {
        background: linear-gradient(135deg, #fff6f0, #fff);
        color: #f8773c;
        font-weight: 600;
        border: 1px solid #fffaf8;
        box-shadow: inset 0 0 0 1px rgba(248, 119, 60, 0.1);
    }

    .nav-item.active i {
        color: #f8773c;
        stroke-width: 2;
    }

    .nav-label {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        opacity: 1;
        transition: opacity 0.2s ease, width 0.2s ease;
    }

    /* Collapsed State (Mini Sidebar) */
    body[data-sidebartype="mini"] .left-sidebar {
        width: 80px !important;
    }

    body[data-sidebartype="mini"] .brand-logo {
        padding: 20px 0;
        justify-content: center;
    }

    body[data-sidebartype="mini"] .logo-anchor {
        justify-content: center;
        width: auto;
    }

    body[data-sidebartype="mini"] .logo-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
        position: absolute;
    }

    body[data-sidebartype="mini"] .nav-header {
        opacity: 0;
        height: 0;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    body[data-sidebartype="mini"] .nav-item {
        justify-content: center;
        padding: 10px;
        margin: 4px 12px;
        gap: 0;
    }

    body[data-sidebartype="mini"] .nav-label {
        opacity: 0;
        width: 0;
        overflow: hidden;
        position: absolute;
    }

    /* Mobile State */
    @media (max-width: 1199.98px) {
        .left-sidebar {
            transform: translateX(-100%);
            box-shadow: none;
            width: 270px !important;
        }

        .left-sidebar.show {
            transform: translateX(0);
            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.08);
        }

        body[data-sidebartype="mini"] .left-sidebar {
            width: 270px !important;
            transform: translateX(-100%);
        }

        body[data-sidebartype="mini"] .left-sidebar.show {
            transform: translateX(0);
        }

        body[data-sidebartype="mini"] .brand-logo {
            padding: 0 20px;
            justify-content: flex-start;
        }

        body[data-sidebartype="mini"] .logo-anchor {
            justify-content: flex-start;
            width: 100%;
        }

        body[data-sidebartype="mini"] .logo-text,
        body[data-sidebartype="mini"] .nav-header,
        body[data-sidebartype="mini"] .nav-label {
            opacity: 1;
            width: auto;
            height: auto;
            position: static;
        }

        body[data-sidebartype="mini"] .nav-header {
            padding: 12px 24px 8px;
        }

        body[data-sidebartype="mini"] .nav-item {
            justify-content: flex-start;
            padding: 10px 16px;
            gap: 14px;
        }
    }
</style>