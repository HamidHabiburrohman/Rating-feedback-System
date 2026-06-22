@extends('layouts.admin.app')
@section('title', 'Edit Facility')
@section('admin-content')
    <div class="page-container">
        <header class="page-header">
            <div>
                <h1>Edit Fasilitas</h1>
                <p class="subtitle">Perbarui informasi fasilitas: {{ $facility->name }}</p>
            </div>
            <a href="{{ route('admin.facilities.index') }}" class="btn-ghost">
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
                    <strong>Terjadi kesalahan:</strong>
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

        <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST" id="facilityForm"
            class="form-layout">
            @csrf
            @method('PUT')

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">1</span>
                    Informasi Fasilitas
                </h2>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Fasilitas <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" value="{{ old('name', $facility->name) }}"
                                placeholder="Contoh: AC, WiFi, Proyektor"
                                class="form-input @error('name') is-invalid @enderror" required>
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <line x1="3" y1="9" x2="21" y2="9" />
                                    <line x1="9" y1="21" x2="9" y2="9" />
                                </svg>
                            </span>
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Ikon</label>
                        @php
                            $currentIconKey = old('icon_key', $facility->icon_key);
                            $currentIconData = $icons[$currentIconKey] ?? null;
                            $currentIconName = $currentIconData
                                ? (is_array($currentIconData)
                                    ? $currentIconData['name']
                                    : $currentIconData)
                                : 'Pilih Ikon';
                            $currentIconSvg = $currentIconData
                                ? (is_array($currentIconData)
                                    ? $currentIconData['svg']
                                    : '')
                                : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4" /><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" /></svg>';
                        @endphp
                        <input type="hidden" id="selected_icon_key" name="icon_key" value="{{ $currentIconKey }}">
                        <div class="icon-picker-wrapper @error('icon_key') is-invalid @enderror" id="iconPickerWrapper">
                            <button type="button" class="icon-picker-trigger" id="iconPickerTrigger">
                                <span class="icon-preview" id="iconPreview">{!! $currentIconSvg !!}</span>
                                <span class="icon-label" id="iconLabel">{{ $currentIconName }}</span>
                                <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </button>
                            <div class="icon-picker-dropdown" id="iconPickerDropdown">
                                <div class="icon-picker-grid">
                                    @foreach ($icons ?? [] as $key => $iconData)
                                        @php
                                            $name = is_array($iconData) ? $iconData['name'] : $iconData;
                                            $svg = is_array($iconData) ? $iconData['svg'] : '';
                                            $isSelected = $currentIconKey === $key;
                                        @endphp
                                        <div class="icon-option {{ $isSelected ? 'selected' : '' }}"
                                            data-value="{{ $key }}" data-name="{{ $name }}"
                                            data-svg="{!! htmlspecialchars($svg) !!}">
                                            {!! $svg !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <span class="help-text">Ikon untuk memudahkan identifikasi</span>
                        @error('icon_key')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="settings-section">
                <div class="settings-row">
                    <div class="settings-info">
                        <h3>Status Aktif</h3>
                        <p>Fasilitas yang tidak aktif tidak akan muncul di pilihan</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $facility->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">Update Fasilitas</span>
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
            --color-primary-light: #f1c3ae;
            --color-primary-dark: #e55a2b;
            --color-primary-50: #fff5f0;
            --color-gray-50: #f9fafb;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-400: #9ca3af;
            --color-gray-500: #6b7280;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-900: #111827;
            --color-error: #ef4444;
            --color-error-50: #fef2f2;
            --color-success: #10b981;
            --color-success-50: #d1fae5;
            --color-warning: #f59e0b;
            --color-warning-50: #fef3c7;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-primary: 0 4px 14px rgba(248, 119, 60, 0.25);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .page-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-gray-900);
            margin-bottom: 0.25rem;
        }

        .page-header .subtitle {
            color: var(--color-gray-500);
            font-size: 0.875rem;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .form-card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-gray-200);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-gray-900);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--color-gray-100);
        }

        .section-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: var(--color-primary-50);
            color: var(--color-primary);
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50%;
        }

        .grid {
            display: grid;
            gap: 1rem;
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
            gap: 0.375rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-gray-700);
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .required {
            color: var(--color-error);
        }

        .help-text {
            font-size: 0.75rem;
            color: var(--color-gray-400);
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
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.625rem 0.875rem;
            padding-left: 2.5rem;
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            color: var(--color-gray-900);
            background: white;
            transition: var(--transition);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-50);
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: var(--color-gray-400);
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            color: var(--color-gray-400);
            pointer-events: none;
        }

        .form-input.is-invalid,
        .form-select.is-invalid,
        .form-textarea.is-invalid {
            border-color: var(--color-error);
            background-color: var(--color-error-50);
        }

        .form-textarea {
            padding-left: 0.875rem;
            resize: vertical;
            min-height: 100px;
        }

        .icon-picker-wrapper {
            position: relative;
        }

        .icon-picker-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: white;
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            text-align: left;
            font-family: inherit;
            font-size: 0.875rem;
            color: var(--color-gray-900);
        }

        .icon-picker-trigger:hover {
            border-color: #cbd5e1;
        }

        .icon-picker-wrapper.open .icon-picker-trigger,
        .icon-picker-trigger:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-50);
        }

        .icon-picker-wrapper.is-invalid .icon-picker-trigger {
            border-color: var(--color-error);
            background: var(--color-error-50);
        }

        .icon-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            color: var(--color-gray-500);
            flex-shrink: 0;
        }

        .icon-preview svg {
            width: 100%;
            height: 100%;
        }

        .icon-picker-wrapper.open .icon-preview,
        .icon-picker-wrapper.open .icon-label {
            color: var(--color-primary);
        }

        .icon-label {
            flex: 1;
            font-weight: 500;
            color: var(--color-gray-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chevron {
            color: var(--color-gray-400);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
        }

        .icon-picker-wrapper.open .chevron {
            transform: rotate(180deg);
            color: var(--color-primary);
        }

        .icon-picker-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--color-gray-200);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 16px;
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.96);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            max-height: 320px;
            display: flex;
            flex-direction: column;
        }

        .icon-picker-wrapper.open .icon-picker-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .icon-picker-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .icon-picker-grid::-webkit-scrollbar {
            width: 6px;
        }

        .icon-picker-grid::-webkit-scrollbar-track {
            background: transparent;
        }

        .icon-picker-grid::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .icon-option {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--color-gray-500);
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }

        .icon-option svg {
            width: 22px;
            height: 22px;
        }

        .icon-option:hover {
            background: var(--color-gray-50);
            color: var(--color-gray-900);
            transform: translateY(-2px);
        }

        .icon-option.selected {
            background: var(--color-primary-50);
            color: var(--color-primary);
            border-color: rgba(248, 119, 60, 0.2);
            box-shadow: 0 2px 8px rgba(248, 119, 60, 0.1);
        }

        .settings-section {
            background: white;
            border-radius: var(--radius-xl);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-gray-200);
        }

        .settings-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .settings-info h3 {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-gray-900);
            margin-bottom: 0.25rem;
        }

        .settings-info p {
            font-size: 0.8125rem;
            color: var(--color-gray-500);
        }

        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
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
            background: var(--color-gray-300);
            border-radius: var(--radius-full);
            transition: var(--transition);
        }

        .toggle-slider:before {
            content: '';
            position: absolute;
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: var(--color-primary);
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        .btn-ghost {
            background: transparent;
            color: var(--color-gray-600);
            padding: 0.5rem 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-ghost:hover {
            background: var(--color-gray-100);
            color: var(--color-gray-900);
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
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            font-weight: 500;
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
            background: white;
            color: var(--color-gray-700);
            border-color: var(--color-gray-200);
        }

        .btn-secondary:hover {
            background: var(--color-gray-50);
        }

        .btn-orange {
            background: var(--color-primary);
            color: white;
            box-shadow: var(--shadow-primary);
        }

        .btn-orange:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            color: white;
        }

        .btn-orange:disabled {
            opacity: 0.8;
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
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: var(--color-error-50);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--color-error);
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

@push('admin-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeIconPicker();
            initializeSlugGeneration();
            initializeFormValidation();
            initializeAlerts();
        });

        function initializeIconPicker() {
            const wrapper = document.getElementById('iconPickerWrapper');
            const trigger = document.getElementById('iconPickerTrigger');
            const hiddenInput = document.getElementById('selected_icon_key');
            const preview = document.getElementById('iconPreview');
            const label = document.getElementById('iconLabel');
            const options = document.querySelectorAll('.icon-option');

            if (!wrapper || !trigger) return;

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                wrapper.classList.toggle('open');
            });

            options.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    options.forEach(o => o.classList.remove('selected'));
                    this.classList.add('selected');
                    hiddenInput.value = this.dataset.value;
                    preview.innerHTML = this.dataset.svg;
                    label.textContent = this.dataset.name;
                    wrapper.classList.remove('open');
                    wrapper.classList.remove('is-invalid');
                });
            });

            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) wrapper.classList.remove('open');
            });
        }

        function initializeSlugGeneration() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            if (!nameInput || !slugInput) return;

            let slugEdited = slugInput.value.length > 0;

            slugInput.addEventListener('input', function() {
                slugEdited = true;
            });

            nameInput.addEventListener('input', function() {
                if (!slugEdited) {
                    slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                }
            });
        }

        function initializeFormValidation() {
            const form = document.getElementById('facilityForm');
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
                    submitBtn.querySelector('.btn-text').style.display = 'none';
                    submitBtn.querySelector('.btn-loader').style.display = 'inline-flex';
                }
            });

            form.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(input => {
                input.addEventListener('input', () => input.classList.remove('is-invalid'));
            });
        }

        function initializeAlerts() {
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        if (bsAlert) bsAlert.close();
                    }
                }, 5000);
            });
        }
    </script>
@endpush
