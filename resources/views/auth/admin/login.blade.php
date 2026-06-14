<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Unit Rating Feedback - Admin Login')</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logos/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #f8773c;
            --primary-orange-dark: #f86c2a;
            --primary-orange-light: #ff9a6b;
            --success-green: #28a745;
            --error-red: #dc3545;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .page-wrapper {
            min-height: 100vh;
        }

        .auth-container {
            background: var(--bg-gradient);
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        .auth-card:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .auth-logo {
            max-width: 200px;
            height: auto;
        }

        .auth-alerts {
            margin-bottom: 24px;
        }

        .form-control:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.25rem rgba(248, 119, 60, 0.25);
            background-color: #fff;
        }

        .form-control:focus::placeholder {
            color: rgba(0, 0, 0, 0.3);
        }

        .glow-input:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.25rem rgba(248, 119, 60, 0.25);
        }

        .form-check-input:checked {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .form-check-input:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.2rem rgba(248, 119, 60, 0.25);
        }

        .form-check-input:hover:not(:checked) {
            border-color: var(--primary-orange);
        }

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

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
            20%, 40%, 60%, 80% { transform: translateX(3px); }
        }

        @keyframes pulse-error {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }

        .btn-pulse {
            animation: pulse-error 1.5s infinite;
        }

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

        .shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="auth-container d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">

                        <div class="card auth-card mb-0">
                            <div class="card-body p-5">

                                <div class="text-center mb-4">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ asset('assets/images/logos/logo1.svg') }}" alt="Logo"
                                            class="auth-logo">
                                    </a>
                                </div>

                                <div class="text-center mb-4">
                                    <h5 class="fw-bold text-dark mb-1">Unit Rating Feedback System</h5>
                                    <p class="text-muted">Admin Dashboard Access</p>
                                </div>

                                <div class="auth-alerts">
                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('status') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger" role="alert">
                                            <div class="alert-title fw-bold">Please fix the following errors:</div>
                                            <div class="alert-message">
                                                <ul style="margin: 6px 0 0 0; padding-left: 18px;">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email"
                                            class="form-control rounded-2 glow-input @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required autofocus
                                            placeholder="admin@university.edu" data-error-field="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-medium">Password</label>
                                        <input type="password" class="form-control rounded-2 @error('password') is-invalid @enderror"
                                            id="password" name="password" required placeholder="Enter your password"
                                            data-error-field="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

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

                                    <button type="submit" class="btn w-100 py-3 fw-semibold mb-4 border-0 rounded-pill" id="submitBtn"
                                        style="background: linear-gradient(135deg, #f8773c 0%, #f86c2a 100%); 
                                                color: #ffffff;
                                                letter-spacing: 0.5px;
                                                transition: all 0.3s ease;
                                                box-shadow: 0 4px 12px rgba(248, 119, 60, 0.2);;">
                                        <span id="btnText">Sign In</span>
                                        <span id="btnIcon" class="ms-2 d-none">
                                            <i class="ti ti-alert-triangle"></i>
                                        </span>
                                    </button>
                                </form>

                                <div class="text-center mt-4">
                                    <p class="text-muted small">
                                        <i class="ti ti-shield-lock me-1"></i>
                                        Only authorized admin accounts can login
                                    </p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            function checkForErrors() {
                const hasEmailError = emailInput.classList.contains('is-invalid');
                const hasPasswordError = passwordInput.classList.contains('is-invalid');

                if (hasEmailError || hasPasswordError) {
                    submitBtn.classList.add('btn-error', 'btn-pulse');
                    btnText.textContent = hasEmailError ? 'Invalid Email' : 'Invalid Password';
                    btnIcon.classList.remove('d-none');

                    setTimeout(resetButton, 5000);

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

            function resetButton() {
                submitBtn.classList.remove('btn-error', 'btn-pulse');
                btnText.textContent = 'Sign In';
                btnIcon.classList.add('d-none');
            }

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

            loginForm.addEventListener('submit', function(e) {
                resetButton();

                let hasError = false;

                if (!emailInput.value.trim()) {
                    emailInput.classList.add('is-invalid');
                    hasError = true;
                }

                if (!passwordInput.value.trim()) {
                    passwordInput.classList.add('is-invalid');
                    hasError = true;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailInput.value.trim() && !emailRegex.test(emailInput.value)) {
                    emailInput.classList.add('is-invalid');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();

                    submitBtn.classList.add('btn-error', 'btn-pulse');
                    btnText.textContent = 'Please fix errors above';
                    btnIcon.classList.remove('d-none');

                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }

                    setTimeout(resetButton, 3000);
                }
            });

            checkForErrors();
        });
    </script>
</body>
</html>