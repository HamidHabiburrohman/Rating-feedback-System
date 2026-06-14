@props(['ratings'])

@if($ratings && $ratings->count())
<div class="section-block" style="margin-bottom: 14px;">
    <div class="review-list">
        @foreach($ratings->sortByDesc('created_at') as $i => $rating)
            <div class="review-item" style="animation-delay: {{ $i * 0.05 }}s">

                <div class="review-header">
                    <div class="review-avatar">
                        {{ strtoupper(substr($rating->student?->name ?? $rating->student?->student_identifier ?? 'M', 0, 1)) }}
                    </div>
                    <div class="review-meta">
                        <p class="review-name">{{ $rating->student?->name ?? 'Mahasiswa' }}</p>
                        <div class="review-info">
                            <span class="review-score">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="#f59e0b">
                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                {{ number_format($rating->overall_score, 1) }}
                            </span>
                            @if($rating->student?->student_identifier)
                                <span class="review-identifier">{{ $rating->student->student_identifier }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($rating->is_comment_censored ?? false)
                    <p class="review-text censored">[Komentar telah disembunyikan]</p>
                @elseif($rating->review ?? $rating->comment)
                    <p class="review-text">{{ $rating->review ?? $rating->comment }}</p>
                @endif

                <div class="review-footer">
                    <button class="review-action" onclick="this.classList.toggle('liked')">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Suka
                    </button>
                    <button class="review-action">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Balas
                    </button>
                    <span class="review-date">{{ $rating->created_at->diffForHumans() }}</span>
                </div>

            </div>
        @endforeach
    </div>
</div>
@else
<div class="section-block" style="margin-bottom: 14px;">
    <div class="empty-state">
        <div class="empty-icon">💬</div>
        <p class="empty-text">Belum ada ulasan untuk unit ini.</p>
    </div>
</div>
@endif