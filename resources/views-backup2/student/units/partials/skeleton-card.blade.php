<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12" id="unitsGridInner">
    @if($loading ?? false)
        @for($i = 0; $i < 6; $i++)
            @include('student.units.partials.skeleton-card')
        @endfor
    @else
        @forelse($units as $unit)
            @include('student.units.partials.unit-grid', ['unit' => $unit])
        @empty
            <div class="col-span-full text-center py-20">
                <span class="material-symbols-outlined text-6xl text-outline">search_off</span>
                <p class="text-on-surface-variant mt-4">No units found.</p>
            </div>
        @endforelse
    @endif
</div>