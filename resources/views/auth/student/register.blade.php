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

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:1rem;">
                        @foreach($errors->all() as $error)
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
                    <input id="password" type="password" name="password"
                        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Buat password (minimal 6 karakter)" autocomplete="new-password" required>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-input"
                        placeholder="Ulangi password" autocomplete="new-password" required>
                </div>

                <div class="password-match" id="passwordMatch"></div>

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
@endsection

@section('scripts')
    <script>
        const pwdInput = document.getElementById('password');
        const pwdConfirm = document.getElementById('password_confirmation');
        const matchDiv = document.getElementById('passwordMatch');
        const submitBtn = document.getElementById('registerBtn');
        const termsCheckbox = document.getElementById('termsCheckbox');

        function checkMatch() {
            const a = pwdInput.value;
            const b = pwdConfirm.value;

            if (!a || !b) {
                matchDiv.textContent = '';
                matchDiv.className = 'password-match';
                submitBtn.disabled = true;
                return;
            }

            if (a === b && a.length >= 6) {
                matchDiv.textContent = '✓ Password cocok';
                matchDiv.className = 'password-match ok';
                updateSubmitButton();
            } else if (a === b && a.length < 6) {
                matchDiv.textContent = '✗ Password minimal 6 karakter';
                matchDiv.className = 'password-match err';
                submitBtn.disabled = true;
            } else {
                matchDiv.textContent = '✗ Password tidak cocok';
                matchDiv.className = 'password-match err';
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

        document.getElementById('registerForm').addEventListener('submit', function (e) {
            if (pwdInput.value !== pwdConfirm.value) {
                e.preventDefault();
                matchDiv.textContent = '✗ Password tidak cocok';
                matchDiv.className = 'password-match err';
                return false;
            }

            if (pwdInput.value.length < 6) {
                e.preventDefault();
                matchDiv.textContent = '✗ Password minimal 6 karakter';
                matchDiv.className = 'password-match err';
                return false;
            }

            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Anda harus menyetujui Syarat & Ketentuan');
                return false;
            }

            submitBtn.disabled = true;
            document.getElementById('btnText').style.display = 'none';
            document.getElementById('btnSpinner').style.display = 'inline-block';
            return true;
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
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);
    </script>
@endsection