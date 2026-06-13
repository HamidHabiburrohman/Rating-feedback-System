@props(['unit'])

@if($unit->photos && $unit->photos->count())
    <div class="gallery-grid" style="padding-bottom: 14px;">
        @foreach($unit->photos->take(7) as $idx => $photo)
            @php
                $src   = asset($photo->thumbnail_path ?? $photo->original_path ?? $photo->url);
                $large = asset($photo->large_path ?? $photo->original_path ?? $photo->url);
                $isLast = $idx === 6 && $unit->photos->count() > 7;
            @endphp

            @if($isLast)
                <a href="{{ route('student.units.show', $unit->slug) }}#gallery" class="gallery-item">
                    <img src="{{ $src }}" alt="" loading="lazy" style="filter: brightness(0.5);">
                    <div class="gallery-more">+{{ $unit->photos->count() - 6 }}</div>
                </a>
            @else
                <a href="{{ $large }}" target="_blank" class="gallery-item">
                    <img src="{{ $src }}" alt="{{ $unit->name }} foto {{ $idx + 1 }}" loading="lazy">
                </a>
            @endif
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">🖼️</div>
        <p class="empty-text">Belum ada foto tersedia.</p>
    </div>
@endif