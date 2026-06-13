@extends('layouts.admin.app')

@section('title', 'Edit Laporan - ' . $report->tracking_code)

@section('admin-content')
    <div class="page-container">
        <header class="page-header">
            <div>
                <h1>Edit Laporan</h1>
                <p class="subtitle">{{ $report->tracking_code }}</p>
            </div>
            <a href="{{ route('admin.reports.show', $report->id) }}" class="btn-ghost">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Kembali ke Detail
            </a>
        </header>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span>{{ session('success') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" role="alert">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <span>{{ session('error') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" id="reportForm" class="form-layout">
            @csrf
            @method('PUT')

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">1</span>
                    Informasi Laporan
                </h2>

                <div class="grid grid-cols-2">
                    <div class="form-group full-width">
                        <label for="title" class="form-label">Judul Laporan <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" id="title" name="title" value="{{ old('title', $report->title) }}"
                                placeholder="Judul laporan" class="form-input @error('title') is-invalid @enderror"
                                required>
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M4 4h16v16H4z" />
                                    <line x1="9" y1="9" x2="15" y2="15" />
                                    <line x1="15" y1="9" x2="9" y2="15" />
                                </svg>
                            </span>
                        </div>
                        @error('title')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="description" class="form-label">Deskripsi Laporan <span
                                class="required">*</span></label>
                        <textarea id="description" name="description" rows="5" placeholder="Deskripsi lengkap..."
                            class="form-textarea @error('description') is-invalid @enderror"
                            required>{{ old('description', $report->description) }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="priority" class="form-label">Prioritas <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <select id="priority" name="priority"
                                class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" {{ old('priority', $report->priority) == 'low' ? 'selected' : '' }}>Rendah
                                </option>
                                <option value="medium" {{ old('priority', $report->priority) == 'medium' ? 'selected' : '' }}>
                                    Sedang</option>
                                <option value="high" {{ old('priority', $report->priority) == 'high' ? 'selected' : '' }}>
                                    Tinggi</option>
                                <option value="critical" {{ old('priority', $report->priority) == 'critical' ? 'selected' : '' }}>Kritis</option>
                            </select>
                        </div>
                        @error('priority')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror"
                                required>
                                <option value="new" {{ old('status', $report->status) == 'new' ? 'selected' : '' }}>Baru
                                </option>
                                <option value="in_progress" {{ old('status', $report->status) == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                                <option value="replied" {{ old('status', $report->status) == 'replied' ? 'selected' : '' }}>
                                    Ditanggapi</option>
                                <option value="resolved" {{ old('status', $report->status) == 'resolved' ? 'selected' : '' }}>
                                    Selesai</option>
                                <option value="rejected" {{ old('status', $report->status) == 'rejected' ? 'selected' : '' }}>
                                    Ditolak</option>
                            </select>
                        </div>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            @if($report->admin_response || old('admin_response'))
                <section class="form-card">
                    <h2 class="section-title">
                        <span class="section-number">2</span>
                        Tanggapan Admin
                    </h2>

                    <div class="form-group full-width">
                        <label for="admin_response" class="form-label">Tanggapan</label>
                        <textarea id="admin_response" name="admin_response" rows="4" placeholder="Tulis tanggapan admin..."
                            class="form-textarea @error('admin_response') is-invalid @enderror">{{ old('admin_response', $report->admin_response) }}</textarea>
                        @error('admin_response')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </section>
            @endif

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">3</span>
                    Informasi Waktu
                </h2>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Dibuat Pada</label>
                        <div class="info-value">{{ $report->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Terakhir Diupdate</label>
                        <div class="info-value">{{ $report->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-light" style="color: #ffff; background-color: #f8773c;" id="submitBtn">
                    <span class="btn-text">Perbarui Laporan</span>
                    <span class="btn-loader" style="display: none;">
                        <svg class="spinner" width="16" height="16" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"
                                stroke-dasharray="60" stroke-dashoffset="20" />
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

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        @media (max-width: 768px) {

            .grid-cols-2,
            .grid-cols-3 {
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

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-textarea {
            padding-left: 0.875rem;
            resize: vertical;
            min-height: 100px;
        }

        .code-wrapper {
            display: flex;
            gap: 0.5rem;
        }

        .code-input {
            font-family: 'SF Mono', monospace;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            background: var(--color-gray-50);
            color: var(--color-gray-600);
        }

        .btn-icon {
            padding: 0.5rem;
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-md);
            background: white;
            color: var(--color-gray-500);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
            background: var(--color-primary-50);
        }

        .badge.auto {
            font-size: 0.625rem;
            padding: 0.125rem 0.375rem;
            background: var(--color-primary-50);
            color: var(--color-primary);
            border-radius: var(--radius-sm);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .time-range-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .time-range {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--color-gray-50);
            padding: 0.5rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--color-gray-200);
        }

        .time-input {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }

        .time-input input {
            width: 100%;
            text-align: center;
            padding: 0.5rem;
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-gray-700);
            background: white;
            transition: var(--transition);
        }

        .time-input input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-50);
        }

        .time-label {
            font-size: 0.625rem;
            color: var(--color-gray-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        .time-separator {
            color: var(--color-gray-400);
            font-size: 1.25rem;
            font-weight: 300;
        }

        .time-validation {
            font-size: 0.75rem;
            text-align: center;
        }

        .status-options {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .status-option {
            cursor: pointer;
            flex: 1;
            min-width: 80px;
        }

        .status-option input {
            position: absolute;
            opacity: 0;
        }

        .status-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-full);
            font-size: 0.8125rem;
            font-weight: 500;
            border: 1px solid transparent;
            transition: var(--transition);
            text-align: center;
            white-space: nowrap;
        }

        .status-badge.open {
            background: var(--color-success-50);
            color: #059669;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .status-badge.maintenance {
            background: var(--color-warning-50);
            color: #d97706;
            border-color: rgba(245, 158, 11, 0.2);
        }

        .status-badge.closed {
            background: var(--color-error-50);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .status-badge.low {
            background: var(--color-success-50);
            color: #059669;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .status-badge.medium {
            background: var(--color-warning-50);
            color: #d97706;
            border-color: rgba(245, 158, 11, 0.2);
        }

        .status-badge.high {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .status-badge.critical {
            background: rgba(124, 29, 8, 0.1);
            color: #7c1d08;
            border-color: rgba(124, 29, 8, 0.2);
        }

        .status-badge.new {
            background: var(--color-primary-50);
            color: var(--color-primary);
            border-color: rgba(248, 119, 60, 0.2);
        }

        .status-badge.in_progress {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            border-color: rgba(37, 99, 235, 0.2);
        }

        .status-badge.replied {
            background: rgba(147, 51, 234, 0.1);
            color: #9333ea;
            border-color: rgba(147, 51, 234, 0.2);
        }

        .status-badge.resolved {
            background: var(--color-success-50);
            color: #059669;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .status-badge.rejected {
            background: var(--color-error-50);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .status-option:hover .status-badge {
            filter: brightness(0.95);
        }

        .status-option input:checked+.status-badge.open,
        .status-option input:checked+.status-badge.resolved,
        .status-option input:checked+.status-badge.low {
            background: #10b981;
            color: white;
            border-color: #10b981;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        }

        .status-option input:checked+.status-badge.maintenance,
        .status-option input:checked+.status-badge.medium {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25);
        }

        .status-option input:checked+.status-badge.closed,
        .status-option input:checked+.status-badge.rejected,
        .status-option input:checked+.status-badge.high {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
        }

        .status-option input:checked+.status-badge.critical {
            background: #7c1d08;
            color: white;
            border-color: #7c1d08;
            box-shadow: 0 2px 8px rgba(124, 29, 8, 0.25);
        }

        .status-option input:checked+.status-badge.new {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
            box-shadow: 0 2px 8px rgba(248, 119, 60, 0.25);
        }

        .status-option input:checked+.status-badge.in_progress {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        .status-option input:checked+.status-badge.replied {
            background: #9333ea;
            color: white;
            border-color: #9333ea;
            box-shadow: 0 2px 8px rgba(147, 51, 234, 0.25);
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

            .status-options {
                flex-direction: column;
            }

            .status-option {
                width: 100%;
            }
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

        .ts-control {
            border-color: var(--color-gray-200) !important;
            border-radius: var(--radius-md) !important;
            padding: 0.5rem 0.75rem !important;
            min-height: 46px;
        }

        .ts-control:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px var(--color-primary-50) !important;
        }

        .ts-dropdown {
            border-radius: var(--radius-md) !important;
            border-color: var(--color-gray-200) !important;
            box-shadow: var(--shadow-lg) !important;
        }

        .ts-dropdown .active {
            background: var(--color-primary-50) !important;
            color: var(--color-primary) !important;
        }

        .no-results {
            padding: 0.5rem;
            color: var(--color-gray-500);
            font-size: 0.875rem;
        }

        .facilities-wrapper {
            margin-top: 0.5rem;
        }

        .select-simple {
            padding-left: 0.875rem;
        }
    </style>
@endpush

@push('admin-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initializeTomSelect();
            initializeCodeGeneration();
            initializeSlugGeneration();
            initializeTimeValidation();
            initializeFormValidation();
            initializeAlerts();
        });

        function initializeTomSelect() {
            const facilitiesSelect = document.getElementById('facilities');
            if (!facilitiesSelect) return;

            if (typeof TomSelect !== 'undefined') {
                new TomSelect(facilitiesSelect, {
                    plugins: ['remove_button'],
                    maxItems: null,
                    hideSelected: true,
                    create: false,
                    render: {
                        no_results: function () {
                            return '<div class="no-results">Tidak ada fasilitas yang cocok</div>';
                        }
                    }
                });
            }
        }

        function initializeCodeGeneration() {
            const nameInput = document.getElementById('name');
            const typeSelect = document.getElementById('unit_type_id');
            const codeInput = document.getElementById('code');
            const regenerateBtn = document.getElementById('regenerateCode');

            if (!nameInput || !codeInput) return;

            const generateCode = () => {
                const name = nameInput.value.trim();
                const typeOption = typeSelect ? typeSelect.options[typeSelect.selectedIndex] : null;

                if (!name || (typeSelect && !typeSelect.value)) {
                    codeInput.value = '';
                    return;
                }

                const typePrefix = typeOption && typeOption.dataset.code
                    ? typeOption.dataset.code
                    : (typeOption ? typeOption.text.substring(0, 3).toUpperCase() : 'UNT');

                const words = name.split(/\s+/);
                let nameCode = '';

                if (words.length === 1) {
                    nameCode = words[0].substring(0, 3).toUpperCase();
                } else {
                    nameCode = words.map(w => w.charAt(0).toUpperCase()).join('').substring(0, 3);
                }

                const randomNum = Math.floor(Math.random() * 90 + 10);
                codeInput.value = `${typePrefix}-${nameCode}-${randomNum}`;
            };

            nameInput.addEventListener('blur', generateCode);

            if (typeSelect) {
                typeSelect.addEventListener('change', () => {
                    if (nameInput.value.trim()) generateCode();
                });
            }

            if (regenerateBtn) {
                regenerateBtn.addEventListener('click', generateCode);
            }
        }

        function initializeSlugGeneration() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            if (!nameInput || !slugInput) return;

            let slugEdited = false;

            slugInput.addEventListener('input', function () {
                slugEdited = true;
            });

            nameInput.addEventListener('input', function () {
                if (!slugEdited && (!slugInput.value || slugInput.value === '')) {
                    slugInput.value = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-|-$/g, '');
                }
            });
        }

        function initializeTimeValidation() {
            const openTime = document.getElementById('open_time');
            const closeTime = document.getElementById('close_time');
            const timeValidation = document.getElementById('timeValidation');

            if (!openTime || !closeTime) return;

            const validateTime = () => {
                const open = openTime.value;
                const close = closeTime.value;

                if (!open || !close) {
                    if (timeValidation) timeValidation.textContent = '';
                    return true;
                }

                if (open >= close) {
                    if (timeValidation) {
                        timeValidation.textContent = 'Jam tutup harus setelah jam buka';
                        timeValidation.style.color = 'var(--color-error)';
                    }
                    closeTime.classList.add('is-invalid');
                    return false;
                }

                const openDate = new Date(`2000-01-01T${open}`);
                const closeDate = new Date(`2000-01-01T${close}`);
                const duration = (closeDate - openDate) / (1000 * 60 * 60);

                if (timeValidation) {
                    timeValidation.textContent = `Durasi: ${duration} jam`;
                    timeValidation.style.color = 'var(--color-success)';
                }
                closeTime.classList.remove('is-invalid');
                return true;
            };

            openTime.addEventListener('change', validateTime);
            closeTime.addEventListener('change', validateTime);
        }

        function initializeFormValidation() {
            const form = document.querySelector('form[id$="Form"]');
            const submitBtn = document.getElementById('submitBtn');

            if (!form) return;

            form.addEventListener('submit', function (e) {
                const openTime = document.getElementById('open_time');
                const closeTime = document.getElementById('close_time');

                if (openTime && closeTime) {
                    const open = openTime.value;
                    const close = closeTime.value;

                    if (open && close && open >= close) {
                        e.preventDefault();
                        closeTime.classList.add('is-invalid');
                        closeTime.focus();
                        return;
                    }
                }

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
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

            const inputs = form.querySelectorAll('.form-input, .form-select, .form-textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    this.classList.remove('is-invalid');
                });
            });
        }

        function initializeAlerts() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
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