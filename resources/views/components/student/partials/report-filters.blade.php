@props(['currentFilters' => []])

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
            class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ empty($currentFilters['status']) ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Semua
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'new']) }}"
            class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ ($currentFilters['status'] ?? '') === 'new' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Baru
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}"
            class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ ($currentFilters['status'] ?? '') === 'in_progress' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Diproses
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'replied']) }}"
            class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ ($currentFilters['status'] ?? '') === 'replied' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Ditanggapi
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'resolved']) }}"
            class="px-4 py-2 rounded-full text-sm font-medium transition-all
                  {{ ($currentFilters['status'] ?? '') === 'resolved' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
            Selesai
        </a>
    </div>

    <form method="GET" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ $currentFilters['search'] ?? '' }}" placeholder="Cari laporan..."
            class="bg-surface-container-low border-0 rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 w-48">
        <button type="submit" class="p-2 rounded-full bg-primary/10 text-primary">
            <span class="material-symbols-outlined text-base">search</span>
        </button>
    </form>
</div>