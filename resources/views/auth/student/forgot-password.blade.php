@extends('layouts.student.auth.forgot-password')

@section('title', 'Lupa Password')


@section('content')
<div class="auth-card">

    <div class="auth-left">

        <div class="brand-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>

        <h1 class="auth-heading">Lupa password?</h1>
        <p class="auth-subheading">Masukkan NIM atau email Anda. Kami akan mengirimkan link untuk mereset password.</p>

        @if(session('status'))
            <div class="alert alert-success">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

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

        <form action="{{ route('student.password.email') }}" method="POST" id="forgotForm" novalidate>
            @csrf

            <div class="form-field">
                <label class="form-label" for="email">NIM atau Email</label>
                <input
                    id="email"
                    type="text"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    placeholder="Masukkan NIM atau email"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <p class="field-error">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
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
    document.getElementById('forgotForm').addEventListener('submit', function () {
        const btn  = document.getElementById('forgotBtn');
        const text = document.getElementById('btnText');
        const spin = document.getElementById('btnSpinner');
        btn.disabled       = true;
        text.style.display = 'none';
        spin.style.display = 'block';
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