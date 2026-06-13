@props(['unit'])

@if($unit->avg_rating > 0)
<div class="section-block">
    <div class="rating-summary">

        {{-- Big number --}}
        <div class="rating-big">
            <span class="rating-big-num">{{ number_format($unit->avg_rating, 1) }}</span>
            <div class="rating-stars-row">
                @for($s = 1; $s <= 5; $s++)
                    <svg width="13" height="13" viewBox="0 0 24 24"
                         fill="{{ $s <= round($unit->avg_rating) ? '#f59e0b' : 'none' }}"
                         stroke="#f59e0b" stroke-width="1.5">
                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                @endfor
            </div>
            <span class="rating-big-count">{{ $unit->total_ratings }} ulasan</span>
        </div>

        {{-- Category bars --}}
        @if($unit->avg_facility_score || $unit->avg_service_score || $unit->avg_quality_score)
        <div class="rating-cat-bars">
            @php
                $cats = [
                    'Fasilitas' => $unit->avg_facility_score ?? 0,
                    'Layanan'   => $unit->avg_service_score  ?? 0,
                    'Kualitas'  => $unit->avg_quality_score  ?? 0,
                ];
            @endphp
            @foreach($cats as $label => $score)
                <div class="rating-cat-row">
                    <span class="rating-cat-label">{{ $label }}</span>
                    <div class="rating-bar-track">
                        <div class="rating-bar-fill" style="width: {{ ($score / 5) * 100 }}%;"></div>
                    </div>
                    <span class="rating-cat-score">{{ number_format($score, 1) }}</span>
                </div>
            @endforeach
        </div>
        @else
        {{-- Fallback: per-star distribution bars --}}
        <div class="star-bars">
            @foreach([5, 4, 3, 2, 1] as $star)
                @php
                    $count = $unit->ratings->where('overall_score', '>=', $star)->where('overall_score', '<', $star + 1)->count();
                    $pct   = $unit->total_ratings > 0 ? ($count / $unit->total_ratings * 100) : 0;
                @endphp
                <div class="star-bar-row">
                    <svg width="11" height="11" fill="#f59e0b" viewBox="0 0 24 24">
                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>{{ $star }}</span>
                    <div class="rating-bar-track">
                        <div class="rating-bar-fill" style="width: {{ $pct }}%;"></div>
                    </div>
                    <span class="star-bar-num">{{ $count }}</span>
                </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endif