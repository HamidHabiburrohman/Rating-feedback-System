@php
    use App\Services\Student\RatingService;

    $ratingService = app(RatingService::class);
    $stats = $ratingService->getRatingStats($unit->id);
    $categoryAverages = $stats['by_category'] ?? [];
    $totalRatings = $stats['total'] ?? 0;
    $avgOverall = $stats['average'] ?? 0;
    $roundedOverall = round($avgOverall);

    $categories = $ratingService->getActiveCategories();

    $ratingsQuery = $unit
        ->ratings()
        ->with(['student', 'scores.category', 'adminReply'])
        ->whereIn('status', ['active', 'edited'])
        ->latest();

    $ratings = $ratingsQuery->paginate(10);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-16 lg:mb-20">
    <div
        class="lg:col-span-5 bg-surface-container-lowest p-8 lg:p-12 rounded-2xl shadow-lg text-center border border-outline-variant/10">
        <h3 class="text-on-surface-variant font-bold text-sm tracking-widest uppercase mb-4">Total Score</h3>
        <div class="text-7xl lg:text-8xl font-extrabold text-on-surface leading-none tracking-tighter mb-4">
            {{ number_format($avgOverall, 1) }}
        </div>
        <div class="flex justify-center gap-1 mb-6">
            @for ($i = 1; $i <= 5; $i++)
                <span
                    class="material-symbols-outlined text-primary text-3xl lg:text-4xl {{ $i <= $roundedOverall ? 'fill-icon' : '' }}"
                    style="{{ $i <= $roundedOverall ? 'font-variation-settings: "FILL" 1;' : '' }}">star</span>
            @endfor
        </div>
        <p class="text-on-surface-variant font-medium">
            Based on {{ $totalRatings }} Student {{ Str::plural('Review', $totalRatings) }}
        </p>
    </div>

    <div class="lg:col-span-7 space-y-5 lg:space-y-6">
        @forelse($categories as $category)
            @php
                $categorySlug = $category['slug'];
                $categoryScore = $categoryAverages[$categorySlug] ?? 0;
                $percentage = ($categoryScore / 5) * 100;
            @endphp
            <div class="flex items-center gap-4 lg:gap-6">
                <span
                    class="w-28 lg:w-32 text-xs lg:text-sm font-bold text-on-surface-variant uppercase tracking-tighter">
                    {{ $category['name'] }}
                </span>
                <div class="flex-1 h-2.5 lg:h-3 bg-surface-container-high rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full transition-all duration-500"
                        style="width: {{ $percentage }}%"></div>
                </div>
                <span
                    class="w-10 text-sm font-bold text-on-surface text-right">{{ number_format($categoryScore, 1) }}</span>
            </div>
        @empty
            <p class="text-on-surface-variant text-center">No rating categories available yet.</p>
        @endforelse
    </div>
</div>

