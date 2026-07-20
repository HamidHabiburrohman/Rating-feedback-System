{{-- resources/views/components/admin/conversations/typing-indicator.blade.php --}}
@props([
    'user' => null,
    'users' => [],
    'showName' => true,
    'compact' => false,
])

@php
    $typingText = '';
    if ($showName) {
        if (count($users) > 1) {
            $typingText = 'Several people are typing...';
        } elseif (count($users) === 1) {
            $typingText = ($users[0]->name ?? 'Someone') . ' is typing...';
        } elseif ($user) {
            $typingText = ($user->name ?? 'Someone') . ' is typing...';
        } else {
            $typingText = 'Someone is typing...';
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'conv-typing-indicator ' . ($compact ? 'conv-typing-compact' : '')]) }}>
    <span class="conv-typing-dots">
        <span></span>
        <span></span>
        <span></span>
    </span>
    
    @if($showName && $typingText)
        <span class="conv-typing-text">{{ $typingText }}</span>
    @endif
</div>

@once
@push('styles')
<style>
.conv-typing-indicator {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
}
.conv-typing-compact { gap: 6px; font-size: 12px; }
.conv-typing-dots { display: inline-flex; gap: 3px; align-items: center; padding: 0 2px; }
.conv-typing-dots span {
    width: 4px; height: 4px; background: #94a3b8; border-radius: 50%;
    animation: conv-typing-bounce 1.4s infinite ease-in-out both;
}
.conv-typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.conv-typing-dots span:nth-child(2) { animation-delay: -0.16s; }
@keyframes conv-typing-bounce {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
    40% { transform: scale(1); opacity: 1; }
}
.conv-typing-text { letter-spacing: -0.01em; }
</style>
@endpush
@endonce