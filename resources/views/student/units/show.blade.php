@extends('layouts.student.app')

@section('title', $unit->name . ' | Itenas Portal')

@section('content')

    @php
        $primaryPhoto = $unit->photos->where('is_primary', true)->first() ?? $unit->photos->first();
        $imageUrl = $primaryPhoto
            ? ($primaryPhoto->url ?? asset('images/unit-placeholder.jpg'))
            : asset('images/unit-placeholder.jpg');
        $avgRounded = round($unit->avg_rating ?? 0);
        $emptyStars = 5 - $avgRounded;
    @endphp

    <div class="pt-24 pb-20 max-w-7xl mx-auto px-8">

        {{-- ════════════════════════════════════════════
        HERO SECTION
        ════════════════════════════════════════════ --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

            {{-- Cover Image --}}
            <div class="lg:col-span-8">
                <div class="relative overflow-hidden rounded-lg aspect-16/9 shadow-lg group">
                    <img src="{{ $imageUrl }}" alt="{{ $unit->name }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

                    @if($unit->type)
                        <div class="absolute top-6 left-6">
                            <span
                                class="bg-tertiary-fixed text-on-tertiary-fixed-variant px-4 py-1 rounded-full text-xs font-bold tracking-widest uppercase">
                                {{ $unit->type->name }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Meta Info --}}
            <div class="lg:col-span-4 flex flex-col justify-center space-y-6">

                <div>
                    <h1 class="text-5xl font-extrabold text-on-surface tracking-tight mb-2">
                        {{ $unit->name }}
                    </h1>
                    <p class="text-on-surface-variant text-lg">
                        {{ $unit->department->name ?? 'Itenas' }}
                    </p>
                </div>

                {{-- Stars + Score --}}
                <div class="flex items-center space-x-2">
                    <div class="flex text-primary">
                        @for($i = 0; $i < $avgRounded; $i++)
                            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">star</span>
                        @endfor
                        @for($i = 0; $i < $emptyStars; $i++)
                            <span class="material-symbols-outlined">star</span>
                        @endfor
                    </div>
                    <span class="font-bold text-xl">{{ number_format($unit->avg_rating ?? 0, 1) }}</span>
                    <span class="text-on-surface-variant text-sm">({{ $unit->total_ratings ?? 0 }} Reviews)</span>
                </div>

                {{-- Dropdown Menu --}}
                <div class="relative flex items-start gap-2" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                            class="p-3 rounded-full hover:bg-surface-container-high transition-colors flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            x-cloak
                            class="absolute left-0 mt-2 w-48 bg-white rounded-[1rem] shadow-lg border border-outline-variant/10 py-2 z-20 overflow-hidden origin-top-left">

                            <button
                                class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                                <span>Favorite</span>
                            </button>

                            @auth
                                <a href="{{ route('student.reports.create', $unit->id) }}"
                                    class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                                    <span class="material-symbols-outlined text-[20px]">flag</span>
                                    <span>Report Issue</span>
                                </a>
                            @else
                                <button
                                    class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                                    <span class="material-symbols-outlined text-[20px]">flag</span>
                                    <span>Report Issue</span>
                                </button>
                            @endauth

                            <button onclick="navigator.clipboard.writeText(window.location.href).then(() => {
                                            const el = this.querySelector('span:last-child');
                                            el.textContent = 'Copied!';
                                            setTimeout(() => el.textContent = 'Share', 1500);
                                        })"
                                class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                                <span class="material-symbols-outlined text-[20px]">share</span>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ════════════════════════════════════════════
        TAB NAVIGATION
        ════════════════════════════════════════════ --}}
        <nav class="flex border-b-2 border-outline-variant/10 space-x-12 mb-10">
            <button data-tab="about"
                class="tab-button pb-4 text-lg transition-all -mb-[2px] text-primary border-b-[3px] border-primary font-bold">
                About
            </button>
            <button data-tab="gallery"
                class="tab-button pb-4 text-lg transition-all -mb-[2px] text-on-surface-variant hover:text-on-surface font-medium">
                Gallery
            </button>
            <button data-tab="reviews"
                class="tab-button pb-4 text-lg transition-all -mb-[2px] text-on-surface-variant hover:text-on-surface font-medium">
                Reviews
                @if(($unit->total_ratings ?? 0) > 0)
                    <span class="ml-2 text-xs font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">
                        {{ $unit->total_ratings }}
                    </span>
                @endif
            </button>
        </nav>

        {{-- ════════════════════════════════════════════
        TAB CONTENTS
        ════════════════════════════════════════════ --}}
        <div id="tab-about" class="tab-content">
            @include('student.units.partials.about', ['unit' => $unit])
        </div>

        <div id="tab-gallery" class="tab-content hidden">
            @include('student.units.partials.gallery', ['unit' => $unit])
        </div>

        <div id="tab-reviews" class="tab-content hidden">
            @include('student.units.partials.reviews-tab', ['unit' => $unit])
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabs = document.querySelectorAll('.tab-button');
                const contents = {
                    about: document.getElementById('tab-about'),
                    gallery: document.getElementById('tab-gallery'),
                    reviews: document.getElementById('tab-reviews'),
                };

                function activateTab(tabId) {
                    Object.values(contents).forEach(c => c.classList.add('hidden'));
                    if (contents[tabId]) contents[tabId].classList.remove('hidden');

                    tabs.forEach(btn => {
                        const isActive = btn.getAttribute('data-tab') === tabId;
                        btn.classList.toggle('text-primary', isActive);
                        btn.classList.toggle('border-b-[3px]', isActive);
                        btn.classList.toggle('border-primary', isActive);
                        btn.classList.toggle('font-bold', isActive);
                        btn.classList.toggle('text-on-surface-variant', !isActive);
                        btn.classList.toggle('hover:text-on-surface', !isActive);
                        btn.classList.toggle('font-medium', !isActive);
                    });
                }

                tabs.forEach(btn => {
                    btn.addEventListener('click', function () {
                        activateTab(this.getAttribute('data-tab'));
                    });
                });

                // Triggered from "View Full Gallery" inside about tab
                window.addEventListener('switch-tab', function (e) {
                    if (e.detail?.tab) activateTab(e.detail.tab);
                });
            });
        </script>
    @endpush

@endsection