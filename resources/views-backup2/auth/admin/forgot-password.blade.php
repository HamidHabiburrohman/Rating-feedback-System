@extends('layouts.admin.auth.login')

@section('title', 'Admin Forgot Password')

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
                                    <p class="text-muted">Enter your email to receive reset link</p>
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

                                <form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email"
                                            class="form-control glow-input @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required autofocus
                                            placeholder="admin@university.edu">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn w-100 py-3 fw-semibold mb-4 border-0" id="submitBtn"
                                        style="background: linear-gradient(135deg, #f8773c 0%, #f86c2a 100%); 
                                           color: #ffffff;
                                           letter-spacing: 0.5px;
                                           transition: all 0.3s ease;
                                           box-shadow: 0 4px 12px rgba(248, 119, 60, 0.2);">
                                        <span id="btnText">Send Reset Link</span>
                                        <span id="btnSpinner" class="d-none">
                                            <span class="spinner-border spinner-border-sm ms-2"></span>
                                        </span>
                                    </button>

                                    <div class="text-center">
                                        <a href="{{ route('admin.login') }}"
                                            style="color: #6c757d; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <i class="ti ti-arrow-left"></i>
                                            Back to Login
                                        </a>
                                    </div>
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
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forgotForm = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const emailInput = document.getElementById('email');

            forgotForm.addEventListener('submit', function(e) {
                let hasError = false;

                if (!emailInput.value.trim()) {
                    emailInput.classList.add('is-invalid');
                    hasError = true;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailInput.value.trim() && !emailRegex.test(emailInput.value)) {
                    emailInput.classList.add('is-invalid');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    emailInput.focus();
                } else {
                    submitBtn.disabled = true;
                    btnText.textContent = 'Sending...';
                    btnSpinner.classList.remove('d-none');
                }
            });

            emailInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
@endpush
