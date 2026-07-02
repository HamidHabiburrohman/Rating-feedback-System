@extends('layouts.admin.app')
@section('title', 'Edit Unit')
@section('admin-content')
 <div class="page-container">
     <header class="page-header">
         <div>
             <h1 class="page-title">Edit Unit</h1>
             <p class="page-subtitle">Perbarui informasi unit: <strong>{{ $unit->name }}</strong></p>
         </div>
         <a href="{{ route('admin.units.index') }}" class="btn-ghost">
             <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M19 12H5M12 19l-7-7 7-7" />
             </svg>
             Kembali
         </a>
     </header>

    @if (session('success'))
         <div class="alert alert-success" role="alert">
             <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

    @if ($errors->any())
         <div class="alert alert-error" role="alert">
             <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                     <path d="M18 6L6 18M6 6l12 12" />
                 </svg>
             </button>
         </div>
    @endif

     <form action="{{ route('admin.units.update', $unit->id) }}" method="POST" id="unitForm" class="form-layout">
        @csrf
        @method('PUT')

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
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                 <circle cx="12" cy="7" r="4" />
                             </svg>
                         </span>
                         <input type="text" id="name" name="name" value="{{ old('name', $unit->name) }}" placeholder="Contoh: Laboratorium Komputer Dasar" class="form-input @error('name') is-invalid @enderror" required>
                     </div>
                     @error('name') <span class="error-message">{{ $message }}</span> @enderror
                 </div>

                 <div class="form-group">
                     <label class="form-label">Tipe Unit <span class="required">*</span></label>
                     <div class="custom-select-wrapper @error('unit_type_id') is-invalid @enderror">
                         <input type="hidden" name="unit_type_id" id="unit_type_id" value="{{ old('unit_type_id', $unit->unit_type_id) }}" required>
                         <button type="button" class="custom-select-trigger">
                             <span class="selected-icon">
                                 <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                     <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                     <line x1="7" y1="7" x2="7.01" y2="7" />
                                 </svg>
                             </span>
                             <span class="selected-text">Pilih tipe unit</span>
                             <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M6 9l6 6 6-6" />
                             </svg>
                         </button>
                         <div class="custom-select-dropdown">
                            @foreach ($unitTypes as $type)
                                 <div class="custom-select-option {{ old('unit_type_id', $unit->unit_type_id) == $type->id ? 'is-selected' : '' }}" data-value="{{ $type->id }}" data-text="{{ $type->name }}" data-code="{{ $type->code_prefix ?? '' }}">
                                     <span class="option-text">{{ $type->name }}</span>
                                 </div>
                            @endforeach
                         </div>
                     </div>
                     @error('unit_type_id') <span class="error-message">{{ $message }}</span> @enderror
                 </div>

                 <div class="form-group">
                     <label for="code" class="form-label">Kode Unit <span class="badge-auto">Auto</span></label>
                     <div class="code-wrapper">
                         <span class="input-icon code-icon">
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <polyline points="16 18 22 12 16 6" />
                                 <polyline points="8 6 2 12 8 18" />
                             </svg>
                         </span>
                         <input type="text" id="code" name="code" value="{{ old('code', $unit->code) }}" class="form-input code-input @error('code') is-invalid @enderror" readonly required>
                         <button type="button" class="btn-icon" id="regenerateCode" title="Generate ulang">
                             <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                             </svg>
                         </button>
                     </div>
                     <span class="help-text">Format: TIPE-INISIAL-NOMOR (contoh: LAB-KOM-42)</span>
                     @error('code') <span class="error-message">{{ $message }}</span> @enderror
                 </div>

                 <div class="form-group">
                     <label class="form-label">Departemen</label>
                     <div class="custom-select-wrapper @error('unit_department_id') is-invalid @enderror">
                         <input type="hidden" name="unit_department_id" id="unit_department_id" value="{{ old('unit_department_id', $unit->unit_department_id) }}">
                         <button type="button" class="custom-select-trigger">
                             <span class="selected-icon">
                                 <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                     <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                     <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                 </svg>
                             </span>
                             <span class="selected-text">Pilih departemen (opsional)</span>
                             <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M6 9l6 6 6-6" />
                             </svg>
                         </button>
                         <div class="custom-select-dropdown">
                             <div class="custom-select-option" data-value="" data-text="Pilih departemen (opsional)">
                                 <span class="option-text">Pilih departemen (opsional)</span>
                             </div>
                            @foreach ($unitDepartments as $dept)
                                 <div class="custom-select-option {{ old('unit_department_id', $unit->unit_department_id) == $dept->id ? 'is-selected' : '' }}" data-value="{{ $dept->id }}" data-text="{{ $dept->name }}">
                                     <span class="option-text">{{ $dept->name }}</span>
                                 </div>
                            @endforeach
                         </div>
                     </div>
                     @error('unit_department_id') <span class="error-message">{{ $message }}</span> @enderror
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
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                 <polyline points="9 22 9 12 15 12 15 22" />
                             </svg>
                         </span>
                         <input type="text" id="building" name="building" value="{{ old('building', $unit->building) }}" placeholder="Contoh: Gedung FIK" class="form-input @error('building') is-invalid @enderror">
                     </div>
                     @error('building') <span class="error-message">{{ $message }}</span> @enderror
                 </div>

                 <div class="form-group">
                     <label for="floor" class="form-label">Lantai</label>
                     <div class="input-wrapper">
                         <span class="input-icon">
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                 <polyline points="2 17 12 22 22 17" />
                                 <polyline points="2 12 12 17 22 12" />
                             </svg>
                         </span>
                         <input type="text" id="floor" name="floor" value="{{ old('floor', $unit->floor) }}" placeholder="Contoh: 2" class="form-input @error('floor') is-invalid @enderror">
                     </div>
                     @error('floor') <span class="error-message">{{ $message }}</span> @enderror
                 </div>

                 <div class="form-group">
                     <label for="location" class="form-label">Detail Lokasi <span class="required">*</span></label>
                     <div class="input-wrapper">
                         <span class="input-icon">
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                 <circle cx="12" cy="10" r="3" />
                             </svg>
                         </span>
                         <input type="text" id="location" name="location" value="{{ old('location', $unit->location) }}" placeholder="Contoh: Ruang 201" class="form-input @error('location') is-invalid @enderror" required>
                     </div>
                     @error('location') <span class="error-message">{{ $message }}</span> @enderror
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
                    'monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu',
                    'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu',
                ];
                $openTimeValue = old('open_time', $unit->open_time ? \Carbon\Carbon::parse($unit->open_time)->format('H:i') : '08:00');
                $closeTimeValue = old('close_time', $unit->close_time ? \Carbon\Carbon::parse($unit->close_time)->format('H:i') : '17:00');
            @endphp

             <div class="grid grid-cols-2 mb-4">
                 <div class="form-group">
                     <label class="form-label">Hari Operasional</label>
                     <div class="days-range-picker">
                         <div class="custom-select-wrapper compact">
                             <input type="hidden" name="open_days_start" id="openDaysStart" value="{{ old('open_days_start', $unit->open_days_start ?? 'monday') }}">
                             <button type="button" class="custom-select-trigger">
                                 <span class="selected-text">Senin</span>
                                 <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6" /></svg>
                             </button>
                             <div class="custom-select-dropdown">
                                @foreach ($daysList as $val => $label)
                                     <div class="custom-select-option {{ old('open_days_start', $unit->open_days_start ?? 'monday') == $val ? 'is-selected' : '' }}" data-value="{{ $val }}" data-text="{{ $label }}">
                                         <span class="option-text">{{ $label }}</span>
                                     </div>
                                @endforeach
                             </div>
                         </div>
                         <span class="range-separator">
                             <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
                         </span>
                         <div class="custom-select-wrapper compact">
                             <input type="hidden" name="open_days_end" id="openDaysEnd" value="{{ old('open_days_end', $unit->open_days_end ?? 'friday') }}">
                             <button type="button" class="custom-select-trigger">
                                 <span class="selected-text">Jumat</span>
                                 <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6" /></svg>
                             </button>
                             <div class="custom-select-dropdown">
                                @foreach ($daysList as $val => $label)
                                     <div class="custom-select-option {{ old('open_days_end', $unit->open_days_end ?? 'friday') == $val ? 'is-selected' : '' }}" data-value="{{ $val }}" data-text="{{ $label }}">
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
                         <input type="hidden" name="open_time" id="open_time" value="{{ $openTimeValue }}">
                         <button type="button" class="time-input-trigger" id="open_time_trigger" data-target="open_time">
                             <span class="time-input-icon">
                                 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                             </span>
                             <span class="time-input-value" id="open_time_display">{{ $openTimeValue }}</span>
                         </button>
                         <span class="range-separator">
                             <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
                         </span>
                         <input type="hidden" name="close_time" id="close_time" value="{{ $closeTimeValue }}">
                         <button type="button" class="time-input-trigger" id="close_time_trigger" data-target="close_time">
                             <span class="time-input-icon">
                                 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                             </span>
                             <span class="time-input-value" id="close_time_display">{{ $closeTimeValue }}</span>
                         </button>
                     </div>
                     <span class="help-text" id="timeValidation"></span>
                 </div>
             </div>

             <div class="grid grid-cols-2">
                 <div class="form-group">
                     <label for="capacity" class="form-label">Kapasitas Maksimum</label>
                     <div class="input-wrapper">
                         <span class="input-icon">
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                             </svg>
                         </span>
                         <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $unit->capacity) }}" class="form-input" min="1" placeholder="40">
                     </div>
                     @error('capacity') <span class="error-message">{{ $message }}</span> @enderror
                 </div>

                 <div class="form-group">
                     <label class="form-label">Status Operasional</label>
                     <div class="status-selector">
                        @foreach (['open' => 'Buka', 'maintenance' => 'Perawatan', 'closed' => 'Tutup'] as $val => $label)
                             <label class="status-option">
                                 <input type="radio" name="operational_status" value="{{ $val }}" {{ old('operational_status', $unit->operational_status ?? 'open') == $val ? 'checked' : '' }}>
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
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.86a16 16 0 0 0 6.22 6.22l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.04z" />
                             </svg>
                         </span>
                         <input type="tel" id="phone" name="phone" value="{{ old('phone', $unit->phone) }}" placeholder="021-5550001" class="form-input @error('phone') is-invalid @enderror">
                     </div>
                     @error('phone') <span class="error-message">{{ $message }}</span> @enderror
                 </div>

                 <div class="form-group">
                     <label for="email" class="form-label">Email</label>
                     <div class="input-wrapper">
                         <span class="input-icon">
                             <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" />
                             </svg>
                         </span>
                         <input type="email" id="email" name="email" value="{{ old('email', $unit->email) }}" placeholder="unit@kampus.ac.id" class="form-input @error('email') is-invalid @enderror">
                     </div>
                     @error('email') <span class="error-message">{{ $message }}</span> @enderror
                 </div>
             </div>

             <div class="form-group full-width" style="margin-top: 1.5rem;">
                 <label for="description" class="form-label">Deskripsi</label>
                 <textarea id="description" name="description" rows="4" placeholder="Deskripsikan unit ini..." class="form-textarea @error('description') is-invalid @enderror">{{ old('description', $unit->description) }}</textarea>
                 @error('description') <span class="error-message">{{ $message }}</span> @enderror
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
                         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                                 <img src="{{ asset('storage/' . $photo->thumbnail_path) }}" alt="{{ $photo->alt_text ?? 'Unit Photo' }}">

                                @if($photo->is_primary)
                                     <div class="primary-badge">
                                         <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                             <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                         </svg>
                                         Utama
                                     </div>
                                @endif

                                 <div class="gallery-overlay">
                                     <button type="button" class="overlay-btn set-primary-btn" data-id="{{ $photo->id }}" title="Jadikan foto utama">
                                         <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                             <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                         </svg>
                                     </button>
                                     <button type="button" class="overlay-btn overlay-btn-danger delete-photo-btn" data-id="{{ $photo->id }}" title="Hapus foto">
                                         <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                                 <span class="meta-size">{{ $photo->file_size ? number_format($photo->file_size / 1024, 1) . ' KB' : '' }}</span>
                             </div>
                         </div>
                    @endforeach
                 </div>

                @if($photos->isEmpty())
                     <div class="gallery-empty" id="galleryEmpty">
                         <div class="gallery-empty-icon">
                             <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
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

         <div class="form-actions">
             <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Batal</a>
             <button type="submit" class="btn btn-primary" id="submitBtn">
                 <span class="btn-loader">
                     <svg class="spinner" width="16" height="16" viewBox="0 0 24 24">
                         <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="60" stroke-dashoffset="20" />
                     </svg>
                     Menyimpan...
                 </span>
                 <span class="btn-text">Simpan Perubahan</span>
             </button>
         </div>
     </form>

     <div class="time-picker-overlay" id="timePickerOverlay">
         <div class="time-picker-modal">
             <div class="time-picker-header">
                 <h3 class="time-picker-title">Atur Jam</h3>
                 <button type="button" class="time-picker-close" id="timePickerClose">
                     <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                         <path d="M18 6L6 18M6 6l12 12" />
                     </svg>
                 </button>
             </div>
             <div class="time-picker-display">
                 <span class="time-picker-display-value" id="timePickerPreview">08:00</span>
             </div>
             <div class="time-picker-wheels">
                 <div class="time-wheel-col">
                     <div class="time-wheel" id="hourWheel" data-type="hour"></div>
                 </div>
                 <div class="time-wheel-separator">:</div>
                 <div class="time-wheel-col">
                     <div class="time-wheel" id="minuteWheel" data-type="minute"></div>
                 </div>
                 <div class="time-wheel-highlight"></div>
             </div>
             <div class="time-picker-actions">
                 <button type="button" class="btn-time-cancel" id="timePickerCancel">Batal</button>
                 <button type="button" class="btn-time-confirm" id="timePickerConfirm">Pilih Jam</button>
             </div>
         </div>
     </div>
 </div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- UPLOAD MODAL — Modern Minimalist --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="uploadModal" class="modal-backdrop" style="display:none;" role="dialog" aria-modal="true">
    <div class="modal-panel">
        <div class="modal-head">
            <span class="modal-title">Unggah Foto</span>
            <button type="button" class="modal-x" id="closeModalBtn" aria-label="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <input type="file" id="photoInput" multiple accept="image/jpeg,image/png,image/jpg" style="display:none;">

            <div class="drop-zone" id="dropZone">
                <div class="drop-zone-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
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
    --color-success-50: #d1fae5;
    --radius-card: 24px;
    --radius-input: 12px;
    --radius-pill: 9999px;
    --shadow-resting: 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-dropdown: 0 12px 32px rgba(15, 23, 42, 0.12), 0 4px 8px rgba(15, 23, 42, 0.04);
    --shadow-primary: 0 4px 14px rgba(248, 119, 60, 0.25);
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --ease-out: cubic-bezier(0.16, 1, 0.3, 1);

    /* Additional variables for Gallery & Modal */
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-900: #111827;
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-xl: 18px;
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, .05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -2px rgba(0, 0, 0, .1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, .1), 0 4px 6px -4px rgba(0, 0, 0, .1);
}

