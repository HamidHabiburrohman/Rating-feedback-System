@props(['rating', 'report' => null])

<form method="POST" 
      action="{{ $report ? route('student.reports.update', $report->tracking_code) : route('student.reports.store') }}" 
      class="space-y-8">
    @csrf
    @if($report)
        @method('PUT')
    @endif
    
    <input type="hidden" name="rating_id" value="{{ $rating->id }}">
    
    <!-- Judul Laporan -->
    <div class="space-y-2">
        <label class="block text-sm font-bold uppercase tracking-wider text-on-surface-variant">
            Judul Laporan <span class="text-error">*</span>
        </label>
        <input type="text" 
               name="title" 
               value="{{ old('title', $report->title ?? '') }}"
               class="w-full px-5 py-3 bg-surface-container-low rounded-xl border-0 focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-on-surface-variant/50"
               placeholder="Contoh: Fasilitas AC tidak berfungsi">
        @error('title')
            <p class="text-error text-sm">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Prioritas -->
    <div class="space-y-3">
        <label class="block text-sm font-bold uppercase tracking-wider text-on-surface-variant">
            Prioritas <span class="text-error">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'critical' => 'Kritis'] as $value => $label)
                <label class="cursor-pointer">
                    <input type="radio" 
                           name="priority" 
                           value="{{ $value }}"
                           {{ old('priority', $report->priority ?? 'medium') === $value ? 'checked' : '' }}
                           class="peer hidden">
                    <div class="text-center py-3 rounded-xl border border-outline-variant/20 bg-surface-container-low peer-checked:bg-primary/10 peer-checked:border-primary transition-all">
                        <span class="text-sm font-medium peer-checked:text-primary">{{ $label }}</span>
                    </div>
                </label>
            @endforeach
        </div>
        @error('priority')
            <p class="text-error text-sm">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Deskripsi Detail -->
    <div class="space-y-2">
        <label class="block text-sm font-bold uppercase tracking-wider text-on-surface-variant">
            Deskripsi Lengkap <span class="text-error">*</span>
        </label>
        <textarea 
            name="description" 
            rows="6"
            class="w-full px-5 py-3 bg-surface-container-low rounded-xl border-0 focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-on-surface-variant/50 resize-none"
            placeholder="Jelaskan secara detail masalah yang Anda alami...">{{ old('description', $report->description ?? '') }}</textarea>
        @error('description')
            <p class="text-error text-sm">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Submit Button -->
    <button type="submit" 
            class="w-full py-4 rounded-full bg-linear-to-r from-primary to-primary-container text-white font-bold text-lg shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
        <span class="material-symbols-outlined">send</span>
        {{ $report ? 'Perbarui Laporan' : 'Kirim Laporan' }}
    </button>
</form>