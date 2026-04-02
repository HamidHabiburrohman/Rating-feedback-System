@php
    $avgFacility = $unit->avg_facility_score ?? 0;
    $avgService = $unit->avg_service_score ?? 0;
    $avgQuality = $unit->avg_quality_score ?? 0;
    $totalRatings = $unit->total_ratings ?? 0;
    $avgOverall = $unit->avg_rating ?? 0;
    $roundedOverall = round($avgOverall);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-20">
    <div
        class="lg:col-span-5 bg-surface-container-lowest p-12 rounded-2xl shadow-lg text-center border border-outline-variant/10">
        <h3 class="text-on-surface-variant font-bold text-sm tracking-widest uppercase mb-4">Total Score</h3>
        <div class="text-8xl font-extrabold text-on-surface leading-none tracking-tighter mb-4">
            {{ number_format($avgOverall, 1) }}</div>
        <div class="flex justify-center gap-1 mb-6">
            @for($i = 1; $i <= 5; $i++)
                <span class="material-symbols-outlined text-primary text-4xl {{ $i <= $roundedOverall ? 'fill-icon' : '' }}"
                    style="{{ $i <= $roundedOverall ? 'font-variation-settings: \"FILL\" 1;' : '' }}">star</span>
            @endfor
        </div>
        <p class="text-on-surface-variant font-medium">Based on {{ $totalRatings }} Student Reviews</p>
    </div>

    <div class="lg:col-span-7 space-y-6">
        <div class="flex items-center gap-6">
            <span class="w-32 text-sm font-bold text-on-surface-variant uppercase tracking-tighter">Fasilitas</span>
            <div class="flex-1 h-3 bg-surface-container-high rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full transition-all duration-500"
                    style="width: {{ ($avgFacility / 5) * 100 }}%"></div>
            </div>
            <span class="w-10 text-sm font-bold text-right">{{ number_format($avgFacility, 1) }}</span>
        </div>
        <div class="flex items-center gap-6">
            <span class="w-32 text-sm font-bold text-on-surface-variant uppercase tracking-tighter">Pelayanan</span>
            <div class="flex-1 h-3 bg-surface-container-high rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full transition-all duration-500"
                    style="width: {{ ($avgService / 5) * 100 }}%"></div>
            </div>
            <span class="w-10 text-sm font-bold text-right">{{ number_format($avgService, 1) }}</span>
        </div>
        <div class="flex items-center gap-6">
            <span class="w-32 text-sm font-bold text-on-surface-variant uppercase tracking-tighter">Kualitas</span>
            <div class="flex-1 h-3 bg-surface-container-high rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full transition-all duration-500"
                    style="width: {{ ($avgQuality / 5) * 100 }}%"></div>
            </div>
            <span class="w-10 text-sm font-bold text-right">{{ number_format($avgQuality, 1) }}</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    @forelse($unit->ratings()->with(['student', 'scores.category', 'adminReply'])->latest()->paginate(10) as $review)
        <div
            class="bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 transition-all duration-300 hover:shadow-lg group">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span
                            class="text-primary font-bold text-lg uppercase">{{ substr($review->student->name ?? 'A', 0, 1) }}</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface">{{ $review->student->name ?? 'Anonymous' }}</h4>
                        <p class="text-xs text-on-surface-variant">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex text-primary">
                    @php $filled = round($review->overall_score); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined text-sm {{ $i <= $filled ? 'fill-icon' : '' }}"
                            style="{{ $i <= $filled ? 'font-variation-settings: \"FILL\" 1;' : '' }}">star</span>
                    @endfor
                </div>
            </div>

            <p class="text-on-surface-variant text-base leading-relaxed mb-6">{{ $review->comment_display }}</p>

            @if($review->scores->count())
                <div class="flex flex-wrap gap-3 pt-4 border-t border-outline-variant/10">
                    @foreach($review->scores as $score)
                        <div
                            class="flex items-center gap-1.5 text-on-surface-variant bg-surface-container-low px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            <span class="text-xs font-medium">{{ $score->category->name ?? 'Category' }}</span>
                            <span class="text-xs font-bold text-primary">{{ number_format($score->score, 1) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($review->adminReply)
                <div class="mt-6 pt-4 border-t border-outline-variant/10 bg-surface-container-low rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-sm text-primary">support_agent</span>
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">{{ $review->adminReply->admin->name ?? 'Admin' }}</span>
                    </div>
                    <p class="text-sm text-on-surface-variant">{{ $review->adminReply->reply_message }}</p>
                    <p class="text-xs text-on-surface-variant/60 mt-2">{{ $review->adminReply->replied_at->diffForHumans() }}
                    </p>
                </div>
            @endif
        </div>
    @empty
        <div class="col-span-full text-center py-16">
            <span class="material-symbols-outlined text-6xl text-outline/40 mb-4">chat</span>
            <p class="text-on-surface-variant text-lg">No reviews yet. Be the first to rate!</p>
        </div>
    @endforelse
</div>

@auth('student')
    @php
        $hasReviewed = $unit->ratings()->where('student_id', auth('student')->id())->exists();
        $existingReview = $unit->ratings()->where('student_id', auth('student')->id())->first();
    @endphp

    @if(!$hasReviewed)
        <div
            class="mt-12 bg-primary/5 p-10 rounded-2xl border-2 border-dashed border-primary/30 flex flex-col justify-center items-center text-center">
            <span class="material-symbols-outlined text-6xl text-primary mb-4">rate_review</span>
            <h4 class="text-2xl font-bold mb-3 text-on-surface">Used this Unit?</h4>
            <p class="text-on-surface-variant mb-8 max-w-md">Share your experience to help other researchers and improve our
                facilities.</p>
            <a href="{{ route('student.ratings.create', $unit->slug) }}"
                class="bg-primary text-white px-10 py-4 rounded-full font-bold shadow-lg shadow-primary/30 hover:scale-105 transition-transform inline-block">
                Write a Review
            </a>
        </div>
    @else
        <div
            class="mt-12 bg-surface-container-low p-10 rounded-2xl border border-outline-variant/10 flex flex-col justify-center items-center text-center">
            <span class="material-symbols-outlined text-6xl text-primary mb-4">task_alt</span>
            <h4 class="text-2xl font-bold mb-2 text-on-surface">You've Reviewed This Unit</h4>
            <p class="text-on-surface-variant mb-6">Thank you for sharing your feedback.</p>
            <a href="{{ route('student.ratings.edit', $existingReview->tracking_code ?? '#') }}"
                class="border-2 border-primary text-primary px-10 py-4 rounded-full font-bold hover:bg-primary hover:text-white transition-all inline-block">
                Edit Your Review
            </a>
        </div>
    @endif
@else
    <div
        class="mt-12 bg-primary/5 p-10 rounded-2xl border-2 border-dashed border-primary/30 flex flex-col justify-center items-center text-center">
        <span class="material-symbols-outlined text-6xl text-primary mb-4">rate_review</span>
        <h4 class="text-2xl font-bold mb-3 text-on-surface">Used this Unit?</h4>
        <p class="text-on-surface-variant mb-8 max-w-md">Log in to share your experience and help other researchers.</p>
        <a href="{{ route('student.login') }}"
            class="bg-primary text-white px-10 py-4 rounded-full font-bold shadow-lg shadow-primary/30 hover:scale-105 transition-transform inline-block">
            Login to Review
        </a>
    </div>
@endauth

@if(method_exists($unit->ratings(), 'links') && $unit->ratings() instanceof \Illuminate\Pagination\LengthAwarePaginator && $unit->ratings()->paginate(10)->hasPages())
    <div class="mt-12">
        {{ $unit->ratings()->paginate(10)->links('layouts.student.partials.pagination') }}
    </div>
@endif