.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; }
.page-title { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; margin: 0 0 0.25rem 0; }
.page-subtitle { color: var(--color-text-secondary); font-size: 0.875rem; font-weight: 400; margin: 0; }
.form-layout { display: flex; flex-direction: column; gap: 1.5rem; }
.form-card { background: var(--color-surface); border-radius: var(--radius-card); padding: 2rem; box-shadow: var(--shadow-resting); border: 1px solid var(--color-border); }
.section-title { display: flex; align-items: center; gap: 0.75rem; font-size: 1rem; font-weight: 600; margin: 0 0 1.5rem 0; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border); }
.section-number { display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: var(--color-primary-50); color: var(--color-primary); font-size: 0.75rem; font-weight: 700; border-radius: var(--radius-pill); }
.grid { display: grid; gap: 1.5rem; }
.grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
.grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
@media (max-width: 768px) { .grid-cols-2, .grid-cols-3 { grid-template-columns: 1fr; } }
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-group.full-width { grid-column: 1 / -1; }
.form-label { font-size: 0.875rem; font-weight: 500; color: var(--color-text-primary); display: flex; align-items: center; gap: 0.5rem; }
.required { color: var(--color-error); }
.help-text { font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 0.25rem; }
.error-message { font-size: 0.75rem; color: var(--color-error); margin-top: 0.25rem; }
.input-wrapper { position: relative; display: flex; align-items: center; }
.form-input, .form-textarea { width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border: 1px solid var(--color-border); border-radius: var(--radius-input); font-size: 0.875rem; font-family: inherit; color: var(--color-text-primary); background: var(--color-surface); transition: var(--transition); }
.form-textarea { padding-left: 1rem; resize: vertical; min-height: 120px; }
.form-input:focus, .form-textarea:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1); }
.input-icon { position: absolute; left: 0.875rem; color: var(--color-text-secondary); pointer-events: none; display: flex; align-items: center; }
.form-input.is-invalid, .form-textarea.is-invalid { border-color: var(--color-error); background-color: var(--color-error-50); }
.badge-auto { font-size: 0.625rem; padding: 0.125rem 0.5rem; background: var(--color-primary-50); color: var(--color-primary); border-radius: var(--radius-pill); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.code-wrapper { position: relative; display: flex; align-items: center; gap: 0.5rem; }
.code-icon { position: absolute; left: 0.875rem; color: var(--color-text-secondary); pointer-events: none; z-index: 1; }
.code-input { flex: 1; font-family: 'SF Mono', 'Menlo', monospace; font-size: 0.8rem; letter-spacing: 0.05em; background: var(--color-bg); color: var(--color-text-secondary); padding-left: 2.75rem; }
.btn-icon { flex-shrink: 0; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-input); background: var(--color-surface); color: var(--color-text-secondary); cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; }
.btn-icon:hover { border-color: var(--color-primary); color: var(--color-primary); background: var(--color-primary-50); }
.custom-select-wrapper { position: relative; width: 100%; }
.custom-select-trigger { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-input); background: var(--color-surface); display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; font-family: inherit; color: var(--color-text-primary); cursor: pointer; transition: var(--transition); text-align: left; }
.custom-select-trigger:hover { border-color: #cbd5e1; }
.custom-select-wrapper.open .custom-select-trigger, .custom-select-trigger:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1); }
.custom-select-wrapper.is-invalid .custom-select-trigger { border-color: var(--color-error); background-color: var(--color-error-50); }
.selected-icon { display: flex; align-items: center; justify-content: center; width: 20px; height: 20px; color: var(--color-text-secondary); flex-shrink: 0; }
.selected-text { flex: 1; color: var(--color-text-secondary); }
.custom-select-wrapper.open .selected-text { color: var(--color-text-primary); }
.chevron { color: var(--color-text-secondary); transition: transform 0.3s var(--ease-out); flex-shrink: 0; }
.custom-select-wrapper.open .chevron { transform: rotate(180deg); color: var(--color-primary); }
.custom-select-dropdown { position: absolute; top: calc(100% + 8px); left: 0; right: 0; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: 20px; box-shadow: var(--shadow-dropdown); max-height: 240px; overflow-y: auto; z-index: 50; opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96); transition: all 0.25s var(--ease-out); padding: 8px; }
.custom-select-wrapper.open .custom-select-dropdown { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
.custom-select-option { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 1rem; cursor: pointer; transition: background 0.15s ease; border-radius: 12px; font-size: 0.875rem; font-weight: 500; color: var(--color-text-primary); }
.custom-select-option:hover { background: var(--color-bg); }
.custom-select-option.is-selected { background: var(--color-primary-50); color: var(--color-primary); }
.days-range-picker, .time-range-picker { display: flex; align-items: center; gap: 0.5rem; }
.days-range-picker .custom-select-wrapper, .time-range-picker .time-input-trigger { flex: 1; }
.range-separator { color: var(--color-text-secondary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.time-input-trigger { position: relative; display: flex; align-items: center; gap: 0.625rem; width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-input); font-size: 0.875rem; font-family: inherit; font-weight: 500; color: var(--color-text-primary); background: var(--color-surface); cursor: pointer; transition: var(--transition); }
.time-input-trigger:hover { border-color: var(--color-primary); background: var(--color-primary-50); }
.time-input-trigger.is-open { border-color: var(--color-primary); box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1); }
.time-input-icon { display: flex; align-items: center; color: var(--color-text-secondary); }
.time-input-value { letter-spacing: 0.02em; }
.time-picker-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: opacity 0.25s var(--ease-out), visibility 0.25s var(--ease-out); z-index: 1000; }
.time-picker-overlay.is-active { opacity: 1; visibility: visible; }
.time-picker-modal { width: 320px; background: var(--color-surface); border-radius: var(--radius-card); box-shadow: var(--shadow-dropdown); padding: 1.5rem; transform: translateY(16px) scale(0.97); opacity: 0; transition: transform 0.25s var(--ease-out), opacity 0.25s var(--ease-out); }
.time-picker-overlay.is-active .time-picker-modal { transform: translateY(0) scale(1); opacity: 1; }
.time-picker-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.time-picker-title { font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin: 0; }
.time-picker-close { display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border: none; border-radius: 50%; background: var(--color-bg); color: var(--color-text-secondary); cursor: pointer; transition: var(--transition); }
.time-picker-close:hover { background: var(--color-error-50); color: var(--color-error); }
.time-picker-display { display: flex; justify-content: center; margin-bottom: 1.25rem; }
.time-picker-display-value { font-size: 1.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--color-primary); }
.time-picker-wheels { position: relative; display: flex; align-items: center; justify-content: center; gap: 0.5rem; height: 200px; margin-bottom: 1.5rem; }
.time-wheel-highlight { position: absolute; left: 0; right: 0; top: 50%; height: 44px; transform: translateY(-50%); background: var(--color-primary-50); border-top: 1px solid rgba(248, 119, 60, 0.25); border-bottom: 1px solid rgba(248, 119, 60, 0.25); border-radius: 10px; pointer-events: none; z-index: 0; }
.time-wheel-col { position: relative; width: 90px; height: 100%; overflow: hidden; z-index: 1; }
.time-wheel-col::before, .time-wheel-col::after { content: ''; position: absolute; left: 0; right: 0; height: 78px; pointer-events: none; z-index: 2; }
.time-wheel-col::before { top: 0; background: linear-gradient(to bottom, var(--color-surface) 10%, transparent 100%); }
.time-wheel-col::after { bottom: 0; background: linear-gradient(to top, var(--color-surface) 10%, transparent 100%); }
.time-wheel { height: 100%; overflow-y: scroll; scroll-snap-type: y mandatory; scrollbar-width: none; padding: 78px 0; cursor: grab; }
.time-wheel:active { cursor: grabbing; }
.time-wheel::-webkit-scrollbar { display: none; }
.time-wheel-item { display: flex; align-items: center; justify-content: center; height: 44px; scroll-snap-align: center; font-size: 1.125rem; font-weight: 600; color: var(--color-text-secondary); opacity: 0.35; transition: opacity 0.15s, color 0.15s, font-size 0.15s; user-select: none; }
.time-wheel-item.is-active { color: var(--color-primary); opacity: 1; font-size: 1.375rem; }
.time-wheel-separator { font-size: 1.5rem; font-weight: 700; color: var(--color-text-secondary); z-index: 1; }
.time-picker-actions { display: flex; gap: 0.625rem; }
.btn-time-cancel, .btn-time-confirm { flex: 1; padding: 0.75rem 1rem; border-radius: var(--radius-input); font-size: 0.875rem; font-weight: 600; font-family: inherit; cursor: pointer; transition: var(--transition); border: none; }
.btn-time-cancel { background: var(--color-bg); color: var(--color-text-secondary); }
.btn-time-cancel:hover { background: var(--color-border); }
.btn-time-confirm { background: var(--color-primary); color: #fff; box-shadow: var(--shadow-primary); }
.btn-time-confirm:hover { background: var(--color-primary-dark); }
.status-selector { display: flex; gap: 0.5rem; padding: 0.5rem; background: var(--color-bg); border: 1px solid var(--color-border); border-radius: var(--radius-card); }
.status-option { flex: 1; cursor: pointer; }
.status-option input { position: absolute; opacity: 0; width: 0; height: 0; }
.status-label { display: flex; align-items: center; justify-content: center; padding: 0.5rem 0.75rem; border-radius: var(--radius-pill); font-size: 0.8125rem; font-weight: 500; color: var(--color-text-secondary); transition: var(--transition); background: var(--color-surface); border: 1px solid transparent; }
.status-option input:checked+.status-label { background: var(--color-primary); color: white; border-color: var(--color-primary); box-shadow: 0 2px 8px rgba(248, 119, 60, 0.3); }
.status-option:hover .status-label { border-color: #cbd5e1; }
.settings-section { background: var(--color-surface); border-radius: var(--radius-card); padding: 1.5rem 2rem; box-shadow: var(--shadow-resting); border: 1px solid var(--color-border); }
.settings-row { display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; }
.settings-info h3 { font-size: 0.9375rem; font-weight: 600; color: var(--color-text-primary); margin: 0 0 0.25rem 0; }
.settings-info p { font-size: 0.8125rem; color: var(--color-text-secondary); margin: 0; }
.toggle-switch { position: relative; width: 48px; height: 26px; cursor: pointer; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; inset: 0; background: #cbd5e1; border-radius: var(--radius-pill); transition: var(--transition); }
.toggle-slider:before { content: ''; position: absolute; height: 20px; width: 20px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: var(--transition); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
.toggle-switch input:checked+.toggle-slider { background: var(--color-primary); }
.toggle-switch input:checked+.toggle-slider:before { transform: translateX(22px); }
.btn-ghost { background: transparent; color: var(--color-text-secondary); padding: 0.5rem 0.75rem; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; border-radius: var(--radius-input); border: 1px solid transparent; cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-ghost:hover { background: #f1f5f9; color: var(--color-text-primary); }
.form-actions { display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 0.5rem; }
.btn { padding: 0.75rem 1.75rem; border-radius: var(--radius-pill); font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: var(--transition); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border: 1px solid transparent; text-decoration: none; font-family: inherit; }
.btn-secondary { background: var(--color-surface); color: var(--color-text-primary); border-color: var(--color-border); }
.btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
.btn-primary { background: var(--color-primary); color: white; box-shadow: var(--shadow-primary); }
.btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); }
.btn-primary:disabled { opacity: 0.8; cursor: not-allowed; transform: none; }
.btn-loader { display: none; align-items: center; gap: 0.5rem; }
.btn.loading .btn-text { display: none; }
.btn.loading .btn-loader { display: inline-flex; }
.spinner { animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.alert { display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: var(--radius-input); margin-bottom: 1.5rem; }
.alert-success { background: var(--color-success-50); border: 1px solid rgba(16, 185, 129, 0.2); color: #059669; }
.alert-error { background: var(--color-error-50); border: 1px solid rgba(239, 68, 68, 0.2); color: #b91c1c; }
.alert-icon { flex-shrink: 0; margin-top: 0.125rem; }
.alert-close { margin-left: auto; padding: 0.25rem; background: none; border: none; color: inherit; opacity: 0.5; cursor: pointer; display: flex; align-items: center; }
.alert-close:hover { opacity: 1; }
.alert ul { margin: 0.25rem 0 0 0; padding-left: 1.25rem; font-size: 0.875rem; }
@media (max-width: 640px) { .form-actions { flex-direction: column-reverse; } .form-actions .btn { width: 100%; } }

/* ═══════════════════════════════════════════════════════════ */
/* GALLERY & MODAL STYLES                                    */
/* ═══════════════════════════════════════════════════════════ */
.gallery-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; gap: 1rem; flex-wrap: wrap; }
.btn-add-photo { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem 1rem; background: var(--color-primary-50); border: 1px solid var(--color-primary); border-radius: var(--radius-md); color: var(--color-primary); font-size: .875rem; font-weight: 500; cursor: pointer; transition: var(--transition); white-space: nowrap; }
.btn-add-photo:hover { background: var(--color-primary); color: white; }
.gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: .875rem; margin-bottom: 1rem; }
.gallery-item { background: var(--gray-50); border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--gray-200); transition: var(--transition); }
.gallery-item:hover { border-color: var(--color-primary); box-shadow: var(--shadow-md); }
.gallery-item-image { position: relative; aspect-ratio: 1; overflow: hidden; }
.gallery-item-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
.primary-badge { position: absolute; top: .5rem; left: .5rem; background: var(--color-primary); color: white; padding: .2rem .5rem; border-radius: var(--radius-sm); font-size: .625rem; font-weight: 700; display: flex; align-items: center; gap: .25rem; letter-spacing: .02em; }
.gallery-overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, .55); display: flex; align-items: center; justify-content: center; gap: .625rem; opacity: 0; transition: var(--transition); }
.gallery-item:hover .gallery-overlay { opacity: 1; }
.overlay-btn { width: 34px; height: 34px; border-radius: 50%; background: white; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--gray-700); transition: var(--transition); flex-shrink: 0; }
.overlay-btn:hover { background: var(--color-primary); color: white; }
.overlay-btn-danger:hover { background: var(--color-error); color: white; }
.gallery-item-meta { padding: .5rem .625rem; display: flex; flex-direction: column; gap: .125rem; }
.meta-name { font-size: .75rem; font-weight: 500; color: var(--gray-700); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.meta-size { font-size: .625rem; color: var(--gray-400); }
.gallery-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 2rem; gap: .75rem; background: var(--gray-50); border-radius: var(--radius-lg); border: 2px dashed var(--gray-300); color: var(--gray-500); text-align: center; margin-bottom: 1rem; }
.gallery-empty-icon { width: 56px; height: 56px; border-radius: var(--radius-lg); background: var(--gray-100); display: flex; align-items: center; justify-content: center; color: var(--gray-400); }
.gallery-empty p { font-size: .875rem; color: var(--gray-500); margin: 0; }
.gallery-hint { display: flex; align-items: center; gap: .5rem; padding: .625rem .875rem; background: var(--gray-50); border-radius: var(--radius-md); font-size: .75rem; color: var(--gray-500); }

.modal-backdrop { position: fixed; inset: 0; background: rgba(10, 10, 10, .55); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 1rem; }
.modal-panel { background: white; border-radius: 16px; width: 100%; max-width: 500px; max-height: 88vh; display: flex; flex-direction: column; box-shadow: 0 24px 48px -8px rgba(0, 0, 0, .22); overflow: hidden; }
.modal-head { display: flex; justify-content: space-between; align-items: center; padding: 1.125rem 1.375rem; border-bottom: 1px solid var(--gray-100); }
.modal-title { font-size: .9375rem; font-weight: 600; color: var(--gray-900); }
.modal-x { width: 30px; height: 30px; border-radius: 8px; background: var(--gray-100); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--gray-500); transition: var(--transition); }
.modal-x:hover { background: var(--gray-200); color: var(--gray-900); }
.modal-body { flex: 1; overflow-y: auto; padding: 1.25rem 1.375rem; }
.drop-zone { border: 1.5px dashed var(--gray-300); border-radius: var(--radius-lg); padding: 2rem 1.5rem; text-align: center; cursor: pointer; transition: var(--transition); background: var(--gray-50); }
.drop-zone:hover, .drop-zone.drag-over { border-color: var(--color-primary); background: var(--color-primary-50); }
.drop-zone-icon { width: 52px; height: 52px; border-radius: var(--radius-lg); background: white; border: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: center; margin: 0 auto .875rem; color: var(--gray-400); box-shadow: var(--shadow-sm); }
.drop-zone:hover .drop-zone-icon, .drop-zone.drag-over .drop-zone-icon { border-color: var(--color-primary); color: var(--color-primary); }
.drop-zone-text { font-size: .875rem; font-weight: 500; color: var(--gray-700); margin-bottom: .25rem; }
.drop-zone-hint { font-size: .75rem; color: var(--gray-400); margin: .25rem 0; }
.btn-pick { display: inline-flex; align-items: center; padding: .4375rem .875rem; margin-top: .375rem; background: white; border: 1px solid var(--gray-300); border-radius: var(--radius-md); color: var(--gray-700); font-size: .8125rem; font-weight: 500; cursor: pointer; transition: var(--transition); }
.btn-pick:hover { border-color: var(--color-primary); color: var(--color-primary); background: var(--color-primary-50); }
.preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(76px, 1fr)); gap: .625rem; margin-top: 1rem; }
.preview-thumb { position: relative; aspect-ratio: 1; border-radius: var(--radius-md); overflow: visible; border: 1px solid var(--gray-200); }
.preview-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: calc(var(--radius-md) - 1px); }
.preview-thumb-remove { position: absolute; top: -6px; right: -6px; width: 20px; height: 20px; border-radius: 50%; background: var(--color-error); color: white; border: 2px solid white; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 11px; font-weight: 700; line-height: 1; transition: var(--transition); }
.preview-thumb-remove:hover { background: #c0392b; transform: scale(1.1); }
.modal-foot { display: flex; justify-content: space-between; align-items: center; padding: .875rem 1.375rem; border-top: 1px solid var(--gray-100); }
.preview-count { font-size: .8125rem; color: var(--gray-500); }
.btn-cancel { padding: .5rem 1rem; background: white; border: 1px solid var(--gray-200); border-radius: var(--radius-md); color: var(--gray-600); font-size: .875rem; font-weight: 500; cursor: pointer; transition: var(--transition); }
.btn-cancel:hover { background: var(--gray-50); border-color: var(--gray-300); }
.btn-upload { padding: .5rem 1.25rem; background: var(--color-primary); border: none; border-radius: var(--radius-md); color: white; font-size: .875rem; font-weight: 600; cursor: pointer; transition: var(--transition); display: inline-flex; align-items: center; gap: .5rem; }
.btn-upload:hover { background: var(--color-primary-dark); }
.btn-upload:disabled { opacity: .55; cursor: not-allowed; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initCustomSelects();
    initCodeGeneration();
    initDaysRangePreview();
    initTimeValidation();
    initFormSubmit();
    initializeGallery();
});

