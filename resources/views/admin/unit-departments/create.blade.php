@extends('layouts.admin.app')

@section('title', 'Add New Department')

@section('admin-content')
    <div class="page-container">
        <header class="page-header">
            <div>
                <h1 class="page-title">Tambah Departemen</h1>
                <p class="page-subtitle">Buat departemen baru untuk struktur unit fasilitas.</p>
            </div>
            <a href="{{ route('admin.unit-departments.index') }}" class="btn-ghost">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </header>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div>
                    <strong>Terjadi kesalahan validasi:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.unit-departments.store') }}" method="POST" id="departmentForm" class="form-layout">
            @csrf

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">1</span>
                    Informasi Dasar
                </h2>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Departemen <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <line x1="3" y1="9" x2="21" y2="9" />
                                    <line x1="9" y1="21" x2="9" y2="9" />
                                </svg>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Fakultas Teknik, Departemen IT"
                                class="form-input @error('name') is-invalid @enderror" required>
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="code" class="form-label">Kode Departemen</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <text x="6" y="16" font-size="10" fill="currentColor" font-weight="bold">A</text>
                                </svg>
                            </span>
                            <input type="text" id="code" name="code" value="{{ old('code') }}"
                                placeholder="Contoh: FT, IT"
                                class="form-input code-input @error('code') is-invalid @enderror" maxlength="10">
                        </div>
                        <span class="help-text">Kode unik singkat untuk departemen (otomatis kapital)</span>
                        @error('code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" rows="4"
                            placeholder="Deskripsikan fungsi dan ruang lingkup departemen ini..."
                            class="form-textarea @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="form-card settings-section">
                <div class="settings-row">
                    <div class="settings-info">
                        <h3 class="settings-title">Status Aktif</h3>
                        <p class="settings-desc">Departemen yang tidak aktif tidak akan ditampilkan dalam pemilihan unit.
                        </p>
                    </div>
                    <label class="toggle-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </section>

            <div class="form-actions">
                <a href="{{ route('admin.unit-departments.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">Simpan Departemen</span>
                    <span class="btn-loader" style="display: none;">
                        <svg class="spinner" width="16" height="16" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                fill="none" stroke-dasharray="60" stroke-dashoffset="20" />
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --color-primary: #f8773c;
            --color-primary-dark: #e55a2b;
            --color-primary-50: #fff5f0;
            --color-bg: #f8fafc;
            --color-surface: #ffffff;
            --color-border: rgba(15, 23, 42, 0.06);
            --color-text-primary: #0f172a;
            --color-text-secondary: #64748b;
            --color-error: #ef4444;
            --color-error-50: #fef2f2;
            --radius-card: 24px;
            --radius-input: 12px;
            --radius-pill: 9999px;
            --shadow-resting: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            --shadow-primary: 0 4px 14px rgba(248, 119, 60, 0.25);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .page-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--color-text-primary);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 0.25rem 0;
        }

        .page-subtitle {
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            font-weight: 400;
            margin: 0;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-card {
            background: var(--color-surface);
            border-radius: var(--radius-card);
            padding: 2rem;
            box-shadow: var(--shadow-resting);
            border: 1px solid var(--color-border);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 1.5rem 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-border);
        }

        .section-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: var(--color-primary-50);
            color: var(--color-primary);
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: var(--radius-pill);
        }

        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .required {
            color: var(--color-error);
        }

        .help-text {
            font-size: 0.75rem;
            color: var(--color-text-secondary);
            margin-top: 0.25rem;
        }

        .error-message {
            font-size: 0.75rem;
            color: var(--color-error);
            margin-top: 0.25rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-input);
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--color-text-primary);
            background: var(--color-surface);
            transition: var(--transition);
        }

        .form-textarea {
            padding-left: 1rem;
            resize: vertical;
            min-height: 120px;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 0.875rem;
            color: var(--color-text-secondary);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-input.is-invalid,
        .form-textarea.is-invalid {
            border-color: var(--color-error);
            background-color: var(--color-error-50);
        }

        .form-input.is-invalid:focus,
        .form-textarea.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .code-input {
            font-family: 'SF Mono', ui-monospace, monospace;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .settings-section {
            padding: 1.5rem 2rem;
        }

        .settings-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .settings-title {
            font-size: 0.9375rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
        }

        .settings-desc {
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            margin: 0;
        }

        .toggle-switch {
            position: relative;
            width: 48px;
            height: 26px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #cbd5e1;
            border-radius: var(--radius-pill);
            transition: var(--transition);
        }

        .toggle-slider:before {
            content: '';
            position: absolute;
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: var(--color-primary);
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(22px);
        }

        .btn-ghost {
            background: transparent;
            color: var(--color-text-secondary);
            padding: 0.5rem 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius-input);
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-ghost:hover {
            background: #f1f5f9;
            color: var(--color-text-primary);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding-top: 0.5rem;
        }

        @media (max-width: 640px) {
            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn {
                width: 100%;
            }
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-input);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-secondary {
            background: var(--color-surface);
            color: var(--color-text-primary);
            border-color: var(--color-border);
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-primary {
            background: var(--color-primary);
            color: white;
            box-shadow: var(--shadow-primary);
        }

        .btn-primary:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-loader {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-radius: var(--radius-input);
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: var(--color-error-50);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #b91c1c;
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .alert-close {
            margin-left: auto;
            padding: 0.25rem;
            background: none;
            border: none;
            color: inherit;
            opacity: 0.5;
            cursor: pointer;
            line-height: 1;
            display: flex;
            align-items: center;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .alert ul {
            margin: 0.25rem 0 0 0;
            padding-left: 1.25rem;
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeCodeInput();
            initializeFormValidation();
        });

        function initializeCodeInput() {
            const codeInput = document.getElementById('code');
            if (!codeInput) return;

            codeInput.addEventListener('input', function() {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(start, end);
            });
        }

        function initializeFormValidation() {
            const form = document.getElementById('departmentForm');
            const submitBtn = document.getElementById('submitBtn');

            if (!form) return;

            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let firstError = null;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        if (!firstError) firstError = field;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                    return;
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                    const btnText = submitBtn.querySelector('.btn-text');
                    const btnLoader = submitBtn.querySelector('.btn-loader');
                    if (btnText) btnText.style.display = 'none';
                    if (btnLoader) btnLoader.style.display = 'inline-flex';
                }
            });

            const inputs = form.querySelectorAll('.form-input, .form-textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
        }
    </script>
@endpush
