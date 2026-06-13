<div class="rounded-xl bg-surface-container overflow-hidden h-64 relative group shadow-lg">
    <div class="w-full h-full bg-linear-to-br from-primary/20 to-primary/5 flex items-center justify-center">
        <div class="text-center p-6">
            <span class="material-symbols-outlined text-5xl text-primary">location_on</span>
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mt-2">
                {{ $unit->building ?? 'Building' }}</p>
            <h4 class="text-lg font-bold text-on-surface">Floor {{ $unit->floor ?? '-' }}</h4>
            <p class="text-sm text-on-surface-variant mt-1">{{ $unit->location ?? 'Location not specified' }}</p>
        </div>
    </div>
</div>