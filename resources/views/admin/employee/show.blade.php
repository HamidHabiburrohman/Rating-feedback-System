@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-6 text-center">
            <div class="d-inline-flex align-items-start gap-5 mb-4">
                <a href="{{ route('admin.employees.index') }}"
                    class="btn-back rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px; background-color: white; border: 1px solid #e5e7eb; transition: all 0.2s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="#6b7280" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </a>

                <div>
                    <div class="d-flex align-items-center gap-3 mb-2 justify-content-center">
                        <h1 class="h3 fw-bold mb-3" style="color: #111827;">{{ $employee->nama }}</h1>
                    </div>
                    <div class="d-flex align-items-center gap-3 justify-content-center" style="font-size: 0.875rem;">
                        <div class="status-indicator {{ $employee->status == 'aktif' ? 'active' : ($employee->status == 'cuti' ? 'warning' : 'inactive') }} d-flex align-items-center gap-1">
                            <div class="status-dot"></div>
                            <span class="status-text fw-medium" style="font-size: 0.875rem;">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </div>
                        <span class="text-muted">•</span>
                        <span class="text-muted">Jabatan:</span>
                        <span class="fw-medium text-dark">{{ $employee->jabatan }}</span>
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

        <div class="mx-auto" style="max-width: 800px;">
            <div class="row g-4 mb-7">
                <div class="col-md-4">
                    <div class="card-base p-4 text-center">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background-color: #eff6ff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#3b82f6" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.875rem; color: #6b7280;">Unit</div>
                        <div class="metric-value fw-medium" style="color: #111827; font-size: 1rem;">
                            {{ $employee->unit->nama_unit ?? 'Tidak ada unit' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-base p-4 text-center">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background-color: #f0fdf4;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#10b981" stroke-width="2">
                                    <path d="M12 19l7-7 3 3-7 7-3-3z" />
                                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                                    <path d="M2 2l7.586 7.586" />
                                    <circle cx="11" cy="11" r="2" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.875rem; color: #6b7280;">Bidang</div>
                        <div class="metric-value fw-medium" style="color: #111827; font-size: 1rem;">
                            {{ $employee->bidang ?: 'Tidak ditentukan' }}
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
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.875rem; color: #6b7280;">Email</div>
                        <div class="metric-value fw-medium" style="color: #111827; font-size: 1rem;">
                            {{ $employee->email ?: 'Tidak tersedia' }}
                        </div>
                    </div>
                </div>
            </div>

            @if($employee->keterangan)
            <div class="mb-7">
                <h3 class="h5 fw-semibold mb-4" style="color: #374151;">Keterangan</h3>
                <div class="description-box p-5" style="background-color: #f9fafb;">
                    <p class="mb-0" style="line-height: 1.6; color: #374151;">
                        {{ $employee->keterangan }}
                    </p>
                </div>
            </div>
            @endif

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
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-semibold mb-0" style="color: #374151;">Informasi Pribadi</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Nama Lengkap</div>
                                    <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                        {{ $employee->nama }}
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Jabatan</div>
                                    <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                        {{ $employee->jabatan }}
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Bidang</div>
                                    <div class="info-value fw-medium mt-1" style="color: #111827;">
                                        {{ $employee->bidang ?: 'Tidak ditentukan' }}
                                    </div>
                                </div>
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
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-semibold mb-0" style="color: #374151;">Kontak</h4>
                            </div>
                            <div class="space-y-3">
                                @if($employee->email)
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Email</div>
                                    <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                        {{ $employee->email }}
                                    </div>
                                </div>
                                @endif
                                @if($employee->telepon)
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Telepon</div>
                                    <div class="info-value fw-medium mt-1" style="color: #111827;">
                                        {{ $employee->telepon }}
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
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-semibold mb-0" style="color: #374151;">Masa Kerja</h4>
                            </div>
                            <div class="space-y-3">
                                @if($employee->tanggal_mulai)
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Tanggal Mulai</div>
                                    <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                        {{ \Carbon\Carbon::parse($employee->tanggal_mulai)->format('d F Y') }}
                                    </div>
                                </div>
                                @endif
                                @if($employee->tanggal_selesai)
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Tanggal Selesai</div>
                                    <div class="info-value fw-medium mt-1" style="color: #111827;">
                                        {{ \Carbon\Carbon::parse($employee->tanggal_selesai)->format('d F Y') }}
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
                                    style="width: 32px; height: 32px; background-color: #e0e7ff;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="#6366f1" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                </div>
                                <h4 class="h6 fw-semibold mb-0" style="color: #374151;">Unit & Status</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Unit</div>
                                    <div class="info-value fw-medium mt-1 mb-2" style="color: #111827;">
                                        {{ $employee->unit->nama_unit ?? 'Tidak ada unit' }}
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label text-muted mb-1" style="font-size: 0.875rem; color: #6b7280;">
                                        Status</div>
                                    <div class="info-value fw-medium mt-1" style="color: #111827;">
                                        {{ ucfirst($employee->status) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 pt-4 border-top" style="border-color: #e5e7eb;">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary rounded-pill px-5" style="height: 48px;">
                    Back to List
                </a>
                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary rounded-pill px-5" style="height: 48px;">
                    Edit Employee
                </a>
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

        .status-indicator.warning {
            background: rgba(245, 158, 11, .1);
            color: #f59e0b;
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

        .status-indicator.warning .status-dot {
            background: #f59e0b;
        }

        .status-indicator.inactive .status-dot {
            background: #ef4444;
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

            const cards = document.querySelectorAll('.card-base');
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