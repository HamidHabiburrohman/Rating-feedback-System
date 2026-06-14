{{-- partials/review-list.blade.php --}}
{{-- Props: $reviews (paginated collection of Review models) --}}

@forelse ($reviews as $review)
    @php
        $filled = $review->rating ?? 0;
        $empty  = 5 - $filled;
    @endphp

    <div class="bg-surface-container-lowest p-8 rounded-lg border border-outline-variant/10 transition-all duration-300 hover:bg-surface-bright">

        {{-- Reviewer + Rating --}}
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center gap-4">
                @if ($review->user->avatar_url ?? false)
                    <img
                        src="{{ $review->user->avatar_url }}"
                        alt="{{ $review->user->name }}"
                        class="w-12 h-12 rounded-full object-cover shrink-0"
                    >
                @else
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="text-primary font-bold text-lg uppercase">
                            {{ substr($review->user->name, 0, 1) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h4 class="font-bold text-on-surface">{{ $review->user->name }}</h4>
                    <p class="text-xs text-on-surface-variant">
                        {{ $review->user->role ?? 'Student' }} &bull; {{ $review->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            <div class="flex text-primary shrink-0">
                @for ($i = 0; $i < $filled; $i++)
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                @endfor
                @for ($i = 0; $i < $empty; $i++)
                    <span class="material-symbols-outlined text-sm">star</span>
                @endfor
            </div>
        </div>

        {{-- Review Body --}}
        <p class="text-on-surface-variant text-base leading-relaxed mb-8">
            {{ $review->body }}
        </p>

        {{-- Tags --}}
        @if ($review->tags && $review->tags->isNotEmpty())
            <div class="flex flex-wrap gap-4 pt-6 border-t border-outline-variant/10">
                @foreach ($review->tags as $tag)
                    <div class="flex items-center gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined text-lg">{{ $tag->icon ?? 'label' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest">{{ $tag->name }}</span>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@empty
    {{-- Empty state handled in show.blade.php — the review-form CTA takes this slot --}}
@endforelse