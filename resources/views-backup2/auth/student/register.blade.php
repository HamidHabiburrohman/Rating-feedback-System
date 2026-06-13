@extends('layouts.student.auth.register')

@section('title', 'Registrasi Mahasiswa')

@section('content')
    <div class="auth-card">

        <div class="auth-left">

            <div class="brand-mark">
                <img src="{{ asset('landing/images/svg/Itenas 192x192.svg') }}" alt="Logo" width="48" height="48">
            </div>

            <h1 class="auth-heading">Buat akun baru.</h1>
            <p class="auth-subheading">Isi data Anda untuk bergabung sebagai mahasiswa.</p>

            @if (session('error'))
                <div class="alert alert-error">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('student.register') }}" method="POST" id="registerForm" novalidate>
                @csrf

                <div class="form-field">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input id="name" type="text" name="name"
                        class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nama lengkap Anda"
                        value="{{ old('name') }}" autocomplete="name" required>
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label class="form-label" for="student_identifier">NIM</label>
                    <input id="student_identifier" type="text" name="student_identifier"
                        class="form-input {{ $errors->has('student_identifier') ? 'is-invalid' : '' }}"
                        placeholder="Nomor Induk Mahasiswa" value="{{ old('student_identifier') }}" required>
                    @error('student_identifier')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label class="form-label" for="password">Password</label>
                    <div class="form-input-wrap" style="position: relative;">
                        <input id="password" type="password" name="password"
                            class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Buat password (minimal 6 karakter)" autocomplete="new-password" required>
                        <button type="button" class="toggle-password" data-target="password"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: var(--c-ink-muted); display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 20px;">visibility_off</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <div class="form-input-wrap" style="position: relative;">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-input"
                            placeholder="Ulangi password" autocomplete="new-password" required>
                        <button type="button" class="toggle-password"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: var(--c-ink-muted); display: flex; align-items: center; justify-content: center;">
                        </button>
                    </div>
                </div>

                <div class="form-field">
                    <div class="terms-wrapper">
                        <label class="terms-label">
                            <input type="checkbox" id="termsCheckbox" name="terms" {{ old('terms') ? 'checked' : '' }}>
                            <span class="terms-text">
                                Saya menyetujui <a href="#" target="_blank" class="terms-link">Syarat & Ketentuan</a>
                                dan
                                <a href="#" target="_blank" class="terms-link">Kebijakan Privasi</a> yang berlaku
                            </span>
                        </label>
                        @error('terms')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="registerBtn" disabled>
                    <span id="btnText">Daftar</span>
                    <span class="spinner" id="btnSpinner"></span>
                </button>
            </form>

            <p class="auth-footer">
                Sudah punya akun? <a href="{{ route('student.login') }}">Login di sini</a>
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

    <style>
        .terms-wrapper {
            margin: 1rem 0;
        }

        .terms-label {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            color: #4b5563;
            line-height: 1.4;
        }

        .terms-label input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.125rem;
            border: 2px solid #e8e6e2;
            border-radius: 6px;
            cursor: pointer;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            background-color: #fff;
            position: relative;
        }

        .terms-label input[type="checkbox"]:checked {
            background-color: #f8773c;
            border-color: #f8773c;
        }

        .terms-label input[type="checkbox"]:checked::after {
            content: '';
            display: block;
            width: 5px;
            height: 9px;
            border: solid #ffffff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            position: absolute;
            top: 3px;
        }

        .terms-label input[type="checkbox"]:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.2);
        }

        .terms-label input[type="checkbox"]:hover:not(:checked) {
            border-color: #f8773c;
        }

        .terms-text {
            user-select: none;
        }

        .terms-link {
            color: #f8773c;
            text-decoration: underline;
            font-weight: 500;
        }

        .terms-link:hover {
            color: #e05c22;
            text-decoration: none;
        }

        .field-error {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            margin-left: 1.625rem;
        }

        .terms-label input[type="checkbox"].is-invalid {
            outline: 2px solid #dc2626;
            outline-offset: 2px;
            border-color: #dc2626;
        }

        .form-input.password-match-success {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12) !important;
        }

        .form-input.password-match-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
        }
    </style>
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

        const pwdInput = document.getElementById('password');
        const pwdConfirm = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('registerBtn');
        const termsCheckbox = document.getElementById('termsCheckbox');

        function checkMatch() {
            const a = pwdInput.value;
            const b = pwdConfirm.value;

            pwdConfirm.classList.remove('password-match-success', 'password-match-error');

            if (!a || !b) {
                submitBtn.disabled = true;
                return;
            }

            if (a === b && a.length >= 6) {
                pwdConfirm.classList.add('password-match-success');
                updateSubmitButton();
            } else {
                pwdConfirm.classList.add('password-match-error');
                submitBtn.disabled = true;
            }
        }

        function updateSubmitButton() {
            const isPasswordValid = pwdInput.value === pwdConfirm.value && pwdInput.value.length >= 6;
            const isTermsChecked = termsCheckbox.checked;
            submitBtn.disabled = !(isPasswordValid && isTermsChecked);
        }

        pwdInput.addEventListener('input', checkMatch);
        pwdConfirm.addEventListener('input', checkMatch);
        termsCheckbox.addEventListener('change', updateSubmitButton);

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (pwdInput.value !== pwdConfirm.value) {
                e.preventDefault();
                pwdConfirm.classList.add('password-match-error');
                pwdConfirm.focus();
                return false;
            }

            if (pwdInput.value.length < 6) {
                e.preventDefault();
                pwdInput.classList.add('is-invalid');
                pwdInput.focus();
                return false;
            }

            if (!termsCheckbox.checked) {
                e.preventDefault();
                termsCheckbox.classList.add('is-invalid');
                return false;
            }

            submitBtn.disabled = true;
            document.getElementById('btnText').style.display = 'none';
            document.getElementById('btnSpinner').style.display = 'inline-block';
            return true;
        });

        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'password-match-error');
                const err = this.closest('.form-field').querySelector('.field-error');
                if (err) err.remove();
            });
        });

        termsCheckbox.addEventListener('change', function() {
            this.classList.remove('is-invalid');
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
