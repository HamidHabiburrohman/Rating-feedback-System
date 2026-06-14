@props(['type' => 'info', 'message' => '', 'dismissible' => true])

@php
$colors = [
    'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-400', 'text' => 'text-green-800', 'icon' => '✓'],
    'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-400', 'text' => 'text-red-800', 'icon' => '✕'],
    'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-400', 'text' => 'text-yellow-800', 'icon' => '⚠'],
    'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-400', 'text' => 'text-blue-800', 'icon' => 'ℹ'],
];
$color = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-md p-4 border-l-4 {$color['bg']} {$color['border']} {$color['text']}"]) }} role="alert">
    <div class="flex">
        <div class="shrink-0">
            <span class="text-lg">{{ $color['icon'] }}</span>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm">{{ $message ?: $slot }}</p>
        </div>
        @if($dismissible)
        <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-auto">
            <span class="text-xl">&times;</span>
        </button>
        @endif
    </div>
</div>