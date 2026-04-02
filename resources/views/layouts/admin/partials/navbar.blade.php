@php
    $user = Auth::guard('web')->user();
    $userName = $user?->name ?? $user?->nama ?? 'Admin';
    $userPhoto = $user?->profile_photo_path ?? null;
    $userRole = $user?->role ?? 'admin';
@endphp

<nav class="navbar navbar-expand w-100">
    <!-- Left side - breadcrumb atau judul halaman -->
    <div class="d-flex align-items-center">
        <!-- Sidebar toggle button untuk mobile -->
        <button class="btn btn-link d-xl-none p-0 me-3" type="button" id="sidebarToggle">
            <i class="ti ti-menu-2" style="font-size: 1.5rem; color: #64748b;"></i>
        </button>
        
        <!-- Breadcrumb bisa ditambahkan di sini nanti -->
    </div>

    <!-- Right side - user menu -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" 
               id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-wrapper">
                    @if($userPhoto)
                        <img src="{{ asset('storage/' . $userPhoto) }}" alt="{{ $userName }}" class="rounded-circle">
                    @else
                        <div class="avatar-fallback">
                            <i class="ti ti-user"></i>
                        </div>
                    @endif
                </div>
                <i class="ti ti-chevron-down"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <!-- Profile Header -->
                <li>
                    <div class="dropdown-header d-flex align-items-center gap-3 py-3">
                        <div class="avatar-wrapper-lg">
                            @if($userPhoto)
                                <img src="{{ asset('storage/' . $userPhoto) }}" alt="{{ $userName }}" class="rounded">
                            @else
                                <div class="avatar-fallback-lg">
                                    <i class="ti ti-user"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ $userName }}</h6>
                            <small class="text-muted text-uppercase">{{ $userRole }}</small>
                        </div>
                    </div>
                </li>
                
                <li><hr class="dropdown-divider"></li>
                
                <!-- Menu Items -->
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="ti ti-user me-2"></i> My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="ti ti-settings me-2"></i> Settings
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="ti ti-help me-2"></i> Help Support
                    </a>
                </li>
                
                <li><hr class="dropdown-divider"></li>
                
                <!-- Logout -->
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ti ti-logout me-2"></i> Logout
                    </button>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<style>
/* Hanya styling minimal yang diperlukan, sisanya pakai dari header.scss */
.avatar-wrapper {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f8fafc;
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
    color: #64748b;
}

.avatar-fallback i {
    font-size: 1.2rem;
}

.avatar-wrapper-lg {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #edf2f7;
    overflow: hidden;
}

.avatar-fallback-lg {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 1.5rem;
}

/* Override Bootstrap dropdown styling agar sesuai dengan tema */
.navbar .dropdown-menu {
    border: 1px solid #edf2f7 !important;
    box-shadow: none !important;
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
    color: #2563eb;
}

.navbar .dropdown-item i {
    font-size: 1.1rem;
    color: #64748b;
}

.navbar .dropdown-item:hover i {
    color: #2563eb;
}

.navbar .dropdown-item.text-danger:hover {
    background: #fef2f2 !important;
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

/* Styling untuk toggle button */
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
    display: none; /* Hide default Bootstrap dropdown arrow */
}

.navbar .nav-link.dropdown-toggle i.ti-chevron-down {
    font-size: 0.8rem;
    transition: transform 0.2s ease;
}

.navbar .nav-link.dropdown-toggle.show i.ti-chevron-down {
    transform: rotate(180deg);
}

/* Mobile toggle button */
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