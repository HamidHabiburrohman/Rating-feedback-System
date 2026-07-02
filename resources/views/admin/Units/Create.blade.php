@extends('layouts.admin.app')
@section('title', 'Tambah Unit Baru')
@section('admin-content')
<div class="form-container">
    <header class="page-header">
        <div>
            <h1 class="page-title">Tambah Unit Baru</h1>
            <p class="page-subtitle">Buat unit atau ruangan baru dalam sistem</p>
        </div>
        <a href="{{ route('admin.units.index') }}" class="btn-ghost">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

    <form action="{{ route('admin.units.store') }}" method="POST" id="unitForm" class="form-layout">
        @csrf

        <section class="form-card">
            <h2 class="section-title">
                <span class="section-number">1</span>
                Informasi Dasar
            </h2>

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="name" class="form-label">Nama Unit <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: Laboratorium Komputer Dasar"
                            class="form-input @error('name') is-invalid @enderror" required>
                    </div>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Unit <span class="required">*</span></label>
                    <div class="custom-select-wrapper @error('unit_type_id') is-invalid @enderror">
                        <input type="hidden" name="unit_type_id" id="unit_type_id" value="{{ old('unit_type_id') }}"
                            required>
                        <button type="button" class="custom-select-trigger">
                            <span class="selected-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                    <line x1="7" y1="7" x2="7.01" y2="7" />
                                </svg>
                            </span>
                            <span class="selected-text">Pilih tipe unit</span>
                            <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div class="custom-select-dropdown">
                            @foreach ($unitTypes as $type)
                                <div class="custom-select-option {{ old('unit_type_id') == $type->id ? 'is-selected' : '' }}"
                                    data-value="{{ $type->id }}" data-text="{{ $type->name }}"
                                    data-code="{{ $type->code_prefix ?? '' }}">
                                    <span class="option-text">{{ $type->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('unit_type_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="code" class="form-label">Kode Unit <span class="badge-auto">Auto</span></label>
                    <div class="code-wrapper">
                        <span class="input-icon code-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="16 18 22 12 16 6" />
                                <polyline points="8 6 2 12 8 18" />
                            </svg>
                        </span>
                        <input type="text" id="code" name="code" value="{{ old('code') }}"
                            class="form-input code-input @error('code') is-invalid @enderror" readonly required>
                        <button type="button" class="btn-icon" id="regenerateCode" title="Generate ulang">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path
                                    d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                            </svg>
                        </button>
                    </div>
                    <span class="help-text">Format: TIPE-INISIAL-NOMOR (contoh: LAB-KOM-42)</span>
                    @error('code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Departemen</label>
                    <div class="custom-select-wrapper @error('unit_department_id') is-invalid @enderror">
                        <input type="hidden" name="unit_department_id" id="unit_department_id"
                            value="{{ old('unit_department_id') }}">
                        <button type="button" class="custom-select-trigger">
                            <span class="selected-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                </svg>
                            </span>
                            <span class="selected-text">Pilih departemen (opsional)</span>
                            <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div class="custom-select-dropdown">
                            <div class="custom-select-option" data-value="" data-text="Pilih departemen (opsional)">
                                <span class="option-text">Pilih departemen (opsional)</span>
                            </div>
                            @foreach ($unitDepartments as $dept)
                                <div class="custom-select-option {{ old('unit_department_id') == $dept->id ? 'is-selected' : '' }}"
                                    data-value="{{ $dept->id }}" data-text="{{ $dept->name }}">
                                    <span class="option-text">{{ $dept->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('unit_department_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </section>

        <section class="form-card">
            <h2 class="section-title">
                <span class="section-number">2</span>
                Lokasi
            </h2>

            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label for="building" class="form-label">Gedung</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                        </span>
                        <input type="text" id="building" name="building" value="{{ old('building') }}"
                            placeholder="Contoh: Gedung FIK"
                            class="form-input @error('building') is-invalid @enderror">
                    </div>
                    @error('building')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="floor" class="form-label">Lantai</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                <polyline points="2 17 12 22 22 17" />
                                <polyline points="2 12 12 17 22 12" />
                            </svg>
                        </span>
                        <input type="text" id="floor" name="floor" value="{{ old('floor') }}"
                            placeholder="Contoh: 2" class="form-input @error('floor') is-invalid @enderror">
                    </div>
                    @error('floor')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Detail Lokasi <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </span>
                        <input type="text" id="location" name="location" value="{{ old('location') }}"
                            placeholder="Contoh: Ruang 201" class="form-input @error('location') is-invalid @enderror"
                            required>
                    </div>
                    @error('location')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </section>

        <section class="form-card">
            <h2 class="section-title">
                <span class="section-number">3</span>
                Jam Operasional
            </h2>

            @php
                $daysList = [
                    'monday' => 'Senin',
                    'tuesday' => 'Selasa',
                    'wednesday' => 'Rabu',
                    'thursday' => 'Kamis',
                    'friday' => 'Jumat',
                    'saturday' => 'Sabtu',
                    'sunday' => 'Minggu',
                ];
            @endphp

            <div class="grid grid-cols-2 mb-4">
                <div class="form-group">
                    <label class="form-label">Hari Operasional</label>
                    <div class="days-range-picker">
                        <div class="custom-select-wrapper compact">
                            <input type="hidden" name="open_days_start" id="openDaysStart"
                                value="{{ old('open_days_start', 'monday') }}">
                            <button type="button" class="custom-select-trigger">
                                <span class="selected-text">Senin</span>
                                <svg class="chevron" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </button>
                            <div class="custom-select-dropdown">
                                @foreach ($daysList as $val => $label)
                                    <div class="custom-select-option {{ old('open_days_start', 'monday') == $val ? 'is-selected' : '' }}"
                                        data-value="{{ $val }}" data-text="{{ $label }}">
                                        <span class="option-text">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <span class="range-separator">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </span>
                        <div class="custom-select-wrapper compact">
                            <input type="hidden" name="open_days_end" id="openDaysEnd"
                                value="{{ old('open_days_end', 'friday') }}">
                            <button type="button" class="custom-select-trigger">
                                <span class="selected-text">Jumat</span>
                                <svg class="chevron" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </button>
                            <div class="custom-select-dropdown">
                                @foreach ($daysList as $val => $label)
                                    <div class="custom-select-option {{ old('open_days_end', 'friday') == $val ? 'is-selected' : '' }}"
                                        data-value="{{ $val }}" data-text="{{ $label }}">
                                        <span class="option-text">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <span class="help-text" id="closedDaysPreview">Tutup: Sabtu, Minggu</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Operasional</label>
                    <div class="time-range-picker">
                        <div class="time-picker-wrapper" id="openTimePicker">
                            <div class="time-picker-trigger">
                                <span class="time-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </span>
                                <span class="time-display">
                                    <span class="time-value">08:00</span>
                                    <span class="time-period">AM</span>
                                </span>
                                <span class="time-chevron">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6" />
                                    </svg>
                                </span>
                            </div>
                            <input type="hidden" name="open_time" id="open_time"
                                value="{{ old('open_time', '08:00') }}">
                        </div>
                        <span class="range-separator">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </span>
                        <div class="time-picker-wrapper" id="closeTimePicker">
                            <div class="time-picker-trigger">
                                <span class="time-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </span>
                                <span class="time-display">
                                    <span class="time-value">17:00</span>
                                    <span class="time-period">PM</span>
                                </span>
                                <span class="time-chevron">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6" />
                                    </svg>
                                </span>
                            </div>
                            <input type="hidden" name="close_time" id="close_time"
                                value="{{ old('close_time', '17:00') }}">
                        </div>
                    </div>
                    <span class="help-text" id="timeValidation"></span>
                </div>
            </div>

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="capacity" class="form-label">Kapasitas Maksimum</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </span>
                        <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}"
                            class="form-input" min="1" placeholder="40">
                    </div>
                    @error('capacity')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status Operasional</label>
                    <div class="status-selector">
                        @foreach (['open' => 'Buka', 'maintenance' => 'Perawatan', 'closed' => 'Tutup'] as $val => $label)
                            <label class="status-option">
                                <input type="radio" name="operational_status" value="{{ $val }}"
                                    {{ old('operational_status', 'open') == $val ? 'checked' : '' }}>
                                <span class="status-label">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="form-card">
            <h2 class="section-title">
                <span class="section-number">4</span>
                Kontak & Detail
            </h2>

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="phone" class="form-label">Telepon</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0  1-.45 2.11L7.91 8.86a16 16 0 0 0 6.22 6.22l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.04z" />
                            </svg>
                        </span>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                            placeholder="021-5550001" class="form-input @error('phone') is-invalid @enderror">
                    </div>
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="unit@kampus.ac.id" class="form-input @error('email') is-invalid @enderror">
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group full-width" style="margin-top: 1.5rem;">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" placeholder="Deskripsikan unit ini..."
                    class="form-textarea @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </section>

        <section class="form-card">
            <h2 class="section-title">
                <span class="section-number">5</span>
                Gallery & Foto
            </h2>
            <div class="photo-deferred-notice">
                <div class="notice-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                </div>
                <div class="notice-text">
                    <p class="notice-title">Foto dapat ditambahkan setelah unit disimpan</p>
                    <p class="notice-desc">Setelah menyimpan, Anda akan diarahkan ke halaman edit di mana foto-foto
                        unit dapat diunggah dan dikelola.</p>
                </div>
            </div>
        </section>

        <section class="settings-section">
            <div class="settings-row">
                <div class="settings-info">
                    <h3>Status Aktif</h3>
                    <p>Unit yang tidak aktif tidak akan terlihat oleh mahasiswa</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <span class="btn-loader">
                    <svg class="spinner" width="16" height="16" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                            fill="none" stroke-dasharray="60" stroke-dashoffset="20" />
                    </svg>
                    Menyimpan...
                </span>
                <span class="btn-text">Simpan Unit</span>
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
    --color-success: #10b981;
    --radius-card: 24px;
    --radius-input: 12px;
    --radius-pill: 9999px;
    --shadow-resting: 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-dropdown: 0 12px 32px rgba(15, 23, 42, 0.12), 0 4px 8px rgba(15, 23, 42, 0.04);
    --shadow-primary: 0 4px 14px rgba(248, 119, 60, 0.25);
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
    --picker-primary: #f8773c;
    --picker-primary-dark: #e55a2b;
    --picker-primary-50: #fff5f0;
    --picker-bg: #ffffff;
    --picker-surface: #f9fafb;
    --picker-border: #e5e7eb;
    --picker-text: #111827;
    --picker-text-secondary: #6b7280;
    --picker-text-muted: #9ca3af;
    --picker-radius: 16px;
    --picker-radius-sm: 10px;
    --picker-radius-full: 9999px;
    --picker-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.12), 0 8px 16px -4px rgba(0, 0, 0, 0.06);
    --picker-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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

.badge-auto {
    font-size: 0.625rem;
    padding: 0.125rem 0.5rem;
    background: var(--color-primary-50);
    color: var(--color-primary);
    border-radius: var(--radius-pill);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.code-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.code-icon {
    position: absolute;
    left: 0.875rem;
    color: var(--color-text-secondary);
    pointer-events: none;
    z-index: 1;
}

.code-input {
    flex: 1;
    font-family: 'SF Mono', 'Menlo', monospace;
    font-size: 0.8rem;
    letter-spacing: 0.05em;
    background: var(--color-bg);
    color: var(--color-text-secondary);
    padding-left: 2.75rem;
}

.btn-icon {
    flex-shrink: 0;
    padding: 0.5rem;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-input);
    background: var(--color-surface);
    color: var(--color-text-secondary);
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

.custom-select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select-trigger {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-input);
    background: var(--color-surface);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    font-family: inherit;
    color: var(--color-text-primary);
    cursor: pointer;
    transition: var(--transition);
    text-align: left;
}

.custom-select-trigger:hover {
    border-color: #cbd5e1;
}

.custom-select-wrapper.open .custom-select-trigger,
.custom-select-trigger:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1);
}

.custom-select-wrapper.is-invalid .custom-select-trigger {
    border-color: var(--color-error);
    background-color: var(--color-error-50);
}

.selected-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    color: var(--color-text-secondary);
    flex-shrink: 0;
}

.selected-text {
    flex: 1;
    color: var(--color-text-secondary);
}

.custom-select-wrapper.open .selected-text {
    color: var(--color-text-primary);
}

.chevron {
    color: var(--color-text-secondary);
    transition: transform 0.3s var(--ease-out);
    flex-shrink: 0;
}

.custom-select-wrapper.open .chevron {
    transform: rotate(180deg);
    color: var(--color-primary);
}

.custom-select-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 20px;
    box-shadow: var(--shadow-dropdown);
    max-height: 240px;
    overflow-y: auto;
    z-index: 50;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px) scale(0.96);
    transition: all 0.25s var(--ease-out);
    padding: 8px;
}

