@extends('layouts.admin.app')

@section('title', 'Tambah Unit Baru')

@section('admin-content')
    <div class="page-container">
        <header class="page-header">
            <div>
                <h1>Tambah Unit Baru</h1>
                <p class="subtitle">Buat unit atau ruangan baru dalam sistem</p>
            </div>
            <a href="{{ route('admin.units.index') }}" class="btn-ghost">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </header>

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

        <form action="{{ route('admin.units.store') }}" method="POST" id="unitForm" class="form-layout">
            @csrf

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">1</span>
                    Informasi Dasar
                </h2>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Nama Unit <span class="required">*</span>
                        </label>
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
                        @error('name')<span class="error-message">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="unit_type_id" class="form-label">
                            Tipe Unit <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                    <line x1="7" y1="7" x2="7.01" y2="7" />
                                </svg>
                            </span>
                            <select id="unit_type_id" name="unit_type_id"
                                class="form-select @error('unit_type_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih tipe unit</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}" data-code="{{ $type->code_prefix ?? '' }}" {{ old('unit_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('unit_type_id')<span class="error-message">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="code" class="form-label">
                            Kode Unit
                            <span class="badge-auto">Auto</span>
                        </label>
                        <div class="code-wrapper">
                            <span class="input-icon code-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="16 18 22 12 16 6" />
                                    <polyline points="8 6 2 12 8 18" />
                                </svg>
                            </span>
                            <input type="text" id="code" name="code" value="{{ old('code') }}"
                                class="form-input code-input @error('code') is-invalid @enderror" readonly required>
                            <button type="button" class="btn-icon" id="regenerateCode" title="Generate ulang">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                                </svg>
                            </button>
                        </div>
                        <span class="help-text">Format: TIPE-INISIAL-NOMOR (contoh: LAB-KOM-42)</span>
                        @error('code')<span class="error-message">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="unit_department_id" class="form-label">Departemen</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                </svg>
                            </span>
                            <select id="unit_department_id" name="unit_department_id"
                                class="form-select @error('unit_department_id') is-invalid @enderror">
                                <option value="" selected>Pilih departemen (opsional)</option>
                                @foreach($unitDepartments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('unit_department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('unit_department_id')<span class="error-message">{{ $message }}</span>@enderror
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
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                            </span>
                            <input type="text" id="building" name="building" value="{{ old('building') }}"
                                placeholder="Contoh: Gedung FIK" class="form-input @error('building') is-invalid @enderror">
                        </div>
                        @error('building')<span class="error-message">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="floor" class="form-label">Lantai</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                    <polyline points="2 17 12 22 22 17" />
                                    <polyline points="2 12 12 17 22 12" />
                                </svg>
                            </span>
                            <input type="text" id="floor" name="floor" value="{{ old('floor') }}" placeholder="Contoh: 2"
                                class="form-input @error('floor') is-invalid @enderror">
                        </div>
                        @error('floor')<span class="error-message">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Detail Lokasi</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </span>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                placeholder="Contoh: Ruang 201" class="form-input @error('location') is-invalid @enderror"
                                required>
                        </div>
                        @error('location')<span class="error-message">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            <section class="form-card">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <h5 class="card-title fw-bold mb-0 d-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold"
                                style="width:26px;height:26px;background:#fff5f0;color:#f8773c;font-size:12px;">3</span>
                            Jam Operasional
                        </h5>
                        <span class="badge rounded-pill px-3 py-2"
                            style="background:#fff5f0;color:#f8773c;font-size:10px;letter-spacing:.1em;font-weight:700;">
                            ACTIVE SCHEDULE
                        </span>
                    </div>
                    @php
                        $daysList = [
                            'monday' => 'Senin',
                            'tuesday' => 'Selasa',
                            'wednesday' => 'Rabu',
                            'thursday' => 'Kamis',
                            'friday' => 'Jumat',
                            'saturday' => 'Sabtu',
                            'sunday' => 'Minggu'
                        ];
                    @endphp
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="d-block mb-2 form-label">Hari Operasional</label>

                            <div class="d-flex align-items-center gap-2 p-1 rounded-pill">
                                <select name="open_days_start" id="openDaysStart"
                                    class="form-select">
                                    @foreach($daysList as $value => $label)
                                        <option value="{{ $value }}" {{ old('open_days_start', 'monday') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-secondary px-1" style="font-size:18px;">→</span>
                                <select name="open_days_end" id="openDaysEnd"
                                    class="form-select">
                                    @foreach($daysList as $value => $label)
                                        <option value="{{ $value }}" {{ old('open_days_end', 'friday') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('open_days_start')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @error('open_days_end')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="d-block mb-2 form-label">Jam Operasional</label>

                            <div class="d-flex align-items-center gap-2 p-1 rounded-pill">
                                <input type="time" name="open_time" value="{{ old('open_time', '08:00') }}"
                                    class="form-control">
                                <span class="text-secondary px-1" style="font-size:18px;">→</span>
                                <input type="time" name="close_time" value="{{ old('close_time', '17:00') }}"
                                    class="form-control">
                            </div>

                            @error('open_time')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @error('close_time')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="d-block mb-2 form-label">Kapasitas Maksimum</label>
                            <div class="position-relative">
                                <input type="number" name="capacity" value="{{ old('capacity') }}"
                                    class="form-control"
                                    min="1">
                            </div>
                            @error('capacity')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-8" x-data="{ status: '{{ old('operational_status', 'open') }}' }">
                            <label class="d-block mb-2 form-label">Status Operasional</label>
                            <div class="d-flex gap-1 p-1 rounded-pill"
                                style="background:#f3f4f6;border:0.5px solid #e5e7eb;">
                                @foreach(['open' => 'Buka', 'maintenance' => 'Perawatan', 'closed' => 'Tutup'] as $val => $label)
                                    <button type="button" @click="status = '{{ $val }}'"
                                        :class="status === '{{ $val }}' ? 'btn-primary text-white shadow' : 'btn-light text-secondary bg-transparent'"
                                        class="btn rounded-pill px-4 py-2 fw-semibold flex-fill"
                                        style="font-size:13px;transition:all .2s;">
                                        {{ $label }}
                                    </button>
                                @endforeach
                                <input type="hidden" name="operational_status" :value="status">
                            </div>
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
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.86a16 16 0 0 0 6.22 6.22l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.04z" />
                                </svg>
                            </span>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="021-5550001"
                                class="form-input @error('phone') is-invalid @enderror">
                        </div>
                        @error('phone')<span class="error-message">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="unit@kampus.ac.id" class="form-input @error('email') is-invalid @enderror">
                        </div>
                        @error('email')<span class="error-message">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group full-width" style="margin-top: 0.5rem;">
                    <label for="facilities" class="form-label">Fasilitas</label>
                    <select id="facilities" name="facilities[]" multiple
                        class="form-select no-icon @error('facilities') is-invalid @enderror"
                        data-placeholder="Cari dan pilih fasilitas...">
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ in_array($facility->id, old('facilities', [])) ? 'selected' : '' }}>{{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="help-text">Ketik untuk mencari, klik untuk memilih. Bisa pilih lebih dari satu.</span>
                    @error('facilities')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group full-width" style="margin-top: 0.5rem;">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" placeholder="Deskripsikan unit ini..."
                        class="form-textarea @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<span class="error-message">{{ $message }}</span>@enderror
                </div>
            </section>

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">5</span>
                    Gallery & Foto
                </h2>
                <div class="photo-deferred-notice">
                    <div class="notice-icon-wrap">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    <div class="notice-text">
                        <p class="notice-title">Foto dapat ditambahkan setelah unit disimpan</p>
                        <p class="notice-desc">Setelah menyimpan, Anda akan diarahkan ke halaman edit di mana foto-foto unit
                            dapat diunggah dan dikelola.</p>
                    </div>
                </div>
            </section>

            <div class="settings-section">
                <div class="settings-row">
                    <div class="settings-info">
                        <h3>Status Aktif</h3>
                        <p>Unit yang tidak aktif tidak akan terlihat oleh mahasiswa</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-light" style="color: #ffff; background-color: #f8773c;" id="submitBtn">
                    <span class="btn-text">Simpan Unit</span>
                    <span class="btn-loader" style="display: none;">
                        <svg class="spinner" width="16" height="16" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4" fill="none" stroke-dasharray="60"
                                stroke-dashoffset="20" />
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
            --primary: #f8773c;
            --primary-dark: #e55a2b;
            --primary-50: #fff5f0;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --error: #ef4444;
            --error-50: #fef2f2;
            --success: #10b981;
            --success-50: #d1fae5;
            --warning: #f59e0b;
            --warning-50: #fef3c7;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, .05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, .1);
            --transition: all .2s cubic-bezier(.4, 0, .2, 1);
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
            color: var(--gray-900);
            margin-bottom: .25rem;
        }

        .page-header .subtitle {
            color: var(--gray-500);
            font-size: .875rem;
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
            border: 1px solid var(--gray-200);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 1.25rem;
            padding-bottom: .75rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .section-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: var(--primary-50);
            color: var(--primary);
            font-size: .75rem;
            font-weight: 700;
            border-radius: 50%;
            flex-shrink: 0;
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
            gap: .375rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: .875rem;
            font-weight: 500;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: .375rem;
        }

        .required {
            color: var(--error);
        }

        .help-text {
            font-size: .75rem;
            color: var(--gray-400);
            margin-top: .125rem;
        }

        .error-message {
            font-size: .75rem;
            color: var(--error);
            margin-top: .125rem;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: .625rem .875rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: .875rem;
            color: var(--gray-900);
            background: white;
            transition: var(--transition);
            box-sizing: border-box;
        }

        .input-wrapper .form-input,
        .input-wrapper .form-select {
            padding-left: 2.5rem;
        }

        .form-select.no-icon {
            padding-left: .875rem !important;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-50);
        }

        .form-input.is-invalid,
        .form-select.is-invalid,
        .form-textarea.is-invalid {
            border-color: var(--error);
            background-color: var(--error-50);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: .75rem;
            color: var(--gray-400);
            pointer-events: none;
            display: flex;
            align-items: center;
            z-index: 1;
        }

        .code-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .code-icon {
            position: absolute;
            left: .75rem;
            color: var(--gray-400);
            pointer-events: none;
            z-index: 1;
        }

        .code-input {
            flex: 1;
            font-family: monospace;
            font-size: .8rem;
            letter-spacing: .05em;
            background: var(--gray-50);
            color: var(--gray-600);
            padding-left: 2.5rem;
        }

        .badge-auto {
            font-size: .6rem;
            padding: .125rem .375rem;
            background: var(--primary-50);
            color: var(--primary);
            border-radius: var(--radius-sm);
            font-weight: 600;
        }

        .btn-icon {
            flex-shrink: 0;
            padding: .5rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            background: white;
            color: var(--gray-500);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-50);
        }

        .photo-deferred-notice {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            background: var(--primary-50);
            border: 1px solid rgba(248, 119, 60, .2);
            border-radius: var(--radius-lg);
        }

        .notice-icon-wrap {
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            background: white;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .notice-text {
            flex: 1;
        }

        .notice-title {
            font-size: .875rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: .25rem;
        }

        .notice-desc {
            font-size: .8125rem;
            color: var(--gray-500);
            line-height: 1.5;
        }

        .settings-section {
            background: white;
            border-radius: var(--radius-xl);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }

        .settings-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .settings-info h3 {
            font-size: .9375rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: .25rem;
        }

        .settings-info p {
            font-size: .8125rem;
            color: var(--gray-500);
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
            inset: 0;
            background: var(--gray-300);
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: var(--primary);
        }

        .toggle-switch input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        .btn-ghost {
            background: transparent;
            color: var(--gray-600);
            padding: .5rem .75rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .875rem;
            font-weight: 500;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-ghost:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
            padding-top: .25rem;
        }

        .btn-secondary {
            padding: .625rem 1.5rem;
            background: var(--gray-100);
            color: var(--gray-700);
            border-radius: var(--radius-md);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
        }

        .btn-light {
            padding: .625rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-light:hover {
            background: var(--primary-dark);
        }

        .btn-light:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .btn-loader {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
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
            gap: .75rem;
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: var(--error-50);
            border: 1px solid rgba(239, 68, 68, .2);
            color: var(--error);
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: .125rem;
        }

        .alert-close {
            margin-left: auto;
            padding: .25rem;
            background: none;
            border: none;
            color: inherit;
            opacity: .5;
            cursor: pointer;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .alert ul {
            margin: .25rem 0 0;
            padding-left: 1.25rem;
            font-size: .875rem;
        }

        .ts-control {
            border-color: var(--gray-200) !important;
            border-radius: var(--radius-md) !important;
            padding: .5rem .75rem !important;
            min-height: 46px;
        }

        .ts-control:focus-within {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-50) !important;
        }

        @media (max-width: 640px) {
            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn,
            .btn-light {
                width: 100%;
                justify-content: center;
            }
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #f8773c;
            border-color: #f8773c;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #e55a2b;
            border-color: #e55a2b;
        }

        .btn-primary.active,
        .btn-primary:active {
            background-color: #d94a1a;
            border-color: #d94a1a;
        }

        .bg-primary-subtle {
            background-color: #fef0e8;
        }

        .text-primary {
            color: #f8773c !important;
        }

        .border-primary {
            border-color: #f8773c !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        select.form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236c757d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 0.5;
        }

        [x-cloak] {
            display: none;
        }
    </style>
@endpush

@push('admin-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initTomSelect();
            initCodeGeneration();
            initFormSubmit();
            initDaysRangePreview();
        });

        function initTomSelect() {
            const el = document.getElementById('facilities');
            if (el && typeof TomSelect !== 'undefined') {
                new TomSelect(el, {
                    plugins: ['remove_button'],
                    maxItems: null,
                    hideSelected: true,
                    create: false
                });
            }
        }

        function initCodeGeneration() {
            const nameInput = document.getElementById('name');
            const typeSelect = document.getElementById('unit_type_id');
            const codeInput = document.getElementById('code');
            const regenerateBtn = document.getElementById('regenerateCode');
            if (!nameInput || !codeInput) return;

            const generate = () => {
                const name = nameInput.value.trim();
                const typeOption = typeSelect ? typeSelect.options[typeSelect.selectedIndex] : null;
                if (!name || (typeSelect && !typeSelect.value)) {
                    codeInput.value = '';
                    return;
                }
                const prefix = (typeOption && typeOption.dataset.code)
                    ? typeOption.dataset.code
                    : (typeOption ? typeOption.text.substring(0, 3).toUpperCase() : 'UNT');
                const words = name.split(/\s+/);
                const nameCode = words.length === 1
                    ? words[0].substring(0, 3).toUpperCase()
                    : words.map(w => w.charAt(0).toUpperCase()).join('').substring(0, 3);
                const num = Math.floor(Math.random() * 90 + 10);
                codeInput.value = `${prefix}-${nameCode}-${num}`;
            };

            nameInput.addEventListener('blur', generate);
            if (typeSelect) typeSelect.addEventListener('change', () => { if (nameInput.value.trim()) generate(); });
            if (regenerateBtn) regenerateBtn.addEventListener('click', generate);
        }

        function initFormSubmit() {
            const form = document.getElementById('unitForm');
            const btn = document.getElementById('submitBtn');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                let firstError = null;
                form.querySelectorAll('[required]').forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        if (!firstError) firstError = field;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (firstError) {
                    e.preventDefault();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                    return;
                }

                if (btn) {
                    btn.disabled = true;
                    const btnText = btn.querySelector('.btn-text');
                    const btnLoader = btn.querySelector('.btn-loader');
                    if (btnText) btnText.style.display = 'none';
                    if (btnLoader) btnLoader.style.display = 'inline-flex';
                }
            });

            form.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(el => {
                el.addEventListener('input', function () { this.classList.remove('is-invalid'); });
            });
        }

        function initDaysRangePreview() {
            const startSelect = document.getElementById('openDaysStart');
            const endSelect = document.getElementById('openDaysEnd');
            const previewSpan = document.getElementById('closedDaysPreview');

            if (!startSelect || !endSelect || !previewSpan) return;

            const daysMap = {
                'monday': 'Senin', 'tuesday': 'Selasa', 'wednesday': 'Rabu',
                'thursday': 'Kamis', 'friday': 'Jumat', 'saturday': 'Sabtu',
                'sunday': 'Minggu'
            };
            const daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            function updatePreview() {
                const start = startSelect.value;
                const end = endSelect.value;
                const startIndex = daysOrder.indexOf(start);
                const endIndex = daysOrder.indexOf(end);

                let openDays = [];
                if (startIndex <= endIndex) {
                    openDays = daysOrder.slice(startIndex, endIndex + 1);
                } else {
                    openDays = daysOrder.slice(startIndex).concat(daysOrder.slice(0, endIndex + 1));
                }

                const closedDays = daysOrder.filter(day => !openDays.includes(day));

                if (closedDays.length === 0) {
                    previewSpan.textContent = 'Tidak ada hari tutup';
                } else if (closedDays.length === 7) {
                    previewSpan.textContent = 'Setiap hari tutup';
                } else {
                    previewSpan.textContent = closedDays.map(d => daysMap[d]).join(', ');
                }
            }

            startSelect.addEventListener('change', updatePreview);
            endSelect.addEventListener('change', updatePreview);
            updatePreview();
        }
    </script>
@endpush