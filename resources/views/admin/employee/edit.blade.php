@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Edit Employee</h1>
                <p class="text-muted mb-0">Update information for employee: {{ $employee->nama }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
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
                <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" id="employeeForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Personal Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama" class="form-label fw-medium mb-2">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3 @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $employee->nama) }}" placeholder="Enter full name" style="height: 48px; border: 1px solid #e5e7eb;" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jabatan" class="form-label fw-medium mb-2">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3 @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $employee->jabatan) }}" placeholder="Enter position" style="height: 48px; border: 1px solid #e5e7eb;" required>
                                    @error('jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_id" class="form-label fw-medium mb-2">Unit <span class="text-danger">*</span></label>
                                    <select class="form-select rounded-3 @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" style="height: 48px; border: 1px solid #e5e7eb;" required>
                                        <option value="" disabled>Select Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->nama_unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bidang" class="form-label fw-medium mb-2">Bidang</label>
                                    <input type="text" class="form-control rounded-3 @error('bidang') is-invalid @enderror" id="bidang" name="bidang" value="{{ old('bidang', $employee->bidang) }}" placeholder="Enter field/division" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('bidang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Contact Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label fw-medium mb-2">Email</label>
                                    <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}" placeholder="Enter email address" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telepon" class="form-label fw-medium mb-2">Telepon</label>
                                    <input type="text" class="form-control rounded-3 @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $employee->telepon) }}" placeholder="Enter phone number" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Employment Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label fw-medium mb-2">Status <span class="text-danger">*</span></label>
                                    <select class="form-select rounded-3 @error('status') is-invalid @enderror" id="status" name="status" style="height: 48px; border: 1px solid #e5e7eb;" required>
                                        <option value="" disabled>Select Status</option>
                                        <option value="aktif" {{ old('status', $employee->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="cuti" {{ old('status', $employee->status) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                        <option value="resign" {{ old('status', $employee->status) == 'resign' ? 'selected' : '' }}>Resign</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai" class="form-label fw-medium mb-2">Tanggal Mulai</label>
                                    <input type="date" class="form-control rounded-3 @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $employee->tanggal_mulai ? \Carbon\Carbon::parse($employee->tanggal_mulai)->format('Y-m-d') : '') }}" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_selesai" class="form-label fw-medium mb-2">Tanggal Selesai</label>
                                    <input type="date" class="form-control rounded-3 @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $employee->tanggal_selesai ? \Carbon\Carbon::parse($employee->tanggal_selesai)->format('Y-m-d') : '') }}" style="height: 48px; border: 1px solid #e5e7eb;">
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Additional Information</h5>
                        <div class="form-group">
                            <label for="keterangan" class="form-label fw-medium mb-2">Keterangan</label>
                            <textarea class="form-control rounded-3 @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="4" placeholder="Enter additional notes or description..." style="border: 1px solid #e5e7eb;">{{ old('keterangan', $employee->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-4 border-top" style="border-color: #e5e7eb;">
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary rounded-pill px-5" style="height: 48px;">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5" style="height: 48px;">
                            Update Employee
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
        
        .is-invalid {
            border-color: #dc2626;
        }
        
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('employeeForm');
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');
            const statusSelect = document.getElementById('status');

            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
                });

                if (tanggalMulai.value && tanggalSelesai.value && tanggalMulai.value > tanggalSelesai.value) {
                    tanggalSelesai.classList.add('is-invalid');
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
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            if (tanggalMulai && tanggalSelesai) {
                tanggalMulai.addEventListener('change', function() {
                    if (tanggalMulai.value && tanggalSelesai.value && tanggalMulai.value > tanggalSelesai.value) {
                        tanggalSelesai.classList.add('is-invalid');
                    } else {
                        tanggalSelesai.classList.remove('is-invalid');
                    }
                });

                tanggalSelesai.addEventListener('change', function() {
                    if (tanggalMulai.value && tanggalSelesai.value && tanggalMulai.value > tanggalSelesai.value) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    if (this.value === 'resign' && !tanggalSelesai.value) {
                        const today = new Date().toISOString().split('T')[0];
                        tanggalSelesai.value = today;
                    }
                });
            }
        });
    </script>
@endsection