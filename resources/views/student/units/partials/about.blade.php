@php
    // HAPUS INI:
    // use App\Services\Student\RatingService;
    // $ratingService = app(RatingService::class);
    // $stats = $ratingService->getRatingStats($unit->id);
    
    // GANTI DENGAN INI - gunakan $stats yang dikirim dari controller
    $categoryAverages = $stats['by_category'] ?? [];
    $totalRatings = $stats['total'] ?? 0;
    $avgOverall = $stats['average'] ?? 0;
    $avgRounded = round($avgOverall);
    $emptyStars = 5 - $avgRounded;
    
    // Untuk categories, tetap panggil service
    $ratingService = app(\App\Services\Student\RatingService::class);
    $categories = $ratingService->getActiveCategories();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-16">

    <div class="lg:col-span-2 space-y-12">

        <div class="space-y-6">
            <h2 class="text-3xl font-bold tracking-tight text-slate-800">Unit Overview</h2>
            <p class="text-on-surface-variant text-lg leading-relaxed">
                {{ $unit->description ?? 'Belum ada deskripsi untuk unit ini.' }}
            </p>
        </div>

        @if(isset($unit->facilities) && $unit->facilities->count())
            <div class="space-y-8">
                <h3 class="text-2xl font-bold text-slate-800">Available Facilities</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($unit->facilities as $facility)
                        <div class="flex items-start space-x-4 p-6 rounded-lg bg-surface-container-lowest transition-colors group border">
                            <div class="w-12 h-12 rounded-full bg-[#ffffff] flex items-center justify-center group-hover:scale-110 transition-transform shrink-0 border">
                                @if(method_exists($facility, 'getIconSvg'))
                                    <div class="text-[#DD3A00] w-6 h-6 flex items-center justify-center">
                                        {!! $facility->getIconSvg() !!}
                                    </div>
                                @else
                                    <span class="material-symbols-outlined text-[#DD3A00]">apartment</span>
                                @endif
                            </div>
                            <div class="text-center justify-center m-auto">
                                <h4 class="font-bold text-slate-800">{{ $facility->name }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <div class="lg:col-span-1 space-y-8">

        <div class="p-8 rounded-lg bg-surface-container-lowest shadow-[0_20px_40px_rgba(173,43,0,0.04)] border">
            <h3 class="text-xl font-bold mb-6">Rating Summary</h3>

            <div class="flex flex-col items-center mb-8">
                <span class="text-7xl font-black text-on-surface mb-2">
                    {{ number_format($avgOverall, 1) }}
                </span>
                <div class="flex text-primary mb-2 scale-125">
                    @for($i = 0; $i < $avgRounded; $i++)
                        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">star</span>
                    @endfor
                    @for($i = 0; $i < $emptyStars; $i++)
                        <span class="material-symbols-outlined">star</span>
                    @endfor
                </div>
                <span class="text-on-surface-variant text-sm">
                    Based on {{ $totalRatings }} student {{ Str::plural('review', $totalRatings) }}
                </span>
            </div>

            <div class="space-y-4">
                @forelse($categories as $category)
                    @php
                        $categorySlug = $category['slug'];
                        $categoryScore = $categoryAverages[$categorySlug] ?? 0;
                        $percentage = $categoryScore > 0 ? ($categoryScore / 5) * 100 : 0;
                    @endphp
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-bold text-slate-600 uppercase tracking-tighter">
                            <span>{{ $category['name'] }}</span>
                            <span>{{ number_format($categoryScore, 1) }}</span>
                        </div>
                        <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all duration-700"
                                style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-on-surface-variant text-center text-sm">Belum ada kategori rating</p>
                @endforelse
            </div>
        </div>

        @if($unit->open_time && $unit->close_time)
            <div class="p-8 rounded-lg lg:rounded-2rem bg-surface-container-lowest shadow-[0_20px_40px_rgba(173,43,0,0.04)] border border-outline-variant/10">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-[#FFEDE9] flex items-center justify-center text-[#DD3A00]">
                        <span class="material-symbols-outlined text-xl">schedule</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Operational Times</h3>
                </div>
                <div class="space-y-4">
                    @foreach($unit->operational_schedule as $schedule)
                        <div class="flex justify-between items-center {{ !$loop->last ? 'pb-3 border-b border-outline-variant/10' : '' }}">
                            <span class="font-semibold text-slate-800">{{ $schedule['days'] }}</span>
                            @if($schedule['is_closed'])
                                <span class="text-red-500 font-medium">Closed</span>
                            @else
                                <span class="text-slate-500">{{ $schedule['open'] }} - {{ $schedule['close'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($unit->lokasi ?? 'Itenas Main Campus') }}"
           target="_blank"
           rel="noopener noreferrer"
           class="rounded-lg bg-surface-container overflow-hidden h-64 relative group block hover:shadow-lg transition-all duration-300 cursor-pointer">

            <div class="w-full h-full bg-linear-to-br from-slate-200 via-slate-100 to-slate-200 flex items-center justify-center relative overflow-hidden">
                <svg class="absolute inset-0 w-full h-full opacity-20" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="map-grid" width="32" height="32" patternUnits="userSpaceOnUse">
                            <path d="M 32 0 L 0 0 0 32" fill="none" stroke="#94a3b8" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#map-grid)" />
                </svg>

                <div class="relative z-10">
                    <div class="relative">
                        <div class="absolute -inset-3 bg-primary/20 rounded-full animate-ping opacity-60 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-14 h-14 rounded-full bg-white shadow-lg flex items-center justify-center relative z-10 transition-all duration-300 group-hover:scale-110 group-hover:bg-primary">
                            <span class="material-symbols-outlined text-2xl text-primary transition-colors group-hover:text-white"
                                style="font-variation-settings:'FILL' 1;">location_on</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/30 to-transparent flex items-end p-6 transition-all duration-300 group-hover:from-black/80">
                <div class="text-white transform transition-transform duration-300 group-hover:translate-y-[-4px]">
                    @if($unit->gedung)
                        <p class="text-xs font-bold uppercase tracking-widest opacity-80">
                            Gedung {{ $unit->gedung }}@if($unit->lantai), Lantai {{ $unit->lantai }}@endif
                        </p>
                    @endif

                    <h4 class="text-lg font-bold flex items-center gap-2">
                        {{ $unit->lokasi ?? 'Itenas Main Campus' }}
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </h4>

                    @if($unit->kode_unit)
                        <p class="text-xs opacity-70 mt-0.5">Kode: {{ $unit->kode_unit }}</p>
                    @endif

                    <p class="text-xs text-white/50 mt-2 flex items-center gap-1 group-hover:text-white/80 transition-colors">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        Klik untuk buka Google Maps
                    </p>
                </div>
            </div>
        </a>

        @if($unit->kontak_telepon || $unit->kontak_email)
            <div class="p-6 rounded-lg bg-surface-container-lowest shadow-[0_20px_40px_rgba(173,43,0,0.04)] border border-outline-variant/10 space-y-3">
                <h3 class="text-base font-bold text-slate-800 mb-4">Kontak</h3>

                @if($unit->kontak_telepon)
                    <a href="tel:{{ $unit->kontak_telepon }}"
                        class="flex items-center gap-3 text-sm text-slate-600 hover:text-primary transition-colors group">
                        <div class="w-8 h-8 rounded-full bg-[#FFEDE9] flex items-center justify-center text-[#DD3A00] shrink-0 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-base">phone</span>
                        </div>
                        <span class="font-medium">{{ $unit->kontak_telepon }}</span>
                    </a>
                @endif

                @if($unit->kontak_email)
                    <a href="mailto:{{ $unit->kontak_email }}"
                        class="flex items-center gap-3 text-sm text-slate-600 hover:text-primary transition-colors group">
                        <div class="w-8 h-8 rounded-full bg-[#FFEDE9] flex items-center justify-center text-[#DD3A00] shrink-0 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-base">mail</span>
                        </div>
                        <span class="font-medium">{{ $unit->kontak_email }}</span>
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>

@if(isset($unit->photos) && $unit->photos->isNotEmpty())
    <section class="space-y-8 mt-20">
        <div class="flex justify-between items-end">
            <h2 class="text-3xl font-bold tracking-tight text-slate-800">Inside the Unit</h2>
            <button onclick="window.dispatchEvent(new CustomEvent('switch-tab', { detail: { tab: 'gallery' } }))"
                class="text-primary font-bold flex items-center space-x-1 group hover:opacity-80 transition-opacity">
                <span>View Full Gallery</span>
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">
                    arrow_forward
                </span>
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($unit->photos->take(4) as $photo)
                <div class="rounded-lg overflow-hidden h-48 bg-surface-container">
                    <img src="{{ $photo->thumbnail_url ?? $photo->url }}" alt="{{ $photo->alt_text ?? $unit->name }}"
                        class="w-full h-full object-cover transition-transform duration-500">
                </div>
            @endforeach

            @for($i = $unit->photos->take(4)->count(); $i < 4; $i++)
                <div class="rounded-lg overflow-hidden h-48 bg-surface-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">image</span>
                </div>
            @endfor
        </div>
    </section>
@endif