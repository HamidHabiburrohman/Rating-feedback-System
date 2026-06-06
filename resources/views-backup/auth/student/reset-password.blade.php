@extends('layouts.student.auth.simple')

@section('title', 'Reset Password')

@section('content')
    <div class="auth-card-single">

        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logos/logo1.svg') }}" alt="Logo" style="width: 180px; height: auto;">
            </a>
        </div>

        <h3 class="auth-heading" style="text-align: center;">Reset Password</h3>
        <p class="auth-subheading" style="text-align: center;">Masukkan password baru untuk akun Anda.</p>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('student.password.update') }}" method="POST" id="resetForm" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="identifier" value="{{ $identifier }}">

            <div class="form-field">
                <label class="form-label" for="password">Password Baru</label>
                <div class="form-input-wrap" style="position: relative;">
                    <input type="password" name="password" id="password" class="form-input"
                        placeholder="Minimal 6 karakter" required>
                    <button type="button" class="toggle-password" data-target="password"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: var(--c-ink-muted); display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">visibility_off</span>
                    </button>
                </div>
            </div>

            <div class="form-field">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <div class="form-input-wrap" style="position: relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input"
                        placeholder="Ulangi password" required>
                    <button type="button" class="toggle-password" data-target="password_confirmation"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: var(--c-ink-muted); display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">visibility_off</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="resetBtn">
                <span id="btnText">Reset Password</span>
                <span class="spinner" id="btnSpinner"></span>
            </button>
        </form>

        <p class="auth-footer">
            <a href="{{ route('student.login') }}">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5" style="vertical-align: middle; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke login
            </a>
        </p>

    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('.material-symbols-outlined');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility_off';
                }
            });
        });

        document.getElementById('resetForm').addEventListener('submit', function() {
            const btn = document.getElementById('resetBtn');
            const text = document.getElementById('btnText');
            const spin = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.style.display = 'none';
            spin.style.display = 'block';
        });

        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', function() {
                const parentField = this.closest('.form-field');
                if (parentField) {
                    const err = parentField.querySelector('.field-error');
                    if (err) err.remove();
                }
            });
        });
    </script>
@endsection
