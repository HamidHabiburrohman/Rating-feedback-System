@extends('layouts.admin.app')

@section('admin-content')
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast toast-success" role="alert">
                <div class="toast-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="toast-content">
                    <p>{{ session('success') }}</p>
                </div>
                <button type="button" class="toast-close" aria-label="Close" onclick="this.closest('.toast').remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="toast toast-error" role="alert">
                <div class="toast-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="toast-content">
                    <p>{{ session('error') }}</p>
                </div>
                <button type="button" class="toast-close" aria-label="Close" onclick="this.closest('.toast').remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <main class="report-detail-container">
        <!-- Navigation -->
        <div class="nav-wrapper">
            <a href="{{ route('admin.reports.index') }}" class="btn-back-nav">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Daftar Laporan</span>
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-5 border-0" role="alert"
                style="background-color: #e8f5e9; color: #2e7d32; padding: 1rem 1.5rem; border-left: 4px solid #2e7d32;">
                <div class="d-flex align-items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                        style="filter: brightness(0.5);"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-5 border-0" role="alert"
                style="background-color: #ffebee; color: #c62828; padding: 1rem 1.5rem; border-left: 4px solid #c62828;">
                <div class="d-flex align-items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                        style="filter: brightness(0.5);"></button>
                </div>
            </div>
        @endif

        <div class="row g-4 g-lg-5">
            <!-- Left Column - Main Content (8 col) -->
            <div class="col-lg-7 col-xl-8">
                <!-- Report Header Card -->
                <div class="card border-0 rounded-4 mb-4"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                    <div class="card-body p-4 p-xl-5">
                        <div class="row g-4 align-items-start">
                            <div class="col-md-auto">
                                <div class="rounded-4 overflow-hidden d-flex align-items-center justify-content-center"
                                    style="width: 96px; height: 96px; background: #f8fafc; border: 1px solid #f0f0f0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                                        fill="none" stroke="#f8773c" stroke-width="1.5">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </div>
                            </div>

                            <div class="col">
                                <div class="d-flex flex-column gap-2">
                                    <h2 class="h4 fw-semibold mb-0" style="color: #1e2937; letter-spacing: -0.01em;">
                                        {{ $report->title }}
                                    </h2>
                                    <div class="d-flex flex-wrap gap-3 gap-lg-4 text-muted small">
                                        <span class="d-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            @if($report->unit && $report->unit->lokasi)
                                                {{ $report->unit->lokasi }}
                                            @elseif($report->unit && $report->unit->gedung)
                                                Gedung {{ $report->unit->gedung }}, Lantai {{ $report->unit->lantai }}
                                            @else
                                                Lokasi tidak tersedia
                                            @endif
                                        </span>
                                        <span class="d-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            {{ $report->created_at->format('d M Y') }}
                                        </span>
                                        @if($report->unit && $report->unit->kode_unit)
                                            <span class="d-flex align-items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="3" y1="9" x2="21" y2="9"></line>
                                                </svg>
                                                {{ $report->unit->kode_unit }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-auto text-md-end mt-3 mt-md-0">
                                <div class="d-flex flex-column align-items-md-end gap-2">
                                    @php
                                        $priorityMap = [
                                            'low' => 'Rendah',
                                            'medium' => 'Sedang',
                                            'high' => 'Tinggi',
                                            'critical' => 'Kritis'
                                        ];
                                        $priorityClass = [
                                            'low' => 'priority-rendah',
                                            'medium' => 'priority-sedang',
                                            'high' => 'priority-tinggi',
                                            'critical' => 'priority-kritis'
                                        ];
                                        $statusMap = [
                                            'new' => 'Baru',
                                            'in_progress' => 'Diproses',
                                            'replied' => 'Ditanggapi',
                                            'resolved' => 'Selesai',
                                            'rejected' => 'Ditolak'
                                        ];
                                        $statusClass = [
                                            'new' => 'status-baru',
                                            'in_progress' => 'status-diproses',
                                            'replied' => 'status-ditanggapi',
                                            'resolved' => 'status-selesai',
                                            'rejected' => 'status-ditolak'
                                        ];
                                    @endphp
                                    <span
                                        class="badge-priority {{ $priorityClass[$report->priority] ?? 'priority-sedang' }} px-3 py-1 rounded-pill">
                                        {{ $priorityMap[$report->priority] ?? ucfirst($report->priority) }}
                                    </span>
                                    <span
                                        class="badge-status {{ $statusClass[$report->status] ?? 'status-baru' }} px-3 py-1 rounded-pill">
                                        {{ $statusMap[$report->status] ?? ($report->status === 'new' ? 'NEW' : ucfirst($report->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($report->description)
                            <div class="mt-5 pt-4" style="border-top: 1px solid #f0f0f0;">
                                <h6 class="small fw-semibold text-uppercase mb-3"
                                    style="color: #64748b; letter-spacing: 0.02em;">
                                    Deskripsi Laporan</h6>
                                <div class="p-4 rounded-4" style="background: #f8fafc; border: 1px solid #f0f0f0;">
                                    <p class="mb-0 lh-lg" style="color: #334155; white-space: pre-wrap; font-size: 1rem;">
                                        {{ $report->description }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Admin Response Card -->
                @if($report->admin_response)
                    <div class="card border-0 rounded-4 mb-4"
                        style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                        <div class="card-body p-4 p-xl-5">
                            <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                                Tanggapan Admin</h6>
                            <div class="p-4 rounded-4" style="background: #fff5f0; border: 1px solid #f1c3ae;">
                                <p class="mb-3 lh-lg" style="color: #1e2937; font-size: 1rem;">
                                    {{ $report->admin_response }}
                                </p>
                                @if($report->admin)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: white; border: 1px solid #f1c3ae;">
                                            <span class="fw-semibold small" style="color: #f8773c;">
                                                {{ substr($report->admin->name ?? 'A', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="fw-medium small">{{ $report->admin->name }}</span>
                                            @if($report->replied_at)
                                                <span class="text-muted small d-block">
                                                    {{ \Carbon\Carbon::parse($report->replied_at)->format('d M Y • H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Unit Information Card -->
                @if($report->unit)
                    <div class="card border-0 rounded-4 mb-4"
                        style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                        <div class="card-body p-4 p-xl-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-0"
                                    style="color: #64748b; letter-spacing: 0.02em;">
                                    Unit Terkait
                                </h6>

                                <div class="d-flex align-items-center gap-3">
                                    <!-- Badge Unit Name -->
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #f1c3ae, #f8773c); 
                             color: white; 
                             font-size: 0.85rem; 
                             font-weight: 500;
                             border-radius: 30px;
                             max-width: 250px;
                             white-space: nowrap;
                             overflow: hidden;
                             text-overflow: ellipsis;
                             box-shadow: 0 2px 4px rgba(248, 119, 60, 0.15);">
                                        <i class="bi bi-building me-1" style="font-size: 0.8rem;"></i>
                                        {{ Str::limit($report->unit->name ?? 'Tipe tidak diketahui', 30) }}
                                    </span>

                                    <!-- Detail Unit Link -->
                                    <a href="{{ route('admin.units.show', $report->unit_id) }}"
                                        class="text-decoration-none d-flex align-items-center gap-1 small fw-medium px-3 py-1 rounded-pill"
                                        style="background: #f8fafc;
                          color: #f8773c;
                          border: 1px solid #f1c3ae;
                          transition: all 0.2s ease;"
                                        onmouseover="this.style.background='#f8773c'; this.style.color='white'; this.style.borderColor='#f8773c'"
                                        onmouseout="this.style.background='#f8fafc'; this.style.color='#f8773c'; this.style.borderColor='#f1c3ae'">
                                        <span>Detail Unit</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            style="transition: transform 0.2s;">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="rounded-4 overflow-hidden d-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px; background: #fff5f0; border: 1px solid #f1c3ae;">
                                    @if($report->unit->foto_unit)
                                        <img src="{{ asset('storage/' . $report->unit->foto_unit) }}"
                                            alt="{{ $report->unit->nama_unit }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span class="fw-bold"
                                            style="color: #f8773c;">{{ substr($report->unit->nama_unit, 0, 2) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="fw-semibold mb-1" style="color: #1e2937;">{{ $report->unit->nama_unit }}</h5>
                                    <div class="d-flex flex-wrap gap-2 text-muted small">
                                        <span>{{ $report->unit->type->name ?? 'Tidak ada tipe' }}</span>
                                        <span>•</span>
                                        <span>{{ $report->unit->kode_unit }}</span>
                                        <span>•</span>
                                        <span class="{{ $report->unit->status_aktif ? 'text-success' : 'text-secondary' }}">
                                            {{ $report->unit->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($report->unit->kapasitas || $report->unit->jam_buka || $report->unit->kontak_telepon || $report->unit->kontak_email)
                                <div class="row g-3">
                                    @if($report->unit->kapasitas)
                                        <div class="col-6 col-md-3">
                                            <div class="d-flex flex-column">
                                                <span class="small text-muted">Kapasitas</span>
                                                <span class="fw-medium">{{ number_format($report->unit->kapasitas) }} orang</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($report->unit->jam_buka && $report->unit->jam_tutup)
                                        <div class="col-6 col-md-3">
                                            <div class="d-flex flex-column">
                                                <span class="small text-muted">Operasional</span>
                                                <span class="fw-medium">
                                                    {{ \Carbon\Carbon::parse($report->unit->jam_buka)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($report->unit->jam_tutup)->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($report->unit->kontak_telepon)
                                        <div class="col-6 col-md-3">
                                            <div class="d-flex flex-column">
                                                <span class="small text-muted">Telepon</span>
                                                <a href="tel:{{ $report->unit->kontak_telepon }}" class="fw-medium text-decoration-none"
                                                    style="color: #f8773c;">
                                                    {{ $report->unit->kontak_telepon }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    @if($report->unit->kontak_email)
                                        <div class="col-6 col-md-3">
                                            <div class="d-flex flex-column">
                                                <span class="small text-muted">Email</span>
                                                <a href="mailto:{{ $report->unit->kontak_email }}"
                                                    class="fw-medium text-decoration-none" style="color: #f8773c;">
                                                    {{ $report->unit->kontak_email }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($report->unit->deskripsi)
                                <div class="mt-3 pt-3 small text-muted" style="border-top: 1px dashed #e9ecef;">
                                    {{ Str::limit($report->unit->deskripsi, 100) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Timeline Card -->
                <div class="card border-0 rounded-4"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #f0f0f0;">
                    <div class="card-body p-4 p-xl-5">
                        <h6 class="small fw-semibold text-uppercase mb-4" style="color: #64748b; letter-spacing: 0.02em;">
                            Timeline Laporan</h6>

                        @php
                            $steps = [
                                [
                                    'key' => 'new',
                                    'label' => 'Laporan Masuk',
                                    'time' => $report->created_at,
                                    'color' => '#f8773c'
                                ],
                                [
                                    'key' => 'in_progress',
                                    'label' => 'Sedang Diproses',
                                    'time' => in_array($report->status, ['in_progress', 'replied', 'resolved', 'rejected']) ? $report->updated_at : null,
                                    'color' => '#f8773c'
                                ],
                                [
                                    'key' => 'replied',
                                    'label' => 'Ditanggapi',
                                    'time' => $report->replied_at,
                                    'color' => '#f8773c'
                                ],
                                [
                                    'key' => 'resolved',
                                    'label' => 'Selesai',
                                    'time' => $report->status === 'resolved' ? $report->updated_at : null,
                                    'color' => '#f8773c'
                                ]
                            ];
                            $currentFound = false;

                            // Hitung persentase untuk gradient line
                            $completedCount = 0;
                            foreach ($steps as $step) {
                                if ($step['time'] !== null)
                                    $completedCount++;
                            }
                            $gradientPercentage = $completedCount > 0 ? ($completedCount / count($steps)) * 100 : 0;
                        @endphp

                        <div class="position-relative" style="padding-left: 32px;">
                            <!-- Timeline Line dengan Gradient -->
                            <div
                                style="position: absolute; left: 15px; top: 12px; bottom: 12px; width: 2px; background: linear-gradient(to bottom, #f8773c 0%, #f8773c {{ $gradientPercentage }}%, #e9ecef {{ $gradientPercentage }}%, #e9ecef 100%);">
                            </div>

                            @foreach($steps as $index => $step)
                                @php
                                    $isCompleted = $step['time'] !== null;
                                    $isCurrent = !$currentFound && (
                                        ($step['key'] === $report->status) ||
                                        ($step['key'] === 'replied' && $report->admin_response && !in_array($report->status, ['resolved', 'rejected']))
                                    );
                                    if ($isCurrent)
                                        $currentFound = true;

                                    $dotColor = $isCompleted ? '#f8773c' : ($isCurrent ? '#f8773c' : '#94a3b8');
                                    $dotSize = $isCurrent ? '20px' : '16px';
                                    $dotBorder = $isCurrent ? '4px' : '2px';
                                @endphp

                                <div class="position-relative mb-4" style="z-index: 2;">
                                    <div
                                        style="position: absolute; left: -32px; top: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <div
                                            style="width: {{ $dotSize }}; height: {{ $dotSize }}; background-color: {{ $dotColor }}; border: {{ $dotBorder }} solid white; border-radius: 50%; box-shadow: 0 0 0 2px {{ $isCurrent ? 'rgba(248,119,60,0.2)' : 'rgba(148,163,184,0.2)' }}, 0 2px 4px rgba(0,0,0,0.1); {{ $isCurrent ? 'animation: pulse-report 2s infinite;' : '' }}">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium" style="color: #1e2937;">{{ $step['label'] }}</div>
                                        @if($step['time'])
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($step['time'])->format('d M Y • H:i') }}</div>
                                        @else
                                            <div class="small text-muted fst-italic">Menunggu</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar (4 col) -->
            <div class="col-lg-5 col-xl-4">
                <!-- Technical Details Card -->
                <div class="card border-0 rounded-4 mb-4"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                            Detail Teknis</h6>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">Tracking Code</span>
                                <code class="small fw-medium" style="color: #1e2937;">{{ $report->tracking_code }}</code>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">Rating Terkait</span>
                                <a href="{{ route('admin.ratings.show', $report->rating_id) }}"
                                    class="small fw-medium text-decoration-none" style="color: #f8773c;">
                                    Lihat Rating →
                                </a>
                            </div>

                            @if($report->student)
                                <div class="d-flex justify-content-between align-items-center py-2"
                                    style="border-bottom: 1px solid #f0f0f0;">
                                    <span class="small text-muted">Pelapor</span>
                                    <span class="small fw-medium"
                                        style="color: #1e2937;">{{ $report->student->name ?? 'Anonim' }}</span>
                                </div>
                            @endif

                            @if($report->admin)
                                <div class="d-flex justify-content-between align-items-center py-2"
                                    style="border-bottom: 1px solid #f0f0f0;">
                                    <span class="small text-muted">Handler</span>
                                    <span class="small fw-medium" style="color: #1e2937;">{{ $report->admin->name }}</span>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">Dibuat Pada</span>
                                <span class="small fw-medium"
                                    style="color: #1e2937;">{{ $report->created_at->format('d M Y H:i') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="small text-muted">Terakhir Diupdate</span>
                                <span class="small fw-medium"
                                    style="color: #1e2937;">{{ $report->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card border-0 rounded-4"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                            Actions</h6>

                        <div class="d-flex flex-column gap-2">
                            @if(in_array($report->status, ['new', 'in_progress']))
                                <button type="button"
                                    class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3 border-0"
                                    style="background: linear-gradient(135deg, #f8773c, #e55a2b); color: white; box-shadow: 0 4px 12px rgba(248,119,60,0.15);"
                                    data-bs-toggle="modal" data-bs-target="#replyModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span class="fw-medium">Beri Tanggapan</span>
                                </button>
                            @elseif($report->status === 'replied')
                                <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="resolved">
                                    <button type="submit"
                                        class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3 border-0"
                                        style="background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16,185,129,0.15);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        <span class="fw-medium">Tandai Selesai</span>
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.reports.edit', $report->id) }}"
                                class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3"
                                style="background: white; border: 1px solid #e9ecef; color: #64748b; font-size: 0.95rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path>
                                    <polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon>
                                </svg>
                                <span>Edit Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Action Bar -->
        @if(in_array($report->status, ['new', 'in_progress', 'replied']))
            <div class="mobile-action-bar">
                <div class="mobile-action-content">
                    @if(in_array($report->status, ['new', 'in_progress']))
                        <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal"
                            data-bs-target="#replyModal">
                            Beri Tanggapan
                        </button>
                    @elseif($report->status === 'replied')
                        <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                Tandai Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <!-- Reply Modal -->
        <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content"
                    style="border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">

                    <!-- Header - Gradient Orange dengan Icon Circle Putih -->
                    <div class="modal-header"
                        style="background: linear-gradient(135deg, #f1c3ae, #f8773c); padding: 1.5rem 2rem; border: none; position: relative;">
                        <!-- Icon Circle Putih -->
                        <div
                            style="position: absolute; left: 2rem; top: 50%; transform: translateY(-50%); width: 48px; height: 48px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="#f8773c" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </div>

                        <!-- Title -->
                        <div style="margin-left: 60px;">
                            <h3 class="modal-title fw-bold text-white" style="font-size: 1.25rem; letter-spacing: -0.01em;">
                                Beri Tanggapan
                            </h3>
                            <p class="text-white-50 mb-0 small" style="opacity: 0.9; margin-top: 0.25rem;">
                                Laporan akan langsung diteruskan ke pelapor
                            </p>
                        </div>

                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body" style="padding: 2rem; background: #f8fafc;">
                        <!-- Report Summary Card -->
                        <div
                            style="background: white; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #e9eef2; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                            <div class="d-flex gap-3">
                                <!-- Icon -->
                                <div
                                    style="width: 48px; height: 48px; background: #fff5f0; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #f8773c;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </div>

                                <!-- Report Info -->
                                <div style="flex: 1;">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge"
                                            style="background: #e9eef2; color: #475569; font-weight: 500; padding: 0.35rem 0.75rem; border-radius: 30px; font-size: 0.7rem;">
                                            {{ $report->created_at->format('d M Y') }}
                                        </span>
                                        <span class="badge"
                                            style="background: #fff5f0; color: #f8773c; font-weight: 500; padding: 0.35rem 0.75rem; border-radius: 30px; font-size: 0.7rem;">
                                            {{ $priorityMap[$report->priority] ?? ucfirst($report->priority) }}
                                        </span>
                                    </div>
                                    <h6 class="fw-semibold mb-0" style="color: #0f172a; font-size: 1rem;">
                                        {{ $report->title }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form -->
                        <form action="{{ route('admin.reports.reply', $report->id) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 1.5rem;">
                                <label for="tanggapan_admin"
                                    style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; letter-spacing: 0.02em;">
                                    TULIS TANGGAPAN
                                </label>
                                <textarea id="tanggapan_admin" name="tanggapan_admin" rows="5" required
                                    style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 14px; font-size: 0.95rem; line-height: 1.6; color: #0f172a; background: white; transition: all 0.2s; resize: vertical;"
                                    placeholder="Deskripsikan tindakan yang telah atau akan dilakukan...">{{ old('tanggapan_admin') }}</textarea>
                                <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #94a3b8; text-align: right;">
                                    <span id="charCount">0/5000</span>
                                </div>
                            </div>

                            <!-- Quick Responses -->
                            <div style="margin-bottom: 1rem;">
                                <label
                                    style="display: block; font-size: 0.8rem; font-weight: 500; color: #64748b; margin-bottom: 0.5rem;">
                                    Quick Responses
                                </label>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <button type="button" class="quick-response"
                                        data-response="Terima kasih atas laporannya. Kami akan segera menindaklanjuti."
                                        style="background: white; border: 1px solid #e2e8f0; border-radius: 30px; padding: 0.3rem 0.8rem; font-size: 0.75rem; color: #475569; transition: all 0.2s; cursor: pointer;">
                                        Akan ditindaklanjuti
                                    </button>
                                    <button type="button" class="quick-response"
                                        data-response="Laporan Anda sudah kami proses. Terima kasih."
                                        style="background: white; border: 1px solid #e2e8f0; border-radius: 30px; padding: 0.3rem 0.8rem; font-size: 0.75rem; color: #475569; transition: all 0.2s; cursor: pointer;">
                                        Sedang diproses
                                    </button>
                                    <button type="button" class="quick-response"
                                        data-response="Mohon maaf atas ketidaknyamanannya. Kami sudah mencatat laporan Anda."
                                        style="background: white; border: 1px solid #e2e8f0; border-radius: 30px; padding: 0.3rem 0.8rem; font-size: 0.75rem; color: #475569; transition: all 0.2s; cursor: pointer;">
                                        Permintaan maaf
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal"
                                    style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; color: #475569; font-weight: 500; font-size: 0.9rem;">
                                    Batal
                                </button>
                                <button type="submit" class="btn px-4 py-2"
                                    style="background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; border-radius: 12px; color: white; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 6px -1px rgba(248, 119, 60, 0.2);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem;">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                    Kirim Tanggapan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Quick response hover effects */
        .quick-response:hover {
            background: #f8773c !important;
            border-color: #f8773c !important;
            color: white !important;
            transform: translateY(-1px);
        }

        textarea:focus {
            outline: none;
            border-color: #f8773c !important;
            box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1);
        }

        /* Badge Styles */
        .badge-priority {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .priority-rendah {
            background-color: #f0fdf4;
            color: #16a34a;
        }

        .priority-sedang {
            background-color: #fefce8;
            color: #eab308;
        }

        .priority-tinggi {
            background-color: #fff5f0;
            color: #f8773c;
        }

        .priority-kritis {
            background-color: #fef2f2;
            color: #ef4444;
        }

        .badge-status {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .status-baru {
            background-color: #fff5f0;
            color: #f8773c;
        }

        .status-diproses {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .status-ditanggapi {
            background-color: #faf5ff;
            color: #9333ea;
        }

        .status-selesai {
            background-color: #f0fdf4;
            color: #16a34a;
        }

        .status-ditolak {
            background-color: #fef2f2;
            color: #ef4444;
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 0.75rem;
            min-width: 320px;
            border-left: 4px solid transparent;
            animation: slideIn 0.3s ease;
        }

        .toast-success {
            border-left-color: #10b981;
        }

        .toast-success .toast-icon {
            color: #10b981;
        }

        .toast-error {
            border-left-color: #ef4444;
        }

        .toast-error .toast-icon {
            color: #ef4444;
        }

        .toast-content {
            flex: 1;
        }

        .toast-content p {
            margin: 0;
            font-size: 0.875rem;
            color: #1e293b;
        }

        .toast-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .toast-close:hover {
            background: #f1f5f9;
            color: #475569;
        }

        /* Mobile Action Bar */
        .mobile-action-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }

        .mobile-action-content {
            max-width: 600px;
            margin: 0 auto;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse-report {
            0% {
                box-shadow: 0 0 0 0 rgba(248, 119, 60, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(248, 119, 60, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(248, 119, 60, 0);
            }
        }

        /* Navigation */
        .btn-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 2rem;
        }

        .btn-back-nav:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
            transform: translateX(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .report-detail-container {
                padding: 1rem 1rem 100px 1rem;
            }

            .mobile-action-bar {
                display: block;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Character counter
            const textarea = document.getElementById('tanggapan_admin');
            const charCount = document.getElementById('charCount');

            if (textarea && charCount) {
                textarea.addEventListener('input', function () {
                    const count = this.value.length;
                    charCount.textContent = `${count}/5000`;

                    if (count > 4500) {
                        charCount.style.color = '#eab308';
                    } else if (count > 4800) {
                        charCount.style.color = '#ef4444';
                    } else {
                        charCount.style.color = '#94a3b8';
                    }
                });
            }

            // Quick responses
            document.querySelectorAll('.quick-response').forEach(btn => {
                btn.addEventListener('click', function () {
                    const response = this.dataset.response;
                    if (textarea) {
                        textarea.value = response;
                        textarea.dispatchEvent(new Event('input'));
                    }
                });
            });

            // Toasts
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.animation = 'slideOut 0.3s ease';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });
        });
    </script>
@endsection