@php 
$photos = $unit->photos ?? collect(); 

$getPhotoUrl = function ($photo) {
    if ($photo->thumbnail_url) {
        return $photo->thumbnail_url;
    }
    if ($photo->thumbnail_path) {
        return asset($photo->thumbnail_path);
    }
    if ($photo->original_path) {
        return asset($photo->original_path);
    }
    return asset('assets/images/UnitPlaceholder.png');
};

$getOriginalUrl = function ($photo) use ($getPhotoUrl) {
    if ($photo->original_url) {
        return $photo->original_url;
    }
    if ($photo->original_path) {
        return asset($photo->original_path);
    }
    return $getPhotoUrl($photo);
};
@endphp

<div class="space-y-8" x-data="{
    lightboxOpen: false,
    currentPhoto: null,
    currentAlt: '',
    openLightbox(photo, alt) {
        this.currentPhoto = photo;
        this.currentAlt = alt;
        this.lightboxOpen = true;
        document.body.style.overflow = 'hidden';
    },
    closeLightbox() {
        this.lightboxOpen = false;
        this.currentPhoto = null;
        this.currentAlt = '';
        document.body.style.overflow = '';
    }
}" x-cloak>

    <div x-show="lightboxOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-8"
         style="display: none;"
         @click.self="closeLightbox()"
         @keydown.escape.window="closeLightbox()">

        <div class="absolute inset-0 bg-black/60 backdrop-blur-xl"></div>

        <button @click="closeLightbox()"
            class="absolute top-6 right-6 z-10 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 hover:scale-105 transition-all duration-200 flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>

        <div class="relative z-10 max-w-5xl max-h-full">
            <img :src="currentPhoto" :alt="currentAlt"
                class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl"
                @click.stop>

            <div x-show="currentAlt"
                class="absolute bottom-0 left-0 right-0 p-6 bg-linear-to-t from-black/80 via-black/40 to-transparent rounded-b-2xl">
                <p class="text-white text-lg font-medium" x-text="currentAlt"></p>
            </div>
        </div>
    </div>

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

            <div class="md:col-span-8 group cursor-pointer overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm relative md:h-[500px]"
                @click="openLightbox('{{ $getOriginalUrl($featured) }}', '{{ $featured->alt_text ?? $unit->name }}')">
                <img src="{{ $getPhotoUrl($featured) }}" alt="{{ $featured->alt_text ?? 'Gallery' }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-8">
                    @if ($featured->alt_text)
                        <h3 class="text-white text-2xl font-bold">{{ $featured->alt_text }}</h3>
                    @endif
                </div>
                
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="w-16 h-16 rounded-full bg-black/30 backdrop-blur-md flex items-center justify-center border border-white/20">
                        <span class="material-symbols-outlined text-white text-3xl">zoom_in</span>
                    </div>
                </div>
            </div>

            @if ($rest->isNotEmpty())
                @php
                    $side = $rest->first();
                    $rest = $rest->skip(1);
                @endphp
                
                <div class="md:col-span-4 group cursor-pointer overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm relative md:h-[500px]"
                    @click="openLightbox('{{ $getOriginalUrl($side) }}', '{{ $side->alt_text ?? $unit->name }}')">
                    <img src="{{ $getPhotoUrl($side) }}" alt="{{ $side->alt_text ?? 'Gallery' }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-14 h-14 rounded-full bg-black/30 backdrop-blur-md flex items-center justify-center border border-white/20">
                            <span class="material-symbols-outlined text-white text-2xl">zoom_in</span>
                        </div>
                    </div>
                    
                    @if ($side->alt_text)
                        <div class="absolute bottom-6 left-6 right-6 p-4 backdrop-blur-md bg-black/50 rounded-lg border border-white/10">
                            <p class="text-white font-bold text-lg leading-tight">{{ $side->alt_text }}</p>
                        </div>
                    @endif
                </div>
            @endif

            @foreach ($rest as $item)
                <div class="md:col-span-4 group cursor-pointer overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm relative aspect-4/3"
                    @click="openLightbox('{{ $getOriginalUrl($item) }}', '{{ $item->alt_text ?? $unit->name }}')">
                    <img src="{{ $getPhotoUrl($item) }}" alt="{{ $item->alt_text ?? 'Gallery' }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        @if ($item->alt_text)
                            <p class="text-white text-sm font-medium">{{ $item->alt_text }}</p>
                        @endif
                    </div>
                    
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-12 h-12 rounded-full bg-black/30 backdrop-blur-md flex items-center justify-center border border-white/20">
                            <span class="material-symbols-outlined text-white">zoom_in</span>
                        </div>
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
</div>