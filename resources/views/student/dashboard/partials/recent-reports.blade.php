{{-- partials/recent-reports.blade.php --}}
{{-- Variables: $recentReports (array from DashboardService::getRecentReports) --}}

@php
    $statusConfig = [
        'new'         => ['label' => 'Baru',         'class' => 'pill-blue'],
        'in_progress' => ['label' => 'Diproses',     'class' => 'pill-amber'],
        'resolved'    => ['label' => 'Selesai',      'class' => 'pill-green'],
        'rejected'    => ['label' => 'Ditolak',      'class' => 'pill-rose'],
        'closed'      => ['label' => 'Ditutup',      'class' => 'pill-neutral'],
    ];

    $priorityConfig = [
        'low'      => ['label' => 'Rendah',   'icon' => '↓', 'color' => '#16a34a'],
        'medium'   => ['label' => 'Sedang',   'icon' => '→', 'color' => '#d97706'],
        'high'     => ['label' => 'Tinggi',   'icon' => '↑', 'color' => '#e11d48'],
        'critical' => ['label' => 'Kritis',   'icon' => '⚡', 'color' => '#7c3aed'],
    ];
@endphp

<div class="dash-card">
    <div class="card-header">
        <div class="card-title-group">
            <div class="card-icon" style="background:#dbeafe;">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="card-title">Laporan Terbaru</span>
            <span class="card-count">{{ count($recentReports) }}</span>
        </div>
        <a href="{{ route('student.reports.index') }}" class="card-link">
            Lihat semua
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="card-body" style="padding: 0;">
        @if(empty($recentReports))
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <p class="empty-text">Belum ada laporan yang diajukan.</p>
                <a href="{{ route('student.reports.create') }}" class="btn-pill btn-primary" style="margin-top:14px; display:inline-flex;">
                    Buat Laporan Pertama
                </a>
            </div>
        @else
            <div class="report-list">
                @foreach($recentReports as $i => $report)
                    @php
                        $sc  = $statusConfig[$report['status']] ?? ['label' => ucfirst($report['status']), 'class' => 'pill-neutral'];
                        $pri = $priorityConfig[$report['priority']] ?? ['label' => $report['priority'], 'icon' => '·', 'color' => '#a09e9a'];
                    @endphp
                    <a href="{{ route('student.reports.show', $report['id']) }}"
                       class="report-row"
                       style="animation-delay: {{ $i * 0.06 }}s">

                        {{-- Priority indicator --}}
                        <div class="priority-bar" style="background: {{ $pri['color'] }};"></div>

                        {{-- Content --}}
                        <div class="report-content">
                            <div class="report-top">
                                <p class="row-title">{{ $report['title'] }}</p>
                                <span class="pill {{ $sc['class'] }}">
                                    <span class="pill-dot" style="background: currentColor;"></span>
                                    {{ $sc['label'] }}
                                </span>
                            </div>
                            <div class="report-bottom">
                                <span class="row-meta">{{ $report['unit_name'] }}</span>
                                <span class="report-meta-sep">·</span>
                                <span class="row-meta">{{ $report['tracking_code'] }}</span>
                                <span class="report-meta-sep">·</span>
                                <span class="priority-label" style="color: {{ $pri['color'] }}; font-weight: 600; font-size: 12px;">
                                    {{ $pri['icon'] }} {{ $pri['label'] }}
                                </span>
                                <span class="report-meta-sep">·</span>
                                <span class="row-meta">{{ $report['created_at'] }}</span>
                            </div>
                        </div>

                        <svg class="row-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .report-list { display: flex; flex-direction: column; }

    .report-row {
        display: flex;
        align-items: center;
        gap: 0;
        padding: 0;
        text-decoration: none;
        border-bottom: 1px solid var(--border-soft);
        transition: background var(--transition);
        overflow: hidden;
        animation: rowIn 0.4s cubic-bezier(0.22,1,0.36,1) both;
        position: relative;
    }
    .report-row:last-child { border-bottom: none; }
    .report-row:hover { background: var(--bg); }

    .priority-bar {
        width: 3px;
        align-self: stretch;
        flex-shrink: 0;
        opacity: 0.85;
        transition: width var(--transition);
    }
    .report-row:hover .priority-bar { width: 4px; opacity: 1; }

    .report-content {
        flex: 1;
        min-width: 0;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .report-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .report-bottom {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .report-meta-sep { color: var(--border); font-size: 12px; }

    .row-chevron {
        color: var(--text-muted);
        flex-shrink: 0;
        margin-right: 16px;
        transition: transform var(--transition), color var(--transition);
    }
    .report-row:hover .row-chevron {
        transform: translateX(3px);
        color: var(--text-primary);
    }
</style>