function initCustomSelects() {
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        const trigger = wrapper.querySelector('.custom-select-trigger');
        const dropdown = wrapper.querySelector('.custom-select-dropdown');
        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
        const selectedText = wrapper.querySelector('.selected-text');

        const initialOption = dropdown.querySelector(`.custom-select-option[data-value="${hiddenInput.value}"]`);
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

                dropdown.querySelectorAll('.custom-select-option').forEach(o => o.classList.remove('is-selected'));
                option.classList.add('is-selected');
                wrapper.classList.remove('open');
                wrapper.classList.remove('is-invalid');

                if (hiddenInput.id === 'unit_type_id') {
                    hiddenInput.dispatchEvent(new Event('change'));
                }
                if (hiddenInput.id === 'openDaysStart' || hiddenInput.id === 'openDaysEnd') {
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
        const nameCode = words.length === 1 ? words[0].substring(0, 3).toUpperCase() : words.map(w => w.charAt(0).toUpperCase()).join('').substring(0, 3);
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
        'monday': 'Senin', 'tuesday': 'Selasa', 'wednesday': 'Rabu', 'thursday': 'Kamis',
        'friday': 'Jumat', 'saturday': 'Sabtu', 'sunday': 'Minggu'
    };
    const daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    window.updateDaysPreview = function() {
        const start = startInput.value;
        const end = endInput.value;
        const startIndex = daysOrder.indexOf(start);
        const endIndex = daysOrder.indexOf(end);
        let openDays = startIndex <= endIndex ? daysOrder.slice(startIndex, endIndex + 1) : daysOrder.slice(startIndex).concat(daysOrder.slice(0, endIndex + 1));
        const closedDays = daysOrder.filter(day => !openDays.includes(day));
        previewSpan.textContent = closedDays.length === 0 ? 'Buka setiap hari' : 'Tutup: ' + closedDays.map(d => daysMap[d]).join(', ');
    };
    updateDaysPreview();
}

