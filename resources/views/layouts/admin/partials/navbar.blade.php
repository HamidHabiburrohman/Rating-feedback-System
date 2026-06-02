@php
    $admin = Auth::guard('admin')->user();
    $adminName = $admin?->nama ?? 'Admin';
    $adminPhoto = $admin?->photo ?? null;
    $adminRole = $admin?->role ?? 'admin';
    $adminPhotoUrl = $admin?->photo_url ?? null;
    $adminInitials = $admin?->initials ?? 'AD';
@endphp

<nav class="navbar navbar-expand w-100">
    <div class="d-flex align-items-center">
        <button class="btn btn-link d-xl-none p-0 me-3" type="button" id="sidebarToggle">
            <i class="ti ti-menu-2" style="font-size: 1.5rem; color: #64748b;"></i>
        </button>
    </div>

    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-wrapper">
                    @if ($adminPhotoUrl)
                        <img src="{{ $adminPhotoUrl }}" alt="{{ $adminName }}" class="rounded-circle">
                    @else
                        <div class="avatar-fallback">
                            <span class="fw-bold" style="font-size: 0.875rem;">{{ $adminInitials }}</span>
                        </div>
                    @endif
                </div>
                <i class="ti ti-chevron-down"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <div class="dropdown-header d-flex align-items-center gap-3 py-3">
                        <div class="avatar-wrapper-lg">
                            @if ($adminPhotoUrl)
                                <img src="{{ $adminPhotoUrl }}" alt="{{ $adminName }}" class="rounded">
                            @else
                                <div class="avatar-fallback-lg">
                                    <span class="fw-bold" style="font-size: 1.25rem;">{{ $adminInitials }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ $adminName }}</h6>
                            <small class="text-muted text-uppercase">
                                @if ($adminRole === 'super_admin')
                                    Super Admin
                                @elseif($adminRole === 'admin')
                                    Administrator
                                @elseif($adminRole === 'unit')
                                    Unit Admin
                                @else
                                    {{ ucfirst($adminRole) }}
                                @endif
                            </small>
                        </div>
                    </div>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item" href="{{ route('admin.profile.show') }}">
                        <i class="ti ti-user me-2"></i> My Profile
                    </a>
                </li>
                {{-- <li>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="ti ti-settings me-2"></i> Settings
                    </a>
                </li> --}}
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="ti ti-help me-2"></i> Help Support
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <form method="POST" action="{{ route('admin.logout') }}" id="logout-form" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="dropdown-item text-danger"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ti ti-logout me-2"></i> Logout
                    </button>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<style>
    .avatar-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f8773c, #e5652a);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .avatar-wrapper-lg {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f8773c, #e5652a);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .avatar-wrapper-lg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-fallback-lg {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .navbar .dropdown-menu {
        border: 1px solid #edf2f7 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important;
        border-radius: 12px !important;
        padding: 8px !important;
        margin-top: 8px !important;
        min-width: 240px;
    }

    .navbar .dropdown-item {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .navbar .dropdown-item:hover {
        background: #f8fafc;
        color: #f8773c;
    }

    .navbar .dropdown-item i {
        font-size: 1.1rem;
        color: #64748b;
    }

    .navbar .dropdown-item:hover i {
        color: #f8773c;
    }

    .navbar .dropdown-item.text-danger:hover {
        background: #fef2f2 !important;
        color: #dc2626 !important;
    }

    .navbar .dropdown-item.text-danger:hover i {
        color: #dc2626 !important;
    }

    .navbar .dropdown-header {
        padding: 8px 12px;
        color: #1e293b;
    }

    .navbar .dropdown-divider {
        margin: 8px 0;
        border-top-color: #edf2f7;
    }

    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 100%;
        padding: 0;
    }

    .navbar .nav-link.dropdown-toggle {
        padding: 6px 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .navbar .nav-link.dropdown-toggle::after {
        display: none;
    }

    .navbar .nav-link.dropdown-toggle i.ti-chevron-down {
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }

    .navbar .nav-link.dropdown-toggle.show i.ti-chevron-down {
        transform: rotate(180deg);
    }

    #sidebarToggle {
        background: transparent;
        border: none;
        color: #64748b;
        padding: 6px;
        border-radius: 8px;
        transition: all 0.2s ease;
        line-height: 1;
    }

    #sidebarToggle:hover {
        background: #f8fafc;
    }
</style>
