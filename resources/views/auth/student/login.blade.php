@extends('layouts.student.auth.login')

@section('title', 'Login Mahasiswa')

@section('content')
    <div class="auth-card">

        <div class="auth-left">

            <div class="brand-mark">
                <img src="{{ asset('landing/images/svg/Itenas 192x192.svg') }}" alt="Logo" width="48" height="48">
            </div>

            <h1 class="auth-heading">Selamat datang kembali.</h1>
            <p class="auth-subheading">Masukkan identitas mahasiswa Anda untuk melanjutkan.</p>

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('student.login') }}" method="POST" id="loginForm" novalidate>
                @csrf

                <div class="form-field">
                    <label class="form-label" for="student_identifier">NIM / Identitas</label>
                    <div class="form-input-wrap">
                        <input id="student_identifier" type="text" name="student_identifier"
                            class="form-input {{ $errors->has('student_identifier') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan NIM Anda" value="{{ old('student_identifier') }}" autocomplete="username"
                            required>
                    </div>

                    <label class="form-label" for="password">Password</label>
                    <div class="form-input-wrap">
                        <input id="password" type="password" name="password"
                            class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan Password Anda" value="{{ old('password') }}"
                            autocomplete="current-password" required>
                    </div>
                    @error('password')
                        <p class="field-error">
                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div style="display:flex; justify-content:flex-end; margin: -8px 0 18px;">
                    <a href=""
                        style="font-size:12.5px; color:var(--c-ink-muted); text-decoration:none; font-weight:500; transition:color .15s;">
                        Lupa identitas?
                    </a>
                </div>

                <button type="submit" class="btn-submit" id="loginBtn">
                    <span id="btnText">Masuk</span>
                    <span class="spinner" id="btnSpinner"></span>
                </button>
            </form>

            <div class="divider">ATAU</div>

            <div class="social-row">
                <button class="btn-social" type="button" title="Google">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                </button>
                <button class="btn-social" type="button" title="Facebook">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                            fill="#1877F2" />
                    </svg>
                </button>
                <button class="btn-social" type="button" title="Apple">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"
                            fill="#141414" />
                    </svg>
                </button>
            </div>

            <p class="auth-footer">
                Belum punya akun? <a href="">Daftar sekarang</a>
            </p>

        </div>

        @include('auth.student._auth-right')

    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            const text = document.getElementById('btnText');
            const spin = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.style.display = 'none';
            spin.style.display = 'block';
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