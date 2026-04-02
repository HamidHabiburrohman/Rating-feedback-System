@extends('layouts.student.auth.reset-password')

@section('title', 'Reset Password')

@section('content')
<div class="auth-card">

    <div class="auth-left">

        <div class="brand-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>

        <h1 class="auth-heading">Buat password<br>baru.</h1>
        <p class="auth-subheading">Password baru harus berbeda dari yang sebelumnya.</p>

        @if(session('status'))
            <div class="alert alert-success">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Terdapat kesalahan pada input Anda.
            </div>
        @endif

        <form action="{{ route('student.password.update') }}" method="POST" id="resetForm" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-field">
                <label class="form-label" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email', $email) }}"
                    readonly
                >
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Password Baru</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Minimal 8 karakter"
                    autocomplete="new-password"
                    required
                >
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="Ulangi password baru"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="password-match" id="passwordMatch"></div>

            <button type="submit" class="btn-submit" id="resetBtn" disabled>
                <span id="btnText">Reset Password</span>
                <span class="spinner" id="btnSpinner"></span>
            </button>
        </form>

        <div style="text-align:center;">
            <a href="{{ route('student.login') }}" class="back-link">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke login
            </a>
        </div>

    </div>

    @include('auth.student._auth-right')

</div>
@endsection

@section('scripts')
<script>
    const pwdInput   = document.getElementById('password');
    const pwdConfirm = document.getElementById('password_confirmation');
    const matchDiv   = document.getElementById('passwordMatch');
    const submitBtn  = document.getElementById('resetBtn');

    function checkMatch() {
        const a = pwdInput.value;
        const b = pwdConfirm.value;

        if (!a || !b) {
            matchDiv.textContent = '';
            matchDiv.className   = 'password-match';
            submitBtn.disabled   = true;
            return;
        }

        if (a === b) {
            matchDiv.textContent = '✓ Password cocok';
            matchDiv.className   = 'password-match ok';
            submitBtn.disabled   = false;
        } else {
            matchDiv.textContent = '✗ Password tidak cocok';
            matchDiv.className   = 'password-match err';
            submitBtn.disabled   = true;
        }
    }

    pwdInput.addEventListener('input', checkMatch);
    pwdConfirm.addEventListener('input', checkMatch);

    document.getElementById('resetForm').addEventListener('submit', function () {
        submitBtn.disabled                           = true;
        document.getElementById('btnText').style.display   = 'none';
        document.getElementById('btnSpinner').style.display = 'block';
    });

    document.querySelectorAll('.form-input:not([readonly])').forEach(input => {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');
            const err = this.parentNode.querySelector('.field-error');
            if (err) err.remove();
        });
    });

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity    = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
@endsection