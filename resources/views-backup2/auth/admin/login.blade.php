<!-- resources/views/admin/auth/login.blade.php -->
@extends('layouts.admin.auth.login')

@section('title', 'Unit Rating Feedback - Admin Login')

@section('auth-content')
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <div class="auth-container d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">

                        <!-- Login Card -->
                        <div class="card auth-card mb-0">
                            <div class="card-body p-5">

                                <!-- Logo Section -->
                                <div class="text-center mb-4">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ asset('assets/images/logos/logo1.svg') }}" alt="Logo"
                                            class="auth-logo">
                                    </a>
                                </div>

                                <!-- Title Section -->
                                <div class="text-center mb-4">
                                    <h5 class="fw-bold text-dark mb-1">Unit Rating Feedback System</h5>
                                    <p class="text-muted">Admin Dashboard Access</p>
                                </div>

                                <!-- Alert Section -->
                                <div class="auth-alerts">
                                    @if (session('status'))
                                        <X-admin type="success" :title="session('status')" auto-hide="true" duration="4000" />
                                    @endif

                                    @if (session('success'))
                                        <X-admin type="success" :title="session('success')" auto-hide="true" duration="4000" />
                                    @endif

                                    @if (session('error'))
                                        <X-admin type="error" :title="session('error')" auto-hide="false" />
                                    @endif

                                    {{-- Untuk validation errors --}}
                                    @if ($errors->any())
                                        <X-admin type="error" :dismissible="false" :auto-hide="false">
                                            <div class="alert-title">Please fix the following errors:</div>
                                            <div class="alert-message">
                                                <ul style="margin: 6px 0 0 0; padding-left: 18px;">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </X-admin>
                                    @endif
                                </div>

                                <!-- Include Login Form -->
                                <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email"
                                            class="form-control glow-input @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required autofocus
                                            placeholder="admin@university.edu" data-error-field="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-medium">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required placeholder="Enter your password"
                                            data-error-field="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Remember & Forgot -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted" for="remember">
                                                Remember me (30 days)
                                            </label>
                                        </div>
                                        <a class="fw-medium" style="color: #f8773c;"
                                            href="{{ route('admin.password.request') }}">
                                            Forgot Password?
                                        </a>
                                    </div>

                                    <!-- Submit Button dengan ID untuk manipulasi -->
                                    <button type="submit" class="btn w-100 py-3 fw-semibold mb-4 border-0" id="submitBtn"
                                        style="background: linear-gradient(135deg, #f8773c 0%, #f86c2a 100%); 
                                                color: #ffffff;
                                                letter-spacing: 0.5px;
                                                transition: all 0.3s ease;
                                                box-shadow: 0 4px 12px rgba(248, 119, 60, 0.2);">
                                        <span id="btnText">Sign In</span>
                                        <span id="btnIcon" class="ms-2 d-none">
                                            <i class="ti ti-alert-triangle"></i>
                                        </span>
                                    </button>
                                </form>

                                <!-- Footer -->
                                <div class="text-center mt-4">
                                    <p class="text-muted small">
                                        <i class="ti ti-shield-lock me-1"></i>
                                        Only authorized admin accounts can login
                                    </p>
                                </div>

                            </div>
                        </div>
                        <!-- End Login Card -->

                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .auth-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .auth-logo {
            max-width: 200px;
            height: auto;
        }

        /* Container untuk alerts */
        .auth-alerts {
            margin-bottom: 24px;
        }

        .form-control:focus {
            border-color: #f8773c;
            box-shadow: 0 0 0 0.25rem rgba(248, 119, 60, 0.25);
            background-color: #fff;
        }

        .form-control:focus::placeholder {
            color: rgba(0, 0, 0, 0.3);
        }

        .glow-input:focus {
            border-color: #f8773c;
            box-shadow: 0 0 0 0.25rem rgba(248, 119, 60, 0.25);
        }

        .form-check-input:checked {
            background-color: #f8773c;
            border-color: #f8773c;
        }

        /* Efek focus pada checkbox */
        .form-check-input:focus {
            border-color: #f8773c;
            box-shadow: 0 0 0 0.2rem rgba(248, 119, 60, 0.25);
        }

        /* Optional: Hover effect */
        .form-check-input:hover:not(:checked) {
            border-color: #f8773c;
        }

        /* Button error state */
        .btn-error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3) !important;
            animation: shake 0.5s ease-in-out;
        }

        .btn-error:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%) !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4) !important;
        }

        /* Shake animation untuk tombol error */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-3px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(3px);
            }
        }

        /* Pulsing effect untuk tombol error */
        @keyframes pulse-error {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .btn-pulse {
            animation: pulse-error 1.5s infinite;
        }

        /* Error highlight untuk input */
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            // Cek apakah ada error saat halaman dimuat
            function checkForErrors() {
                const hasEmailError = emailInput.classList.contains('is-invalid');
                const hasPasswordError = passwordInput.classList.contains('is-invalid');

                if (hasEmailError || hasPasswordError) {
                    // Tambahkan class error ke tombol
                    submitBtn.classList.add('btn-error', 'btn-pulse');
                    btnText.textContent = hasEmailError ? 'Invalid Email' : 'Invalid Password';
                    btnIcon.classList.remove('d-none');

                    // Reset tombol setelah 5 detik
                    setTimeout(resetButton, 5000);

                    // Tambahkan efek shake pada input yang error
                    if (hasEmailError) {
                        emailInput.classList.add('shake');
                        setTimeout(() => emailInput.classList.remove('shake'), 500);
                    }
                    if (hasPasswordError) {
                        passwordInput.classList.add('shake');
                        setTimeout(() => passwordInput.classList.remove('shake'), 500);
                    }
                }
            }

            // Reset tombol ke keadaan normal
            function resetButton() {
                submitBtn.classList.remove('btn-error', 'btn-pulse');
                btnText.textContent = 'Sign In';
                btnIcon.classList.add('d-none');
            }

            // Reset tombol ketika user mulai mengetik di input yang error
            emailInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    resetButton();
                }
            });

            passwordInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    resetButton();
                }
            });

            // Form submission dengan validasi client-side
            loginForm.addEventListener('submit', function(e) {
                // Reset tombol terlebih dahulu
                resetButton();

                // Validasi sederhana client-side
                let hasError = false;

                if (!emailInput.value.trim()) {
                    emailInput.classList.add('is-invalid');
                    hasError = true;
                }

                if (!passwordInput.value.trim()) {
                    passwordInput.classList.add('is-invalid');
                    hasError = true;
                }

                // Validasi format email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailInput.value.trim() && !emailRegex.test(emailInput.value)) {
                    emailInput.classList.add('is-invalid');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault(); // Mencegah form submit

                    // Tampilkan error di tombol
                    submitBtn.classList.add('btn-error', 'btn-pulse');
                    btnText.textContent = 'Please fix errors above';
                    btnIcon.classList.remove('d-none');

                    // Scroll ke input pertama yang error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }

                    // Reset tombol setelah 3 detik
                    setTimeout(resetButton, 3000);
                }
            });

            // Inisialisasi check saat halaman dimuat
            checkForErrors();
        });
    </script>
@endpush
