<!-- resources/views/layouts/admin/partials/navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end gap-2">
            <!-- Notifications Dropdown (commented) -->

            <!-- User Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center gap-2 p-1" href="javascript:void(0)" id="userMenu"
                    data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                    <div class="position-relative">
                        <img src="{{ asset('assets/images/profile/user1.jpg')}}" width="36" height="36"
                            class="rounded-circle border border-2 border-white shadow-sm">
                    </div>
                    <i class="ti ti-chevron-down fs-4 text-muted d-none d-md-block"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up border-0 shadow-lg p-0"
                    aria-labelledby="userMenu" style="min-width: 260px; margin-top: 8px !important;">
                    <!-- Header -->
                    <div class="p-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                <img src="{{ asset('assets/images/profile/user1.jpg')}}" alt="{{ Auth::user()->name }}"
                                    width="48" height="48" class="rounded-circle border border-2 border-primary">
                                <span
                                    class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                    style="width: 12px; height: 12px;"></span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="mb-0 fw-semibold text-truncate">{{ Auth::user()->name }}</h6>
                                <small class="text-muted text-truncate d-block">{{ Auth::user()->email }}</small>
                                <span class="badge bg-primary bg-opacity-10 text-primary fs-2 mt-1">Administrator</span>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="p-2">
                        <a href="javascript:void(0)"
                            class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 mb-1 rounded-2">
                            <i class="ti ti-user fs-5 text-primary" style="width: 24px;"></i>
                            <span class="flex-grow-1">My Profile</span>
                        </a>

                        <a href="javascript:void(0)"
                            class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 mb-1 rounded-2">
                            <i class="ti ti-settings fs-5 text-primary" style="width: 24px;"></i>
                            <span class="flex-grow-1">Settings</span>
                        </a>

                        <a href="javascript:void(0)"
                            class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded-2">
                            <i class="ti ti-help fs-5 text-primary" style="width: 24px;"></i>
                            <span class="flex-grow-1">Help & Support</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <hr class="my-1 mx-3">

                    <!-- Logout -->
                    <div class="p-2">
                        <button type="button" id="logoutBtn"
                            class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 rounded-2 w-100 text-danger"
                            style="background: none; border: none; cursor: pointer;">
                            <i class="ti ti-logout fs-5" style="width: 24px;"></i>
                            <span class="flex-grow-1 fw-medium">Logout</span>
                        </button>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>