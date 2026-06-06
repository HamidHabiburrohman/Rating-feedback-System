@extends('layouts.admin.auth.login')

@section('title', 'Admin Reset Password')

@section('auth-content')
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
                                    <h5 class="fw-bold text-dark mb-1">Reset Password</h5>
                                    <p class="text-muted">Enter your new password</p>
                                </div>

                                <div class="auth-alerts">
                                    @if (session('status'))
                                        <x-admin.alert type="success" :title="session('status')" auto-hide="true" duration="4000" />
                                    @endif

                                    @if (session('error'))
                                        <x-admin.alert type="error" :title="session('error')" auto-hide="false" />
                                    @endif

                                    @if ($errors->any())
                                        <x-admin.alert type="error" :dismissible="false" :auto-hide="false">
                                            <div class="alert-title">Please fix the following errors:</div>
                                            <div class="alert-message">
                                                <ul style="margin: 6px 0 0 0; padding-left: 18px;">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </x-admin.alert>
                                    @endif
                                </div>

                                <form method="POST" action="{{ route('admin.password.update') }}" id="resetForm">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token ?? '' }}">

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email"
                                            class="form-control glow-input @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $email ?? '') }}" required
                                            readonly>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-medium">New Password</label>
                                        <input type="password"
                                            class="form-control glow-input @error('password') is-invalid @enderror"
                                            id="password" name="password" required placeholder="Enter new password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="password-requirements mb-3" id="passwordRequirements">
                                        <div class="requirement-item" id="reqLength">
                                            <i class="ti ti-circle"></i>
                                            At least 8 characters
                                        </div>
                                        <div class="requirement-item" id="reqUppercase">
                                            <i class="ti ti-circle"></i>
                                            At least one uppercase letter
                                        </div>
                                        <div class="requirement-item" id="reqLowercase">
                                            <i class="ti ti-circle"></i>
                                            At least one lowercase letter
                                        </div>
                                        <div class="requirement-item" id="reqNumber">
                                            <i class="ti ti-circle"></i>
                                            At least one number
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label fw-medium">Confirm
                                            Password</label>
                                        <input type="password" class="form-control glow-input" id="password_confirmation"
                                            name="password_confirmation" required placeholder="Confirm new password">
                                        <div id="passwordMatch" class="password-match"></div>
                                    </div>

                                    <button type="submit" class="btn w-100 py-3 fw-semibold mb-4 border-0" id="submitBtn"
                                        disabled
                                        style="background: linear-gradient(135deg, #f8773c 0%, #f86c2a 100%); 
                                           color: #ffffff;
                                           letter-spacing: 0.5px;
                                           transition: all 0.3s ease;
                                           box-shadow: 0 4px 12px rgba(248, 119, 60, 0.2);">
                                        <span id="btnText">Reset Password</span>
                                        <span id="btnSpinner" class="d-none">
                                            <span class="spinner-border spinner-border-sm ms-2"></span>
                                        </span>
                                    </button>
                                </form>

                            </div>
                        </div>

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

        .auth-alerts {
            margin-bottom: 24px;
        }

        .form-control:focus {
            border-color: #f8773c;
            box-shadow: 0 0 0 0.25rem rgba(248, 119, 60, 0.25);
            background-color: #fff;
        }

        .glow-input:focus {
            border-color: #f8773c;
            box-shadow: 0 0 0 0.25rem rgba(248, 119, 60, 0.25);
        }

        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .requirement-item.valid {
            color: #10b981;
        }

        .requirement-item i {
            font-size: 1rem;
        }

        .password-match {
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .text-match {
            color: #10b981;
        }

        .text-not-match {
            color: #dc3545;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resetForm = document.getElementById('resetForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const passwordMatch = document.getElementById('passwordMatch');

            const reqLength = document.getElementById('reqLength');
            const reqUppercase = document.getElementById('reqUppercase');
            const reqLowercase = document.getElementById('reqLowercase');
            const reqNumber = document.getElementById('reqNumber');

            function checkPasswordStrength() {
                const value = password.value;
                let isValid = true;

                if (value.length >= 8) {
                    reqLength.classList.add('valid');
                    reqLength.querySelector('i').className = 'ti ti-check-circle';
                } else {
                    reqLength.classList.remove('valid');
                    reqLength.querySelector('i').className = 'ti ti-circle';
                    isValid = false;
                }

                if (/[A-Z]/.test(value)) {
                    reqUppercase.classList.add('valid');
                    reqUppercase.querySelector('i').className = 'ti ti-check-circle';
                } else {
                    reqUppercase.classList.remove('valid');
                    reqUppercase.querySelector('i').className = 'ti ti-circle';
                    isValid = false;
                }

                if (/[a-z]/.test(value)) {
                    reqLowercase.classList.add('valid');
                    reqLowercase.querySelector('i').className = 'ti ti-check-circle';
                } else {
                    reqLowercase.classList.remove('valid');
                    reqLowercase.querySelector('i').className = 'ti ti-circle';
                    isValid = false;
                }

                if (/\d/.test(value)) {
                    reqNumber.classList.add('valid');
                    reqNumber.querySelector('i').className = 'ti ti-check-circle';
                } else {
                    reqNumber.classList.remove('valid');
                    reqNumber.querySelector('i').className = 'ti ti-circle';
                    isValid = false;
                }

                return isValid;
            }

            function checkPasswordMatch() {
                if (confirmPassword.value === '') {
                    passwordMatch.innerHTML = '';
                    return false;
                }

                if (password.value === confirmPassword.value) {
                    passwordMatch.innerHTML =
                        '<span class="text-match"><i class="ti ti-check-circle"></i> Passwords match</span>';
                    return true;
                } else {
                    passwordMatch.innerHTML =
                        '<span class="text-not-match"><i class="ti ti-alert-circle"></i> Passwords do not match</span>';
                    return false;
                }
            }

            function validateForm() {
                const isStrong = checkPasswordStrength();
                const isMatch = checkPasswordMatch();
                const hasValue = password.value.length > 0;

                submitBtn.disabled = !(isStrong && isMatch && hasValue);
            }

            password.addEventListener('input', validateForm);
            confirmPassword.addEventListener('input', validateForm);

            resetForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return;
                }

                submitBtn.disabled = true;
                btnText.textContent = 'Resetting...';
                btnSpinner.classList.remove('d-none');
            });
        });
    </script>
@endpush
