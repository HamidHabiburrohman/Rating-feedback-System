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

    <main class="modlog-detail-container">

        {{-- Navigation --}}
        <div class="nav-wrapper">
            <a href="{{ route('admin.moderation-logs.index') }}" class="btn-back-nav">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Daftar Log</span>
            </a>
        </div>

        @php
            /* ─── Action Label & Styling Maps ─── */
            $actionLabelMap = [
                'ban_user'          => 'Ban User',
                'unban_user'        => 'Unban User',
                'delete_review'     => 'Hapus Review',
                'restore_review'    => 'Pulihkan Review',
                'delete_report'     => 'Hapus Laporan',
                'close_report'      => 'Tutup Laporan',
                'warn_user'         => 'Peringatkan User',
                'approve_content'   => 'Setujui Konten',
                'reject_content'    => 'Tolak Konten',
                'flag_content'      => 'Tandai Konten',
                'edit_content'      => 'Edit Konten',
            ];

            $actionColorMap = [
                'ban_user'          => ['bg' => '#fef2f2', 'color' => '#dc2626', 'border' => '#fecaca', 'icon_bg' => '#fee2e2'],
                'unban_user'        => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '#bbf7d0', 'icon_bg' => '#dcfce7'],
                'delete_review'     => ['bg' => '#fef2f2', 'color' => '#dc2626', 'border' => '#fecaca', 'icon_bg' => '#fee2e2'],
                'restore_review'    => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '#bbf7d0', 'icon_bg' => '#dcfce7'],
                'delete_report'     => ['bg' => '#fef2f2', 'color' => '#dc2626', 'border' => '#fecaca', 'icon_bg' => '#fee2e2'],
                'close_report'      => ['bg' => '#eff6ff', 'color' => '#2563eb', 'border' => '#bfdbfe', 'icon_bg' => '#dbeafe'],
                'warn_user'         => ['bg' => '#fefce8', 'color' => '#ca8a04', 'border' => '#fde68a', 'icon_bg' => '#fef9c3'],
                'approve_content'   => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '#bbf7d0', 'icon_bg' => '#dcfce7'],
                'reject_content'    => ['bg' => '#fef2f2', 'color' => '#dc2626', 'border' => '#fecaca', 'icon_bg' => '#fee2e2'],
                'flag_content'      => ['bg' => '#fff7ed', 'color' => '#ea580c', 'border' => '#fed7aa', 'icon_bg' => '#ffedd5'],
                'edit_content'      => ['bg' => '#f5f3ff', 'color' => '#7c3aed', 'border' => '#ddd6fe', 'icon_bg' => '#ede9fe'],
            ];

            $targetLabelMap = [
                'User'          => 'User',
                'Review'        => 'Review',
                'Report'        => 'Laporan',
                'Rating'        => 'Rating',
                'Unit'          => 'Unit',
                'Comment'       => 'Komentar',
            ];

            $actionLabel  = $actionLabelMap[$log->action]  ?? ucwords(str_replace('_', ' ', $log->action));
            $actionStyle  = $actionColorMap[$log->action]  ?? ['bg' => '#f8fafc', 'color' => '#64748b', 'border' => '#e2e8f0', 'icon_bg' => '#f1f5f9'];
            $targetLabel  = $targetLabelMap[$log->target_type] ?? ($log->target_type ?? '—');
            $target       = $log->target;
        @endphp

        <div class="row g-4 g-lg-5">

            {{-- ══════════════════════════ LEFT COLUMN ══════════════════════════ --}}
            <div class="col-lg-7 col-xl-8">

                {{-- ── Header Card ── --}}
                <div class="card border-0 rounded-4 mb-4 modlog-card">
                    <div class="card-body p-4 p-xl-5">
                        <div class="row g-4 align-items-start">

                            {{-- Action Icon --}}
                            <div class="col-md-auto">
                                <div class="rounded-4 d-flex align-items-center justify-content-center"
                                    style="width:96px;height:96px;background:{{ $actionStyle['icon_bg'] }};border:1px solid {{ $actionStyle['border'] }};">
                                    @switch($log->action)
                                        @case('ban_user')
                                        @case('warn_user')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <circle cx="12" cy="7" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/>
                                                <line x1="4" y1="4" x2="20" y2="20" stroke-width="2"/>
                                            </svg>
                                            @break
                                        @case('unban_user')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <circle cx="12" cy="7" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/>
                                                <polyline points="7 13 10 16 17 9"/>
                                            </svg>
                                            @break
                                        @case('delete_review')
                                        @case('delete_report')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                                                <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                                            </svg>
                                            @break
                                        @case('restore_review')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                                <path d="M3 3v5h5"/>
                                            </svg>
                                            @break
                                        @case('approve_content')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                                            </svg>
                                            @break
                                        @case('reject_content')
                                        @case('close_report')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                            </svg>
                                            @break
                                        @case('flag_content')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>
                                            </svg>
                                            @break
                                        @case('edit_content')
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            @break
                                        @default
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="{{ $actionStyle['color'] }}" stroke-width="1.5">
                                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                            </svg>
                                    @endswitch
                                </div>
                            </div>

                            {{-- Title + Meta --}}
                            <div class="col">
                                <div class="d-flex flex-column gap-2">
                                    <h2 class="h4 fw-semibold mb-0" style="color:#1e2937;letter-spacing:-0.01em;">
                                        {{ $actionLabel }}
                                    </h2>
                                    <div class="d-flex flex-wrap gap-3 text-muted small">
                                        {{-- <span class="d-flex align-items-center gap-1">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            {{ $log->created_at->format('d M Y • H:i') }}
                                        </span> --}}
                                        <span class="d-flex align-items-center gap-1">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            {{ Str::limit($log->admin->nama,5) ?? 'admin' }}
                                        </span>
                                        @if($log->target_type)
                                            <span class="d-flex align-items-center gap-1">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                    <line x1="3" y1="9" x2="21" y2="9"/>
                                                </svg>
                                                Target: {{ $targetLabel }}
                                                @if($log->target_id)
                                                    #{{ $log->target_id }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Action Badge --}}
                            <div class="col-md-auto text-md-end mt-3 mt-md-0">
                                <span class="px-3 py-2 rounded-pill fw-semibold small"
                                    style="background:{{ $actionStyle['bg'] }};color:{{ $actionStyle['color'] }};border:1px solid {{ $actionStyle['border'] }};letter-spacing:0.02em;">
                                    {{ $actionLabel }}
                                </span>
                            </div>
                        </div>

                        {{-- Reason --}}
                        @if($log->reason)
                            <div class="mt-5 pt-4" style="border-top:1px solid #f0f0f0;">
                                <h6 class="small fw-semibold text-uppercase mb-3" style="color:#64748b;letter-spacing:0.06em;">
                                    Alasan Moderasi
                                </h6>
                                <div class="p-4 rounded-4" style="background:{{ $actionStyle['bg'] }};border:1px solid {{ $actionStyle['border'] }};">
                                    <p class="mb-0 lh-lg" style="color:#334155;white-space:pre-wrap;font-size:1rem;">
                                        {{ $log->reason }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── Target Object Card ── --}}
                @if($log->target_type && $log->target_id)
                    <div class="card border-0 rounded-4 mb-4 modlog-card">
                        <div class="card-body p-4 p-xl-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-0" style="color:#64748b;letter-spacing:0.06em;">
                                    Objek Target
                                </h6>
                                <span class="badge px-3 py-2 rounded-pill"
                                    style="background:{{ $actionStyle['bg'] }};color:{{ $actionStyle['color'] }};border:1px solid {{ $actionStyle['border'] }};font-size:0.8rem;font-weight:500;">
                                    {{ $targetLabel }}
                                </span>
                            </div>

                            @if($target)
                                <div class="target-detail-grid">
                                    @switch($log->target_type)

                                        @case('User')
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                    style="width:56px;height:56px;background:{{ $actionStyle['icon_bg'] }};color:{{ $actionStyle['color'] }};font-size:1.25rem;flex-shrink:0;">
                                                    {{ strtoupper(substr($target->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold" style="color:#1e2937;">{{ $target->name ?? '—' }}</div>
                                                    <div class="small text-muted">{{ $target->email ?? '—' }}</div>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-1">
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">ID User</span>
                                                    <span class="fw-medium small">#{{ $target->id }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Bergabung</span>
                                                    <span class="fw-medium small">{{ $target->created_at ? $target->created_at->format('d M Y') : '—' }}</span>
                                                </div>
                                            </div>
                                            @break

                                        @case('Report')
                                            <div class="fw-semibold mb-2" style="color:#1e2937;">{{ $target->title ?? 'Laporan #'.$target->id }}</div>
                                            @if($target->description)
                                                <p class="small text-muted mb-3" style="white-space:pre-wrap;">{{ Str::limit($target->description, 200) }}</p>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Status</span>
                                                    <span class="fw-medium small">{{ $target->status ?? '—' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Dibuat</span>
                                                    <span class="fw-medium small">{{ $target->created_at ? $target->created_at->format('d M Y') : '—' }}</span>
                                                </div>
                                            </div>
                                            @break

                                        @case('Review')
                                        @case('Rating')
                                            @if(isset($target->comment) || isset($target->body))
                                                <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;border:1px solid #f0f0f0;">
                                                    <p class="mb-0 small lh-lg" style="color:#334155;">
                                                        {{ Str::limit($target->comment ?? $target->body ?? '—', 250) }}
                                                    </p>
                                                </div>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">ID</span>
                                                    <span class="fw-medium small">#{{ $target->id }}</span>
                                                </div>
                                                @if(isset($target->rating) || isset($target->score))
                                                    <div class="col-6">
                                                        <span class="small text-muted d-block">Rating</span>
                                                        <span class="fw-medium small">
                                                            @php $score = $target->rating ?? $target->score ?? null; @endphp
                                                            @if($score)
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <span style="color:{{ $i <= $score ? '#f59e0b' : '#e2e8f0' }};">★</span>
                                                                @endfor
                                                            @else
                                                                —
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            @break

                                        @default
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f0f0f0;">
                                                    <span class="small text-muted">Tipe Target</span>
                                                    <span class="small fw-medium">{{ $targetLabel }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between py-2">
                                                    <span class="small text-muted">ID Target</span>
                                                    <code class="small fw-medium" style="color:#1e2937;">#{{ $log->target_id }}</code>
                                                </div>
                                            </div>
                                    @endswitch
                                </div>

                                {{-- Link to Detail --}}
                                @php
                                    $detailRoutes = [
                                        'User'   => 'admin.users.show',
                                        'Report' => 'admin.reports.show',
                                        'Rating' => 'admin.ratings.show',
                                        'Unit'   => 'admin.units.show',
                                    ];
                                    $detailRoute = $detailRoutes[$log->target_type] ?? null;
                                @endphp
                                @if($detailRoute && \Route::has($detailRoute))
                                    <div class="mt-4 pt-3" style="border-top:1px dashed #e9ecef;">
                                        <a href="{{ route($detailRoute, $log->target_id) }}"
                                            class="d-inline-flex align-items-center gap-1 small fw-medium text-decoration-none px-3 py-2 rounded-pill"
                                            style="background:#f8fafc;color:#f8773c;border:1px solid #f1c3ae;transition:all 0.2s;"
                                            onmouseover="this.style.background='#f8773c';this.style.color='white';this.style.borderColor='#f8773c'"
                                            onmouseout="this.style.background='#f8fafc';this.style.color='#f8773c';this.style.borderColor='#f1c3ae'">
                                            <span>Lihat Detail {{ $targetLabel }}</span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                            @else
                                {{-- Target deleted / not found --}}
                                <div class="d-flex align-items-center gap-3 p-4 rounded-4"
                                    style="background:#fef2f2;border:1px solid #fecaca;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    <div>
                                        <div class="fw-medium small" style="color:#dc2626;">Objek Tidak Ditemukan</div>
                                        <div class="text-muted small">{{ $targetLabel }} #{{ $log->target_id }} sudah dihapus atau tidak tersedia.</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ── Metadata Card ── --}}
                @if($log->metadata && count($log->metadata) > 0)
                    <div class="card border-0 rounded-4 modlog-card">
                        <div class="card-body p-4 p-xl-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-0" style="color:#64748b;letter-spacing:0.06em;">
                                    Metadata
                                </h6>
                                <span class="badge rounded-pill" style="background:#f8fafc;color:#94a3b8;font-size:0.7rem;border:1px solid #e9ecef;">
                                    {{ count($log->metadata) }} item
                                </span>
                            </div>

                            <div class="metadata-grid">
                                @foreach($log->metadata as $key => $value)
                                    <div class="metadata-item d-flex justify-content-between align-items-start py-2 px-3 rounded-3 mb-2"
                                        style="background:#f8fafc;border:1px solid #f0f0f0;">
                                        <span class="small text-muted fw-medium text-break me-3" style="min-width:120px;">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </span>
                                        <span class="small fw-medium text-end" style="color:#1e2937;word-break:break-all;">
                                            @if(is_array($value))
                                                <code style="font-size:0.75rem;color:#7c3aed;background:#f5f3ff;padding:2px 6px;border-radius:4px;">
                                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                                </code>
                                            @elseif(is_bool($value))
                                                <span class="badge rounded-pill" style="background:{{ $value ? '#dcfce7' : '#fee2e2' }};color:{{ $value ? '#16a34a' : '#dc2626' }};">
                                                    {{ $value ? 'true' : 'false' }}
                                                </span>
                                            @elseif(is_null($value))
                                                <span class="text-muted fst-italic">null</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Raw JSON Toggle --}}
                            <div class="mt-3">
                                <button type="button" class="btn-raw-toggle d-flex align-items-center gap-1 small"
                                    style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:0;"
                                    onclick="toggleRawJson()">
                                    <svg id="rawJsonChevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition:transform 0.2s;">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                    <span>Lihat Raw JSON</span>
                                </button>
                                <div id="rawJsonBlock" style="display:none;margin-top:0.75rem;">
                                    <pre class="rounded-3 p-3 mb-0 small"
                                        style="background:#0f172a;color:#94a3b8;overflow-x:auto;white-space:pre-wrap;word-break:break-all;border:1px solid #1e293b;font-size:0.75rem;">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ══════════════════════════ RIGHT COLUMN ══════════════════════════ --}}
            <div class="col-lg-5 col-xl-4">

                {{-- ── Technical Details ── --}}
                <div class="card border-0 rounded-4 mb-4 modlog-card">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color:#64748b;letter-spacing:0.06em;">
                            Detail Teknis
                        </h6>

                        <div class="d-flex flex-column gap-0">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f0f0f0;">
                                <span class="small text-muted">Log ID</span>
                                <code class="small fw-medium" style="color:#1e2937;">#{{ $log->id }}</code>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f0f0f0;">
                                <span class="small text-muted">Aksi</span>
                                <code class="small fw-medium" style="color:#7c3aed;background:#f5f3ff;padding:2px 8px;border-radius:4px;">{{ $log->action }}</code>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f0f0f0;">
                                <span class="small text-muted">Target Type</span>
                                <span class="small fw-medium" style="color:#1e2937;">{{ $log->target_type ?? '—' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f0f0f0;">
                                <span class="small text-muted">Target ID</span>
                                <span class="small fw-medium" style="color:#1e2937;">{{ $log->target_id ?? '—' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f0f0f0;">
                                <span class="small text-muted">Dilakukan Oleh</span>
                                <span class="small fw-medium" style="color:#1e2937;">{{ $log->admin->name ?? 'System' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f0f0f0;">
                                <span class="small text-muted">Tanggal & Waktu</span>
                                <span class="small fw-medium" style="color:#1e2937;">{{ $log->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="small text-muted">Terakhir Diupdate</span>
                                <span class="small fw-medium" style="color:#1e2937;">{{ $log->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Admin Info Card ── --}}
                @if($log->admin)
                    <div class="card border-0 rounded-4 mb-4 modlog-card">
                        <div class="card-body p-4">
                            <h6 class="small fw-semibold text-uppercase mb-3" style="color:#64748b;letter-spacing:0.06em;">
                                Admin Pelaksana
                            </h6>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                    style="width:48px;height:48px;background:linear-gradient(135deg,#f1c3ae,#f8773c);color:white;font-size:1.1rem;flex-shrink:0;">
                                    {{ strtoupper(substr($log->admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="color:#1e2937;">{{ $log->admin->name }}</div>
                                    <div class="small text-muted">{{ $log->admin->email ?? '—' }}</div>
                                    <div class="small mt-1">
                                        <span class="badge rounded-pill" style="background:#fff5f0;color:#f8773c;font-size:0.7rem;padding:3px 8px;">
                                            Admin
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Admin's recent logs --}}
                            @php
                                $recentByAdmin = \App\Models\ModerationLog::where('admin_id', $log->admin_id)
                                    ->where('id', '!=', $log->id)
                                    ->latest()
                                    ->limit(3)
                                    ->get();
                            @endphp
                            @if($recentByAdmin->count() > 0)
                                <div class="mt-3 pt-3" style="border-top:1px dashed #e9ecef;">
                                    <div class="small text-muted mb-2">Aksi terbaru admin ini:</div>
                                    @foreach($recentByAdmin as $recent)
                                        @php
                                            $rLabel = $actionLabelMap[$recent->action] ?? ucwords(str_replace('_', ' ', $recent->action));
                                            $rStyle = $actionColorMap[$recent->action] ?? ['color' => '#64748b', 'bg' => '#f8fafc', 'border' => '#e2e8f0'];
                                        @endphp
                                        <a href="{{ route('admin.moderation-logs.show', $recent->id) }}"
                                            class="d-flex align-items-center gap-2 py-2 text-decoration-none recent-log-item"
                                            style="border-bottom:1px solid #f8fafc;transition:all 0.15s;">
                                            <span class="shrink-0" style="width:8px;height:8px;border-radius:50%;background:{{ $rStyle['color'] }};"></span>
                                            <span class="small" style="color:#334155;flex:1;">{{ $rLabel }}</span>
                                            <span class="small text-muted">{{ $recent->created_at->diffForHumans() }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ── Quick Navigation ── --}}
                <div class="card border-0 rounded-4 modlog-card">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color:#64748b;letter-spacing:0.06em;">
                            Navigasi Cepat
                        </h6>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('admin.moderation-logs.index') }}"
                                class="btn d-flex align-items-center gap-2 w-100 py-2 rounded-3 text-start"
                                style="background:#f8fafc;border:1px solid #e9ecef;color:#64748b;font-size:0.9rem;text-decoration:none;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                                    <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                                </svg>
                                <span>Semua Log Moderasi</span>
                            </a>

                            @if($log->admin_id)
                                <a href="{{ route('admin.moderation-logs.index', ['admin_id' => $log->admin_id]) }}"
                                    class="btn d-flex align-items-center gap-2 w-100 py-2 rounded-3 text-start"
                                    style="background:#f8fafc;border:1px solid #e9ecef;color:#64748b;font-size:0.9rem;text-decoration:none;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <span>Log oleh {{ Str::words($log->admin->name ?? 'Admin ini', 2, '') }}</span>
                                </a>
                            @endif

                            @if($log->target_type)
                                <a href="{{ route('admin.moderation-logs.index', ['target_type' => $log->target_type]) }}"
                                    class="btn d-flex align-items-center gap-2 w-100 py-2 rounded-3 text-start"
                                    style="background:#f8fafc;border:1px solid #e9ecef;color:#64748b;font-size:0.9rem;text-decoration:none;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                    <span>Log Target: {{ $targetLabel }}</span>
                                </a>
                            @endif

                            <a href="{{ route('admin.moderation-logs.index', ['action' => $log->action]) }}"
                                class="btn d-flex align-items-center gap-2 w-100 py-2 rounded-3 text-start"
                                style="background:#f8fafc;border:1px solid #e9ecef;color:#64748b;font-size:0.9rem;text-decoration:none;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                                </svg>
                                <span>Log Aksi: {{ $actionLabel }}</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <style>
        /* ─── Base ─── */
        .modlog-detail-container {
            padding: 1.5rem;
        }

        .modlog-card {
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03);
            border: 1px solid #e3e3e3 !important;
        }

        /* ─── Back Button ─── */
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

        /* ─── Recent Log Links ─── */
        .recent-log-item:hover {
            background: #f8fafc;
            border-radius: 8px;
            padding-left: 6px;
        }

        /* ─── Raw JSON Toggle ─── */
        .btn-raw-toggle:hover {
            color: #475569 !important;
        }

        /* ─── Toast ─── */
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
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin-bottom: 0.75rem;
            min-width: 300px;
            border-left: 4px solid transparent;
            animation: slideIn 0.3s ease;
        }

        .toast-success { border-left-color: #10b981; }
        .toast-success .toast-icon { color: #10b981; }
        .toast-error   { border-left-color: #ef4444; }
        .toast-error .toast-icon   { color: #ef4444; }

        .toast-content { flex: 1; }
        .toast-content p { margin: 0; font-size: 0.875rem; color: #1e293b; }

        .toast-close {
            background: none; border: none; color: #94a3b8;
            cursor: pointer; padding: 0.25rem; display: flex;
            border-radius: 6px; transition: all 0.2s;
        }
        .toast-close:hover { background: #f1f5f9; color: #475569; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .modlog-detail-container {
                padding: 1rem;
            }
        }
    </style>

    <script>
        /* ─── Raw JSON Toggle ─── */
        function toggleRawJson() {
            const block   = document.getElementById('rawJsonBlock');
            const chevron = document.getElementById('rawJsonChevron');
            const isHidden = block.style.display === 'none';
            block.style.display   = isHidden ? 'block' : 'none';
            chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        }

        /* ─── Auto-dismiss Toasts ─── */
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toast').forEach(toast => {
                setTimeout(() => {
                    toast.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                    toast.style.transform  = 'translateX(100%)';
                    toast.style.opacity    = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });
        });
    </script>
@endsection