.custom-select-wrapper.open .custom-select-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

.custom-select-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 1rem;
    cursor: pointer;
    transition: background 0.15s ease;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-primary);
}

.custom-select-option:hover {
    background: var(--color-bg);
}

.custom-select-option.is-selected {
    background: var(--color-primary-50);
    color: var(--color-primary);
}

.days-range-picker,
.time-range-picker {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.days-range-picker .custom-select-wrapper,
.time-range-picker .time-picker-wrapper {
    flex: 1;
}

.range-separator {
    color: var(--color-text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.time-picker-wrapper {
    position: relative;
    width: 100%;
}

.time-picker-trigger {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: var(--picker-bg);
    border: 1.5px solid var(--picker-border);
    border-radius: var(--picker-radius-sm);
    cursor: pointer;
    transition: var(--picker-transition);
    user-select: none;
}

.time-picker-trigger:hover {
    border-color: #cbd5e1;
    background: var(--picker-surface);
}

.time-picker-wrapper.is-open .time-picker-trigger {
    border-color: var(--picker-primary);
    box-shadow: 0 0 0 3px var(--picker-primary-50);
}

.time-picker-trigger.is-invalid {
    border-color: #ef4444;
    background: #fef2f2;
}

.time-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--picker-text-muted);
    flex-shrink: 0;
}

.time-picker-wrapper.is-open .time-icon {
    color: var(--picker-primary);
}

.time-display {
    display: flex;
    align-items: baseline;
    gap: 0.375rem;
    flex: 1;
}

.time-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--picker-text);
    letter-spacing: -0.01em;
    font-variant-numeric: tabular-nums;
}

.time-period {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--picker-text-secondary);
    padding: 0.125rem 0.5rem;
    background: var(--picker-surface);
    border-radius: var(--picker-radius-full);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.time-picker-wrapper.is-open .time-period {
    background: var(--picker-primary-50);
    color: var(--picker-primary);
}

.time-chevron {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--picker-text-muted);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    flex-shrink: 0;
}

.time-picker-wrapper.is-open .time-chevron {
    transform: rotate(180deg);
    color: var(--picker-primary);
}

.time-picker-modal {
    position: fixed;
    inset: 0;
    background: rgba(20, 14, 9, 0.55);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.25s;
    padding: 1.25rem;
}

.time-picker-wrapper.is-open .time-picker-modal {
    opacity: 1;
    visibility: visible;
}

.time-picker-modal-content {
    position: relative;
    background: var(--picker-bg);
    border-radius: 28px;
    box-shadow: 0 32px 64px -12px rgba(229, 90, 43, 0.18), 0 12px 24px -8px rgba(15, 23, 42, 0.12);
    padding: 0;
    width: 100%;
    max-width: 360px;
    max-height: min(640px, 92dvh);
    overflow: hidden;
    transform: scale(0.92) translateY(12px);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
}

.time-picker-wrapper.is-open .time-picker-modal-content {
    transform: scale(1) translateY(0);
}

