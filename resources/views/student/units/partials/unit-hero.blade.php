{{-- resources/views/student/units/partials/unit-hero.blade.php --}}
@php
    $primaryPhoto = $unit->photos->where('is_primary', true)->first() ?? $unit->photos->first();
    $imageUrl = $primaryPhoto ? ($primaryPhoto->url ?? asset('images/unit-placeholder.jpg'))
        : asset('images/unit-placeholder.jpg');
    $avgRounded = round($unit->avg_rating ?? 0);
    $emptyStars = 5 - $avgRounded;
@endphp

<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-4">

    {{-- ── Left: Cover Image ── --}}
    <div class="lg:col-span-8">
        <div class="relative overflow-hidden rounded-lg aspect-video shadow-lg group">
            <img src="{{ $imageUrl }}" alt="{{ $unit->name }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

            {{-- Unit Type Badge --}}
            @if($unit->type)
                <div class="absolute top-6 left-6">
                    <span
                        class="bg-tertiary-fixed text-on-tertiary-fixed-variant px-4 py-1 rounded-full text-xs font-bold tracking-widest uppercase shadow-sm">
                        {{ $unit->type->name }}
                    </span>
                </div>
            @endif

            {{-- Status Badge --}}
            @if(isset($unit->status_aktif))
                <div class="absolute top-6 right-6">
                    <span @class([
                        'px-3 py-1 rounded-full text-xs font-bold tracking-wide shadow-sm',
                        'bg-green-100 text-green-700' => $unit->status_aktif,
                        'bg-red-100   text-red-600' => !$unit->status_aktif,
                    ])>
                        {{ $unit->status_aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Right: Meta Info ── --}}
    <div class="lg:col-span-4 flex flex-col justify-center space-y-6">

        {{-- Title & Department --}}
        <div>
            <h1 class="text-4xl xl:text-5xl font-extrabold text-on-surface tracking-tight leading-tight mb-2">
                {{ $unit->name }}
            </h1>
            <p class="text-on-surface-variant text-base lg:text-lg">
                {{ $unit->department->name ?? 'Itenas' }}
            </p>
        </div>

        {{-- Rating Row --}}
        <div class="flex items-center space-x-2">
            <div class="flex text-primary">
                @for($i = 0; $i < $avgRounded; $i++)
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1;">star</span>
                @endfor
                @for($i = 0; $i < $emptyStars; $i++)
                    <span class="material-symbols-outlined text-xl">star</span>
                @endfor
            </div>
            <span class="font-bold text-xl text-on-surface">
                {{ number_format($unit->avg_rating ?? 0, 1) }}
            </span>
            <span class="text-on-surface-variant text-sm">
                ({{ $unit->total_ratings ?? 0 }} Reviews)
            </span>
        </div>

        {{-- Action Dropdown --}}
        {{-- <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false"
                class="p-3 rounded-full hover:bg-surface-container-high transition-colors flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined">more_vert</span>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" x-cloak
                class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-lg border border-outline-variant/10 py-2 z-20 overflow-hidden origin-top-left">

                <button
                    class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                    <span class="material-symbols-outlined text-xl">favorite</span>
                    <span>Favorite</span>
                </button>

                @auth
                    <a href="{{ route('student.reports.create',  $unit->id]) }}"
                        class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                        <span class="material-symbols-outlined text-xl">flag</span>
                        <span>Report Issue</span>
                    </a>
                @else
                    <button
                        class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                        <span class="material-symbols-outlined text-xl">flag</span>
                        <span>Report Issue</span>
                    </button>
                @endauth

                <button onclick="navigator.clipboard.writeText(window.location.href).then(() => {
                            const el = this.querySelector('span:last-child');
                            el.textContent = 'Copied!';
                            setTimeout(() => el.textContent = 'Share', 1500);
                         })"
                    class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                    <span class="material-symbols-outlined text-xl">share</span>
                    <span>Share</span>
                </button>
            </div>
        </div> --}}

    </div>
</section>