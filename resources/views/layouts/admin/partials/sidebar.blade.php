<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ url('/admin/dashboard') }}" class="text-nowrap logo-img">
        <img src="{{ asset('assets/images/logos/logo1.svg')}}" alt="Unit Rating Feedback" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>

    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">

        <li class="nav-small-cap">
          <iconify-icon icon="solar:home-smile-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Dashboard</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link primary-hover-bg" href="{{ url('/admin/dashboard') }}" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-width="1.5"
                d="M3 7.4V3.6a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v3.8a.6.6 0 0 1-.6.6H3.6a.6.6 0 0 1-.6-.6Zm11 13v-3.8a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v3.8a.6.6 0 0 1-.6.6h-5.8a.6.6 0 0 1-.6-.6Zm0-8V3.6a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v8.8a.6.6 0 0 1-.6.6h-5.8a.6.6 0 0 1-.6-.6Zm-11 8v-8.8a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v8.8a.6.6 0 0 1-.6.6H3.6a.6.6 0 0 1-.6-.6Z" />
            </svg>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <li>
          <span class="sidebar-divider lg"></span>
        </li>

        <li class="nav-small-cap">
          <iconify-icon icon="solar:buildings-2-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Unit Management</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link primary-hover-bg"
            href="{{ route('admin.unit-types.index') }}" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" d="M6.5 8h4M14 8h3.5" />
                <path stroke-linejoin="round"
                  d="M2 15V9a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v6a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4Z" />
                <path d="M12 12.5a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3Z" />
              </g>
            </svg>
            <span class="hide-menu">Types</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link primary-hover-bg"
            href="{{ url('/admin/units') }}" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                  d="M17 11.805c0-.346 0-.519.052-.673c.151-.448.55-.621.95-.803c.448-.205.672-.307.895-.325c.252-.02.505.034.721.155c.286.16.486.466.69.714c.943 1.146 1.415 1.719 1.587 2.35c.14.51.14 1.044 0 1.553c-.251.922-1.046 1.694-1.635 2.41c-.301.365-.452.548-.642.655a1.27 1.27 0 0 1-.721.155c-.223-.018-.447-.12-.896-.325c-.4-.182-.798-.355-.949-.803c-.052-.154-.052-.327-.052-.672zm-10 0c0-.436-.012-.827-.364-1.133c-.128-.111-.298-.188-.637-.343c-.449-.204-.673-.307-.896-.325c-.667-.054-1.026.402-1.41.87c-.944 1.145-1.416 1.718-1.589 2.35a2.94 2.94 0 0 0 0 1.553c.252.921 1.048 1.694 1.636 2.409c.371.45.726.861 1.363.81c.223-.018.447-.12.896-.325c.34-.154.509-.232.637-.343c.352-.306.364-.697.364-1.132z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20 10.5V9c0-3.866-3.582-7-8-7S4 5.134 4 9v1.5m16 7c0 4.5-4 4.5-8 4.5" />
              </g>
            </svg>
            <span class="hide-menu">Units</span>
          </a>
        </li>

        <li>
          <span class="sidebar-divider lg"></span>
        </li>

        <li class="nav-small-cap">
          <iconify-icon icon="solar:chat-line-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Feedback Management</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link primary-hover-bg" href="{{ url('/admin/ratings') }}" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 2l2.5 5.5 6 .5-4.5 4 1.5 6-5.5-3.5-5.5 3.5 1.5-6-4.5-4 6-.5L12 2z"/>
              </g>
            </svg>
            <span class="hide-menu">Ratings</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link primary-hover-bg" href="{{ url('/admin/reports') }}" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
              </g>
            </svg>
            <span class="hide-menu">Reports</span>
          </a>
        </li>

        <li>
          <span class="sidebar-divider lg"></span>
        </li>

        <li>
          <span class="sidebar-divider lg"></span>
        </li>

        <li class="nav-small-cap">
          <iconify-icon icon="solar:settings-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">System</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link primary-hover-bg" href="{{ url('/admin/settings') }}" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
              </g>
            </svg>
            <span class="hide-menu">Settings</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link primary-hover-bg" href="{{ url('/admin/audit-logs') }}" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" d="M12 8v4l2 2"/>
                <path d="M3 10c0-3.771 0-5.657 1.172-6.828C5.343 2 7.229 2 11 2h2c3.771 0 5.657 0 6.828 1.172C21 4.343 21 6.229 21 10v4c0 3.771 0 5.657-1.172 6.828C18.657 22 16.771 22 13 22h-2c-3.771 0-5.657 0-6.828-1.172C3 19.657 3 17.771 3 14v-4z"/>
                <path stroke-linecap="round" d="M8 2v3M16 2v3"/>
              </g>
            </svg>
            <span class="hide-menu">Audit Logs</span>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>