.time-picker-modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(160deg, var(--picker-primary-50) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

.time-picker-header {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 1.375rem 1.5rem 1.125rem;
}

.time-picker-title-group {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.time-picker-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 11px;
    background: linear-gradient(135deg, var(--picker-primary) 0%, var(--picker-primary-dark) 100%);
    color: white;
    box-shadow: 0 4px 10px -2px rgba(229, 90, 43, 0.45);
    flex-shrink: 0;
}

.time-picker-title {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--picker-text);
    letter-spacing: -0.01em;
    line-height: 1.1;
}

.time-picker-subtitle {
    font-size: 0.6875rem;
    font-weight: 500;
    color: var(--picker-text-muted);
    margin-top: 0.0625rem;
}

.time-picker-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: none;
    background: var(--picker-surface);
    color: var(--picker-text-muted);
    cursor: pointer;
    transition: var(--picker-transition);
    flex-shrink: 0;
}

.time-picker-close:hover {
    background: #f3e4dc;
    color: var(--picker-primary-dark);
}

/* Live preview chip — updates in real time as the wheels scroll */
.time-live-preview {
    position: relative;
    z-index: 1;
    margin: 0 1.5rem 1.125rem;
    padding: 0.875rem 1.125rem;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--picker-primary) 0%, var(--picker-primary-dark) 100%);
    box-shadow: 0 8px 20px -6px rgba(229, 90, 43, 0.4);
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
}

.time-live-preview::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 100% 0%, rgba(255, 255, 255, 0.22) 0%, transparent 55%);
    pointer-events: none;
}

.time-live-preview-label {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.6875rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.85);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.time-live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
    animation: timeLivePulse 1.8s ease-out infinite;
}

@keyframes timeLivePulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.55);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(255, 255, 255, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
    }
}

.time-live-value {
    font-size: 1.625rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.02em;
    font-variant-numeric: tabular-nums;
    line-height: 1;
    transition: opacity 0.15s ease;
}

.time-live-period {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.22);
    padding: 0.25rem 0.5625rem;
    border-radius: 999px;
    margin-left: 0.5rem;
    letter-spacing: 0.03em;
}

.time-picker-body {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: stretch;
    justify-content: center;
    gap: 0;
    padding: 0 1.25rem;
    flex: 1;
    min-height: 0;
}

.time-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    min-width: 0;
}

.time-column-label {
    font-size: 0.625rem;
    font-weight: 700;
    color: var(--picker-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.625rem;
}

.time-scroll {
    position: relative;
    width: 100%;
    max-width: 88px;
    height: clamp(160px, 32dvh, 210px);
    overflow: hidden;
    user-select: none;
    -webkit-user-select: none;
    touch-action: none;
    cursor: grab;
}

.time-scroll:active {
    cursor: grabbing;
}

.time-scroll::before,
.time-scroll::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    height: 38%;
    pointer-events: none;
    z-index: 2;
}

.time-scroll::before {
    top: 0;
    background: linear-gradient(to bottom, var(--picker-bg) 0%, rgba(255, 255, 255, 0) 100%);
}

.time-scroll::after {
    bottom: 0;
    background: linear-gradient(to top, var(--picker-bg) 0%, rgba(255, 255, 255, 0) 100%);
}

/* Center focus band — the selection "lens" shared across all columns */
.time-picker-body::before {
    content: '';
    position: absolute;
    left: 1.25rem;
    right: 1.25rem;
    top: 50%;
    height: 40px;
    transform: translateY(-50%);
    background: var(--picker-primary-50);
    border-top: 1.5px solid rgba(229, 90, 43, 0.25);
    border-bottom: 1.5px solid rgba(229, 90, 43, 0.25);
    border-radius: 10px;
    z-index: 0;
    pointer-events: none;
}

.time-scroll-inner {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}

.time-scroll-inner.scrolling {
    transition: transform 0.1s linear;
}

.time-scroll-inner.snapping {
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.time-item {
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.0625rem;
    font-weight: 500;
    color: var(--picker-text-muted);
    cursor: pointer;
    transition: color 0.2s ease, font-weight 0.2s ease, transform 0.2s ease;
    user-select: none;
    font-variant-numeric: tabular-nums;
}

.time-item:hover {
    color: var(--picker-text-secondary);
}

.time-item.is-selected {
    color: var(--picker-primary-dark);
    font-weight: 700;
}

.time-item.in-focus {
    font-size: 1.3125rem;
    font-weight: 800;
    color: var(--picker-primary-dark);
    transform: scale(1.02);
}

.time-separator-static {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 14px;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--picker-text-muted);
    flex-shrink: 0;
    align-self: center;
    margin-top: 1.5rem;
}

.time-picker-footer {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 1.125rem 1.5rem 1.375rem;
    border-top: 1px solid var(--picker-border);
    margin-top: 0.25rem;
}

.time-picker-actions {
    display: flex;
    gap: 0.625rem;
    width: 100%;
}

.time-btn {
    flex: 1;
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 700;
    border: none;
    border-radius: 13px;
    cursor: pointer;
    transition: var(--picker-transition);
}

.time-btn-cancel {
    background: var(--picker-surface);
    color: var(--picker-text-secondary);
    border: 1.5px solid var(--picker-border);
    flex: 0.8;
}

.time-btn-cancel:hover {
    background: #f3f4f6;
    color: var(--picker-text);
    border-color: #d1d5db;
}

