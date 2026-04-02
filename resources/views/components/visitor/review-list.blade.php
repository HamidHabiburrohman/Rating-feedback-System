@props(['reviews' => []])

<div class="reviews-section" data-tab-content="reviews">
    @if(count($reviews) > 0)
        <div class="reviews-list">
            @foreach($reviews as $review)
                <div class="review-card">
                    <div class="review-card__header">
                        <div class="review-card__avatar">
                            @if(isset($review['avatar']))
                                <img src="{{ $review['avatar'] }}" alt="{{ $review['name'] }}">
                            @else
                                <span>{{ substr($review['name'], 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="review-card__meta">
                            <h4 class="review-card__name">{{ $review['name'] }}</h4>
                            <span class="review-card__date">{{ $review['date'] }}</span>
                        </div>
                        <div class="review-card__rating">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                            </svg>
                            <span>{{ $review['rating'] }}</span>
                        </div>
                    </div>
                    <p class="review-card__comment">{{ $review['comment'] }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="reviews-empty">
            @include('components.empty-state', [
                'icon' => 'star',
                'title' => 'No Reviews Yet',
                'subtitle' => 'Be the first to leave a review',
                'variant' => 'default'
            ])
        </div>
    @endif
</div>