@extends('layouts.student.app')

@section('title', $unit->name . ' | Itenas Portal')

@section('content')

    @php
        $imageUrl = asset('assets/images/UnitPlaceholder.png');

        if ($unit->primaryPhoto) {
            $photo = $unit->primaryPhoto;
            $url = $photo->thumbnail_url ?? ($photo->original_url ?? null);
            if ($url) {
                $imageUrl = $url;
            }
        } elseif ($unit->photos->isNotEmpty()) {
            $photo = $unit->photos->first();
            $url = $photo->thumbnail_url ?? ($photo->original_url ?? null);
            if ($url) {
                $imageUrl = $url;
            }
        }

        $avgRounded = round($unit->avg_rating ?? 0);
        $emptyStars = 5 - $avgRounded;
    @endphp
    <div class="pt-24 pb-20 max-w-7xl mx-auto px-8">


        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

            <div class="lg:col-span-8">
                <div class="relative overflow-hidden rounded-lg aspect-16/9 shadow-lg group border">
                    <img src="{{ $imageUrl }}" alt="{{ $unit->name }}" class="w-full h-full object-cover">

                    <a href="{{ route('student.units.index') }}"
                        class="absolute top-6 left-6 z-10 inline-flex items-center justify-center w-10 h-10 rounded-full bg-black/30 backdrop-blur-sm text-white hover:bg-black/50 hover:scale-105 transition-all duration-200 border border-white/20">
                        <span class="material-symbols-outlined text-xl">arrow_back</span>
                    </a>

                    @if ($unit->type)
                        <div class="absolute top-6 right-6">
                            <span
                                class="bg-tertiary-fixed text-on-tertiary-fixed-variant px-4 py-1 rounded-full text-xs font-bold tracking-widest uppercase">
                                {{ $unit->type->name }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-4 flex flex-col space-y-6">

                {{-- Dropdown Action di Top Right --}}
                <div class="flex justify-end">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="p-3 rounded-full hover:bg-surface-container-high transition-colors flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-[1rem] shadow-lg border border-outline-variant/10 py-2 z-20 overflow-hidden origin-top-right">

                            <button
                                class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                                <span>Favorite</span>
                            </button>

                            @auth('student')
                                @if ($userRating && $canReport)
                                    <a href="{{ route('student.reports.create', $userRating->id) }}"
                                        class="w-full flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 transition-colors text-sm font-medium">
                                        <span class="material-symbols-outlined text-[20px]">flag</span>
                                        <span>Laporkan Rating</span>
                                    </a>
                                @elseif(!$userRating)
                                    <a href="{{ route('student.ratings.create', $unit->slug) }}"
                                        class="w-full flex items-center space-x-3 px-4 py-3 text-primary hover:bg-primary/10 transition-colors text-sm font-medium">
                                        <span class="material-symbols-outlined text-[20px]">rate_review</span>
                                        <span>Beri Rating</span>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('student.login') }}"
                                    class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                                    <span class="material-symbols-outlined text-[20px]">login</span>
                                    <span>Login to Rate</span>
                                </a>
                            @endauth

                            <button
                                onclick="navigator.clipboard.writeText(window.location.href).then(() => {
                                    const btn = this;
                                    const span = btn.querySelector('span:last-child');
                                    span.textContent = 'Copied!';
                                    setTimeout(() => span.textContent = 'Share', 1500);
                                })"
                                class="w-full flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-surface-container-low transition-colors text-sm font-medium">
                                <span class="material-symbols-outlined text-[20px]">share</span>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Judul dan Info --}}
                <div>
                    <h1 class="text-5xl font-extrabold text-on-surface tracking-tight mb-2">
                        {{ $unit->name }}
                    </h1>
                    <p class="text-on-surface-variant text-lg">
                        {{ $unit->department->name ?? 'Itenas' }}
                    </p>
                </div>

                {{-- Rating Stars --}}
                <div class="flex items-center space-x-2">
                    <div class="flex text-primary">
                        @for ($i = 0; $i < $avgRounded; $i++)
                            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">star</span>
                        @endfor
                        @for ($i = 0; $i < $emptyStars; $i++)
                            <span class="material-symbols-outlined">star</span>
                        @endfor
                    </div>
                    <span class="font-bold text-xl">{{ number_format($unit->avg_rating ?? 0, 1) }}</span>
                    <span class="text-on-surface-variant text-sm">({{ $unit->total_ratings ?? 0 }} Reviews)</span>
                </div>

            </div>
        </section>

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
                @if (($unit->total_ratings ?? 0) > 0)
                    <span class="ml-2 text-xs font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">
                        {{ $unit->total_ratings }}
                    </span>
                @endif
            </button>
        </nav>

        <div id="tab-about" class="tab-content">
            @include('student.units.partials.about', ['unit' => $unit, 'stats' => $stats])
        </div>

        <div id="tab-gallery" class="tab-content hidden">
            @include('student.units.partials.gallery', ['unit' => $unit])
        </div>

        <div id="tab-reviews" class="tab-content hidden">
            @include('student.units.partials.reviews-tab', ['unit' => $unit, 'stats' => $stats])
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
                    btn.addEventListener('click', function() {
                        activateTab(this.getAttribute('data-tab'));
                    });
                });

                window.addEventListener('switch-tab', function(e) {
                    if (e.detail?.tab) activateTab(e.detail.tab);
                });
            });
        </script>
    @endpush

@endsection
