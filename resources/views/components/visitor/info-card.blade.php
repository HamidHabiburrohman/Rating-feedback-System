@props(['tag', 'rating', 'title', 'location'])

<div class="info-card">
    <div class="info-card__header">
        <span class="info-card__tag">{{ $tag }}</span>
        <div class="info-card__rating">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
            </svg>
            <span>{{ $rating }}</span>
        </div>
    </div>
    <h1 class="info-card__title">{{ $title }}</h1>
    <div class="info-card__location">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="11" r="3" stroke="currentColor" stroke-width="1.5"/>
            <path d="M12 21C16 17 20 13.4183 20 10C20 5.58172 16.4183 2 12 2C7.58172 2 4 5.58172 4 10C4 13.4183 8 17 12 21Z" stroke="currentColor" stroke-width="1.5"/>
        </svg>
        <span>{{ $location }}</span>
    </div>
</div>