{{-- partials/stats-cards.blade.php --}}
{{-- Variables: $stats (array from DashboardService::getStats) --}}

@php
    $cards = [
        [
            'key'     => 'total_ratings',
            'label'   => 'Total Penilaian',
            'value'   => $stats['total_ratings'] ?? 0,
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
            'color'   => 'amber',
            'suffix'  => 'penilaian diberikan',
        ],
        [
            'key'     => 'average_rating',
            'label'   => 'Rata-rata Skor',
            'value'   => number_format($stats['average_rating'] ?? 0, 1),
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
            'color'   => 'green',
            'suffix'  => 'dari 5.0 skala penilaian',
        ],
        [
            'key'     => 'total_reports',
            'label'   => 'Total Laporan',
            'value'   => $stats['total_reports'] ?? 0,
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            'color'   => 'blue',
            'suffix'  => 'laporan diajukan',
        ],
        [
            'key'     => 'active_reports',
            'label'   => 'Laporan Aktif',
            'value'   => $stats['active_reports'] ?? 0,
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'color'   => 'rose',
            'suffix'  => 'sedang diproses',
        ],
    ];

    $colorMap = [
        'amber'  => ['bg' => '#fef3c7', 'text' => '#d97706', 'ring' => '#fde68a'],
        'green'  => ['bg' => '#dcfce7', 'text' => '#16a34a', 'ring' => '#bbf7d0'],
        'blue'   => ['bg' => '#dbeafe', 'text' => '#2563eb', 'ring' => '#bfdbfe'],
        'rose'   => ['bg' => '#ffe4e6', 'text' => '#e11d48', 'ring' => '#fecdd3'],
        'violet' => ['bg' => '#ede9fe', 'text' => '#7c3aed', 'ring' => '#ddd6fe'],
    ];
@endphp

<div class="stats-grid">
    @foreach($cards as $i => $card)
        @php $c = $colorMap[$card['color']]; @endphp
        <div class="stat-card" style="animation-delay: {{ $i * 0.07 }}s">
            <div class="stat-card-inner">
                <div class="stat-icon-wrap" style="background: {{ $c['bg'] }}; box-shadow: 0 0 0 6px {{ $c['ring'] }}40;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="{{ $c['text'] }}" stroke-width="2">
                        {!! $card['icon'] !!}
                    </svg>
                </div>
                <div class="stat-value-row">
                    <span class="stat-value">{{ $card['value'] }}</span>
                    @if($card['key'] === 'average_rating')
                        <span class="stat-unit">/5</span>
                    @endif
                </div>
                <p class="stat-label">{{ $card['label'] }}</p>
                <p class="stat-suffix">{{ $card['suffix'] }}</p>
            </div>

            {{-- Unique units rated small badge only on first card --}}
            @if($card['key'] === 'total_ratings' && isset($stats['unique_units_rated']))
                <div class="stat-badge" style="background: {{ $c['bg'] }}; color: {{ $c['text'] }};">
                    {{ $stats['unique_units_rated'] }} unit unik
                </div>
            @endif
        </div>
    @endforeach
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-card);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-card);
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: box-shadow var(--transition), transform var(--transition);
        animation: statIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes statIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .stat-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .stat-card-inner { display: flex; flex-direction: column; gap: 10px; }

    .stat-icon-wrap {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        transition: transform var(--transition);
    }
    .stat-card:hover .stat-icon-wrap { transform: scale(1.08); }

    .stat-value-row {
        display: flex;
        align-items: baseline;
        gap: 3px;
        margin-top: 4px;
    }

    .stat-value {
        font-size: 30px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -1.5px;
        line-height: 1;
    }

    .stat-unit {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: -0.3px;
    }

    .stat-label {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.1px;
    }

    .stat-suffix {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 400;
    }

    .stat-badge {
        position: absolute;
        bottom: 14px;
        right: 14px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        letter-spacing: 0.02em;
    }

    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        .stat-value { font-size: 24px; }
    }
</style>