.time-btn-confirm {
    background: linear-gradient(135deg, var(--picker-primary) 0%, var(--picker-primary-dark) 100%);
    color: white;
    box-shadow: 0 6px 16px -4px rgba(229, 90, 43, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

.time-btn-confirm:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px -4px rgba(229, 90, 43, 0.55);
}

.time-btn-confirm:active {
    transform: translateY(0);
}

body.modal-time-open {
    overflow: hidden !important;
}

.status-selector {
    display: flex;
    gap: 0.5rem;
    padding: 0.5rem;
    background: var(--color-bg);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-card);
}

.status-option {
    flex: 1;
    cursor: pointer;
}

.status-option input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.status-label {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-pill);
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--color-text-secondary);
    transition: var(--transition);
    background: var(--color-surface);
    border: 1px solid transparent;
}

.status-option input:checked+.status-label {
    background: var(--color-primary);
    color: white;
    border-color: var(--color-primary);
    box-shadow: 0 2px 8px rgba(248, 119, 60, 0.3);
}

.status-option:hover .status-label {
    border-color: #cbd5e1;
}

.photo-deferred-notice {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--color-primary-50);
    border: 1px solid rgba(248, 119, 60, 0.2);
    border-radius: 16px;
}

.notice-icon-wrap {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    background: white;
    border-radius: var(--radius-input);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-primary);
    box-shadow: var(--shadow-resting);
}

.notice-text {
    flex: 1;
}

.notice-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 0.25rem;
}

.notice-desc {
    font-size: 0.8125rem;
    color: var(--color-text-secondary);
    line-height: 1.5;
}

.settings-section {
    background: var(--color-surface);
    border-radius: var(--radius-card);
    padding: 1.5rem 2rem;
    box-shadow: var(--shadow-resting);
    border: 1px solid var(--color-border);
}

.settings-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
}

.settings-info h3 {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.settings-info p {
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
    inset: 0;
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

.btn {
    padding: 0.75rem 1.75rem;
    border-radius: var(--radius-pill);
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
    font-family: inherit;
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

.btn-primary:disabled {
    opacity: 0.8;
    cursor: not-allowed;
    transform: none;
}

.btn-loader {
    display: none;
    align-items: center;
    gap: 0.5rem;
}

.btn.loading .btn-text {
    display: none;
}

.btn.loading .btn-loader {
    display: inline-flex;
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

@media (max-width: 640px) {
    .time-picker-modal-content {
        max-width: 100%;
        border-radius: 24px 24px 0 0;
        align-self: flex-end;
        max-height: min(560px, 88dvh);
    }

    .time-picker-modal {
        align-items: flex-end;
        padding: 0;
    }

    .time-picker-wrapper.is-open .time-picker-modal-content {
        transform: scale(1) translateY(0);
    }

    .time-picker-header {
        padding: 1.25rem 1.25rem 1rem;
    }

    .time-live-preview {
        margin: 0 1.25rem 1rem;
    }

    .time-picker-body {
        padding: 0 1rem;
    }

    .time-scroll {
        max-width: 76px;
        height: clamp(140px, 26dvh, 180px);
    }

    .time-item {
        height: 36px;
        font-size: 0.9375rem;
    }

    .time-item.in-focus {
        font-size: 1.1875rem;
    }

    .time-picker-body::before {
        left: 1rem;
        right: 1rem;
        height: 36px;
    }

    .time-picker-footer {
        padding: 1rem 1.25rem 1.25rem;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .btn {
        width: 100%;
    }
}

@media (max-width: 380px) {
    .time-live-value {
        font-size: 1.375rem;
    }

    .time-column-label {
        font-size: 0.5625rem;
    }

    .time-scroll {
        max-width: 64px;
    }
}

@media (max-height: 700px) {
    .time-picker-header {
        padding: 1rem 1.5rem 0.75rem;
    }

    .time-live-preview {
        margin: 0 1.5rem 0.75rem;
        padding: 0.625rem 1rem;
    }

    .time-live-value {
        font-size: 1.375rem;
    }

    .time-scroll {
        height: clamp(130px, 24dvh, 170px);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initCustomSelects();
    initCodeGeneration();
    initDaysRangePreview();
    initTimePickers();
    initTimeValidation();
    initFormSubmit();
});

function initCustomSelects() {
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        const trigger = wrapper.querySelector('.custom-select-trigger');
        const dropdown = wrapper.querySelector('.custom-select-dropdown');
        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
        const selectedText = wrapper.querySelector('.selected-text');

        const initialOption = dropdown.querySelector(
            `.custom-select-option[data-value="${hiddenInput.value}"]`);
        if (initialOption && selectedText) {
            selectedText.textContent = initialOption.dataset.text;
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.custom-select-wrapper.open').forEach(w => {
                if (w !== wrapper) w.classList.remove('open');
            });
            wrapper.classList.toggle('open');
        });

        dropdown.querySelectorAll('.custom-select-option').forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                hiddenInput.value = option.dataset.value;
                if (selectedText) selectedText.textContent = option.dataset.text;
                dropdown.querySelectorAll('.custom-select-option').forEach(o => o.classList
                    .remove('is-selected'));
                option.classList.add('is-selected');
                wrapper.classList.remove('open');
                wrapper.classList.remove('is-invalid');
                if (hiddenInput.id === 'unit_type_id') {
                    hiddenInput.dispatchEvent(new Event('change'));
                }
                if (hiddenInput.id === 'openDaysStart' || hiddenInput.id ===
                    'openDaysEnd') {
                    updateDaysPreview();
                }
            });
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select-wrapper.open').forEach(w => w.classList.remove('open'));
    });
}

