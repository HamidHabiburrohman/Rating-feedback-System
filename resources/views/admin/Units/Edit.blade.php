@extends('layouts.admin.app')

@section('title', 'Edit Unit')

@section('admin-content')

    @php
        $operationalHours = $unit->operational_hours ?? [];
    @endphp

    <div class="page-container">
        <header class="page-header">
            <div>
                <h1>Edit Unit</h1>
                <p class="subtitle">Perbarui informasi unit: <strong>{{ $unit->name }}</strong></p>
            </div>
            <a href="{{ route('admin.units.index') }}" class="btn-ghost">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </header>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
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

        <form action="{{ route('admin.units.update', $unit->id) }}" method="POST" id="unitForm" class="form-layout">
            @csrf
            @method('PUT')

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- SECTION 1: INFORMASI DASAR --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">1</span>
                    Informasi Dasar
                </h2>

                <div class="grid grid-cols-2">
                    {{-- Nama Unit --}}
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
                            <input type="text" id="name" name="name" value="{{ old('name', $unit->name) }}"
                                placeholder="Contoh: Laboratorium Komputer Dasar"
                                class="form-input @error('name') is-invalid @enderror" required>
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tipe Unit --}}
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
                                <option value="" disabled>Pilih tipe unit</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}" data-code="{{ $type->code_prefix ?? '' }}" {{ old('unit_type_id', $unit->unit_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('unit_type_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kode Unit --}}
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
                            <input type="text" id="code" name="code" value="{{ old('code', $unit->code) }}"
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
                        @error('code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Departemen --}}
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
                                <option value="">Pilih departemen (opsional)</option>
                                @foreach($unitDepartments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('unit_department_id', $unit->unit_department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('unit_department_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- SECTION 2: LOKASI --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">2</span>
                    Lokasi
                </h2>

                <div class="grid grid-cols-3">
                    {{-- Gedung --}}
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
                            <input type="text" id="building" name="building" value="{{ old('building', $unit->building) }}"
                                placeholder="Contoh: Gedung FIK" class="form-input @error('building') is-invalid @enderror">
                        </div>
                        @error('building')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Lantai --}}
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
                            <input type="text" id="floor" name="floor" value="{{ old('floor', $unit->floor) }}"
                                placeholder="Contoh: 2" class="form-input @error('floor') is-invalid @enderror">
                        </div>
                        @error('floor')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Detail Lokasi --}}
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
                            <input type="text" id="location" name="location" value="{{ old('location', $unit->location) }}"
                                placeholder="Contoh: Ruang 201" class="form-input @error('location') is-invalid @enderror">
                        </div>
                        @error('location')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- SECTION 3: OPERASIONAL --}}
            {{-- ═══════════════════════════════════════════════════ --}}
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
                                <select name="open_days_start" id="openDaysStart" class="form-select">
                                    @foreach($daysList as $value => $label)
                                        <option value="{{ $value }}" {{ old('open_days_start', $unit->open_days_start ?? 'monday') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-secondary px-1" style="font-size:18px;">→</span>
                                <select name="open_days_end" id="openDaysEnd" class="form-select">
                                    @foreach($daysList as $value => $label)
                                        <option value="{{ $value }}" {{ old('open_days_end', $unit->open_days_end ?? 'friday') == $value ? 'selected' : '' }}>
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
                                <input type="time" name="open_time"
                                    value="{{ old('open_time', $unit->open_time ? substr($unit->open_time, 0, 5) : '08:00') }}"
                                    class="form-control">
                                <span class="text-secondary px-1" style="font-size:18px;">→</span>
                                <input type="time" name="close_time"
                                    value="{{ old('close_time', $unit->close_time ? substr($unit->close_time, 0, 5) : '17:00') }}"
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
                                <input type="number" name="capacity" value="{{ old('capacity', $unit->capacity) }}"
                                    class="form-control" min="1" placeholder="40">
                            </div>
                            @error('capacity')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-8"
                            x-data="{ status: '{{ old('operational_status', $unit->operational_status ?? 'open') }}' }">
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

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- SECTION 4: KONTAK & DETAIL --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">4</span>
                    Kontak & Detail
                </h2>

                <div class="grid grid-cols-2">
                    {{-- Telepon --}}
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
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $unit->phone) }}"
                                placeholder="021-5550001" class="form-input @error('phone') is-invalid @enderror">
                        </div>
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
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
                            <input type="email" id="email" name="email" value="{{ old('email', $unit->email) }}"
                                placeholder="unit@kampus.ac.id" class="form-input @error('email') is-invalid @enderror">
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="form-group full-width" style="margin-top: 0.5rem;">
                    <label for="facilities" class="form-label">Fasilitas</label>
                    <select id="facilities" name="facilities[]" multiple
                        class="form-select no-icon @error('facilities') is-invalid @enderror"
                        data-placeholder="Cari dan pilih fasilitas...">
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ in_array($facility->id, old('facilities', $selectedFacilities ?? [])) ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="help-text">Ketik untuk mencari, klik untuk memilih. Bisa pilih lebih dari satu.</span>
                    @error('facilities')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group full-width" style="margin-top: 0.5rem;">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" placeholder="Deskripsikan unit ini..."
                        class="form-textarea @error('description') is-invalid @enderror">{{ old('description', $unit->description) }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </section>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- SECTION 5: GALLERY & FOTO --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">5</span>
                    Gallery & Foto
                </h2>

                <div class="gallery-container">
                    <div class="gallery-header">
                        <p class="help-text" style="margin:0;">
                            Foto pertama akan menjadi thumbnail utama. Hover foto untuk opsi pengaturan.
                        </p>
                        <button type="button" class="btn-add-photo" id="openUploadModalBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Tambah Foto
                        </button>
                    </div>

                    @php $photos = $unit->photos ?? collect(); @endphp

                    <div class="gallery-grid" id="galleryGrid">
                        @foreach($photos as $photo)
                            <div class="gallery-item" data-id="{{ $photo->id }}">
                                <div class="gallery-item-image">
                                    <img src="{{ $photo->thumbnail_url ?? $photo->url }}"
                                        alt="{{ $photo->alt_text ?? 'Unit Photo' }}">

                                    @if($photo->is_primary)
                                        <div class="primary-badge">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                            </svg>
                                            Utama
                                        </div>
                                    @endif

                                    <div class="gallery-overlay">
                                        <button type="button" class="overlay-btn set-primary-btn" data-id="{{ $photo->id }}"
                                            title="Jadikan foto utama">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                            </svg>
                                        </button>
                                        <button type="button" class="overlay-btn overlay-btn-danger delete-photo-btn"
                                            data-id="{{ $photo->id }}" title="Hapus foto">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6M14 11v6" />
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="gallery-item-meta">
                                    <span class="meta-name">{{ Str::limit($photo->file_name ?? 'Image', 24) }}</span>
                                    <span class="meta-size">{{ $photo->formatted_size ?? '' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($photos->isEmpty())
                        <div class="gallery-empty" id="galleryEmpty">
                            <div class="gallery-empty-icon">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                            </div>
                            <p>Belum ada foto. Klik <strong>Tambah Foto</strong> untuk mengunggah.</p>
                        </div>
                    @else
                        <div id="galleryEmpty" style="display:none;"></div>
                    @endif

                    <div class="gallery-hint">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Format JPG, JPEG, PNG — maks. 5 MB per file
                    </div>
                </div>
            </section>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- STATUS AKTIF TOGGLE --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="settings-section">
                <div class="settings-row">
                    <div class="settings-info">
                        <h3>Status Aktif</h3>
                        <p>Unit yang tidak aktif tidak akan terlihat oleh mahasiswa</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            {{-- FORM ACTIONS --}}
            <div class="form-actions">
                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-light" style="color: #ffff; background-color: #f8773c;" id="submitBtn">
                    <span class="btn-text">Simpan Perubahan</span>
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

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- UPLOAD MODAL — Modern Minimalist --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div id="uploadModal" class="modal-backdrop" style="display:none;" role="dialog" aria-modal="true">
        <div class="modal-panel">
            {{-- Header --}}
            <div class="modal-head">
                <span class="modal-title">Unggah Foto</span>
                <button type="button" class="modal-x" id="closeModalBtn" aria-label="Tutup">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <input type="file" id="photoInput" multiple accept="image/jpeg,image/png,image/jpg" style="display:none;">

                <div class="drop-zone" id="dropZone">
                    <div class="drop-zone-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <polyline points="16 16 12 12 8 16" />
                            <line x1="12" y1="12" x2="12" y2="21" />
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3" />
                        </svg>
                    </div>
                    <p class="drop-zone-text">Seret & lepas foto ke sini</p>
                    <p class="drop-zone-hint">atau</p>
                    <button type="button" class="btn-pick" id="selectFilesBtn">Pilih dari Perangkat</button>
                    <p class="drop-zone-hint" style="margin-top:.5rem;">JPG, JPEG, PNG · Maks. 5 MB</p>
                </div>

                <div class="preview-grid" id="previewGrid"></div>
            </div>

            {{-- Footer --}}
            <div class="modal-foot">
                <span class="preview-count" id="previewCount"></span>
                <div style="display:flex;gap:.625rem;">
                    <button type="button" class="btn-cancel" id="cancelUploadBtn">Batal</button>
                    <button type="button" class="btn-upload" id="uploadBtn">Unggah</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* ─── Variables ──────────────────────────────────────────── */
        :root {
            --primary: #f8773c;
            --primary-dark: #e55a2b;
            --primary-light: #f1c3ae;
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
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -2px rgba(0, 0, 0, .1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, .1), 0 4px 6px -4px rgba(0, 0, 0, .1);

            --transition: all .2s cubic-bezier(.4, 0, .2, 1);
        }

        /* ─── Layout ─────────────────────────────────────────────── */
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

        /* ─── Card ───────────────────────────────────────────────── */
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

        /* ─── Grid ───────────────────────────────────────────────── */
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

        /* ─── Form Group ─────────────────────────────────────────── */
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

        /* ─── Inputs ─────────────────────────────────────────────── */
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

        /* Add left-padding only when there's an icon in the wrapper */
        .input-wrapper .form-input,
        .input-wrapper .form-select {
            padding-left: 2.5rem;
        }

        /* Multiselect / select without icon */
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

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: var(--gray-400);
        }

        .form-input.is-invalid,
        .form-select.is-invalid,
        .form-textarea.is-invalid {
            border-color: var(--error);
            background-color: var(--error-50);
        }

        .form-input.is-invalid:focus,
        .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* ─── Input Wrapper + Icon ───────────────────────────────── */
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

        /* ─── Code Field ─────────────────────────────────────────── */
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
            font-family: 'SF Mono', 'Fira Code', monospace;
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
            text-transform: uppercase;
            letter-spacing: .025em;
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

        /* ─── Time Range ─────────────────────────────────────────── */
        .time-range-wrapper {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .time-range {
            display: flex;
            align-items: center;
            gap: .75rem;
            background: var(--gray-50);
            padding: .5rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .time-input {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .25rem;
        }

        .time-input input {
            width: 100%;
            text-align: center;
            padding: .5rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: .875rem;
            font-weight: 600;
            color: var(--gray-700);
            background: white;
            transition: var(--transition);
        }

        .time-input input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-50);
        }

        .time-label {
            font-size: .625rem;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 500;
        }

        .time-separator {
            color: var(--gray-400);
            font-size: 1.125rem;
            font-weight: 300;
        }

        .time-validation {
            font-size: .75rem;
            text-align: center;
        }

        /* ─── Status Options ─────────────────────────────────────── */
        .status-options {
            display: flex;
            gap: .75rem;
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
            width: 0;
            height: 0;
        }

        .status-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: .625rem 1.25rem;
            border-radius: var(--radius-full);
            font-size: .8125rem;
            font-weight: 500;
            border: 1px solid transparent;
            transition: var(--transition);
            text-align: center;
            white-space: nowrap;
            user-select: none;
        }

        .status-badge.open {
            background: var(--success-50);
            color: #059669;
            border-color: rgba(16, 185, 129, .2);
        }

        .status-badge.maintenance {
            background: var(--warning-50);
            color: #d97706;
            border-color: rgba(245, 158, 11, .2);
        }

        .status-badge.closed {
            background: var(--error-50);
            color: #dc2626;
            border-color: rgba(239, 68, 68, .2);
        }

        .status-option input:checked+.status-badge.open {
            background: var(--success);
            color: white;
            border-color: var(--success);
            box-shadow: 0 2px 8px rgba(16, 185, 129, .3);
        }

        .status-option input:checked+.status-badge.maintenance {
            background: var(--warning);
            color: white;
            border-color: var(--warning);
            box-shadow: 0 2px 8px rgba(245, 158, 11, .3);
        }

        .status-option input:checked+.status-badge.closed {
            background: var(--error);
            color: white;
            border-color: var(--error);
            box-shadow: 0 2px 8px rgba(239, 68, 68, .3);
        }

        .status-option:hover .status-badge {
            filter: brightness(.96);
        }

        /* ─── Gallery ────────────────────────────────────────────── */
        .gallery-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-add-photo {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 1rem;
            background: var(--primary-50);
            border: 1px solid var(--primary);
            border-radius: var(--radius-md);
            color: var(--primary);
            font-size: .875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-add-photo:hover {
            background: var(--primary);
            color: white;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: .875rem;
            margin-bottom: 1rem;
        }

        .gallery-item {
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .gallery-item:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .gallery-item-image {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
        }

        .gallery-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .primary-badge {
            position: absolute;
            top: .5rem;
            left: .5rem;
            background: var(--primary);
            color: white;
            padding: .2rem .5rem;
            border-radius: var(--radius-sm);
            font-size: .625rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .25rem;
            letter-spacing: .02em;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .625rem;
            opacity: 0;
            transition: var(--transition);
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .overlay-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--gray-700);
            transition: var(--transition);
            flex-shrink: 0;
        }

        .overlay-btn:hover {
            background: var(--primary);
            color: white;
        }

        .overlay-btn-danger:hover {
            background: var(--error);
            color: white;
        }

        .gallery-item-meta {
            padding: .5rem .625rem;
            display: flex;
            flex-direction: column;
            gap: .125rem;
        }

        .meta-name {
            font-size: .75rem;
            font-weight: 500;
            color: var(--gray-700);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .meta-size {
            font-size: .625rem;
            color: var(--gray-400);
        }

        .gallery-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            gap: .75rem;
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            border: 2px dashed var(--gray-300);
            color: var(--gray-500);
            text-align: center;
            margin-bottom: 1rem;
        }

        .gallery-empty-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-lg);
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
        }

        .gallery-empty p {
            font-size: .875rem;
            color: var(--gray-500);
            margin: 0;
        }

        .gallery-hint {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .625rem .875rem;
            background: var(--gray-50);
            border-radius: var(--radius-md);
            font-size: .75rem;
            color: var(--gray-500);
        }

        /* ─── Toggle Switch ──────────────────────────────────────── */
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

        /* ─── Buttons ────────────────────────────────────────────── */
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
            border: 1px solid transparent;
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

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
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

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        .btn-submit:disabled {
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

        /* ─── Alert ──────────────────────────────────────────────── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: var(--success-50);
            border: 1px solid rgba(16, 185, 129, .2);
            color: #059669;
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

        /* ─── TomSelect overrides ────────────────────────────────── */
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

        .ts-dropdown {
            border-radius: var(--radius-md) !important;
            border-color: var(--gray-200) !important;
            box-shadow: var(--shadow-lg) !important;
        }

        .ts-dropdown .active {
            background: var(--primary-50) !important;
            color: var(--primary) !important;
        }

        /* ─── Modal — Modern Minimalist ─────────────────────────── */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(10, 10, 10, .55);
            backdrop-filter: blur(3px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
        }

        .modal-panel {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 48px -8px rgba(0, 0, 0, .22);
            overflow: hidden;
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.125rem 1.375rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .modal-title {
            font-size: .9375rem;
            font-weight: 600;
            color: var(--gray-900);
        }

        .modal-x {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--gray-100);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--gray-500);
            transition: var(--transition);
        }

        .modal-x:hover {
            background: var(--gray-200);
            color: var(--gray-900);
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.375rem;
        }

        /* Drop zone */
        .drop-zone {
            border: 1.5px dashed var(--gray-300);
            border-radius: var(--radius-lg);
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: var(--gray-50);
        }

        .drop-zone:hover,
        .drop-zone.drag-over {
            border-color: var(--primary);
            background: var(--primary-50);
        }

        .drop-zone-icon {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-lg);
            background: white;
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .875rem;
            color: var(--gray-400);
            box-shadow: var(--shadow-sm);
        }

        .drop-zone:hover .drop-zone-icon,
        .drop-zone.drag-over .drop-zone-icon {
            border-color: var(--primary);
            color: var(--primary);
        }

        .drop-zone-text {
            font-size: .875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: .25rem;
        }

        .drop-zone-hint {
            font-size: .75rem;
            color: var(--gray-400);
            margin: .25rem 0;
        }

        .btn-pick {
            display: inline-flex;
            align-items: center;
            padding: .4375rem .875rem;
            margin-top: .375rem;
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            color: var(--gray-700);
            font-size: .8125rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-pick:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-50);
        }

        /* Preview thumbnails */
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(76px, 1fr));
            gap: .625rem;
            margin-top: 1rem;
        }

        .preview-thumb {
            position: relative;
            aspect-ratio: 1;
            border-radius: var(--radius-md);
            overflow: visible;
            border: 1px solid var(--gray-200);
        }

        .preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: calc(var(--radius-md) - 1px);
        }

        .preview-thumb-remove {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--error);
            color: white;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
            transition: var(--transition);
        }

        .preview-thumb-remove:hover {
            background: #c0392b;
            transform: scale(1.1);
        }

        /* Modal footer */
        .modal-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .875rem 1.375rem;
            border-top: 1px solid var(--gray-100);
        }

        .preview-count {
            font-size: .8125rem;
            color: var(--gray-500);
        }

        .btn-cancel {
            padding: .5rem 1rem;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            color: var(--gray-600);
            font-size: .875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .btn-upload {
            padding: .5rem 1.25rem;
            background: var(--primary);
            border: none;
            border-radius: var(--radius-md);
            color: white;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .btn-upload:hover {
            background: var(--primary-dark);
        }

        .btn-upload:disabled {
            opacity: .55;
            cursor: not-allowed;
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

        /* ─── Responsive ─────────────────────────────────────────── */
        @media (max-width: 640px) {
            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn,
            .btn-submit {
                width: 100%;
                justify-content: center;
            }

            .status-options {
                flex-direction: column;
            }

            .status-option {
                width: 100%;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            }
        }
    </style>
@endpush

@push('admin-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initializeTomSelect();
            initializeCodeGeneration();
            initializeTimeValidation();
            initializeFormSubmit();
            initializeGallery();
        });

        /* ── TomSelect ──────────────────────────────────────────────── */
        function initializeTomSelect() {
            const el = document.getElementById('facilities');
            if (!el || typeof TomSelect === 'undefined') return;

            new TomSelect(el, {
                plugins: ['remove_button'],
                maxItems: null,
                hideSelected: true,
                create: false,
                render: {
                    no_results: () => '<div style="padding:.5rem;color:#6b7280;font-size:.875rem;">Tidak ada fasilitas yang cocok</div>'
                }
            });
        }

        /* ── Code generation ────────────────────────────────────────── */
        function initializeCodeGeneration() {
            const nameInput = document.getElementById('name');
            const typeSelect = document.getElementById('unit_type_id');
            const codeInput = document.getElementById('code');
            const regenerateBtn = document.getElementById('regenerateCode');

            if (!nameInput || !codeInput) return;

            const generate = () => {
                const name = nameInput.value.trim();
                const typeOption = typeSelect ? typeSelect.options[typeSelect.selectedIndex] : null;

                if (!name || (typeSelect && !typeSelect.value)) { codeInput.value = ''; return; }

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

        /* ── Time validation ────────────────────────────────────────── */
        function initializeTimeValidation() {
            const openTime = document.getElementById('open_time');
            const closeTime = document.getElementById('close_time');
            const timeValidation = document.getElementById('timeValidation');

            if (!openTime || !closeTime) return;

            const validate = () => {
                const o = openTime.value, c = closeTime.value;
                if (!o || !c) { if (timeValidation) timeValidation.textContent = ''; return true; }

                if (o >= c) {
                    if (timeValidation) {
                        timeValidation.textContent = 'Jam tutup harus setelah jam buka';
                        timeValidation.style.color = '#ef4444';
                    }
                    closeTime.classList.add('is-invalid');
                    return false;
                }

                const dur = (new Date(`2000-01-01T${c}`) - new Date(`2000-01-01T${o}`)) / 3600000;
                if (timeValidation) {
                    timeValidation.textContent = `Durasi: ${dur} jam`;
                    timeValidation.style.color = '#10b981';
                }
                closeTime.classList.remove('is-invalid');
                return true;
            };

            openTime.addEventListener('change', validate);
            closeTime.addEventListener('change', validate);
        }

        /* ── Form submit ────────────────────────────────────────────── */
        function initializeFormSubmit() {
            const form = document.getElementById('unitForm');
            const btn = document.getElementById('submitBtn');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                const o = document.getElementById('open_time')?.value;
                const c = document.getElementById('close_time')?.value;
                if (o && c && o >= c) { e.preventDefault(); document.getElementById('close_time').focus(); return; }

                let firstError = null;
                form.querySelectorAll('[required]').forEach(field => {
                    if (!field.value.trim()) { field.classList.add('is-invalid'); if (!firstError) firstError = field; }
                    else field.classList.remove('is-invalid');
                });

                if (firstError) {
                    e.preventDefault();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
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

            form.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(el => {
                el.addEventListener('input', function () { this.classList.remove('is-invalid'); });
            });
        }

        /* ── Gallery / Photo management ─────────────────────────────── */
        function initializeGallery() {
            const unitId = {{ $unit->id }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            const modal = document.getElementById('uploadModal');
            const dropZone = document.getElementById('dropZone');
            const photoInput = document.getElementById('photoInput');
            const selectBtn = document.getElementById('selectFilesBtn');
            const openModalBtn = document.getElementById('openUploadModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelUploadBtn');
            const uploadBtn = document.getElementById('uploadBtn');
            const previewGrid = document.getElementById('previewGrid');
            const previewCount = document.getElementById('previewCount');
            const galleryGrid = document.getElementById('galleryGrid');
            const galleryEmpty = document.getElementById('galleryEmpty');

            let selectedFiles = [];

            /* Modal helpers */
            const openModal = () => { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; };
            const closeModal = () => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
                selectedFiles = [];
                if (photoInput) photoInput.value = '';
                if (previewGrid) previewGrid.innerHTML = '';
                updateCount();
            };

            openModalBtn?.addEventListener('click', openModal);
            closeModalBtn?.addEventListener('click', closeModal);
            cancelBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', e => { if (e.target === modal) closeModal(); });

            /* Drop zone */
            dropZone?.addEventListener('click', () => photoInput?.click());

            dropZone?.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });

            dropZone?.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));

            dropZone?.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                if (files.length) addFiles(files);
            });

            selectBtn?.addEventListener('click', e => { e.stopPropagation(); photoInput?.click(); });

            photoInput?.addEventListener('change', e => {
                addFiles(Array.from(e.target.files));
            });

            /* File management */
            function updateCount() {
                if (previewCount) {
                    previewCount.textContent = selectedFiles.length
                        ? `${selectedFiles.length} foto dipilih`
                        : '';
                }
            }

            function addFiles(files) {
                files.forEach(file => {
                    if (file.size > 5 * 1024 * 1024) { alert(`"${file.name}" melebihi 5 MB`); return; }
                    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                        alert(`"${file.name}" bukan format yang didukung`);
                        return;
                    }
                    if (selectedFiles.find(f => f.name === file.name && f.size === file.size)) return;

                    selectedFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = evt => {
                        const thumb = document.createElement('div');
                        thumb.className = 'preview-thumb';
                        thumb.innerHTML = `
                                    <img src="${evt.target.result}" alt="${file.name}">
                                    <button type="button" class="preview-thumb-remove" title="Hapus">×</button>
                                `;
                        thumb.querySelector('.preview-thumb-remove').addEventListener('click', () => {
                            const idx = selectedFiles.findIndex(f => f.name === file.name && f.size === file.size);
                            if (idx > -1) selectedFiles.splice(idx, 1);
                            thumb.remove();
                            updateCount();
                        });
                        previewGrid.appendChild(thumb);
                        updateCount();
                    };
                    reader.readAsDataURL(file);
                });

                if (photoInput) photoInput.value = '';
            }

            /* Upload */
            uploadBtn?.addEventListener('click', async () => {
                if (!selectedFiles.length) { alert('Pilih foto terlebih dahulu'); return; }

                uploadBtn.disabled = true;
                const orig = uploadBtn.innerHTML;
                uploadBtn.innerHTML = `
                            <svg class="spinner" width="15" height="15" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"
                                    stroke-dasharray="60" stroke-dashoffset="20"/>
                            </svg>
                            Mengunggah...
                        `;

                const formData = new FormData();
                selectedFiles.forEach(f => formData.append('photos[]', f));

                try {
                    const res = await fetch(`/admin/units/${unitId}/photos/upload`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const result = await res.json();

                    if (res.ok && result.success) {
                        closeModal();
                        location.reload();
                    } else {
                        const msg = result.errors
                            ? Object.values(result.errors).flat().join('\n')
                            : (result.message || 'Gagal mengunggah foto');
                        alert(msg);
                        uploadBtn.disabled = false;
                        uploadBtn.innerHTML = orig;
                    }
                } catch (err) {
                    console.error('Upload error:', err);
                    alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = orig;
                }
            });

            /* Set primary */
            document.querySelectorAll('.set-primary-btn').forEach(btn => {
                btn.addEventListener('click', async e => {
                    e.preventDefault();
                    const photoId = btn.dataset.id;
                    if (!confirm('Jadikan foto ini sebagai foto utama?')) return;

                    const orig = btn.innerHTML;
                    btn.innerHTML = `<svg class="spinner" width="13" height="13" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="60" stroke-dashoffset="20"/></svg>`;

                    try {
                        const res = await fetch(`/admin/units/${unitId}/photos/${photoId}/primary`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const result = await res.json();

                        if (res.ok && result.success) { location.reload(); }
                        else { alert(result.message || 'Gagal mengatur foto utama'); btn.innerHTML = orig; }
                    } catch (err) {
                        console.error(err);
                        alert('Terjadi kesalahan');
                        btn.innerHTML = orig;
                    }
                });
            });

            /* Delete photo */
            document.querySelectorAll('.delete-photo-btn').forEach(btn => {
                btn.addEventListener('click', async e => {
                    e.preventDefault();
                    const photoId = btn.dataset.id;
                    if (!confirm('Hapus foto ini?')) return;

                    const item = btn.closest('.gallery-item');
                    if (item) item.style.opacity = '.4';

                    try {
                        const res = await fetch(`/admin/units/${unitId}/photos/${photoId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const result = await res.json();

                        if (res.ok && result.success) {
                            item?.remove();
                            if (galleryGrid && galleryEmpty) {
                                const remaining = galleryGrid.querySelectorAll('.gallery-item').length;
                                if (remaining === 0) {
                                    galleryEmpty.style.display = 'flex';
                                }
                            }
                        } else {
                            alert(result.message || 'Gagal menghapus foto');
                            if (item) item.style.opacity = '';
                        }
                    } catch (err) {
                        console.error(err);
                        alert('Terjadi kesalahan');
                        if (item) item.style.opacity = '';
                    }
                });
            });
        }
    </script>
@endpush