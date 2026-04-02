@extends('layouts.admin.app')

@section('title', 'Edit Unit')

@section('admin-content')
    <div class="page-container">
        <header class="page-header">
            <div>
                <h1>Edit Unit</h1>
                <p class="subtitle">Perbarui informasi unit: {{ $unit->name }}</p>
            </div>
            <a href="{{ route('admin.units.index') }}" class="btn-ghost">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                            <input type="text" id="name" name="name" value="{{ old('name', $unit->name) }}"
                                placeholder="Contoh: Laboratorium Komputer Dasar"
                                class="form-input @error('name') is-invalid @enderror" required>
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit_type_id" class="form-label">Tipe Unit <span class="required">*</span></label>
                        <div class="input-wrapper">
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

                    <div class="form-group">
                        <label for="code" class="form-label">
                            Kode Unit
                            <span class="badge auto">Auto</span>
                        </label>
                        <div class="input-wrapper code-wrapper">
                            <input type="text" id="code" name="code" value="{{ old('code', $unit->code) }}"
                                class="form-input code-input @error('code') is-invalid @enderror" readonly required>
                            <button type="button" class="btn-icon" id="regenerateCode" title="Generate ulang">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
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

                    <div class="form-group">
                        <label for="unit_department_id" class="form-label">Departemen</label>
                        <div class="input-wrapper">
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

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">2</span>
                    Lokasi
                </h2>

                <div class="grid grid-cols-3">
                    <div class="form-group">
                        <label for="building" class="form-label">Gedung</label>
                        <input type="text" id="building" name="building" value="{{ old('building', $unit->building) }}"
                            placeholder="Contoh: Gedung FIK" class="form-input @error('building') is-invalid @enderror">
                        @error('building')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="floor" class="form-label">Lantai</label>
                        <input type="text" id="floor" name="floor" value="{{ old('floor', $unit->floor) }}"
                            placeholder="Contoh: 2" class="form-input @error('floor') is-invalid @enderror">
                        @error('floor')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Detail Lokasi</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $unit->location) }}"
                            placeholder="Contoh: Ruang 201" class="form-input @error('location') is-invalid @enderror">
                        @error('location')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="form-card">
                <h2 class="section-title">
                    <span class="section-number">3</span>
                    Operasional
                </h2>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="capacity" class="form-label">Kapasitas</label>
                        <div class="input-wrapper">
                            <input type="number" id="capacity" name="capacity"
                                value="{{ old('capacity', $unit->capacity) }}" placeholder="40" min="1"
                                class="form-input @error('capacity') is-invalid @enderror">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </span>
                        </div>
                        @error('capacity')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jam Operasional</label>
                        <div class="time-range-wrapper">
                            <div class="time-range">
                                <div class="time-input">
                                    <input type="time" id="open_time" name="open_time"
                                        value="{{ old('open_time', substr($unit->open_time ?? '08:00', 0, 5)) }}"
                                        class="form-input @error('open_time') is-invalid @enderror">
                                    <span class="time-label">Buka</span>
                                </div>
                                <span class="time-separator">→</span>
                                <div class="time-input">
                                    <input type="time" id="close_time" name="close_time"
                                        value="{{ old('close_time', substr($unit->close_time ?? '17:00', 0, 5)) }}"
                                        class="form-input @error('close_time') is-invalid @enderror">
                                    <span class="time-label">Tutup</span>
                                </div>
                            </div>
                            <span class="time-validation" id="timeValidation"></span>
                        </div>
                        @error('open_time')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        @error('close_time')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group full-width" style="margin-top: 1rem;">
                    <label class="form-label">Status Operasional</label>
                    <div class="status-options">
                        <label class="status-option">
                            <input type="radio" name="operational_status" value="open" {{ old('operational_status', $unit->operational_status) == 'open' ? 'checked' : '' }}>
                            <span class="status-badge open">Buka</span>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="operational_status" value="maintenance" {{ old('operational_status', $unit->operational_status) == 'maintenance' ? 'checked' : '' }}>
                            <span class="status-badge maintenance">Perawatan</span>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="operational_status" value="closed" {{ old('operational_status', $unit->operational_status) == 'closed' ? 'checked' : '' }}>
                            <span class="status-badge closed">Tutup</span>
                        </label>
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
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $unit->phone) }}"
                                placeholder="021-5550001" class="form-input @error('phone') is-invalid @enderror">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2" />
                                    <line x1="12" y1="18" x2="12.01" y2="18" />
                                </svg>
                            </span>
                        </div>
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" value="{{ old('email', $unit->email) }}"
                                placeholder="unit@kampus.ac.id" class="form-input @error('email') is-invalid @enderror">
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </span>
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group full-width facilities-wrapper">
                    <label for="facilities" class="form-label">Fasilitas</label>
                    <select id="facilities" name="facilities[]" multiple
                        class="form-select @error('facilities') is-invalid @enderror"
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

                <div class="form-group full-width">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" placeholder="Deskripsikan unit ini..."
                        class="form-textarea @error('description') is-invalid @enderror">{{ old('description', $unit->description) }}</textarea>
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

                <div class="gallery-container">
                    <div class="gallery-header">
                        <div class="gallery-info">
                            <p class="help-text" style="margin-top: 0;">
                                Foto-foto unit akan ditampilkan di halaman detail unit. Foto pertama akan menjadi thumbnail
                                utama.
                            </p>
                        </div>
                        <button type="button" class="btn-add-photo" id="openUploadModalBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Tambah Foto
                        </button>
                    </div>

                    <div class="gallery-grid" id="galleryGrid">
                        @php
                            $photos = $unit->photos ?? collect();
                        @endphp
                        @foreach($photos as $photo)
                            <div class="gallery-item" data-id="{{ $photo->id }}" data-sort="{{ $photo->sort_order }}">
                                <div class="gallery-item-image">
                                    <img src="{{ $photo->thumbnail_url ?? $photo->url }}"
                                        alt="{{ $photo->alt_text ?? 'Unit Photo' }}">
                                    @if($photo->is_primary)
                                        <div class="primary-badge">
                                            <span class="material-symbols-outlined" style="font-size: 12px;">star</span>
                                            Primary
                                        </div>
                                    @endif
                                    <div class="gallery-item-overlay">
                                        <button type="button" class="action-btn set-primary-btn" data-id="{{ $photo->id }}"
                                            title="Jadikan Primary">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">star</span>
                                        </button>
                                        <button type="button" class="action-btn delete-photo-btn" data-id="{{ $photo->id }}"
                                            title="Hapus">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="gallery-item-info">
                                    <span class="file-name">{{ $photo->file_name ?? 'Image' }}</span>
                                    <span class="file-size">{{ $photo->formatted_size ?? '' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($photos->isEmpty())
                        <div class="gallery-empty" id="galleryEmpty">
                            <span class="material-symbols-outlined" style="font-size: 48px;">photo_library</span>
                            <p>Belum ada foto. Klik "Tambah Foto" untuk mengunggah gambar.</p>
                        </div>
                    @endif

                    <div class="gallery-help">
                        <span class="material-symbols-outlined" style="font-size: 16px;">info</span>
                        <span>Format: JPG, JPEG, PNG. Maksimal 5MB per file. Foto pertama akan otomatis menjadi thumbnail
                            utama.</span>
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
                <a href="{{ route('admin.units.index') }}" class="btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">Perbarui Unit</span>
                    <span class="btn-loader" hidden>
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

    <div id="uploadModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Foto Unit</h3>
                <button type="button" class="modal-close" id="closeModalBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="upload-area" id="uploadArea">
                    <input type="file" id="photoInput" multiple accept="image/jpeg,image/png,image/jpg"
                        style="display: none;">
                    <div class="upload-icon">
                        <span class="material-symbols-outlined" style="font-size: 48px;">cloud_upload</span>
                    </div>
                    <p class="upload-text">Klik atau drag & drop foto di sini</p>
                    <p class="upload-hint">Maksimal 5MB per file. Format: JPG, JPEG, PNG</p>
                    <button type="button" class="btn-select-files" id="selectFilesBtn">Pilih File</button>
                </div>
                <div class="preview-list" id="previewList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" id="cancelUploadBtn">Batal</button>
                <button type="button" class="btn-primary" id="uploadBtn">Upload</button>
            </div>
        </div>
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

        .gallery-container {
            margin-top: 0.5rem;
        }

        .gallery-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-add-photo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--color-primary-50);
            border: 1px solid var(--color-primary);
            border-radius: var(--radius-md);
            color: var(--color-primary);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-add-photo:hover {
            background: var(--color-primary);
            color: white;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .gallery-item {
            background: var(--color-gray-50);
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--color-gray-200);
            transition: var(--transition);
        }

        .gallery-item:hover {
            border-color: var(--color-primary);
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
        }

        .primary-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: var(--color-primary);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.625rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .gallery-item-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            opacity: 0;
            transition: var(--transition);
        }

        .gallery-item:hover .gallery-item-overlay {
            opacity: 1;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            background: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--color-gray-700);
        }

        .action-btn:hover {
            background: var(--color-primary);
            color: white;
        }

        .gallery-item-info {
            padding: 0.5rem;
            font-size: 0.75rem;
        }

        .file-name {
            display: block;
            color: var(--color-gray-700);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-size {
            display: block;
            color: var(--color-gray-400);
            font-size: 0.625rem;
        }

        .gallery-empty {
            text-align: center;
            padding: 3rem;
            background: var(--color-gray-50);
            border-radius: var(--radius-lg);
            border: 2px dashed var(--color-gray-300);
            color: var(--color-gray-500);
        }

        .gallery-empty .material-symbols-outlined {
            margin-bottom: 0.5rem;
            color: var(--color-gray-400);
        }

        .gallery-help {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            font-size: 0.75rem;
            color: var(--color-gray-500);
            margin-top: 1rem;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            border-radius: var(--radius-xl);
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .modal-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--color-gray-500);
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--color-gray-900);
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .upload-area {
            border: 2px dashed var(--color-gray-300);
            border-radius: var(--radius-lg);
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .upload-area:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-50);
        }

        .upload-icon {
            color: var(--color-gray-400);
            margin-bottom: 0.75rem;
        }

        .upload-text {
            font-weight: 500;
            color: var(--color-gray-700);
            margin-bottom: 0.25rem;
        }

        .upload-hint {
            font-size: 0.75rem;
            color: var(--color-gray-400);
            margin-bottom: 1rem;
        }

        .btn-select-files {
            background: var(--color-primary);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-select-files:hover {
            background: var(--color-primary-dark);
        }

        .preview-list {
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .preview-item {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--color-gray-200);
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--color-error);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--color-gray-200);
        }

        .btn-primary {
            background: var(--color-primary);
            color: white;
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--color-primary-dark);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('admin-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initializeGallery();
        });

        function initializeGallery() {
            const unitId = {{ $unit->id }};
            const galleryGrid = document.getElementById('galleryGrid');
            const galleryEmpty = document.getElementById('galleryEmpty');
            const openModalBtn = document.getElementById('openUploadModalBtn');
            const modal = document.getElementById('uploadModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const cancelUploadBtn = document.getElementById('cancelUploadBtn');
            const uploadArea = document.getElementById('uploadArea');
            const photoInput = document.getElementById('photoInput');
            const selectFilesBtn = document.getElementById('selectFilesBtn');
            const uploadBtn = document.getElementById('uploadBtn');
            const previewList = document.getElementById('previewList');

            let selectedFiles = [];

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function showModal() {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function hideModal() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
                selectedFiles = [];
                if (photoInput) photoInput.value = '';
                if (previewList) previewList.innerHTML = '';
            }

            if (openModalBtn) openModalBtn.addEventListener('click', showModal);
            if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);
            if (cancelUploadBtn) cancelUploadBtn.addEventListener('click', hideModal);

            modal?.addEventListener('click', function (e) {
                if (e.target === modal) hideModal();
            });

            if (uploadArea) {
                uploadArea.addEventListener('click', () => photoInput?.click());
                uploadArea.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploadArea.style.borderColor = 'var(--color-primary)';
                    uploadArea.style.background = 'var(--color-primary-50)';
                });
                uploadArea.addEventListener('dragleave', () => {
                    uploadArea.style.borderColor = 'var(--color-gray-300)';
                    uploadArea.style.background = '';
                });
                uploadArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploadArea.style.borderColor = 'var(--color-gray-300)';
                    uploadArea.style.background = '';
                    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                    if (files.length) addFiles(files);
                });
            }

            if (selectFilesBtn) {
                selectFilesBtn.addEventListener('click', () => photoInput?.click());
            }

            if (photoInput) {
                photoInput.addEventListener('change', (e) => {
                    const files = Array.from(e.target.files);
                    if (files.length) addFiles(files);
                });
            }

            function addFiles(files) {
                files.forEach(file => {
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File ${file.name} melebihi 5MB`);
                        return;
                    }
                    if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                        alert(`File ${file.name} harus berupa gambar (JPG/JPEG/PNG)`);
                        return;
                    }

                    if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const preview = document.createElement('div');
                            preview.className = 'preview-item';
                            preview.innerHTML = `
                                            <img src="${e.target.result}">
                                            <button type="button" class="preview-remove" data-filename="${file.name}" data-filesize="${file.size}">×</button>
                                        `;
                            previewList.appendChild(preview);

                            preview.querySelector('.preview-remove').addEventListener('click', () => {
                                const index = selectedFiles.findIndex(f => f.name === file.name && f.size === file.size);
                                if (index > -1) selectedFiles.splice(index, 1);
                                preview.remove();
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
                if (photoInput) photoInput.value = '';
            }

            if (uploadBtn) {
                uploadBtn.addEventListener('click', async () => {
                    if (selectedFiles.length === 0) {
                        alert('Pilih foto terlebih dahulu');
                        return;
                    }

                    uploadBtn.disabled = true;
                    const originalText = uploadBtn.innerHTML;
                    uploadBtn.innerHTML = `
                                    <svg class="spinner" width="16" height="16" viewBox="0 0 24 24" style="animation: spin 1s linear infinite; margin-right: 8px;">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="60" stroke-dashoffset="20"/>
                                    </svg>
                                    Mengupload...
                                `;

                    const formData = new FormData();
                    selectedFiles.forEach(file => {
                        formData.append('photos[]', file);
                    });

                    try {
                        const response = await fetch(`/admin/units/${unitId}/photos/upload`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (response.ok && result.success === true) {
                            hideModal();
                            location.reload();
                        } else {
                            let errorMsg = result.message || 'Gagal upload foto';
                            if (result.errors) {
                                errorMsg = Object.values(result.errors).flat().join('\n');
                            }
                            alert(errorMsg);
                            uploadBtn.disabled = false;
                            uploadBtn.innerHTML = originalText;
                        }
                    } catch (error) {
                        console.error('Upload error:', error);
                        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
                        uploadBtn.disabled = false;
                        uploadBtn.innerHTML = originalText;
                    }
                });
            }

            document.querySelectorAll('.set-primary-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const photoId = btn.dataset.id;

                    if (!confirm('Jadikan foto ini sebagai foto utama?')) return;

                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<span class="spinner" style="display: inline-block; width: 14px; height: 14px; border: 2px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></span>';

                    try {
                        const response = await fetch(`/admin/units/${unitId}/photos/${photoId}/primary`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            location.reload();
                        } else {
                            alert(result.message || 'Gagal mengatur foto utama');
                            btn.innerHTML = originalText;
                        }
                    } catch (error) {
                        console.error('Set primary error:', error);
                        alert('Terjadi kesalahan');
                        btn.innerHTML = originalText;
                    }
                });
            });

            document.querySelectorAll('.delete-photo-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const photoId = btn.dataset.id;

                    if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) return;

                    const galleryItem = btn.closest('.gallery-item');
                    if (galleryItem) galleryItem.style.opacity = '0.5';

                    try {
                        const response = await fetch(`/admin/units/${unitId}/photos/${photoId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            if (galleryItem) galleryItem.remove();
                            const remainingItems = document.querySelectorAll('.gallery-item').length;
                            if (remainingItems === 0 && galleryEmpty) {
                                galleryEmpty.style.display = 'flex';
                            }
                        } else {
                            alert(result.message || 'Gagal menghapus foto');
                            if (galleryItem) galleryItem.style.opacity = '';
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        alert('Terjadi kesalahan');
                        if (galleryItem) galleryItem.style.opacity = '';
                    }
                });
            });
        }
    </script>
@endpush