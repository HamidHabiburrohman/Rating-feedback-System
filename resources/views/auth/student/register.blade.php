@extends('layouts.student.auth.register')

@section('title', 'Registrasi Mahasiswa')

@section('content')
<div class="auth-card">

    <div class="auth-left">

        <div class="brand-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>

        <h1 class="auth-heading">Buat akun baru.</h1>
        <p class="auth-subheading">Isi data Anda untuk bergabung sebagai mahasiswa.</p>

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.register') }}" method="POST" id="registerForm" novalidate>
            @csrf

            <div class="form-field">
                <label class="form-label" for="nama">Nama Lengkap</label>
                <input
                    id="nama"
                    type="text"
                    name="nama"
                    class="form-input {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                    placeholder="Nama lengkap Anda"
                    value="{{ old('nama') }}"
                    autocomplete="name"
                    required
                >
                @error('nama')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label class="form-label" for="nim">NIM</label>
                <input
                    id="nim"
                    type="text"
                    name="student_identifier"
                    class="form-input {{ $errors->has('student_identifier') ? 'is-invalid' : '' }}"
                    placeholder="Nomor Induk Mahasiswa"
                    value="{{ old('student_identifier') }}"
                    required
                >
                @error('student_identifier')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label class="form-label" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    placeholder="email@itenas.ac.id"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                >
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Buat password"
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
                    placeholder="Ulangi password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="password-match" id="passwordMatch"></div>

            <button type="submit" class="btn-submit" id="registerBtn" disabled>
                <span id="btnText">Daftar</span>
                <span class="spinner" id="btnSpinner"></span>
            </button>
        </form>

        <div class="divider">ATAU</div>

        <div class="social-row">
            <button class="btn-social" type="button" title="Google">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
            </button>
            <button class="btn-social" type="button" title="Facebook">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/>
                </svg>
            </button>
            <button class="btn-social" type="button" title="Apple">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" fill="#141414"/>
                </svg>
            </button>
        </div>

        <p class="auth-footer">
            Sudah punya akun? <a href="{{ route('student.login') }}">Login di sini</a>
        </p>

    </div>

    @include('auth.student._auth-right')

</div>
@endsection

@section('scripts')
<script>
    const pwdInput   = document.getElementById('password');
    const pwdConfirm = document.getElementById('password_confirmation');
    const matchDiv   = document.getElementById('passwordMatch');
    const submitBtn  = document.getElementById('registerBtn');

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

    document.getElementById('registerForm').addEventListener('submit', function () {
        submitBtn.disabled                          = true;
        document.getElementById('btnText').style.display  = 'none';
        document.getElementById('btnSpinner').style.display = 'block';
    });

    document.querySelectorAll('.form-input').forEach(input => {
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