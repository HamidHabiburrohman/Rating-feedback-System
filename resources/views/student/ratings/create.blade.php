@extends('layouts.student.app')

@section('title', 'Submit Rating | Itenas Portal')

@section('content')
    <main class="min-h-screen pt-32 pb-20 flex items-center justify-center px-6">
        <div class="max-w-2xl w-full">

            {{-- Header --}}
            <div class="mb-8 text-center">
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-xs font-bold uppercase tracking-widest mb-4">
                    Unit Feedback
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-on-surface tracking-tight leading-tight">
                    Share your experience
                </h1>
                <p class="mt-4 text-on-surface-variant text-lg max-w-md mx-auto">
                    Your rating helps us improve the quality of our public service units.
                </p>
            </div>

            {{-- Error Alert --}}
            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-error/10 border border-error/20">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-error">error</span>
                        <div class="flex-1">
                            <p class="font-bold text-error text-sm">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-error/80 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-error/10 border border-error/20">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-error">error</span>
                        <p class="text-error text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 border border-green-200">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600">check_circle</span>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif


            {{-- Form Card --}}
            <div
                class="bg-surface-container-lowest rounded-lg p-8 md:p-12 shadow-[0_40px_80px_rgba(173,43,0,0.06)] border border-outline-variant/10">
                <form method="POST" action="{{ route('student.ratings.store') }}" class="space-y-12" x-data="ratingForm()"
                    id="ratingForm">
                    @csrf
                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                    {{-- Rating Categories Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($categories as $category)
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-on-surface tracking-tight">{{ $category['name'] }}</span>
                                </div>
                                <input type="hidden" name="scores[{{ $category['id'] }}]"
                                    x-model="ratings[{{ $category['id'] }}]" :value="ratings[{{ $category['id'] }}]">
                                <div class="flex gap-2">
                                    <template x-for="star in 5" :key="star">
                                        <button type="button" @click="setRating({{ $category['id'] }}, star)"
                                            @mouseenter="hoveredRating[{{ $category['id'] }}] = star"
                                            @mouseleave="hoveredRating[{{ $category['id'] }}] = 0" class="focus:outline-none">
                                            <span class="material-symbols-outlined text-3xl transition-all duration-150"
                                                :class="getStarClass({{ $category['id'] }}, star) ? 'text-primary' : 'text-slate-300'"
                                                :style="getStarClass({{ $category['id'] }}, star) ? 'font-variation-settings: &quot;FILL&quot; 1' : ''">
                                                star
                                            </span>
                                        </button>
                                    </template>
                                </div>
                                <div class="text-xs text-slate-400" x-show="ratings[{{ $category['id'] }}] > 0"
                                    x-text="'Selected: ' + ratings[{{ $category['id'] }}] + '/5'"></div>
                                @error("scores.{$category['id']}")
                                    <p class="text-error text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    {{-- Detailed Feedback --}}
                    <div class="space-y-4">
                        <label class="block text-xs font-extrabold uppercase tracking-widest text-on-surface-variant ml-2">
                            Detailed Feedback
                        </label>
                        <div class="relative">
                            <textarea name="comment" rows="4"
                                class="w-full bg-surface-container-low border-none rounded-lg p-6 text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                                placeholder="Tell us more about your experience... What did you like? What could be better?">{{ old('comment') }}</textarea>
                        </div>
                        @error('comment')
                            <p class="text-error text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-6">
                        <button type="submit" id="submitBtn"
                            class="w-full py-4 rounded-full bg-red-600 text-white font-bold text-lg shadow-lg hover:bg-red-700 transition-all duration-300">
                            Submit Rating
                        </button>
                    </div>
                </form>
            </div>

            {{-- Unit Info Footer --}}
            <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-8 px-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-200 ring-4 ring-white shadow-sm">
                        <img class="w-full h-full object-cover"
                            src="{{ $unit->primaryPhoto->thumbnail_url ?? asset('images/unit-placeholder.png') }}"
                            alt="{{ $unit->name }}">
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface">{{ $unit->name }}</h4>
                        <p class="text-sm text-on-surface-variant">{{ $unit->department->name ?? 'Itenas' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <span
                        class="px-4 py-2 rounded-full bg-surface-container-high text-on-surface text-xs font-bold border border-outline-variant/10">
                        {{ now()->format('M d, Y') }}
                    </span>
                </div>
            </div>

        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function ratingForm() {
            return {
                ratings: {},
                hoveredRating: {},

                setRating(categoryId, value) {
                    this.ratings[categoryId] = value;
                    console.log('Rating set:', categoryId, value, this.ratings);
                },

                getStarClass(categoryId, star) {
                    const rating = this.ratings[categoryId] || 0;
                    const hovered = this.hoveredRating[categoryId] || 0;
                    return (hovered >= star) || (hovered === 0 && rating >= star);
                }
            }
        }

        document.addEventListener('alpine:init', () => {
            window.ratingForm = ratingForm;
        });

        // Debug form submission
        document.getElementById('ratingForm')?.addEventListener('submit', function (e) {
            console.log('Form submitted');
            console.log('Form data:', new FormData(this));
        });
    </script>
@endpush