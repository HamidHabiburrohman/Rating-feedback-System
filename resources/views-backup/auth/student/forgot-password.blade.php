@extends('layouts.student.auth.simple')

@section('title', 'Lupa Password')

@section('content')
    <div class="auth-card-single">

        <div style="display: flex; justify-content: center; margin-bottom: 28px;">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logos/logo1.svg') }}" alt="Logo" style="width: 180px; height: auto;">
            </a>
        </div>

        <h3 class="auth-heading" style="text-align: center;">Lupa password?</h3>
        <p class="auth-subheading" style="text-align: center;">Masukkan NIM atau email Anda. Kami akan mengirimkan link untuk mereset password.</p>

        @if (session('status'))
            <div class="alert alert-success">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.password.email') }}" method="POST" id="forgotForm" novalidate>
            @csrf

            <div class="form-field">
                <label class="form-label" for="email">NIM atau Email</label>
                <div class="form-input-wrap">
                    <input id="email" type="text" name="email"
                        class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="Masukkan NIM atau email" value="{{ old('email') }}" autocomplete="username" required>
                </div>
                @error('email')
                    <p class="field-error">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="btn-submit" id="forgotBtn">
                <span id="btnText">Kirim Link Reset</span>
                <span class="spinner" id="btnSpinner"></span>
            </button>
        </form>

        <p class="auth-footer">
            <a href="{{ route('student.login') }}">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="vertical-align: middle; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke login
            </a>
        </p>

    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = document.getElementById('forgotBtn');
            const text = document.getElementById('btnText');
            const spin = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.style.display = 'none';
            spin.style.display = 'block';
        });

        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const err = this.parentNode.parentNode.querySelector('.field-error');
                if (err) err.remove();
            });
        });

        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);
    </script>
@endsection