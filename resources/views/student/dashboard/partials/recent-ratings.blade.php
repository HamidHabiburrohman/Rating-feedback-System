{{-- partials/recent-ratings.blade.php --}}
{{-- Variables: $recentRatings (array from DashboardService::getRecentRatings) --}}

@php
    $statusConfig = [
        'submitted'  => ['label' => 'Terkirim',   'class' => 'pill-blue'],
        'verified'   => ['label' => 'Terverifikasi','class' => 'pill-green'],
        'rejected'   => ['label' => 'Ditolak',    'class' => 'pill-rose'],
        'pending'    => ['label' => 'Pending',    'class' => 'pill-amber'],
    ];

    function ratingColor(float $score): string {
        if ($score >= 4.5) return '#16a34a';
        if ($score >= 3.5) return '#2563eb';
        if ($score >= 2.5) return '#d97706';
        return '#e11d48';
    }

    function ratingBg(float $score): string {
        if ($score >= 4.5) return '#dcfce7';
        if ($score >= 3.5) return '#dbeafe';
        if ($score >= 2.5) return '#fef3c7';
        return '#ffe4e6';
    }
@endphp

<div class="dash-card">
    <div class="card-header">
        <div class="card-title-group">
            <div class="card-icon" style="background:#fef3c7;">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <span class="card-title">Penilaian Terbaru</span>
            <span class="card-count">{{ count($recentRatings) }}</span>
        </div>
        <a href="{{ route('student.ratings.index') }}" class="card-link">
            Lihat semua
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="card-body" style="padding: 0;">
        @if(empty($recentRatings))
            <div class="empty-state">
                <div class="empty-icon">⭐</div>
                <p class="empty-text">Belum ada penilaian yang diberikan.</p>
                <a href="{{ route('student.ratings.create') }}" class="btn-pill btn-primary" style="margin-top:14px; display:inline-flex;">
                    Beri Penilaian Pertama
                </a>
            </div>
        @else
            <div class="rating-list">
                @foreach($recentRatings as $i => $rating)
                    @php
                        $sc = (float)($rating['overall_score'] ?? 0);
                        $cfg = $statusConfig[$rating['status']] ?? ['label' => ucfirst($rating['status']), 'class' => 'pill-neutral'];
                    @endphp
                    <a href="{{ route('student.ratings.show', $rating['id']) }}"
                       class="rating-row"
                       style="animation-delay: {{ $i * 0.06 }}s">

                        {{-- Score badge --}}
                        <div class="score-badge" style="background: {{ ratingBg($sc) }}; color: {{ ratingColor($sc) }};">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="{{ ratingColor($sc) }}">
                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            {{ number_format($sc, 1) }}
                        </div>

                        {{-- Info --}}
                        <div class="row-info">
                            <p class="row-title">{{ $rating['unit_name'] }}</p>
                            <p class="row-meta">{{ $rating['tracking_code'] }} · {{ $rating['created_at'] }}</p>
                        </div>

                        {{-- Status --}}
                        <div class="row-right">
                            <span class="pill {{ $cfg['class'] }}">
                                <span class="pill-dot" style="background: currentColor;"></span>
                                {{ $cfg['label'] }}
                            </span>
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
    .rating-list { display: flex; flex-direction: column; }

    .rating-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 22px;
        text-decoration: none;
        border-bottom: 1px solid var(--border-soft);
        transition: background var(--transition);
        animation: rowIn 0.4s cubic-bezier(0.22,1,0.36,1) both;
    }

    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-8px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .rating-row:last-child { border-bottom: none; }
    .rating-row:hover { background: var(--bg); }

    .score-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 7px 11px;
        border-radius: var(--radius-pill);
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
        letter-spacing: -0.2px;
    }

    .row-info { flex: 1; min-width: 0; }

    .row-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .row-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
        font-weight: 400;
    }

    .row-right { flex-shrink: 0; }

    .row-chevron {
        color: var(--text-muted);
        flex-shrink: 0;
        transition: transform var(--transition), color var(--transition);
    }
    .rating-row:hover .row-chevron {
        transform: translateX(3px);
        color: var(--text-primary);
    }
</style>