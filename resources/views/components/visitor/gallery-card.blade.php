@props(['image' => null, 'title' => null, 'empty' => false])

@if($empty)
    <div class="gallery-card gallery-card--empty">
        <div class="gallery-card__placeholder">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="3" width="18" height="18" rx="4" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4 4"/>
                <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                <path d="M3 15L8 10L13 15L16 12L21 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @if($title)
                <span class="gallery-card__placeholder-text">{{ $title }}</span>
            @endif
        </div>
    </div>
@else
    <div class="gallery-card">
        <div class="gallery-card__image-wrapper">
            <img 
                src="{{ $image['src'] }}" 
                alt="{{ $image['alt'] ?? 'Gallery image' }}" 
                class="gallery-card__image"
                loading="lazy"
            >
            <div class="gallery-card__overlay"></div>
            @if($title)
                <div class="gallery-card__title-wrapper">
                    <span class="gallery-card__title">{{ $title }}</span>
                </div>
            @endif
        </div>
    </div>
@endif