function initCodeGeneration() {
    const nameInput = document.getElementById('name');
    const typeInput = document.getElementById('unit_type_id');
    const codeInput = document.getElementById('code');
    const regenerateBtn = document.getElementById('regenerateCode');
    if (!nameInput || !codeInput) return;

    const generate = () => {
        const name = nameInput.value.trim();
        const selectedOption = document.querySelector(
            `#unit_type_id + .custom-select-trigger + .custom-select-dropdown .custom-select-option[data-value="${typeInput.value}"]`
            );
        if (!name || !typeInput.value) {
            codeInput.value = '';
            return;
        }
        const prefix = selectedOption && selectedOption.dataset.code ? selectedOption.dataset.code : 'UNT';
        const words = name.split(/\s+/);
        const nameCode = words.length === 1 ? words[0].substring(0, 3).toUpperCase() : words.map(w => w.charAt(
            0).toUpperCase()).join('').substring(0, 3);
        const num = Math.floor(Math.random() * 90 + 10);
        codeInput.value = `${prefix}-${nameCode}-${num}`;
    };

    nameInput.addEventListener('blur', generate);
    typeInput.addEventListener('change', generate);
    if (regenerateBtn) regenerateBtn.addEventListener('click', generate);
}

function initDaysRangePreview() {
    const startInput = document.getElementById('openDaysStart');
    const endInput = document.getElementById('openDaysEnd');
    const previewSpan = document.getElementById('closedDaysPreview');
    if (!startInput || !endInput || !previewSpan) return;

    const daysMap = {
        'monday': 'Senin',
        'tuesday': 'Selasa',
        'wednesday': 'Rabu',
        'thursday': 'Kamis',
        'friday': 'Jumat',
        'saturday': 'Sabtu',
        'sunday': 'Minggu'
    };
    const daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    window.updateDaysPreview = function() {
        const start = startInput.value;
        const end = endInput.value;
        const startIndex = daysOrder.indexOf(start);
        const endIndex = daysOrder.indexOf(end);
        let openDays = startIndex <= endIndex ? daysOrder.slice(startIndex, endIndex + 1) : daysOrder.slice(
            startIndex).concat(daysOrder.slice(0, endIndex + 1));
        const closedDays = daysOrder.filter(day => !openDays.includes(day));
        previewSpan.textContent = closedDays.length === 0 ? 'Buka setiap hari' : 'Tutup: ' + closedDays.map(d =>
            daysMap[d]).join(', ');
    };
    updateDaysPreview();
}

function initTimePickers() {
    const pickers = document.querySelectorAll('.time-picker-wrapper');

    pickers.forEach(wrapper => {
        const trigger = wrapper.querySelector('.time-picker-trigger');
        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
        const valueDisplay = wrapper.querySelector('.time-value');
        const periodDisplay = wrapper.querySelector('.time-period');

        let selectedHour = 8;
        let selectedMinute = 0;
        let selectedPeriod = 'AM';

        const initialValue = hiddenInput.value || '08:00';
        const [hours, minutes] = initialValue.split(':').map(Number);
        selectedPeriod = hours >= 12 ? 'PM' : 'AM';
        selectedHour = hours > 12 ? hours - 12 : (hours === 0 ? 12 : hours);
        selectedMinute = minutes;

        if (!wrapper.querySelector('.time-picker-modal')) {
            wrapper.insertAdjacentHTML('beforeend', buildTimePickerModal(wrapper.id));
        }

        const modal = wrapper.querySelector('.time-picker-modal');
        const confirmBtn = wrapper.querySelector('.time-btn-confirm');
        const cancelBtn = wrapper.querySelector('.time-btn-cancel');
        const closeBtn = wrapper.querySelector('.time-picker-close');
        const liveValue = wrapper.querySelector('.time-live-value');
        const livePeriod = wrapper.querySelector('.time-live-period');

        setupWheelScroll(modal, 'hour', (val) => {
            selectedHour = val;
            updateDisplay();
        }, () => selectedHour);

        setupWheelScroll(modal, 'minute', (val) => {
            selectedMinute = val;
            updateDisplay();
        }, () => selectedMinute);

        setupWheelScroll(modal, 'period', (val) => {
            selectedPeriod = val;
            updateDisplay();
        }, () => selectedPeriod);

        updateDisplay();

        function closePicker() {
            wrapper.classList.remove('is-open');
            document.body.classList.remove('modal-time-open');
        }

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            pickers.forEach(p => {
                if (p !== wrapper) p.classList.remove('is-open');
            });
            wrapper.classList.toggle('is-open');
            if (wrapper.classList.contains('is-open')) {
                document.body.classList.add('modal-time-open');
                setTimeout(() => {
                    snapWheelToValue(modal, 'hour', selectedHour);
                    snapWheelToValue(modal, 'minute', selectedMinute);
                    snapWheelToValue(modal, 'period', selectedPeriod);
                }, 50);
            } else {
                document.body.classList.remove('modal-time-open');
            }
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closePicker();
            }
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const hour24 = selectedPeriod === 'PM' && selectedHour !== 12 ?
                    selectedHour + 12 :
                    (selectedPeriod === 'AM' && selectedHour === 12 ? 0 : selectedHour);
                const timeValue =
                    `${String(hour24).padStart(2, '0')}:${String(selectedMinute).padStart(2, '0')}`;
                hiddenInput.value = timeValue;
                updateDisplay();
                closePicker();
                hiddenInput.dispatchEvent(new Event('change'));
                validateTime();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', closePicker);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closePicker);
        }

        function updateDisplay() {
            const displayHour = String(selectedHour).padStart(2, '0');
            const displayMin = String(selectedMinute).padStart(2, '0');
            if (valueDisplay) valueDisplay.textContent = `${displayHour}:${displayMin}`;
            if (periodDisplay) periodDisplay.textContent = selectedPeriod;
            if (liveValue) liveValue.textContent = `${displayHour}:${displayMin}`;
            if (livePeriod) livePeriod.textContent = selectedPeriod;
        }
    });
}

