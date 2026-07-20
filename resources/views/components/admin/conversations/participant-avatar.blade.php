{{-- resources/views/components/admin/conversations/participant-avatar.blade.php --}}
@props([
    'participant' => null,
    'size' => 'md',
    'status' => null,
    'showStatus' => false,
    'imageUrl' => null,
    'isVerified' => false,
])

@php
    $name = $participant->name ?? 'Unknown';
    $initials = collect(explode(' ', $name))
        ->take(2)
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->join('');
        
    $colorIndex = abs(crc32((string)($participant->id ?? $name))) % 8;
    
    $sizes = [
        'xs' => 'conv-avatar-xs',
        'sm' => 'conv-avatar-sm',
        'md' => 'conv-avatar-md',
        'lg' => 'conv-avatar-lg',
        'xl' => 'conv-avatar-xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    $statusColors = [
        'online' => 'conv-status-online',
        'offline' => 'conv-status-offline',
        'busy' => 'conv-status-busy',
        'away' => 'conv-status-away',
    ];
    $statusClass = $statusColors[$status] ?? '';
@endphp

<div {{ $attributes->merge(['class' => "conv-avatar-wrapper {$sizeClass}"]) }}>
    @if($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="conv-avatar-img">
    @else
        <div class="conv-avatar-fallback conv-color-{{ $colorIndex }}">
            {{ $initials }}
        </div>
    @endif
    
    @if($showStatus && $status)
        <span class="conv-avatar-status {{ $statusClass }}"></span>
    @endif

    @if($isVerified)
        <span class="conv-avatar-verified">
            <i class="ti ti-circle-check-filled"></i>
        </span>
    @endif
</div>

@once
@push('styles')
<style>
.conv-avatar-wrapper {
    position: relative;
    display: inline-flex;
    flex-shrink: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.conv-avatar-img, 
.conv-avatar-fallback {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
}
.conv-avatar-xs { width: 24px; height: 24px; }
.conv-avatar-xs .conv-avatar-fallback { font-size: 10px; }
.conv-avatar-sm { width: 32px; height: 32px; }
.conv-avatar-sm .conv-avatar-fallback { font-size: 12px; }
.conv-avatar-md { width: 40px; height: 40px; }
.conv-avatar-md .conv-avatar-fallback { font-size: 14px; }
.conv-avatar-lg { width: 48px; height: 48px; }
.conv-avatar-lg .conv-avatar-fallback { font-size: 16px; }
.conv-avatar-xl { width: 64px; height: 64px; }
.conv-avatar-xl .conv-avatar-fallback { font-size: 20px; }

.conv-avatar-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 25%;
    height: 25%;
    min-width: 8px;
    min-height: 8px;
    border-radius: 50%;
    border: 2px solid #ffffff;
}
.conv-status-online { background-color: #10b981; }
.conv-status-offline { background-color: #94a3b8; }
.conv-status-busy { background-color: #ef4444; }
.conv-status-away { background-color: #f59e0b; }

.conv-avatar-verified {
    position: absolute;
    bottom: -2px;
    right: -2px;
    color: #2563eb;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    line-height: 1;
}
.conv-avatar-sm .conv-avatar-verified { font-size: 12px; }
.conv-avatar-xs .conv-avatar-verified { display: none; }

.conv-color-0 { background-color: #dbeafe; color: #1d4ed8; }
.conv-color-1 { background-color: #ede9fe; color: #6d28d9; }
.conv-color-2 { background-color: #fce7f3; color: #be185d; }
.conv-color-3 { background-color: #d1fae5; color: #047857; }
.conv-color-4 { background-color: #fef3c7; color: #b45309; }
.conv-color-5 { background-color: #cffafe; color: #0e7490; }
.conv-color-6 { background-color: #fee2e2; color: #b91c1c; }
.conv-color-7 { background-color: #ffedd5; color: #c2410c; }
</style>
@endpush
@endonce