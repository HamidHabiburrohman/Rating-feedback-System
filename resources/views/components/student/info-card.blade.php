@props(['unit', 'hasRated' => false])

@php
    $typePillMap = [
        'Akademik'     => ['class' => 'pill-blue',   'bg' => '#dbeafe', 'color' => '#1d4ed8'],
        'Pelayanan'    => ['class' => 'pill-green',  'bg' => '#dcfce7', 'color' => '#15803d'],
        'Administrasi' => ['class' => 'pill-amber',  'bg' => '#fef3c7', 'color' => '#b45309'],
        'Fasilitas'    => ['class' => 'pill-violet', 'bg' => '#ede9fe', 'color' => '#6d28d9'],
        'Keuangan'     => ['class' => 'pill-rose',   'bg' => '#ffe4e6', 'color' => '#be123c'],
    ];
    $typeName = $unit->unitType?->name ?? $unit->type?->name ?? 'Unit Layanan';
    $typePill = $typePillMap[$typeName] ?? ['class' => 'pill-accent', 'bg' => '#fff2ec', 'color' => '#f8773c'];
@endphp

<div class="info-card">

    <span class="unit-type-badge">
        <svg width="6" height="6" viewBox="0 0 6 6" fill="currentColor"><circle cx="3" cy="3" r="3"/></svg>
        {{ $typeName }}
    </span>

    <h1 class="unit-name">{{ $unit->name }}</h1>

    @if($unit->location)
        <p class="unit-location">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $unit->location }}
            @if($unit->building)
                · {{ $unit->building }}{{ $unit->floor ? ', Lt. '.$unit->floor : '' }}
            @endif
        </p>
    @endif

    <div class="unit-stats">
        <div class="unit-stat">
            <div class="stat-icon-wrap" style="background:#fef3c7;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <span class="unit-stat-value">{{ number_format($unit->avg_rating ?? 0, 1) }}</span>
            <span class="unit-stat-label">Rating</span>
        </div>

        <div class="unit-stat">
            <div class="stat-icon-wrap" style="background:#dcfce7;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="unit-stat-value">{{ $unit->total_ratings ?? 0 }}</span>
            <span class="unit-stat-label">Ulasan</span>
        </div>

        <div class="unit-stat">
            <div class="stat-icon-wrap" style="background:#dbeafe;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="unit-stat-value">{{ $unit->staff_count ?? '—' }}</span>
            <span class="unit-stat-label">Staf</span>
        </div>
    </div>
</div>