@extends('layouts.student.app')

@section('title', 'Edit Rating - ' . $rating->unit->name)

@section('content')
<div class="min-h-screen pt-32 pb-20 flex items-center justify-center px-6 bg-surface">
    <div class="max-w-2xl w-full">
        <div class="mb-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-xs font-bold uppercase tracking-widest mb-4">
                Edit Rating
            </span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight">
                {{ $rating->unit->name }}
            </h1>
            <p class="mt-2 text-on-surface-variant text-sm">
                Kode Tracking: <span class="font-mono font-bold">{{ $rating->tracking_code }}</span>
            </p>
        </div>
        
        <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-10 shadow-lg border border-outline-variant/10">
            @php
    // Persiapan data untuk Alpine.js - konversi ke float
    $initialRatings = [];
    foreach ($categories as $category) {
        // Konversi score dari string ke float (5.0 -> 5)
        $initialRatings[$category['id']] = isset($category['score']) ? (float) $category['score'] : 0;
    }
@endphp
            
            <form method="POST" action="{{ route('student.ratings.update', $rating->tracking_code) }}" class="space-y-8" x-data="ratingForm({{ json_encode($initialRatings) }})">
                @csrf
                @method('PUT')
                <input type="hidden" name="unit_id" value="{{ $rating->unit->id }}">
                
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-on-surface">Kategori Penilaian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($categories as $category)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-xl">star</span>
                                    <span class="font-semibold text-on-surface">{{ $category['name'] }}</span>
                                </div>
                                <span class="text-sm text-on-surface-variant" x-text="ratings[{{ $category['id'] }}] ? ratings[{{ $category['id'] }}] + '/5' : 'Belum dinilai'"></span>
                            </div>
                            <input type="hidden" name="scores[{{ $category['id'] }}]" x-model="ratings[{{ $category['id'] }}]">
                            <div class="flex gap-2">
                                <template x-for="star in 5" :key="star">
                                    <button type="button"
                                        @click="setRating({{ $category['id'] }}, star)"
                                        @mouseenter="hoveredRating[{{ $category['id'] }}] = star"
                                        @mouseleave="hoveredRating[{{ $category['id'] }}] = 0"
                                        class="focus:outline-none transition-transform hover:scale-110">
                                        <span class="material-symbols-outlined text-3xl transition-all duration-150"
                                            :class="(hoveredRating[{{ $category['id'] }}] >= star || (hoveredRating[{{ $category['id'] }}] === 0 && ratings[{{ $category['id'] }}] >= star)) ? 'text-primary' : 'text-outline'"
                                            :style="(hoveredRating[{{ $category['id'] }}] >= star || (hoveredRating[{{ $category['id'] }}] === 0 && ratings[{{ $category['id'] }}] >= star)) ? 'font-variation-settings: &quot;FILL&quot; 1' : ''">
                                            star
                                        </span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('scores')
                        <p class="text-error text-sm">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-bold uppercase tracking-wider text-on-surface-variant">
                        Komentar <span class="font-normal lowercase">(opsional)</span>
                    </label>
                    <textarea 
                        name="comment" 
                        rows="4"
                        class="w-full bg-surface-container-low rounded p-4 text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all resize-none border-0"
                        placeholder="Ceritakan pengalaman Anda..."
                    >{{ old('comment', $rating->comment) }}</textarea>
                    @error('comment')
                        <p class="text-error text-sm">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 rounded-full bg-red-600 text-white font-bold text-lg shadow-lg hover:bg-red-700 transition-all duration-300">
                        Perbarui Rating
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-6 text-center text-xs text-on-surface-variant">
            <span class="material-symbols-outlined text-sm align-middle">info</span>
            Rating hanya dapat diedit dalam waktu 7 hari setelah laporan terakhir diselesaikan
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Register component SEBELUM Alpine.js init
    window.ratingForm = function(initialRatings = {}) {
        return {
            ratings: initialRatings,
            hoveredRating: {},
            init() {
                console.log('Initial ratings:', this.ratings);
            },
            setRating(categoryId, value) {
                this.ratings[categoryId] = value;
                console.log(`Rating for category ${categoryId} set to ${value}`);
            }
        }
    }
</script>
@endpush