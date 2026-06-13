{{-- partials/review-form.blade.php --}}
{{-- Props: $unit --}}

@auth
    {{-- Check if user has already reviewed --}}
    @php
        $hasReviewed = auth()->user()->reviews()
            ->where('unit_id', $unit->id)
            ->exists();
    @endphp

    @if (!$hasReviewed)
        <div class="bg-primary/5 p-8 rounded-lg border-2 border-dashed border-primary/20 flex flex-col justify-center items-center text-center">
            <span class="material-symbols-outlined text-6xl text-primary mb-4">rate_review</span>
            <h4 class="text-xl font-bold mb-2">Used this Unit?</h4>
            <p class="text-sm text-on-surface-variant mb-8 px-4 leading-relaxed">
                Share your experience to help other researchers and improve our facilities.
            </p>
            <a
                href="{{ route('student.units.reviews.create', $unit) }}"
                class="bg-primary text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform inline-block">
                Write a Review
            </a>
        </div>
    @else
        <div class="bg-surface-container-low p-8 rounded-lg border border-outline-variant/10 flex flex-col justify-center items-center text-center">
            <span class="material-symbols-outlined text-5xl text-primary mb-4">task_alt</span>
            <h4 class="text-xl font-bold mb-2">You've Reviewed This Unit</h4>
            <p class="text-sm text-on-surface-variant mb-6 px-4">
                Thank you for sharing your feedback.
            </p>
            <a
                href="{{ route('student.units.reviews.edit', $unit) }}"
                class="border border-primary text-primary px-8 py-3 rounded-full font-bold hover:bg-primary hover:text-white transition-all inline-block">
                Edit Your Review
            </a>
        </div>
    @endif

@else
    {{-- Guest: prompt to login --}}
    <div class="bg-primary/5 p-8 rounded-lg border-2 border-dashed border-primary/20 flex flex-col justify-center items-center text-center">
        <span class="material-symbols-outlined text-6xl text-primary mb-4">rate_review</span>
        <h4 class="text-xl font-bold mb-2">Used this Unit?</h4>
        <p class="text-sm text-on-surface-variant mb-8 px-4 leading-relaxed">
            Log in to share your experience and help other researchers.
        </p>
        <a
            href="{{ route('student.login') }}"
            class="bg-primary text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform inline-block">
            Login to Review
        </a>
    </div>
@endauth