@extends('layouts.admin.auth.reset-password')

@section('title', 'Admin Reset Password')

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

    .btn-reset {
        background: linear-gradient(135deg, #f8773c 0%, #f86c2a 100%);
        color: white;
        border: none;
        padding: 12px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(248, 119, 60, 0.2);
    }

    .btn-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(248, 119, 60, 0.3);
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
@endpush

@section('reset-content')
    <!-- Logo Section -->
    <div class="text-center mb-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/images/logos/logo1.svg') }}" alt="Logo" class="auth-logo">
        </a>
    </div>

    <!-- Title Section -->
    <div class="text-center mb-4">
        <h5 class="fw-bold text-dark mb-1">Reset Password</h5>
        <p class="text-muted">Enter your new password</p>
    </div>

    <!-- Alert Section -->
    <div class="auth-alerts">
        @if(session('status'))
            <x-admin.alert type="success" :title="session('status')" auto-hide="true" duration="4000" />
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

    <!-- Reset Password Form -->
    <form method="POST" action="{{ route('password.update') }}" id="resetForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token ?? '' }}">

        <!-- Email (readonly) -->
        <div class="mb-3">
            <label for="email" class="form-label fw-medium">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email', $email ?? '') }}" 
                   required readonly>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-medium">New Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" required placeholder="Enter new password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Requirements -->
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

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
            <input type="password" class="form-control" 
                   id="password_confirmation" name="password_confirmation" 
                   required placeholder="Confirm new password">
            <div id="passwordMatch" class="password-match"></div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-reset w-100" id="submitBtn" disabled>
            <span id="btnText">Reset Password</span>
            <span id="btnSpinner" class="d-none">
                <span class="spinner-border spinner-border-sm ms-2"></span>
            </span>
        </button>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resetForm = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('passwordMatch');

        // Password requirement elements
        const reqLength = document.getElementById('reqLength');
        const reqUppercase = document.getElementById('reqUppercase');
        const reqLowercase = document.getElementById('reqLowercase');
        const reqNumber = document.getElementById('reqNumber');

        function checkPasswordStrength() {
            const value = password.value;
            let isValid = true;

            // Check length
            if (value.length >= 8) {
                reqLength.classList.add('valid');
                reqLength.querySelector('i').className = 'ti ti-check-circle';
            } else {
                reqLength.classList.remove('valid');
                reqLength.querySelector('i').className = 'ti ti-circle';
                isValid = false;
            }

            // Check uppercase
            if (/[A-Z]/.test(value)) {
                reqUppercase.classList.add('valid');
                reqUppercase.querySelector('i').className = 'ti ti-check-circle';
            } else {
                reqUppercase.classList.remove('valid');
                reqUppercase.querySelector('i').className = 'ti ti-circle';
                isValid = false;
            }

            // Check lowercase
            if (/[a-z]/.test(value)) {
                reqLowercase.classList.add('valid');
                reqLowercase.querySelector('i').className = 'ti ti-check-circle';
            } else {
                reqLowercase.classList.remove('valid');
                reqLowercase.querySelector('i').className = 'ti ti-circle';
                isValid = false;
            }

            // Check number
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
                passwordMatch.innerHTML = '<span class="text-match"><i class="ti ti-check-circle"></i> Passwords match</span>';
                return true;
            } else {
                passwordMatch.innerHTML = '<span class="text-not-match"><i class="ti ti-alert-circle"></i> Passwords do not match</span>';
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

        resetForm.addEventListener('submit', function (e) {
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