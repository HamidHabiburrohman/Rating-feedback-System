{{-- resources/views/components/admin/conversations/message-status.blade.php --}}
@props([
    'message' => null,
    'status' => 'sent',
    'size' => 'sm',
    'light' => false,
])

@php
    $computedStatus = $status;
    $isEdited = false;

    if ($message) {
        $isEdited = (bool)($message->edited_at);
        $readsCount = $message->reads_count ?? ($message->relationLoaded('reads') ? $message->reads->count() : 0);
        
        if ($message->deleted_at) {
            $computedStatus = 'failed';
        } elseif ($readsCount > 0) {
            $computedStatus = 'read';
        } elseif ($message->created_at && $message->created_at->isPast()) {
            $computedStatus = 'delivered';
        } else {
            $computedStatus = 'sent';
        }
    }

    $icons = [
        'sending'   => 'ti-clock',
        'sent'      => 'ti-check',
        'delivered' => 'ti-checks',
        'read'      => 'ti-checks',
        'failed'    => 'ti-alert-circle-filled',
    ];
    
    $icon = $icons[$computedStatus] ?? 'ti-check';
    $sizeClass = $size === 'sm' ? 'conv-status-sm' : 'conv-status-md';
    $lightClass = $light ? 'conv-status-light' : '';
@endphp

<span {{ $attributes->merge(['class' => "conv-msg-status {$sizeClass} {$lightClass} conv-status-{$computedStatus}"]) }}>
    <i class="ti {{ $icon }}"></i>
    @if($isEdited && $computedStatus !== 'failed')
        <span class="conv-status-edited">edited</span>
    @endif
</span>

@once
@push('styles')
<style>
.conv-msg-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 500;
    color: #94a3b8;
    line-height: 1;
}
.conv-msg-status i { font-size: 14px; stroke-width: 2; }
.conv-status-sm { font-size: 11px; }
.conv-status-md { font-size: 13px; }
.conv-status-md i { font-size: 16px; }
.conv-status-light { color: rgba(255, 255, 255, 0.7); }
.conv-status-read { color: #3b82f6; }
.conv-status-light.conv-status-read { color: #93c5fd; }
.conv-status-failed { color: #ef4444; }
.conv-status-edited { font-style: italic; opacity: 0.8; margin-left: 2px; font-weight: 400; }
</style>
@endpush
@endonce