function setupWheelScroll(container, type, onChange, getValue) {
    const scrollContainer = container.querySelector(`.time-scroll[data-type="${type}"]`);
    const inner = scrollContainer.querySelector('.time-scroll-inner');
    const items = scrollContainer.querySelectorAll('.time-item');
    const isNumeric = type !== 'period';

    function parseItemValue(raw) {
        return isNumeric ? parseInt(raw) : raw;
    }

    const ITEM_HEIGHT = 40;
    const VISIBLE_ITEMS = 5;
    const CENTER_OFFSET = Math.floor(VISIBLE_ITEMS / 2) * ITEM_HEIGHT;

    let currentTranslate = 0;
    let scrollTimeout = null;
    let isScrolling = false;
    let touchStartY = 0;
    let touchStartTranslate = 0;

    function getItemPosition(value) {
        const index = Array.from(items).findIndex(item => parseItemValue(item.dataset.value) === value);
        return index === -1 ? 0 : -(index * ITEM_HEIGHT) + CENTER_OFFSET;
    }

    function snapToNearest() {
        isScrolling = false;
        inner.classList.remove('scrolling');
        inner.classList.add('snapping');
        const normalizedPos = currentTranslate - CENTER_OFFSET;
        const nearestIndex = Math.round(-normalizedPos / ITEM_HEIGHT);
        const clampedIndex = Math.max(0, Math.min(items.length - 1, nearestIndex));
        currentTranslate = -(clampedIndex * ITEM_HEIGHT) + CENTER_OFFSET;
        inner.style.transform = `translateY(${currentTranslate}px)`;
        const item = items[clampedIndex];
        if (item) {
            const value = parseItemValue(item.dataset.value);
            items.forEach(i => i.classList.remove('is-selected', 'in-focus'));
            item.classList.add('is-selected', 'in-focus');
            onChange(value);
        }
        setTimeout(() => {
            inner.classList.remove('snapping');
        }, 500);
    }

    function handleWheel(e) {
        e.preventDefault();
        e.stopPropagation();
        const delta = e.deltaY > 0 ? 1 : -1;
        if (!isScrolling) {
            isScrolling = true;
            inner.classList.add('scrolling');
        }
        currentTranslate += delta * ITEM_HEIGHT;
        const minTranslate = -(items.length - 1) * ITEM_HEIGHT + CENTER_OFFSET;
        const maxTranslate = CENTER_OFFSET;
        currentTranslate = Math.max(minTranslate, Math.min(maxTranslate, currentTranslate));
        inner.style.transform = `translateY(${currentTranslate}px)`;
        const normalizedPos = currentTranslate - CENTER_OFFSET;
        const centerIndex = Math.round(-normalizedPos / ITEM_HEIGHT);
        items.forEach((item, i) => {
            if (i === centerIndex) {
                item.classList.add('in-focus');
            } else {
                item.classList.remove('in-focus');
            }
        });
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(snapToNearest, 150);
    }

    function handleTouchStart(e) {
        e.preventDefault();
        const touch = e.touches[0];
        touchStartY = touch.clientY;
        touchStartTranslate = currentTranslate;
        inner.classList.add('scrolling');
    }

    function handleTouchMove(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const deltaY = touch.clientY - touchStartY;
        currentTranslate = touchStartTranslate + deltaY;
        const minTranslate = -(items.length - 1) * ITEM_HEIGHT + CENTER_OFFSET;
        const maxTranslate = CENTER_OFFSET;
        currentTranslate = Math.max(minTranslate, Math.min(maxTranslate, currentTranslate));
        inner.style.transform = `translateY(${currentTranslate}px)`;
    }

    function handleTouchEnd(e) {
        e.preventDefault();
        snapToNearest();
    }

    scrollContainer.addEventListener('wheel', handleWheel, {
        passive: false
    });
    scrollContainer.addEventListener('touchstart', handleTouchStart, {
        passive: false
    });
    scrollContainer.addEventListener('touchmove', handleTouchMove, {
        passive: false
    });
    scrollContainer.addEventListener('touchend', handleTouchEnd, {
        passive: false
    });

    items.forEach((item, index) => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            const value = parseItemValue(this.dataset.value);
            currentTranslate = -(index * ITEM_HEIGHT) + CENTER_OFFSET;
            inner.style.transform = `translateY(${currentTranslate}px)`;
            items.forEach(i => i.classList.remove('is-selected', 'in-focus'));
            this.classList.add('is-selected', 'in-focus');
            onChange(value);
        });
    });

    scrollContainer._snapToValue = function(value) {
        currentTranslate = getItemPosition(value);
        inner.style.transform = `translateY(${currentTranslate}px)`;
        items.forEach(i => {
            if (parseItemValue(i.dataset.value) === value) {
                i.classList.add('is-selected', 'in-focus');
            } else {
                i.classList.remove('is-selected', 'in-focus');
            }
        });
    };
}

