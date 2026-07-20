{{-- resources/views/components/admin/conversations/participant-stack.blade.php --}}
@props([
    'participants' => [],
    'limit' => 4,
    'size' => 'sm',
])

@php
    $collection = collect($participants)->filter();
    $visible = $collection->take($limit);
    $remaining = $collection->count() - $limit;
@endphp

<div {{ $attributes->merge(['class' => 'conv-avatar-stack']) }}>
    @foreach($visible as $index => $participant)
        <div class="conv-stack-item" style="z-index: {{ $limit - $index }};">
            <x-admin.conversations.participant-avatar 
                :participant="$participant" 
                :size="$size" 
            />
        </div>
    @endforeach
    
    @if($remaining > 0)
        <div class="conv-stack-item conv-stack-overflow conv-overflow-{{ $size }}" style="z-index: 0;">
            +{{ $remaining }}
        </div>
    @endif
</div>

@once
@push('styles')
<style>
.conv-avatar-stack {
    display: flex;
    align-items: center;
}
.conv-stack-item {
    margin-left: -8px;
    position: relative;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.conv-stack-item:first-child {
    margin-left: 0;
}
.conv-avatar-stack:hover .conv-stack-item {
    margin-left: -4px;
}
.conv-avatar-stack:hover .conv-stack-item:first-child {
    margin-left: 0;
}
.conv-stack-overflow {
    border-radius: 50%;
    background: #f1f5f9;
    color: #475569;
    border: 2px solid #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
}
.conv-overflow-xs { width: 24px; height: 24px; font-size: 10px; }
.conv-overflow-sm { width: 32px; height: 32px; font-size: 11px; }
.conv-overflow-md { width: 40px; height: 40px; font-size: 12px; }
.conv-overflow-lg { width: 48px; height: 48px; font-size: 14px; }
.conv-overflow-xl { width: 64px; height: 64px; font-size: 16px; }
</style>
@endpush
@endonce