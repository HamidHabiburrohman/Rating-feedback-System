@extends('layouts.admin.app')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-5 mx-auto" role="alert"
                style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 0.75rem 1rem; max-width: 800px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" class="me-2 flex-shrink-0">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span class="flex-grow-1">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-6">
            <div class="text-center">
                <h1 class="h3 fw-bold mb-3" style="color: #111827;">{{ $unit->nama_unit }}</h1>

                <div class="d-flex align-items-center gap-3 justify-content-center mb-4" style="font-size: 0.875rem;">
                    <div
                        class="status-indicator {{ $unit->status_aktif ? 'active' : 'inactive' }} d-flex align-items-center gap-1">
                        <div class="status-dot"></div>
                        <span class="status-text fw-medium"
                            style="font-size: 0.875rem;">{{ $unit->status_aktif ? 'Aktif' : 'Nonaktif' }}</span>
                    </div>
                    <span class="text-muted">•</span>
                    <span class="text-muted">Kode: <span class="fw-medium text-dark">{{ $unit->kode_unit }}</span></span>
                    <span class="text-muted">•</span>
                    <span class="badge badge-type">{{ $unit->unitType->name ?? 'Tidak ada tipe' }}</span>
                </div>

                @if($unit->foto_unit)
                    <div class="mb-4 d-flex justify-content-center position-relative">
                        <div class="unit-photo-container position-relative" style="width: 100%; max-width: 800px;">
                            <a href="{{ route('admin.units.index') }}"
                                class="btn-back rounded-4 d-flex align-items-center justify-content-center position-absolute"
                                style="width: 40px; height: 40px; background-color: white; border: 1px solid #e5e7eb; transition: all 0.2s ease; top: 16px; left: 16px; z-index: 10;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="#6b7280" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7" />
                                </svg>
                            </a>

                            <img src="{{ asset('storage/' . $unit->foto_unit) }}" alt="{{ $unit->nama_unit }}"
                                class="unit-photo img-fluid rounded-5"
                                style="border-radius: 16px; border: 9px solid #fdfdfd; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); max-height: 400px; object-fit: cover; width: 100%;">
                        </div>
                    </div>
                @else
                    <div class="mb-4 d-flex justify-content-center position-relative">
                        @php
                            $icon = match (strtolower($unit->unitType->name ?? 'default')) {
                                'kesehatan' => 'health',
                                'akademik' => 'academic',
                                'fasilitas' => 'facility',
                                'teknologi' => 'technology',
                                'olahraga' => 'sports',
                                'kesenian' => 'art',
                                'administrasi' => 'administration',
                                default => 'general'
                            };
                        @endphp

                        <div class="unit-photo-placeholder rounded-3 d-flex flex-column align-items-center justify-content-center position-relative"
                            style="width: 100%; max-width: 400px; height: 250px; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-radius: 16px; border: 2px dashed #d1d5db;">

                            <a href="{{ route('admin.units.index') }}"
                                class="btn-back rounded-3 d-flex align-items-center justify-content-center position-absolute"
                                style="width: 40px; height: 40px; background-color: white; border: 1px solid #e5e7eb; transition: all 0.2s ease; top: 16px; left: 16px; z-index: 10;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="#6b7280" stroke-width="2">
                                    <path d="M19 12H5M12 19l-7-7 7-7" />
                                </svg>
                            </a>

                            @if($icon === 'health')
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#10b981" stroke-width="1.5">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                </svg>
                            @elseif($icon === 'academic')
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#3b82f6" stroke-width="1.5">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                </svg>
                            @elseif($icon === 'facility')
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#8b5cf6" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                            @elseif($icon === 'technology')
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#f59e0b" stroke-width="1.5">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                </svg>
                            @elseif($icon === 'sports')
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#ef4444" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M16 16s-1.5-2-4-2-4 2-4 2" />
                                    <line x1="9" y1="9" x2="9.01" y2="9" />
                                    <line x1="15" y1="9" x2="15.01" y2="9" />
                                </svg>
                            @elseif($icon === 'art')
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#ec4899" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                                    <line x1="9" y1="9" x2="9.01" y2="9" />
                                    <line x1="15" y1="9" x2="15.01" y2="9" />
                                </svg>
                            @elseif($icon === 'administration')
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#6366f1" stroke-width="1.5">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                    stroke="#6b7280" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                    <line x1="3" y1="9" x2="21" y2="9" />
                                    <line x1="9" y1="21" x2="9" y2="9" />
                                </svg>
                            @endif

                            <span class="mt-3 text-muted" style="font-size: 0.875rem;">Tidak ada foto unit</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mx-auto" style="max-width: 800px;">
            <div class="row g-4 mb-7">
                @if($unit->kapasitas)
                    <div class="col-md-4">
                        <div class="card-base p-4 text-center">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px; background-color: #eff6ff;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="#3b82f6" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-muted mb-2" style="font-size: 0.875rem; color: #6b7280;">Kapasitas</div>
                            <div class="metric-value h4 fw-bold mb-0" style="color: #111827;">{{ $unit->kapasitas }} <span
                                    class="text-muted" style="font-size: 0.875rem;">orang</span></div>
                        </div>
                    </div>
                @endif

                <div class="col-md-4">
                    <div class="card-base p-4 text-center">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background-color: #f0fdf4;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#10b981" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.875rem; color: #6b7280;">Lokasi</div>
                        <div class="metric-value fw-medium" style="color: #111827; font-size: 1rem;">
                            @if($unit->gedung) Gedung {{ $unit->gedung }} @if($unit->lantai) <span class="text-muted"
                            style="font-size: 0.875rem;">Lt. {{ $unit->lantai }}</span> @endif @else {{ $unit->lokasi }}
                                @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-base p-4 text-center">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background-color: #fef3c7;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#f59e0b" stroke-width="2">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12-84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.875rem; color: #6b7280;">Kontak</div>
                        <div class="metric-value fw-medium" style="color: #111827; font-size: 1rem;">
                            @if($unit->kontak_telepon) {{ $unit->kontak_telepon }} @elseif($unit->kontak_email)
                            {{ $unit->kontak_email }} @else Tidak tersedia @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-7">
                <h3 class="h5 fw-semibold mb-4" style="color: #374151;">Deskripsi Unit</h3>
                <div class="description-box p-5" style="background-color: #f9fafb;">
                    <p class="mb-0" style="line-height: 1.6; color: #374151;">
                        {{ $unit->deskripsi ?: 'Tidak ada deskripsi tersedia untuk unit ini.' }}</p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="h5 fw-semibold mb-4" style="color: #374151;">Detail Informasi</h3>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card-base p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; background-color: #eff6ff;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="#3b82f6" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-semibold mb-0" style="color: #374151;">Spesifikasi</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Jenis Unit:</div>
                                    <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                        {{ ucfirst($unit->unitType->name ?? '') }}</div>
                                </div>
                                @if($unit->kapasitas)
                                    <div class="info-row">
                                        <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                            Kapasitas Maksimum:</div>
                                        <div class="info-value fw-medium mt-1" style="color: #111827;">{{ $unit->kapasitas }}
                                            orang</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-base p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; background-color: #f0fdf4;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="#10b981" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-semibold mb-0" style="color: #374151;">Lokasi Lengkap</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Alamat</div>
                                    <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">{{ $unit->lokasi }}
                                    </div>
                                </div>
                                @if($unit->gedung || $unit->lantai)
                                    <div class="info-row">
                                        <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                            Detail Gedung</div>
                                        <div class="info-value fw-medium" style="color: #111827;">
                                            @if($unit->gedung) Gedung {{ $unit->gedung }} @if($unit->lantai) <span
                                            class="text-muted"> (Lantai {{ $unit->lantai }})</span> @endif @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-base p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; background-color: #fef3c7;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="#f59e0b" stroke-width="2">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-semibold mb-" style="color: #374151;">Kontak</h4>
                            </div>
                            <div class="space-y-3">
                                @if($unit->kontak_telepon)
                                    <div class="info-row">
                                        <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                            Telepon</div>
                                        <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                            {{ $unit->kontak_telepon }}</div>
                                    </div>
                                @endif
                                @if($unit->kontak_email)
                                    <div class="info-row">
                                        <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                            Email</div>
                                        <div class="info-value fw-medium mt-1" style="color: #111827;">{{ $unit->kontak_email }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($unit->jam_buka || $unit->jam_tutup)
                        <div class="col-md-6">
                            <div class="card-base p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background-color: #e0e7ff;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="#6366f1" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                    </div>
                                    <h4 class="h6 fw-semibold mb-0" style="color: #374151;">Jam Operasional</h4>
                                </div>
                                <div class="space-y-3">
                                    @if($unit->jam_buka)
                                        <div class="info-row">
                                            <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                                Buka</div>
                                            <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                                {{ \Carbon\Carbon::parse($unit->jam_buka)->format('H:i') }}</div>
                                        </div>
                                    @endif
                                    @if($unit->jam_tutup)
                                        <div class="info-row">
                                            <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                                Tutup</div>
                                            <div class="info-value fw-medium mt-1" style="color: #111827;">
                                                {{ \Carbon\Carbon::parse($unit->jam_tutup)->format('H:i') }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection