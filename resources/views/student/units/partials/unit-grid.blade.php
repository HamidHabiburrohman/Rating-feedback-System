<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="unitsGridInner">
    @forelse($units as $unit)
        @include('student.units.partials.unit-card', ['unit' => $unit])
    @empty
        <div class="col-span-full text-center py-20">
            <span class="material-symbols-outlined text-6xl text-outline">search_off</span>
            <p class="text-on-surface-variant mt-4">No units found.</p>
        </div>
    @endforelse
</div>