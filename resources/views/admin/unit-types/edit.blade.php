@extends('layouts.admin.app')
@section('title', 'Edit Unit Type')

@section('admin-content')
    <div class="page-container">
        <header class="page-header">
            <div>
                <h1 class="page-title">Edit Unit Type</h1>
                <p class="page-subtitle">Update information for: <strong>{{ $type->name }}</strong></p>
            </div>
            <a href="{{ route('admin.unit-types.index') }}" class="btn btn-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </header>

        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div class="alert-content">
                    <strong>Validation Error</strong>
                    <p>Please correct the highlighted fields below.</p>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.unit-types.update', $type->id) }}" method="POST" id="unitTypeForm"
            class="form-layout">
            @csrf
            @method('PUT')
            <section class="form-card">
                <h2 class="section-title">Basic Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="req">*</span></label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <line x1="3" y1="9" x2="21" y2="9" />
                                <line x1="9" y1="21" x2="9" y2="9" />
                            </svg>
                            <input type="text" id="name" name="name" value="{{ old('name', $type->name) }}"
                                placeholder="e.g. Laboratory, Library"
                                class="form-input @error('name') is-invalid @enderror" required>
                        </div>
                        @error('name')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Icon <span class="req">*</span></label>
                        @php
                            $currentIconKey = old('icon_key', $type->icon_key);
                            $currentIconData = $icons[$currentIconKey] ?? null;
                            $currentIconName = $currentIconData
                                ? (is_array($currentIconData)
                                    ? $currentIconData['name']
                                    : $currentIconData)
                                : 'Select Icon';
                            $currentIconSvg = $currentIconData
                                ? (is_array($currentIconData)
                                    ? $currentIconData['svg']
                                    : '')
                                : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>';
                        @endphp
                        <input type="hidden" id="selected_icon_key" name="icon_key" value="{{ $currentIconKey }}" required>
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
                        @error('icon_key')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Describe the purpose of this unit type..."
                            class="form-textarea @error('description') is-invalid @enderror">{{ old('description', $type->description) }}</textarea>
                        @error('description')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="slug" class="form-label">Slug <span class="badge-auto">Auto</span></label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M13 2L3 14h6l-2 8 10-12h-6l2-8z" />
                            </svg>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $type->slug) }}"
                                placeholder="auto-generated" class="form-input @error('slug') is-invalid @enderror">
                        </div>
                        @error('slug')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="form-card settings-card">
                <div class="settings-row">
                    <div>
                        <h3 class="settings-title">Active Status</h3>
                        <p class="settings-desc">Inactive types will be hidden from unit selection.</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $type->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </section>

            <div class="form-actions">
                <a href="{{ route('admin.unit-types.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">Update Type</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --primary: #f8773c;
            --primary-dark: #e56a2e;
            --primary-50: #fff5f0;
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: rgba(15, 23, 42, 0.06);
            --border-focus: rgba(248, 119, 60, 0.15);
            --text-main: #0f172a;
            --text-sub: #64748b;
            --error: #ef4444;
            --error-50: #fef2f2;
            --radius-card: 24px;
            --radius-input: 12px;
            --radius-pill: 9999px;
            --shadow-card: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            --shadow-dropdown: 0 12px 32px rgba(15, 23, 42, 0.12), 0 4px 8px rgba(15, 23, 42, 0.04);
            --shadow-primary: 0 4px 14px rgba(248, 119, 60, 0.25);
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

        .page-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 48px 24px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 4px 0;
        }

        .page-subtitle {
            color: var(--text-sub);
            font-size: 14px;
            font-weight: 400;
            margin: 0;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-card {
            background: var(--surface);
            border-radius: var(--radius-card);
            padding: 32px;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 24px 0;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .req {
            color: var(--error);
        }

        .badge-auto {
            font-size: 10px;
            padding: 2px 8px;
            background: var(--primary-50);
            color: var(--primary);
            border-radius: var(--radius-pill);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-sub);
            pointer-events: none;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid var(--border);
            border-radius: var(--radius-input);
            font-size: 14px;
            font-family: inherit;
            color: var(--text-main);
            background: var(--surface);
            transition: all 0.2s ease;
        }

        .form-textarea {
            padding-left: 16px;
            resize: vertical;
            min-height: 100px;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--border-focus);
        }

        .form-input.is-invalid,
        .form-textarea.is-invalid {
            border-color: var(--error);
            background: var(--error-50);
        }

        .error-msg {
            font-size: 12px;
            color: var(--error);
            font-weight: 500;
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
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-input);
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-main);
        }

        .icon-picker-trigger:hover {
            border-color: #cbd5e1;
        }

        .icon-picker-wrapper.open .icon-picker-trigger,
        .icon-picker-trigger:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--border-focus);
        }

        .icon-picker-wrapper.is-invalid .icon-picker-trigger {
            border-color: var(--error);
            background: var(--error-50);
        }

        .icon-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            color: var(--text-sub);
            flex-shrink: 0;
        }

        .icon-preview svg {
            width: 100%;
            height: 100%;
        }

        .icon-picker-wrapper.open .icon-preview,
        .icon-picker-wrapper.open .icon-label {
            color: var(--primary);
        }

        .icon-label {
            flex: 1;
            font-weight: 500;
            color: var(--text-sub);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chevron {
            color: var(--text-sub);
            transition: transform 0.3s var(--ease-out);
            flex-shrink: 0;
        }

        .icon-picker-wrapper.open .chevron {
            transform: rotate(180deg);
            color: var(--primary);
        }

        .icon-picker-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow-dropdown);
            padding: 16px;
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.96);
            transition: all 0.25s var(--ease-out);
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
            color: var(--text-sub);
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }

        .icon-option svg {
            width: 22px;
            height: 22px;
        }

        .icon-option:hover {
            background: var(--bg);
            color: var(--text-main);
            transform: translateY(-2px);
        }

        .icon-option.selected {
            background: var(--primary-50);
            color: var(--primary);
            border-color: rgba(248, 119, 60, 0.2);
            box-shadow: 0 2px 8px rgba(248, 119, 60, 0.1);
        }

        .settings-card {
            padding: 24px 32px;
        }

        .settings-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
        }

        .settings-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 2px 0;
        }

        .settings-desc {
            font-size: 13px;
            color: var(--text-sub);
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
            inset: 0;
            background: #cbd5e1;
            border-radius: var(--radius-pill);
            transition: all 0.3s var(--ease-out);
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
            transition: all 0.3s var(--ease-out);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: var(--primary);
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(22px);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: var(--radius-pill);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-sub);
            padding: 8px 16px;
            border-radius: var(--radius-input);
        }

        .btn-ghost:hover {
            background: var(--bg);
            color: var(--text-main);
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--text-main);
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: var(--bg);
            border-color: #cbd5e1;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: var(--shadow-primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.8;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }

        .btn.loading .spinner {
            display: block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            border-radius: var(--radius-input);
            margin-bottom: 24px;
            background: var(--error-50);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: #b91c1c;
        }

        .alert svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert strong {
            font-size: 14px;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }

        .alert p {
            font-size: 13px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 24px 16px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-card,
            .settings-card {
                padding: 24px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeIconPicker();
            initializeSlugGeneration();
            initializeFormValidation();
        });

        function initializeIconPicker() {
            const wrapper = document.getElementById('iconPickerWrapper');
            const trigger = document.getElementById('iconPickerTrigger');
            const hiddenInput = document.getElementById('selected_icon_key');
            const preview = document.getElementById('iconPreview');
            const label = document.getElementById('iconLabel');
            const options = document.querySelectorAll('.icon-option');

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
                if (!slugEdited && (!slugInput.value || slugInput.value === '')) {
                    slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                }
            });
        }

        function initializeFormValidation() {
            const form = document.getElementById('unitTypeForm');
            const submitBtn = document.getElementById('submitBtn');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let firstError = null;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        if (field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') field.classList.add(
                            'is-invalid');
                        else field.closest('.icon-picker-wrapper')?.classList.add('is-invalid');
                        isValid = false;
                        if (!firstError) firstError = field;
                    } else {
                        if (field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') field.classList
                            .remove('is-invalid');
                        else field.closest('.icon-picker-wrapper')?.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (firstError) firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                }
            });

            form.querySelectorAll('.form-input, .form-textarea').forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
        }
    </script>
@endpush
