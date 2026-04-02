@php $photos = $unit->photos ?? collect(); @endphp

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-extrabold text-on-surface tracking-tight mb-2">Visual Narrative</h2>
            <p class="text-on-surface-variant text-lg max-w-2xl">
                Explore the facilities and equipment of {{ $unit->name }} through our gallery.
            </p>
        </div>
        <div class="flex gap-4">
            <button
                class="w-12 h-12 rounded-full bg-surface-container-lowest flex items-center justify-center text-primary shadow-sm border border-outline-variant/10 hover:bg-primary-fixed transition-colors">
                <span class="material-symbols-outlined">share</span>
            </button>
        </div>
    </div>

    @if ($photos->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            @php
                $featured = $photos->first();
                $rest = $photos->skip(1);
            @endphp

            <div
                class="md:col-span-8 group cursor-pointer overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm relative md:h-[500px]">
                <img src="{{ $featured->url }}" alt="{{ $featured->alt_text ?? 'Gallery' }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div
                    class="absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-8">
                    @if($featured->alt_text)
                        <h3 class="text-white text-2xl font-bold">{{ $featured->alt_text }}</h3>
                    @endif
                </div>
            </div>

            @if ($rest->isNotEmpty())
                @php $side = $rest->first();
                $rest = $rest->skip(1); @endphp
                <div
                    class="md:col-span-4 group cursor-pointer overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm relative md:h-[500px]">
                    <img src="{{ $side->url }}" alt="{{ $side->alt_text ?? 'Gallery' }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    @if($side->alt_text)
                        <div class="absolute bottom-6 left-6 right-6 p-4 backdrop-blur-md bg-black/40 rounded-lg">
                            <p class="text-white font-bold text-lg leading-tight">{{ $side->alt_text }}</p>
                        </div>
                    @endif
                </div>
            @endif

            @foreach ($rest as $item)
                <div
                    class="md:col-span-4 group cursor-pointer overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm relative aspect-4/3">
                    <img src="{{ $item->url }}" alt="{{ $item->alt_text ?? 'Gallery' }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        @if($item->alt_text)
                            <p class="text-white text-sm font-medium">{{ $item->alt_text }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-32 text-center">
            <span class="material-symbols-outlined text-6xl text-outline/40 mb-4">photo_library</span>
            <p class="text-on-surface-variant font-medium">No gallery images yet.</p>
        </div>
    @endif

    <section class="bg-primary-container rounded-2xl p-12 overflow-hidden relative mt-8">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="max-w-xl">
                <h2 class="text-2xl font-bold text-white mb-3">Interested in collaboration?</h2>
                <p class="text-white/80">Our facilities are open for joint research projects and industrial
                    applications.</p>
            </div>
            <button
                class="bg-white text-primary px-8 py-4 rounded-full font-extrabold shadow-xl hover:scale-105 transition-transform active:scale-95 whitespace-nowrap">
                Request Facility Access
            </button>
        </div>
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-black/5 rounded-full blur-2xl pointer-events-none"></div>
    </section>
</div>