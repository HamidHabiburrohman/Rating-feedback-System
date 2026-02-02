<!-- resources/views/admin/auth/login.blade.php -->
@extends('layouts.auth.layout')

@section('title', 'Unit Rating Feedback - Admin Login')

@push('styles')
    <style>
        .auth-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 16px;
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
    </style>
@endpush

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
                                        <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="Logo" class="auth-logo">
                                    </a>
                                </div>

                                <!-- Title Section -->
                                <div class="text-center mb-4">
                                    <h5 class="fw-bold text-dark mb-1">Unit Rating Feedback System</h5>
                                    <p class="text-muted">Admin Dashboard Access</p>
                                </div>

                                <!-- Alert Section -->
                                <div class="auth-alerts">
                                    @if(session('status'))
                                        <x-alert type="success" :title="session('status')" auto-hide="true" duration="4000" />
                                    @endif

                                    @if(session('success'))
                                        <x-alert type="success" :title="session('success')" auto-hide="true" duration="4000" />
                                    @endif

                                    @if(session('error'))
                                        <x-alert type="error" :title="session('error')" auto-hide="false" />
                                    @endif

                                    {{-- Untuk validation errors --}}
                                    @if($errors->any())
                                        <x-alert type="error" :dismissible="false" :auto-hide="false">
                                            <div class="alert-title">Please fix the following errors:</div>
                                            <div class="alert-message">
                                                <ul style="margin: 6px 0 0 0; padding-left: 18px;">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </x-alert>
                                    @endif
                                </div>

                                <!-- Include Login Form -->
                                <!-- resources/views/admin/auth/partials/login-form.blade.php -->
                                <form method="POST" action="{{ route('admin.login.submit') }}">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required autofocus
                                            placeholder="admin@university.edu">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-medium">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required placeholder="Enter your password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Remember & Forgot -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted" for="remember">
                                                Remember me (30 days)
                                            </label>
                                        </div>
                                        <a class="text-primary fw-medium" href="#" id="forgotPassword">
                                            Forgot Password?
                                        </a>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-medium mb-4">
                                        <i class="ti ti-login me-2"></i>
                                        Sign In
                                    </button>
                                </form>

                                <!-- Footer -->
                                <div class="text-center mt-4">
                                    <p class="text-muted mb-2">
                                        For visitor access, go to
                                        <a href="{{ url('/') }}" class="text-primary fw-medium">main page</a>
                                    </p>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hapus event listener AJAX untuk form submit!
            // Biarkan form submit secara normal
            
            // Forgot password dengan toast alert
            document.getElementById('forgotPassword')?.addEventListener('click', function (e) {
                e.preventDefault();

                if (typeof showToast !== 'undefined') {
                    showToast({
                        type: 'info',
                        title: 'Password Reset',
                        message: 'Please contact system administrator to reset your password.',
                        duration: 4000
                    });
                } else {
                    alert('Please contact system administrator to reset your password.');
                }
            });
        });
    </script>
@endpush