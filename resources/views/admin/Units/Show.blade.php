@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Hero Section - Centered -->
        <div class="mb-6 text-center">
            <div class="d-inline-flex align-items-start gap-5 mb-4">
                <a href="{{ route('admin.units.index') }}"
                    class="btn-back rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px; background-color: white; border: 1px solid #e5e7eb; transition: all 0.2s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="#6b7280" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </a>

                <div>
                    <div class="d-flex align-items-center gap-3 mb-2 justify-content-center">
                        <h1 class="h3 fw-bold mb-3" style="color: #111827;">{{ $unit->nama_unit }}</h1>
                    </div>
                    <div class="d-flex align-items-center gap-3 justify-content-center" style="font-size: 0.875rem;">
                        <div
                            class="status-indicator {{ $unit->status_aktif ? 'active' : 'inactive' }} d-flex align-items-center gap-1">
                            <div class="status-dot"></div>
                            <span class="status-text fw-medium" style="font-size: 0.875rem;">
                                {{ $unit->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <span class="text-muted">•</span>
                        <span class="text-muted">Kode: <span
                                class="fw-medium text-dark">{{ $unit->kode_unit }}</span></span>
                        <span class="text-muted">•</span>
                        <span class="badge badge-type">{{ ucfirst($unit->jenis_unit) }}</span>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Single Column Layout -->
        <div class="mx-auto" style="max-width: 800px;">
            <!-- Key Metrics Grid -->
            <div class="row g-4 mb-7">
                @if($unit->kapasitas)
                    <div class="col-md-4">
                        <div class="card-base p-4 text-center">
                            <!-- Icon Kapasitas -->
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
                            <div class="metric-value h4 fw-bold mb-0" style="color: #111827;">
                                {{ $unit->kapasitas }}
                                <span class="text-muted" style="font-size: 0.875rem;">orang</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-4">
                    <div class="card-base p-4 text-center">
                        <!-- Icon Lokasi -->
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
                            @if($unit->gedung)
                                Gedung {{ $unit->gedung }}
                                @if($unit->lantai)
                                    <span class="text-muted" style="font-size: 0.875rem;">Lt. {{ $unit->lantai }}</span>
                                @endif
                            @else
                                {{ $unit->lokasi }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-base p-4 text-center">
                        <!-- Icon Kontak -->
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
                            @if($unit->kontak_telepon)
                                {{ $unit->kontak_telepon }}
                            @elseif($unit->kontak_email)
                                {{ $unit->kontak_email }}
                            @else
                                Tidak tersedia
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-7">
                <h3 class="h5 fw-semibold mb-4" style="color: #374151;">Deskripsi Unit</h3>
                <div class="description-box p-5" style="background-color: #f9fafb;">
                    <p class="mb-0" style="line-height: 1.6; color: #374151;">
                        {{ $unit->deskripsi ?: 'Tidak ada deskripsi tersedia untuk unit ini.' }}
                    </p>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="mb-6">
                <h3 class="h5 fw-semibold mb-4" style="color: #374151;">Detail Informasi</h3>

                <div class="row g-4">
                    <!-- Spesifikasi -->
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
                                        {{ ucfirst($unit->jenis_unit) }}
                                    </div>
                                </div>
                                @if($unit->kapasitas)
                                    <div class="info-row">
                                        <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                            Kapasitas Maksimum:</div>
                                        <div class="info-value fw-medium mt-1" style="color: #111827;">{{ $unit->kapasitas }}
                                            orang
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
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
                                            @if($unit->gedung)
                                                Gedung {{ $unit->gedung }}
                                                @if($unit->lantai)
                                                    <span class="text-muted"> (Lantai {{ $unit->lantai }})</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Kontak -->
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
                                            {{ $unit->kontak_telepon }}
                                        </div>
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

                    <!-- Jam Operasional -->
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
                                                {{ \Carbon\Carbon::parse($unit->jam_buka)->format('H:i') }}
                                            </div>
                                        </div>
                                    @endif
                                    @if($unit->jam_tutup)
                                        <div class="info-row">
                                            <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                                Tutup</div>
                                            <div class="info-value fw-medium mt-1" style="color: #111827;">
                                                {{ \Carbon\Carbon::parse($unit->jam_tutup)->format('H:i') }}
                                            </div>
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
    </div>

    <style>
        :root {
            --border-soft: #e5e7eb;
            --border-hover: #d1d5db;
            --text-main: #111827;
            --text-muted: #6b7280;
            --bg-soft: #f9fafb;
            --primary: #3b82f6;
            --primary-hover: #2563eb;
        }

        .card-base {
            background: white;
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            transition: all .2s ease;
        }


        .text-main {
            color: var(--text-main);
        }

        .text-muted {
            color: var(--text-muted);
        }

        .section-title {
            font-weight: 600;
            color: #374151;
        }

        .description-box {
            background: var(--bg-soft);
            border: 1px solid var(--border-soft);
            border-radius: 12px;
        }

        .status-indicator {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 500;
        }

        .status-indicator.active {
            background: rgba(16, 185, 129, .1);
            color: #10b981;
        }

        .status-indicator.inactive {
            background: rgba(239, 68, 68, .1);
            color: #ef4444;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-indicator.active .status-dot {
            background: #10b981;
        }

        .status-indicator.inactive .status-dot {
            background: #ef4444;
        }

        .badge-type {
            background: rgba(59, 130, 246, .1);
            color: #3b82f6;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: .75rem;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }


        .btn-back {
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid var(--border-soft);
            transition: all .2s ease;
        }


        @media (max-width:768px) {
            .mx-auto {
                max-width: 100% !important;
                padding: 0 16px;
            }
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });

            const cards = document.querySelectorAll('.metric-card, .detail-section');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                });

                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection