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

            <p class="auth-footer">
                Belum punya akun? <a href="{{ route('student.register') }}">Daftar sekarang</a>
            </p>

        </div>

        <div class="auth-right">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>

            <div class="right-overlay">
                <div class="right-tags">
                    <span class="right-tag">Portal Akademik</span>
                    <span class="right-tag">Layanan Mahasiswa</span>
                </div>
                <p class="right-quote">Akses semua layanan kampus dalam satu platform yang mudah digunakan.</p>
                <div class="right-person">
                    <span class="right-person-name">Institut Teknologi Nasional</span>
                    <span class="right-person-role">Bandung, Jawa Barat</span>
                </div>
            </div>
        </div>

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