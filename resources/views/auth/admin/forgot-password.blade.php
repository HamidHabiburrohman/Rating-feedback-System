@extends('layouts.admin.auth.forgot-password')

@section('title', 'Admin Forgot Password')

@push('styles')
    <style>
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
        }

        .btn-forgot {
            background: linear-gradient(135deg, #f8773c 0%, #f86c2a 100%);
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(248, 119, 60, 0.2);
        }

        .btn-forgot:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(248, 119, 60, 0.3);
        }

        .back-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .back-link:hover {
            color: #f8773c;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('forgot-content')
    <!-- Logo Section -->
    <div class="text-center mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logos/logo1.svg') }}" alt="Logo" class="auth-logo">
        </a>
    </div>

    <!-- Title Section -->
    <div class="text-center mb-4">
        <h5 class="fw-bold text-dark mb-1">Reset Password</h5>
        <p class="text-muted">Enter your email to receive reset link</p>
    </div>

    <!-- Alert Section -->
    <div class="auth-alerts">
        @if(session('status'))
            <div class="alert-success">
                <i class="ti ti-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <x-admin.alert type="error" :title="session('error')" auto-hide="false" />
        @endif

        @if($errors->any())
            <x-admin.alert type="error" :dismissible="false" :auto-hide="false">
                <div class="alert-title">Please fix the following errors:</div>
                <div class="alert-message">
                    <ul style="margin: 6px 0 0 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </x-admin.alert>
        @endif
    </div>

    <!-- Forgot Password Form -->
    <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="form-label fw-medium">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email') }}" required autofocus placeholder="admin@university.edu">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-forgot w-100 mb-3" id="submitBtn">
            <span id="btnText">Send Reset Link</span>
            <span id="btnSpinner" class="d-none">
                <span class="spinner-border spinner-border-sm ms-2"></span>
            </span>
        </button>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="back-link">
                <i class="ti ti-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const forgotForm = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const emailInput = document.getElementById('email');

            forgotForm.addEventListener('submit', function (e) {
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

            emailInput.addEventListener('input', function () {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
@endpush