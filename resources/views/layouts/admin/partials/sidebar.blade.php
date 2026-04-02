<aside class="left-sidebar settings-sidebar" id="leftSidebar">
  <div class="sidebar-container">
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ route('admin.dashboard.index') }}" class="text-nowrap logo-img">
        <img src="{{ asset('assets/images/logos/logo1.svg')}}" alt="Unit Rating Feedback" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarClose">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>

    <nav class="sidebar-nav settings-panel-nav sidebar-scroll">
      <div class="settings-nav">
        <!-- DASHBOARD SECTION -->
        <div class="nav-small-cap">
          <iconify-icon icon="solar:home-smile-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Dashboard</span>
        </div>

        <a class="settings-nav-item {{ request()->routeIs('admin.dashboard.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.dashboard.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-width="1.5"
                d="M3 7.4V3.6a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v3.8a.6.6 0 0 1-.6.6H3.6a.6.6 0 0 1-.6-.6Zm11 13v-3.8a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v3.8a.6.6 0 0 1-.6.6h-5.8a.6.6 0 0 1-.6-.6Zm0-8V3.6a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v8.8a.6.6 0 0 1-.6.6h-5.8a.6.6 0 0 1-.6-.6Zm-11 8v-8.8a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v8.8a.6.6 0 0 1-.6.6H3.6a.6.6 0 0 1-.6-.6Z" />
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Dashboard</span>
          @if(request()->routeIs('admin.dashboard.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <!-- MASTER DATA SECTION -->
        <div class="nav-small-cap">
          <iconify-icon icon="solar:database-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Master Data</span>
        </div>

        <a class="settings-nav-item {{ request()->routeIs('admin.unit-types.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.unit-types.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" d="M6.5 8h4M14 8h3.5" />
                <path stroke-linejoin="round"
                  d="M2 15V9a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v6a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4Z" />
                <path d="M12 12.5a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3Z" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Unit Types</span>
          @if(request()->routeIs('admin.unit-types.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <a class="settings-nav-item {{ request()->routeIs('admin.unit-departments.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.unit-departments.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                  d="M2 18.5V12.1c0-1.12 0-1.68.218-2.108.192-.377.497-.682.874-.874C3.52 8.9 4.08 8.9 5.2 8.9h13.6c1.12 0 1.68 0 2.108.218.377.192.682.497.874.874.218.428.218.988.218 2.108v6.4c0 1.12 0 1.68-.218 2.108a2.002 2.002 0 0 1-.874.874c-.428.218-.988.218-2.108.218H5.2c-1.12 0-1.68 0-2.108-.218a2 2 0 0 1-.874-.874C2 20.18 2 19.62 2 18.5Z" />
                <path
                  d="M7 8.9V7.5c0-1.12 0-1.68.218-2.108.192-.377.497-.682.874-.874C8.52 4.3 9.08 4.3 10.2 4.3h3.6c1.12 0 1.68 0 2.108.218.377.192.682.497.874.874.218.428.218.988.218 2.108v1.4" />
                <path stroke-linecap="round" d="M12 15.5v2" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Departments</span>
          @if(request()->routeIs('admin.unit-departments.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <a class="settings-nav-item {{ request()->routeIs('admin.facilities.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.facilities.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M22 21H2" stroke-linecap="round" />
                <path stroke-linecap="round" d="M18 21V7m0 0v-3h-4v3m4-3h4" />
                <path
                  d="M4 21V9c0-.943 0-1.414.293-1.707C4.586 7 5.057 7 6 7h5c.943 0 1.414 0 1.707.293C13 7.586 13 8.057 13 9v12" />
                <path stroke-linecap="round" d="M8 11h2M8 15h2" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Facilities</span>
          @if(request()->routeIs('admin.facilities.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <a class="settings-nav-item {{ request()->routeIs('admin.rating-categories.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.rating-categories.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 2l2.5 5.5 6 .5-4.5 4 1.5 6-5.5-3.5-5.5 3.5 1.5-6-4.5-4 6-.5L12 2z" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Rating Categories</span>
          @if(request()->routeIs('admin.rating-categories.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <!-- UNIT MANAGEMENT SECTION -->
        <div class="nav-small-cap">
          <iconify-icon icon="solar:buildings-2-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Unit Management</span>
        </div>

        <a class="settings-nav-item {{ request()->routeIs('admin.units.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.units.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                  d="M17 11.805c0-.346 0-.519.052-.673c.151-.448.55-.621.95-.803c.448-.205.672-.307.895-.325c.252-.02.505.034.721.155c.286.16.486.466.69.714c.943 1.146 1.415 1.719 1.587 2.35c.14.51.14 1.044 0 1.553c-.251.922-1.046 1.694-1.635 2.41c-.301.365-.452.548-.642.655a1.27 1.27 0 0 1-.721.155c-.223-.018-.447-.12-.896-.325c-.4-.182-.798-.355-.949-.803c-.052-.154-.052-.327-.052-.672zm-10 0c0-.436-.012-.827-.364-1.133c-.128-.111-.298-.188-.637-.343c-.449-.204-.673-.307-.896-.325c-.667-.054-1.026.402-1.41.87c-.944 1.145-1.416 1.718-1.589 2.35a2.94 2.94 0 0 0 0 1.553c.252.921 1.048 1.694 1.636 2.409c.371.45.726.861 1.363.81c.223-.018.447-.12.896-.325c.34-.154.509-.232.637-.343c.352-.306.364-.697.364-1.132z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20 10.5V9c0-3.866-3.582-7-8-7S4 5.134 4 9v1.5m16 7c0 4.5-4 4.5-8 4.5" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Units</span>
          @if(request()->routeIs('admin.units.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <!-- FEEDBACK MANAGEMENT SECTION -->
        <div class="nav-small-cap">
          <iconify-icon icon="solar:chat-line-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Feedback Management</span>
        </div>

        <a class="settings-nav-item {{ request()->routeIs('admin.ratings.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.ratings.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 2l2.5 5.5 6 .5-4.5 4 1.5 6-5.5-3.5-5.5 3.5 1.5-6-4.5-4 6-.5L12 2z" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Ratings</span>
          @if(request()->routeIs('admin.ratings.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <a class="settings-nav-item {{ request()->routeIs('admin.admin-replies.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.admin-replies.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                  d="M22 7v6.5c0 1.4 0 2.1-.273 2.635a2.5 2.5 0 0 1-1.092 1.092C20.1 17.5 19.4 17.5 18 17.5H6.5l-4 4v-14C2.5 5.9 2.5 5.2 2.773 4.665a2.5 2.5 0 0 1 1.092-1.092C4.4 3.3 5.1 3.3 6.5 3.3h7" />
                <path stroke-linecap="round" d="M17 7h5m-2.5-2.5v5" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Admin Replies</span>
          @if(request()->routeIs('admin.admin-replies.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <a class="settings-nav-item {{ request()->routeIs('admin.reports.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.reports.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 8v4l2 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Reports</span>
          @if(request()->routeIs('admin.reports.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <!-- SYSTEM SECTION -->
        <div class="nav-small-cap">
          <iconify-icon icon="solar:settings-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">System</span>
        </div>

        <a class="settings-nav-item {{ request()->routeIs('admin.moderation-logs.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.moderation-logs.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" d="M12 8v4l2 2" />
                <path
                  d="M3 10c0-3.771 0-5.657 1.172-6.828C5.343 2 7.229 2 11 2h2c3.771 0 5.657 0 6.828 1.172C21 4.343 21 6.229 21 10v4c0 3.771 0 5.657-1.172 6.828C18.657 22 16.771 22 13 22h-2c-3.771 0-5.657 0-6.828-1.172C3 19.657 3 17.771 3 14v-4z" />
                <path stroke-linecap="round" d="M8 2v3M16 2v3" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Moderation Logs</span>
          @if(request()->routeIs('admin.moderation-logs.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>

        <a class="settings-nav-item {{ request()->routeIs('admin.exports.*') ? 'settings-nav-active' : '' }}"
          href="{{ route('admin.exports.index') }}">
          <span class="settings-nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                  d="M4 16v1.5c0 1.4 0 2.1.272 2.635a2.5 2.5 0 0 0 1.093 1.092C5.9 21.5 6.6 21.5 8 21.5h8c1.4 0 2.1 0 2.635-.273a2.5 2.5 0 0 0 1.092-1.092c.273-.535.273-1.235.273-2.635V16" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-3-3m3 3l3-3" />
              </g>
            </svg>
          </span>
          <span class="settings-nav-label hide-menu">Export Data</span>
          @if(request()->routeIs('admin.exports.*'))
            <span class="settings-nav-arrow">
              <i class="ti ti-chevron-right"></i>
            </span>
          @endif
        </a>
      </div>
    </nav>
  </div>
</aside>

<style>
  .left-sidebar {
    background: white;
    border-right: 1px solid #edf2f7;
    height: 100vh;
    position: sticky;
    top: 0;
    display: flex;
    flex-direction: column;
    width: 280px;
    flex-shrink: 0;
    transition: all 0.2s ease;
    font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  }

  .sidebar-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
  }

  .brand-logo {
    padding: 20px 24px;
    border-bottom: 1px solid #edf2f7;
    flex-shrink: 0;
  }

  .sidebar-nav {
    flex: 1 1 auto;
    overflow-y: auto;
    min-height: 0;
    padding: 1rem 0.75rem !important;
  }

  .sidebar-scroll {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f9fafb;
  }

  .sidebar-scroll::-webkit-scrollbar {
    width: 6px;
  }

  .sidebar-scroll::-webkit-scrollbar-track {
    background: #f9fafb;
    border-radius: 10px;
  }

  .sidebar-scroll::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 10px;
    transition: all 0.2s ease;
  }

  .sidebar-scroll::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
  }

  .sidebar-scroll::-webkit-scrollbar-thumb:active {
    background: #6b7280;
  }

  .settings-nav {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    padding-bottom: 2rem;
  }

  .settings-nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: #4b5563;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.2s ease;
    position: relative;
    margin: 2px 0;
    font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 450;
    font-size: 0.9rem;
  }

  .settings-nav-item:hover {
    background: #fef6f0;
    color: #f8773c;
  }

  .settings-nav-item:hover svg,
  .settings-nav-item:hover i {
    color: #f8773c;
    stroke: #f8773c;
  }

  .settings-nav-active {
    background: linear-gradient(135deg, #fff4ed, #fff);
    color: #f8773c !important;
    font-weight: 600;
    box-shadow: inset 0 0 0 1px rgba(248, 119, 60, 0.1);
  }

  .settings-nav-active svg,
  .settings-nav-active i,
  .settings-nav-active span {
    color: #f8773c !important;
    stroke: #f8773c !important;
  }

  .settings-nav-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    margin-right: 12px;
    flex-shrink: 0;
  }

  .settings-nav-icon svg {
    width: 20px;
    height: 20px;
  }

  .settings-nav-label {
    flex: 1;
    font-size: 0.9rem;
    font-weight: 450;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  }

  .settings-nav-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 8px;
    color: #f8773c;
    font-size: 1rem;
    opacity: 1;
  }

  .settings-nav-arrow i {
    font-size: 1.1rem;
    stroke-width: 1.8;
  }

  .nav-small-cap {
    color: #9ca3af;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 14px 16px 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  }

  .nav-small-cap span {
    font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 600;
    font-size: 0.75rem;
  }

  .nav-small-cap-icon {
    font-size: 1rem;
    color: #9ca3af;
  }

  @media (max-width: 1199.98px) {
    .left-sidebar {
      position: fixed;
      left: -280px;
      transition: left 0.2s ease;
      z-index: 1050;
      width: 280px;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.05);
    }

    .left-sidebar.show {
      left: 0;
    }

    .brand-logo {
      padding: 16px 20px;
    }
  }
</style>