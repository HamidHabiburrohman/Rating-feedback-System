@props(['unit', 'categories', 'rating' => null])

<form method="POST"
    action="{{ $rating ? route('student.ratings.update', $rating->tracking_code) : route('student.ratings.store') }}"
    class="space-y-8">
    @csrf
    @if($rating)
        @method('PUT')
    @endif

    <input type="hidden" name="unit_id" value="{{ $unit->id }}">

    <!-- Rating Categories Grid -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-on-surface">Kategori Penilaian</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($categories as $category)
                @php
                    $existingScore = $rating ? $rating->scores->firstWhere('rating_category_id', $category['id'])?->score : null;
                @endphp
                <x-student.partials.category-stars :category="$category" :value="$existingScore" />
            @endforeach
        </div>
        @error('scores')
            <p class="text-error text-sm">{{ $message }}</p>
        @enderror
    </div>

    <!-- Komentar -->
    <div class="space-y-3">
        <label class="block text-sm font-bold uppercase tracking-wider text-on-surface-variant">
            Komentar <span class="font-normal lowercase">(opsional)</span>
        </label>
        <textarea name="comment" rows="4"
            class="w-full bg-surface-container-low rounded-xl p-4 text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all resize-none border-0"
            placeholder="Ceritakan pengalaman Anda... Apa yang Anda sukai? Apa yang bisa ditingkatkan?">{{ old('comment', $rating->comment ?? '') }}</textarea>
        @error('comment')
            <p class="text-error text-sm">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit"
            class="w-full py-4 rounded-full bg-linear-to-r from-primary to-primary-container text-white font-bold text-lg shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all duration-300">
            {{ $rating ? 'Perbarui Rating' : 'Kirim Rating' }}
        </button>
    </div>
</form>