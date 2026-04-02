{{-- partials/recommended-units.blade.php --}}
{{-- Variables: $recommendedUnits (array from DashboardService::getRecommendedUnits) --}}

@php
    // Assign a soft color per unit type for visual variety
    $typeColors = [
        'Akademik'     => ['bg' => '#dbeafe', 'text' => '#2563eb'],
        'Pelayanan'    => ['bg' => '#dcfce7', 'text' => '#16a34a'],
        'Administrasi' => ['bg' => '#fef3c7', 'text' => '#d97706'],
        'Fasilitas'    => ['bg' => '#ede9fe', 'text' => '#7c3aed'],
        'Keuangan'     => ['bg' => '#ffe4e6', 'text' => '#e11d48'],
        'General'      => ['bg' => '#f0ede8', 'text' => '#6b6860'],
    ];

    function unitTypeColor(string $type, array $map): array {
        return $map[$type] ?? $map['General'];
    }
@endphp

<div class="dash-card">
    <div class="card-header">
        <div class="card-title-group">
            <div class="card-icon" style="background:#ede9fe;">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="card-title">Belum Dinilai</span>
            <span class="card-count">{{ count($recommendedUnits) }}</span>
        </div>
    </div>

    <div class="card-body" style="padding: 12px 16px 16px;">
        @if(empty($recommendedUnits))
            <div class="empty-state">
                <div class="empty-icon">🎉</div>
                <p class="empty-text">Anda telah menilai semua unit tersedia!</p>
            </div>
        @else
            <div class="unit-grid">
                @foreach($recommendedUnits as $i => $unit)
                    @php $uc = unitTypeColor($unit['type'], $typeColors); @endphp
                    <a href="{{ route('student.ratings.create', ['unit' => $unit['id']]) }}"
                       class="unit-card"
                       style="animation-delay: {{ $i * 0.05 }}s">

                        {{-- Type pill --}}
                        <div class="unit-type-pill" style="background: {{ $uc['bg'] }}; color: {{ $uc['text'] }};">
                            {{ $unit['type'] }}
                        </div>

                        {{-- Name --}}
                        <p class="unit-name">{{ $unit['name'] }}</p>

                        {{-- Footer --}}
                        <div class="unit-footer">
                            @if($unit['avg_rating'] > 0)
                                <span class="unit-rating">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="#d97706"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    {{ number_format($unit['avg_rating'], 1) }}
                                </span>
                            @else
                                <span class="unit-rating" style="color: var(--text-muted);">Belum ada skor</span>
                            @endif

                            @if($unit['location'])
                                <span class="unit-location">
                                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ Str::limit($unit['location'], 18) }}
                                </span>
                            @endif
                        </div>

                        {{-- Rate CTA --}}
                        <div class="unit-cta">
                            Nilai Sekarang
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- View all link --}}
            <a href="{{ route('student.units.index') }}" class="units-view-all">
                Lihat semua unit
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        @endif
    </div>
</div>

<style>
    .unit-grid {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
    }

    .unit-card {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 13px 14px;
        border-radius: 14px;
        background: var(--bg);
        border: 1px solid var(--border-soft);
        text-decoration: none;
        transition: all var(--transition);
        animation: unitIn 0.4s cubic-bezier(0.22,1,0.36,1) both;
        position: relative;
        overflow: hidden;
    }

    @keyframes unitIn {
        from { opacity: 0; transform: translateY(10px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .unit-card:hover {
        background: var(--surface);
        border-color: var(--border);
        box-shadow: var(--shadow-hover);
        transform: translateY(-1px);
    }

    .unit-type-pill {
        display: inline-flex;
        align-self: flex-start;
        padding: 3px 9px;
        border-radius: var(--radius-pill);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .unit-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.2px;
        line-height: 1.3;
    }

    .unit-footer {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .unit-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #d97706;
    }

    .unit-location {
        display: flex;
        align-items: center;
        gap: 3px;
        font-size: 11.5px;
        color: var(--text-muted);
        font-weight: 400;
    }

    .unit-cta {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        margin-top: 2px;
        opacity: 0;
        transform: translateX(-4px);
        transition: opacity var(--transition), transform var(--transition), color var(--transition);
    }
    .unit-card:hover .unit-cta {
        opacity: 1;
        transform: translateX(0);
        color: var(--text-primary);
    }

    .units-view-all {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 10px;
        border-radius: 12px;
        border: 1.5px dashed var(--border);
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all var(--transition);
        margin-top: 4px;
    }
    .units-view-all:hover {
        border-color: #aaa;
        color: var(--text-primary);
        background: var(--bg);
    }
</style>