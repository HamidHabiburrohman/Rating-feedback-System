@extends('layouts.admin.app')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <a href="{{ route('admin.ratings.index') }}"
                        class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; border-color: #d1d5db;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Detail Rating</h1>
                </div>
                <p class="text-muted mb-0">Tinjau feedback pengunjung secara detail</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-2 fw-medium
                              @if($rating->status == 'selesai') bg-success
                              @elseif($rating->status == 'dibalas') bg-warning text-dark
                              @else bg-danger @endif">
                    {{ ucfirst($rating->status) }}
                </span>
                <span class="text-muted">ID: #{{ str_pad($rating->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert"
                style="background-color: #f0fdf4; border-color: #bbf7d0; color: #15803d;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" class="me-2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert"
                style="background-color: #fef2f2; border-color: #fecaca; color: #dc2626;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" class="me-2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Hero Section - Fokus Utama -->
        <div class="card border shadow-sm rounded-4 mb-4"
            style="border-color: #e5e7eb !important; border-left: 4px solid #3b82f6;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="row align-items-start">

                            <!-- Kolom Foto -->
                            <div class="col-md-3 mb-3 mb-md-0 d-flex align-items-center justify-content-center">
                                <div class="rounded-4 overflow-hidden border position-relative"
                                    style="width: 150px; height: 150px; border-color: #e5e7eb !important; background-color: #f9fafb;">
                                    @if($rating->unit && $rating->unit->foto_unit)
                                        <img src="{{ asset('storage/' . $rating->unit->foto_unit) }}"
                                            alt="{{ $rating->unit->nama_unit ?? 'Unit Photo' }}"
                                            class="w-100 h-100 object-fit-cover"
                                            onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDI0IDI4IiBmaWxsPSJub25lIiBzdHJva2U9IiM5Y2EzYWYiIHN0cm9rZS13aWR0aD0iMS41Ij48cmVjdCB4PSIzIiB5PSIzIiB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHJ4PSIyIiByeT0iMiIvPjxjaXJjbGUgY3g9IjguNSIgY3k9IjguNSIgcj0iMS41Ii8+PHBvbHlsaW5lIHBvaW50cz0iMjEgMTUgMTYgMTAgNSAyMSIvPjwvc3ZnPg=='">
                                    @else
                                        <div
                                            class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                                fill="none" stroke="#9ca3af" stroke-width="1.5" class="mb-1">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <polyline points="21 15 16 10 5 21" />
                                            </svg>
                                            <div class="text-muted small" style="font-size: 0.65rem; line-height: 1.1;">
                                                {{ $rating->unit->nama_unit ?? 'No Photo' }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Kolom Informasi Unit -->
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="#6b7280" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <line x1="3" y1="9" x2="21" y2="9" />
                                            <line x1="9" y1="21" x2="9" y2="9" />
                                        </svg>
                                        <h6 class="fw-semibold mb-0" style="color: #374151;">Unit</h6>
                                    </div>
                                    <h3 class="h4 fw-bold mb-1" style="color: #1a1a1a;">
                                        {{ $rating->unit->nama_unit ?? 'N/A' }}
                                    </h3>
                                    <div class="d-flex align-items-center gap-3">
                                        <p class="text-muted mb-0">Kode: {{ $rating->unit->kode_unit ?? 'N/A' }}</p>
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill small"
                                            style="border-color: #e5e7eb !important; font-size: 0.75rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                class="me-1">
                                                <path
                                                    d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z" />
                                            </svg>
                                            {{ number_format($rating->unit->avg_rating ?? 0, 1) }}/5
                                        </span>
                                    </div>
                                </div>

                                <!-- Visual Ratings -->
                                @php
                                    // Parse metadata untuk rating detail
                                    $metadata = is_string($rating->metadata) ? json_decode($rating->metadata, true) : $rating->metadata;
                                @endphp

                                @if(is_array($metadata) && isset($metadata['ratings']))
                                    <div class="mb-4">
                                        <h6 class="fw-semibold mb-3" style="color: #374151;">Detail Rating</h6>
                                        <div class="row g-3">
                                            @foreach($metadata['ratings'] as $key => $value)
                                                @if(is_numeric($value))
                                                    <div class="col-md-4">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="text-muted" style="font-size: 0.875rem;">
                                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                            </span>
                                                            <span class="fw-bold">{{ number_format($value, 1) }}/5</span>
                                                        </div>
                                                        <div class="rating-visual">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    viewBox="0 0 24 24" fill="{{ $i <= $value ? '#f59e0b' : '#e5e7eb' }}"
                                                                    stroke="{{ $i <= $value ? '#f59e0b' : '#e5e7eb' }}" stroke-width="1">
                                                                    <polygon
                                                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Visitor Quick Info -->
                        <div class="p-3 rounded-3 border"
                            style="border-color: #e5e7eb !important; background-color: #f9fafb;">
                            <div class="text-muted mb-2" style="font-size: 0.875rem;">Pengunjung</div>
                            <div class="fw-medium mb-1" style="color: #111827;">
                                {{ substr($rating->session_id, 0, 12) }}...
                            </div>
                            <div class="text-muted small">{{ $rating->visitor_ip }}</div>

                            <div class="mt-3 pt-3 border-top">
                                <div class="text-muted mb-1" style="font-size: 0.875rem;">Dibuat</div>
                                <div class="fw-medium">{{ $rating->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Kolom Kiri - Konten Utama -->
            <div class="col-lg-10">
                
                <!-- Komentar Pengunjung -->
                <div class="card border shadow-sm rounded-4 mb-4" style="border-color: #e5e7eb !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="p-2 rounded-circle" style="background-color: #eff6ff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#3b82f6" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-0" style="color: #1a1a1a;">Komentar Pengunjung</h6>
                        </div>

                        <div class="p-4 rounded-3" style="background-color: #f9fafb; border-left: 4px solid #3b82f6;">
                            @if($rating->komentar)
                                <p class="mb-0 fst-italic" style="color: #374151; line-height: 1.6;">
                                    "{{ $rating->komentar }}"
                                </p>
                            @else
                                <p class="mb-0 text-muted">
                                    Tidak ada komentar dari pengunjung
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informasi Teknis
                <div class="card border shadow-sm rounded-4 mb-4" style="border-color: #e5e7eb !important;">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2" style="color: #1a1a1a;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            Informasi Teknis
                        </h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border" style="border-color: #e5e7eb !important;">
                                    <div class="text-muted mb-1" style="font-size: 0.875rem;">Session ID</div>
                                    <div class="fw-medium" style="font-size: 0.875rem; word-break: break-all;">
                                        {{ $rating->session_id }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border" style="border-color: #e5e7eb !important;">
                                    <div class="text-muted mb-1" style="font-size: 0.875rem;">IP Address</div>
                                    <div class="fw-medium">{{ $rating->visitor_ip }}</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border" style="border-color: #e5e7eb !important;">
                                    <div class="text-muted mb-1" style="font-size: 0.875rem;">User Agent</div>
                                    <div class="fw-medium small">{{ substr($rating->user_agent ?? 'N/A', 0, 50) }}...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Timeline Aktivitas -->
                <div class="card border shadow-sm rounded-4 mb-4" style="border-color: #e5e7eb !important;">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2" style="color: #1a1a1a;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            Timeline Aktivitas
                        </h6>

                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot bg-primary"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Rating dibuat</div>
                                    <div class="timeline-time">{{ $rating->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>

                            @if($rating->dibalas_pada)
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-warning"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">Status diubah menjadi "Dibalas"</div>
                                        <div class="timeline-time">
                                            {{ \Carbon\Carbon::parse($rating->dibalas_pada)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($rating->status == 'selesai')
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-success"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">Rating diselesaikan</div>
                                        <div class="timeline-time">{{ $rating->updated_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan - Aksi & Metadata -->
            <div class="col-lg-2">
                <!-- Card 1: Aksi Rating -->
                <div class="card border shadow-sm rounded-4 mb-3" style="border-color: #e5e7eb !important;">
                    <div class="card-body p-3">
                        <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2"
                            style="color: #1a1a1a; font-size: 0.875rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M12 19l7-7 3 3-7 7-3-3z" />
                                <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                            </svg>
                            Aksi Rating
                        </h6>

                        <div class="d-grid gap-2">
                            <!-- Balas Komentar - Primary Action -->
                            <button type="button"
                                class="btn btn-primary w-100 rounded-pill d-flex align-items-center justify-content-center gap-1 py-1 px-2"
                                data-bs-toggle="modal" data-bs-target="#replyModal"
                                style="font-size: 0.75rem; min-height: 32px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                                <span>Balas</span>
                            </button>

                            <!-- Status Actions -->
                            @if($rating->status === 'pending')
                                <form action="{{ route('admin.ratings.update-status', $rating->id) }}" method="POST"
                                    class="mb-0">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="dibalas">
                                    <button type="submit"
                                        class="btn btn-warning w-100 rounded-pill d-flex align-items-center justify-content-center gap-1 py-1 px-2"
                                        style="font-size: 0.75rem; min-height: 32px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                        <span>Dibalas</span>
                                    </button>
                                </form>
                            @endif

                            @if($rating->status === 'dibalas')
                                <form action="{{ route('admin.ratings.update-status', $rating->id) }}" method="POST"
                                    class="mb-0">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit"
                                        class="btn btn-success w-100 rounded-pill d-flex align-items-center justify-content-center gap-1 py-1 px-2"
                                        style="font-size: 0.75rem; min-height: 32px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="m9 12 2 2 4-4" />
                                        </svg>
                                        <span>Selesai</span>
                                    </button>
                                </form>
                            @endif

                            <!-- Edit Rating - Secondary Action -->
                            <a href="{{ route('admin.ratings.edit', $rating->id) }}"
                                class="btn btn-outline-secondary w-100 rounded-pill d-flex align-items-center justify-content-center gap-1 py-1 px-2"
                                style="font-size: 0.75rem; min-height: 32px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                                <span>Edit</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Data Tambahan -->
                @if($metadata && is_array($metadata) && count($metadata) > 0 && isset($metadata['ratings']))
                    <div class="card border shadow-sm rounded-4 mb-3" style="border-color: #e5e7eb !important;">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2"
                                style="color: #1a1a1a; font-size: 0.875rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z" />
                                    <line x1="4" y1="22" x2="4" y2="15" />
                                </svg>
                                Data Tambahan
                            </h6>

                            <div class="space-y-1">
                                @php
                                    $additionalCount = 0;
                                @endphp

                                @foreach($metadata as $key => $value)
                                    @if($key !== 'ratings' && !is_array($value) && $additionalCount < 3)
                                        @php $additionalCount++; @endphp
                                        <div class="d-flex justify-content-between align-items-center py-1">
                                            <span class="text-muted" style="font-size: 0.7rem;">
                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                            </span>
                                            <span class="fw-medium" style="font-size: 0.75rem;">{{ $value }}</span>
                                        </div>
                                    @endif
                                @endforeach

                                @if($additionalCount === 0)
                                    <div class="text-center py-2">
                                        <span class="text-muted" style="font-size: 0.75rem;">Tidak ada data</span>
                                    </div>
                                @endif
                            </div>

                            @php
                                $totalAdditional = 0;
                                foreach ($metadata as $key => $value) {
                                    if ($key !== 'ratings' && !is_array($value)) {
                                        $totalAdditional++;
                                    }
                                }
                            @endphp

                            @if($totalAdditional > 3)
                                <div class="text-center mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-0"
                                        style="font-size: 0.65rem;" data-bs-toggle="modal" data-bs-target="#metadataModal">
                                        +{{ $totalAdditional - 3 }} lainnya
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Kembali ke Daftar -->
                <div class="d-grid">
                    <a href="{{ route('admin.ratings.index') }}"
                        class="btn btn-outline-light w-100 rounded-pill d-flex align-items-center justify-content-center gap-1 py-1 px-2"
                        style="border-color: #e5e7eb; color: #6b7280; font-size: 0.75rem; min-height: 32px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Modal untuk Metadata Lengkap -->
            @if($metadata && is_array($metadata) && count($metadata) > 0)
                <div class="modal fade" id="metadataModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content border-0 shadow-lg rounded-3" style="border: 1px solid #e5e7eb !important;">
                            <div class="modal-header border-bottom-0 pb-0">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 rounded-circle" style="background-color: #eff6ff;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="#3b82f6" stroke-width="2">
                                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z" />
                                            <line x1="4" y1="22" x2="4" y2="15" />
                                        </svg>
                                    </div>
                                    <h5 class="modal-title fw-semibold" style="color: #1a1a1a; font-size: 1rem;">Data Tambahan
                                        Lengkap</h5>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <div class="space-y-2">
                                    @foreach($metadata as $key => $value)
                                        @if($key !== 'ratings' && !is_array($value))
                                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom"
                                                style="border-color: #f3f4f6 !important;">
                                                <span class="text-muted" style="font-size: 0.875rem;">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                </span>
                                                <span class="fw-medium text-end" style="font-size: 0.875rem;">{{ $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Balas Komentar -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3" style="border: 1px solid #e5e7eb !important;">
                <div class="modal-header border-bottom-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-circle" style="background-color: #eff6ff;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="#3b82f6" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                        </div>
                        <h5 class="modal-title fw-semibold" style="color: #1a1a1a;">Balas Komentar</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="{{ route('admin.ratings.reply', $rating->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium mb-2">Komentar Pengunjung</label>
                            <div class="p-3 rounded-3 mb-3" style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                                <p class="mb-0">{{ $rating->komentar ?: 'Tidak ada komentar' }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reply_message" class="form-label fw-medium mb-2">Balasan Anda</label>
                            <textarea class="form-control rounded-3" id="reply_message" name="reply_message" rows="4"
                                placeholder="Tulis balasan Anda di sini..." style="border: 1px solid #e5e7eb;"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                data-bs-dismiss="modal" style="border-color: #d1d5db;">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Kirim Balasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection