<section class="max-w-7xl mx-auto px-8 py-12 flex flex-wrap gap-3 justify-center">
    <a href="{{ route('student.units.index') }}"
        class="px-6 py-2 rounded-full {{ !request('type') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-primary-fixed hover:text-on-primary-fixed' }} font-bold text-sm transition-colors">
        All Units
    </a>
    @foreach($unitTypes as $type)
        <a href="{{ route('student.units.index', ['type' => $type['id']]) }}"
            class="px-6 py-2 rounded-full {{ request('type') == $type['id'] ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-primary-fixed hover:text-on-primary-fixed' }} font-medium text-sm transition-colors">
            {{ $type['name'] }}
        </a>
    @endforeach
</section>