<div class="space-y-6">
    @forelse($ratings as $review)
        <div
            class="bg-surface-container-lowest p-6 lg:p-8 rounded-xl border border-outline-variant/10 transition-all duration-300 hover:shadow-lg hover:border-outline-variant/20">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="text-primary font-bold text-lg uppercase">
                            {{ substr($review->student->name ?? 'A', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface">{{ $review->student->name ?? 'Anonymous' }}</h4>
                        <p class="text-xs text-on-surface-variant">{{ $review->created_at->diffForHumans() }}</p>
                        @if ($review->status === 'edited')
                            <span class="text-xs text-primary/70 mt-0.5 inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">edit</span>
                                Edited
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="flex text-primary">
                        @php $filled = round($review->overall_score); @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-lg {{ $i <= $filled ? 'fill-icon' : '' }}"
                                style="{{ $i <= $filled ? 'font-variation-settings: "FILL" 1;' : '' }}">star</span>
                        @endfor
                    </div>
                    <span
                        class="text-sm font-bold text-on-surface">{{ number_format($review->overall_score, 1) }}</span>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-on-surface-variant text-base leading-relaxed">
                    {{ $review->comment_display }}
                </p>
            </div>

            @if ($review->scores->count())
                <div class="flex flex-wrap gap-2 lg:gap-3 pt-4 border-t border-outline-variant/10">
                    @foreach ($review->scores as $score)
                        <div
                            class="flex items-center gap-1.5 text-on-surface-variant bg-surface-container-low px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm text-primary">check_circle</span>
                            <span class="text-xs font-medium">{{ $score->category->name ?? 'Category' }}</span>
                            <span class="text-xs font-bold text-primary">{{ number_format($score->score, 1) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($review->adminReply)
                <div class="mt-6 pt-4 border-t border-outline-variant/10">
                    <div class="bg-surface-container-low rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-sm text-primary">support_agent</span>
                            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                                {{ $review->adminReply->admin->name ?? 'Admin Response' }}
                            </span>
                        </div>
                        <p class="text-sm text-on-surface-variant">{{ $review->adminReply->reply_message }}</p>
                        <p class="text-xs text-on-surface-variant/60 mt-2">
                            {{ $review->adminReply->replied_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-16 lg:py-20">
            <span class="material-symbols-outlined text-6xl lg:text-7xl text-outline/40 mb-4">chat</span>
            <p class="text-on-surface-variant text-lg">No reviews yet. Be the first to rate!</p>
        </div>
    @endforelse
</div>

@auth('student')
    @php
        $studentIdentifier = auth('student')->user()->student_identifier;
        $hasReviewed = $unit
            ->ratings()
            ->where('student_identifier', $studentIdentifier)
            ->where('status', '!=', 'archived')
            ->exists();
        $existingReview = $unit->ratings()->where('student_identifier', $studentIdentifier)->first();
    @endphp

    @if (!$hasReviewed)
        <div
            class="mt-12 bg-linear-to-br from-primary/5 to-primary/10 p-8 lg:p-10 rounded-2xl border-2 border-dashed border-primary/30 flex flex-col justify-center items-center text-center relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-primary/5 rounded-full blur-2xl"></div>

            <span class="material-symbols-outlined text-5xl lg:text-6xl text-primary mb-4 relative">rate_review</span>
            <h4 class="text-xl lg:text-2xl font-bold mb-3 text-on-surface relative">Used this Unit?</h4>
            <p class="text-on-surface-variant mb-8 max-w-md relative">Share your experience to help other researchers and
                improve our facilities.</p>

            <a href="{{ route('student.ratings.create', $unit->slug) }}"
                class="bg-primary text-white px-8 lg:px-10 py-3.5 lg:py-4 rounded-full font-bold shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all duration-300 inline-flex items-center gap-2 group relative">
                <span class="material-symbols-outlined group-hover:animate-pulse">star</span>
                Rate this Unit
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
    @else
        <div
            class="mt-12 bg-surface-container-lowest p-8 lg:p-10 rounded-2xl border border-outline-variant/10 flex flex-col justify-center items-center text-center">
            <span class="material-symbols-outlined text-5xl lg:text-6xl text-primary mb-4">task_alt</span>
            <h4 class="text-xl lg:text-2xl font-bold mb-2 text-on-surface">You've Reviewed This Unit</h4>
            <p class="text-on-surface-variant mb-6">Thank you for sharing your feedback.</p>
            <a href="{{ route('student.ratings.edit', $existingReview->tracking_code ?? '#') }}"
                class="border-2 border-primary text-primary px-8 lg:px-10 py-3.5 lg:py-4 rounded-full font-bold hover:bg-primary hover:text-white transition-all duration-300 inline-flex items-center gap-2">
                <span class="material-symbols-outlined">edit</span>
                Edit Your Review
            </a>
        </div>
    @endif
@else
    <div
        class="mt-12 bg-linear-to-br from-primary/5 via-primary/10 to-tertiary/5 p-8 lg:p-10 rounded-2xl border-2 border-dashed border-primary/30 flex flex-col justify-center items-center text-center relative overflow-hidden">
        <div class="absolute -top-32 -right-32 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-tertiary/5 rounded-full blur-3xl"></div>

        <span class="material-symbols-outlined text-5xl lg:text-6xl text-primary mb-4 relative">rate_review</span>
        <h4 class="text-xl lg:text-2xl font-bold mb-3 text-on-surface relative">Rate this Unit</h4>
        <p class="text-on-surface-variant mb-8 max-w-md relative">Share your experience and help other students make better
            decisions about this unit.</p>

        <button onclick="triggerAuthModal()"
            class="bg-primary text-white px-8 lg:px-10 py-3.5 lg:py-4 rounded-full font-bold shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 hover:scale-105 active:scale-95 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-xl group-hover:animate-pulse">star</span>
                Rate this Unit
                <span
                    class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </span>
        </button>

        <div class="mt-6 flex items-center gap-2 text-on-surface-variant/50">
            <span class="material-symbols-outlined text-sm">info</span>
            <p class="text-xs">You'll need to sign in first to leave a review</p>
        </div>
    </div>
@endauth

@if ($ratings->hasPages())
    <div class="mt-12">
        {{ $ratings->appends(request()->query())->links('layouts.student.partials.pagination') }}
    </div>
@endif