function snapWheelToValue(container, type, value) {
    const scrollContainer = container.querySelector(`.time-scroll[data-type="${type}"]`);
    if (scrollContainer && scrollContainer._snapToValue) {
        scrollContainer._snapToValue(value);
    }
}

function buildTimePickerModal(wrapperId) {
    const hours = Array.from({
        length: 12
    }, (_, i) => i + 1);
    const minutes = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55];
    const periods = ['AM', 'PM'];

    return `
<div class="time-picker-modal">
    <div class="time-picker-modal-content">
        <div class="time-picker-header">
            <div class="time-picker-title-group">
                <span class="time-picker-title-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </span>
                <div>
                    <div class="time-picker-title">Atur Waktu</div>
                    <div class="time-picker-subtitle">Scroll untuk memilih</div>
                </div>
            </div>
            <button type="button" class="time-picker-close" aria-label="Tutup">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="time-live-preview">
            <span class="time-live-preview-label">
                <span class="time-live-dot"></span>
                Live
            </span>
            <div>
                <span class="time-live-value">08:00</span><span class="time-live-period">AM</span>
            </div>
        </div>

        <div class="time-picker-body">
            <div class="time-column">
                <span class="time-column-label">Jam</span>
                <div class="time-scroll" data-type="hour">
                    <div class="time-scroll-inner">
                        ${hours.map(h => `<div class="time-item" data-value="${h}">${String(h).padStart(2, '0')}</div>`).join('')}
                    </div>
                </div>
            </div>
            <span class="time-separator-static">:</span>
            <div class="time-column">
                <span class="time-column-label">Menit</span>
                <div class="time-scroll" data-type="minute">
                    <div class="time-scroll-inner">
                        ${minutes.map(m => `<div class="time-item" data-value="${m}">${String(m).padStart(2, '0')}</div>`).join('')}
                    </div>
                </div>
            </div>
            <div class="time-column">
                <span class="time-column-label">Periode</span>
                <div class="time-scroll" data-type="period">
                    <div class="time-scroll-inner">
                        ${periods.map(p => `<div class="time-item" data-value="${p}">${p}</div>`).join('')}
                    </div>
                </div>
            </div>
        </div>

        <div class="time-picker-footer">
            <div class="time-picker-actions">
                <button type="button" class="time-btn time-btn-cancel">Batal</button>
                <button type="button" class="time-btn time-btn-confirm">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>
`;
}

function initTimeValidation() {
    const openTimeInput = document.getElementById('open_time');
    const closeTimeInput = document.getElementById('close_time');
    const validationEl = document.getElementById('timeValidation');

    if (!openTimeInput || !closeTimeInput) return;

    window.validateTime = function() {
        const openVal = openTimeInput.value;
        const closeVal = closeTimeInput.value;

        if (!openVal || !closeVal) {
            if (validationEl) {
                validationEl.textContent = '';
                validationEl.className = 'help-text';
            }
            closeTimeInput.classList.remove('is-invalid');
            return true;
        }

        const [openHour, openMin] = openVal.split(':').map(Number);
        const [closeHour, closeMin] = closeVal.split(':').map(Number);

        const openTotalMin = openHour * 60 + openMin;
        const closeTotalMin = closeHour * 60 + closeMin;

        if (openTotalMin === closeTotalMin) {
            if (validationEl) {
                validationEl.innerHTML = 'Jam buka dan tutup tidak boleh sama';
                validationEl.style.color = 'var(--color-error)';
            }
            closeTimeInput.classList.add('is-invalid');
            return false;
        }

        if (closeTotalMin <= openTotalMin) {
            if (validationEl) {
                validationEl.innerHTML = 'Jam tutup harus setelah jam buka';
                validationEl.style.color = 'var(--color-error)';
            }
            closeTimeInput.classList.add('is-invalid');
            return false;
        }

        const durationMin = closeTotalMin - openTotalMin;
        const hours = Math.floor(durationMin / 60);
        const minutes = durationMin % 60;
        const durationText = minutes > 0 ? `${hours} jam ${minutes} menit` : `${hours} jam`;

        if (validationEl) {
            validationEl.innerHTML = `Durasi operasional: ${durationText}`;
            validationEl.style.color = 'var(--color-success)';
        }
        closeTimeInput.classList.remove('is-invalid');
        return true;
    };

    openTimeInput.addEventListener('change', validateTime);
    closeTimeInput.addEventListener('change', validateTime);
    validateTime();
}

function initFormSubmit() {
    const form = document.getElementById('unitForm');
    const btn = document.getElementById('submitBtn');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let isValid = true;
        let firstError = null;

        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                if (field.type === 'hidden') {
                    const wrapper = field.closest('.custom-select-wrapper');
                    if (wrapper) {
                        wrapper.classList.add('is-invalid');
                        if (!firstError) firstError = wrapper;
                    }
                } else {
                    field.classList.add('is-invalid');
                    if (!firstError) firstError = field;
                }
            } else {
                if (field.type === 'hidden') {
                    const wrapper = field.closest('.custom-select-wrapper');
                    if (wrapper) wrapper.classList.remove('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
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

        if (btn) {
            btn.disabled = true;
            btn.classList.add('loading');
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