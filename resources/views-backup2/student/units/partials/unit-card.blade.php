<div class="group bg-surface-container-lowest rounded-lg p-4 border border-outline-variant/30 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_40px_80px_rgba(173,43,0,0.08)]">
    <div class="relative h-64 w-full mb-6 overflow-hidden rounded-lg">
        <img src="{{ $unit->primaryPhoto->thumbnail_url ?? asset('assets/images/UnitPlaceholder.png') }}" alt="{{ $unit->name }}"
            class="w-full h-full object-cover transition-transform duration-500">
        <span class="absolute top-4 left-4 bg-tertiary-fixed text-on-tertiary-fixed-variant px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase">{{ $unit->type->name ?? 'Unit' }}</span>
    </div>
    <div class="px-2">
        <h3 class="text-2xl font-bold text-on-surface mb-3 tracking-tight">{{ $unit->name }}</h3>
        <div class="flex flex-col gap-2 mb-8">
            <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-sm">location_on</span>
                <span>{{ $unit->location }}</span>
            </div>
            <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-sm">group</span>
                <span>Capacity: {{ $unit->capacity }} Persons</span>
            </div>
        </div>
        <div class="flex items-center justify-between pt-4 border-t border-outline-variant/10">
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-primary text-xl fill-icon">star</span>
                <span class="font-bold text-on-surface">{{ number_format($unit->avg_rating, 1) }}</span>
                <span class="text-on-surface-variant text-xs">({{ $unit->total_ratings }} reviews)</span>
            </div>
            <a href="{{ route('student.units.show', $unit->slug) }}"
                class="bg-primary text-white px-6 py-2 rounded-full font-bold text-sm hover:bg-primary-container transition-transform active:scale-95">
                View
            </a>
        </div>
    </div>
</div>