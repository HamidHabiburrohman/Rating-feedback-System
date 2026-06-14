@props(['size' => 'md', 'text' => 'Loading...'])

@php
$sizes = ['sm' => 'w-4 h-4', 'md' => 'w-8 h-8', 'lg' => 'w-12 h-12'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-3']) }}>
    <div class="{{ $sizeClass }} border-4 border-gray-200 border-t-orange-500 rounded-full animate-spin"></div>
    @if($text)
    <p class="text-sm text-gray-600">{{ $text }}</p>
    @endif
</div>