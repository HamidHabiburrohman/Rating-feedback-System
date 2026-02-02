@extends('layouts.admin.app')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Edit Unit</h1>
                <p class="text-muted mb-0">Update information for unit: {{ $unit->nama_unit }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" class="me-2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border rounded-4" style="border-color: #e5e7eb;">
            <div class="card-body p-4">
                <form action="{{ route('admin.units.update', $unit->id) }}" method="POST" id="unitForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Basic Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_unit" class="form-label fw-medium mb-2">Kode Unit <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control rounded-3 @error('kode_unit') is-invalid @enderror"
                                        id="kode_unit" name="kode_unit" value="{{ old('kode_unit', $unit->kode_unit) }}"
                                        placeholder="Enter unit code" style="height: 48px; border: 1px solid #e5e7eb;"
                                        required>
                                    @error('kode_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_unit" class="form-label fw-medium mb-2">Nama Unit <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control rounded-3 @error('nama_unit') is-invalid @enderror"
                                        id="nama_unit" name="nama_unit" value="{{ old('nama_unit', $unit->nama_unit) }}"
                                        placeholder="Enter unit name" style="height: 48px; border: 1px solid #e5e7eb;"
                                        required>
                                    @error('nama_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_id" class="form-label fw-medium mb-2">Tipe Unit <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select rounded-3 @error('type_id') is-invalid @enderror"
                                        id="type_id" name="type_id" style="height: 48px; border: 1px solid #e5e7eb;"
                                        required>
                                        <option value="" disabled>Select Type</option>
                                        @foreach($unitTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('type_id', $unit->type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lokasi" class="form-label fw-medium mb-2">Lokasi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3 @error('lokasi') is-invalid @enderror"
                                        id="lokasi" name="lokasi" value="{{ old('lokasi', $unit->lokasi) }}"
                                        placeholder="Enter unit location" style="height: 48px; border: 1px solid #e5e7eb;"
                                        required>
                                    @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Detail Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gedung" class="form-label fw-medium mb-2">Gedung</label>
                                    <input type="text" class="form-control rounded-3 @error('gedung') is-invalid @enderror"
                                        id="gedung" name="gedung" value="{{ old('gedung', $unit->gedung) }}"
                                        placeholder="Enter building name" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('gedung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lantai" class="form-label fw-medium mb-2">Lantai</label>
                                    <input type="text" class="form-control rounded-3 @error('lantai') is-invalid @enderror"
                                        id="lantai" name="lantai" value="{{ old('lantai', $unit->lantai) }}"
                                        placeholder="Enter floor number" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('lantai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kapasitas" class="form-label fw-medium mb-2">Kapasitas</label>
                                    <input type="number"
                                        class="form-control rounded-3 @error('kapasitas') is-invalid @enderror"
                                        id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $unit->kapasitas) }}"
                                        placeholder="Enter capacity" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('kapasitas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label fw-medium mb-2">Status</label>
                                    <select class="form-select rounded-3 @error('status') is-invalid @enderror" id="status"
                                        name="status" style="height: 48px; border: 1px solid #e5e7eb;">
                                        <option value="open" {{ old('status', $unit->status ?? 'open') == 'open' ? 'selected' : '' }}>
                                            Open</option>
                                        <option value="full" {{ old('status', $unit->status ?? 'open') == 'full' ? 'selected' : '' }}>
                                            Full</option>
                                        <option value="maintenance" {{ old('status', $unit->status ?? 'open') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="closed" {{ old('status', $unit->status ?? 'open') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto_unit" class="form-label fw-medium mb-2">Foto Unit</label>

                                    <!-- Input file untuk upload foto -->
                                    <input type="file" name="foto_unit" id="foto_unit"
                                        class="form-control rounded-3 @error('foto_unit') is-invalid @enderror"
                                        accept="image/*" style="height: 48px; padding: 0.5rem; border: 1px solid #e5e7eb;"
                                        onchange="previewImage(event)">

                                    @error('foto_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <!-- Preview gambar saat memilih file -->
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <img id="preview" src="#" alt="Preview" class="rounded-2 img-fluid"
                                            style="max-height: 200px; border: 1px solid #e5e7eb;">
                                    </div>

                                    <!-- Tampilkan foto yang sudah ada jika edit -->
                                    @if(isset($unit) && $unit->foto_unit)
                                        <div class="mt-3">
                                            <p class="mb-2 text-muted" style="font-size: 0.875rem;">Foto saat ini:</p>
                                            <div class="current-photo rounded-2 overflow-hidden"
                                                style="width: 200px; height: 150px; border: 1px solid #e5e7eb;">
                                                <img src="{{ asset('storage/' . $unit->foto_unit) }}" alt="Current Photo"
                                                    class="img-fluid h-100 w-100" style="object-fit: cover;">
                                            </div>
                                            <div class="form-check mt-2">
                                                <input type="checkbox" name="remove_foto" id="remove_foto"
                                                    class="form-check-input" value="1">
                                                <label for="remove_foto" class="form-check-label text-muted"
                                                    style="font-size: 0.875rem;">
                                                    Hapus foto
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Info validasi -->
                                    <div class="form-text mt-2 text-muted" style="font-size: 0.75rem;">
                                        Format: JPG, PNG, GIF, WebP. Maks: 2MB.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Contact Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kontak_telepon" class="form-label fw-medium mb-2">Kontak Telepon</label>
                                    <input type="text"
                                        class="form-control rounded-3 @error('kontak_telepon') is-invalid @enderror"
                                        id="kontak_telepon" name="kontak_telepon"
                                        value="{{ old('kontak_telepon', $unit->kontak_telepon) }}"
                                        placeholder="Enter phone number" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('kontak_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kontak_email" class="form-label fw-medium mb-2">Kontak Email</label>
                                    <input type="email"
                                        class="form-control rounded-3 @error('kontak_email') is-invalid @enderror"
                                        id="kontak_email" name="kontak_email"
                                        value="{{ old('kontak_email', $unit->kontak_email) }}"
                                        placeholder="Enter email address" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('kontak_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Operating Hours</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_buka" class="form-label fw-medium mb-2">Jam Buka</label>
                                    <input type="time"
                                        class="form-control rounded-3 @error('jam_buka') is-invalid @enderror" id="jam_buka"
                                        name="jam_buka"
                                        value="{{ old('jam_buka', $unit->jam_buka ? \Carbon\Carbon::parse($unit->jam_buka)->format('H:i') : '') }}"
                                        style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('jam_buka')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_tutup" class="form-label fw-medium mb-2">Jam Tutup</label>
                                    <input type="time"
                                        class="form-control rounded-3 @error('jam_tutup') is-invalid @enderror"
                                        id="jam_tutup" name="jam_tutup"
                                        value="{{ old('jam_tutup', $unit->jam_tutup ? \Carbon\Carbon::parse($unit->jam_tutup)->format('H:i') : '') }}"
                                        style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('jam_tutup')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Description</h5>
                        <div class="form-group">
                            <label for="deskripsi" class="form-label fw-medium mb-2">Deskripsi</label>
                            <textarea class="form-control rounded-3 @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="4" placeholder="Enter unit description..."
                                style="border: 1px solid #e5e7eb;">{{ old('deskripsi', $unit->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-4 border-top" style="border-color: #e5e7eb;">
                        <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary rounded-pill px-5"
                            style="height: 48px;">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5" style="height: 48px;">
                            Update Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .is-invalid {
            border-color: #dc2626;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('unitForm');
            const jamBuka = document.getElementById('jam_buka');
            const jamTutup = document.getElementById('jam_tutup');

            // HAPUS INI ↓↓↓ (ada duplikasi)
            // const removeCheckbox = document.getElementById('remove_foto');
            // const fileInput = document.getElementById('foto_unit');
            // const currentPhoto = document.querySelector('.current-photo'); const removeCheckbox = document.getElementById('remove_foto');
            // const fileInput = document.getElementById('foto_unit');
            // const currentPhoto = document.querySelector('.current-photo');

            // Tambahkan fungsi previewImage yang hilang
            function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('preview');
                const previewContainer = document.getElementById('imagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    previewContainer.style.display = 'none';
                }
            }

            // Pindahkan ini ke dalam event listener DOMContentLoaded
            const removeCheckbox = document.getElementById('remove_foto');
            const fileInput = document.getElementById('foto_unit');
            const currentPhoto = document.querySelector('.current-photo');

            if (removeCheckbox && fileInput && currentPhoto) {
                removeCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        fileInput.value = '';
                        currentPhoto.style.display = 'none';
                    } else {
                        currentPhoto.style.display = 'block';
                    }
                });
            }

            form.addEventListener('submit', function (e) {
                let isValid = true;

                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
                });

                if (jamBuka.value && jamTutup.value && jamBuka.value >= jamTutup.value) {
                    jamTutup.classList.add('is-invalid');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });

            document.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('input', function () {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            if (jamBuka && jamTutup) {
                jamBuka.addEventListener('change', function () {
                    if (jamBuka.value && jamTutup.value && jamBuka.value >= jamTutup.value) {
                        jamTutup.classList.add('is-invalid');
                    } else {
                        jamTutup.classList.remove('is-invalid');
                    }
                });

                jamTutup.addEventListener('change', function () {
                    if (jamBuka.value && jamTutup.value && jamBuka.value >= jamTutup.value) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            }
        });
    </script>
@endsection