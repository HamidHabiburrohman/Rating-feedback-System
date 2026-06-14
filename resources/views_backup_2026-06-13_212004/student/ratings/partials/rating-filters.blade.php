@props(['currentFilters' => []])

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ empty($currentFilters['status']) ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Semua
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ ($currentFilters['status'] ?? '') === 'active' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Aktif
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'edited']) }}" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ ($currentFilters['status'] ?? '') === 'edited' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Diedit
        </a>
    </div>
    
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-on-surface-variant text-sm">sort</span>
        <select name="sort" onchange="window.location.href=this.value" 
                class="bg-surface-container-low border-0 rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20">
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc']) }}" 
                    {{ (request('sort') ?? 'created_at') === 'created_at' && request('order') !== 'asc' ? 'selected' : '' }}>
                Terbaru
            </option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'asc']) }}"
                    {{ request('sort') === 'created_at' && request('order') === 'asc' ? 'selected' : '' }}>
                Terlama
            </option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'overall_score', 'order' => 'desc']) }}"
                    {{ request('sort') === 'overall_score' && request('order') !== 'asc' ? 'selected' : '' }}>
                Skor Tertinggi
            </option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'overall_score', 'order' => 'asc']) }}"
                    {{ request('sort') === 'overall_score' && request('order') === 'asc' ? 'selected' : '' }}>
                Skor Terendah
            </option>
        </select>
    </div>
</div>