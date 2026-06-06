<section class="max-w-7xl mx-auto px-8 py-12 flex flex-wrap gap-3 justify-center" id="filterSection">
    <button type="button" data-filter-url="{{ route('student.units.index') }}" data-type="all"
        class="filter-btn px-6 py-2 rounded-full {{ !request('type') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-primary-fixed hover:text-on-primary-fixed' }} font-bold text-sm transition-colors">
        All Units
    </button>
    @foreach($unitTypes as $type)
        <button type="button" data-filter-url="{{ route('student.units.index', ['type' => $type['id']]) }}"
            data-type="{{ $type['id'] }}"
            class="filter-btn px-6 py-2 rounded-full {{ request('type') == $type['id'] ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-primary-fixed hover:text-on-primary-fixed' }} font-medium text-sm transition-colors">
            {{ $type['name'] }}
        </button>
    @endforeach
</section>