function initTimeValidation() {
    const openTime = document.getElementById('open_time');
    const closeTime = document.getElementById('close_time');
    const timeValidation = document.getElementById('timeValidation');

    if (!openTime || !closeTime) return;

    const validate = () => {
        const o = openTime.value, c = closeTime.value;
        if (!o || !c) {
            if (timeValidation) timeValidation.textContent = '';
            return true;
        }

        if (o >= c) {
            if (timeValidation) {
                timeValidation.textContent = 'Jam tutup harus setelah jam buka';
                timeValidation.style.color = 'var(--color-error)';
            }
            closeTime.classList.add('is-invalid');
            return false;
        }

        const dur = (new Date(`2000-01-01T${c}`) - new Date(`2000-01-01T${o}`)) / 3600000;
        if (timeValidation) {
            timeValidation.textContent = `Durasi: ${dur} jam`;
            timeValidation.style.color = 'var(--color-success)';
        }
        closeTime.classList.remove('is-invalid');
        return true;
    };

    openTime.addEventListener('change', validate);
    closeTime.addEventListener('change', validate);
    validate();
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
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (btn) {
            btn.disabled = true;
            btn.classList.add('loading');
        }
    });

    form.querySelectorAll('.form-input, .form-textarea, .time-input').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
}

function initializeGallery() {
    const unitId = {{ $unit->id }};
    const csrfToken = '{{ csrf_token() }}';

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
    photoInput?.addEventListener('change', e => { addFiles(Array.from(e.target.files)); });

    function updateCount() {
        if (previewCount) {
            previewCount.textContent = selectedFiles.length ? `${selectedFiles.length} foto dipilih` : '';
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

    uploadBtn?.addEventListener('click', async () => {
        if (!selectedFiles.length) { alert('Pilih foto terlebih dahulu'); return; }

        uploadBtn.disabled = true;
        const orig = uploadBtn.innerHTML;
        uploadBtn.innerHTML = `
            <svg class="spinner" width="15" height="15" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="60" stroke-dashoffset="20"/>
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
                const msg = result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'Gagal mengunggah foto');
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

    document.querySelectorAll('.set-primary-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            e.preventDefault();
            const photoId = btn.dataset.id;
            if (!confirm('Jadikan foto ini sebagai foto utama?')) return;

            const orig = btn.innerHTML;
            btn.innerHTML = `<svg class="spinner" width="13" height="13" viewBox="0 0 24 24"> <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="60" stroke-dashoffset="20"/> </svg>`;

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