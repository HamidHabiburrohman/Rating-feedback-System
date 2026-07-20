{{-- resources/views/components/admin/conversations/conversation-card.blade.php --}}
@props([
    'conversation',
    'isActive' => false,
    'showUnread' => true,
    'isPinned' => false,
    'isMuted' => false,
    'isTyping' => false,
])

@php
    $lastMessage = $conversation->latestMessage;
    $unreadCount = $conversation->unread_count ?? 0;
    $time = $conversation->last_message_at ?? $conversation->updated_at;
    
    $classes = 'conv-card';
    if ($isActive) $classes .= ' conv-card-active';
    if ($isMuted) $classes .= ' conv-card-muted';
    if ($isPinned) $classes .= ' conv-card-pinned';

    // Determine activity state based on last_message_at (active if within last 60 minutes)
    $isConversationActive = $conversation->last_message_at && $conversation->last_message_at->diffInMinutes(now()) <= 60;
@endphp

<a href="{{ route('admin.conversations.show', $conversation) }}" 
   data-conversation-id="{{ $conversation->id }}" 
   data-url="{{ route('admin.conversations.show', $conversation) }}" 
   data-unread-count="{{ $unreadCount }}"
   {{ $attributes->merge(['class' => 'conv-card-link']) }}>
    
    <div class="{{ $classes }}">
        <div class="conv-card-left">
            <div class="conv-card-avatar-wrapper">
                @if($conversation->image)
                    <img src="{{ $conversation->image }}" alt="{{ $conversation->subject }}" class="conv-card-image">
                @else
                    <div class="conv-card-unit-placeholder">
                        <i class="ti ti-building"></i>
                    </div>
                @endif
                
                {{-- Activity Indicator (Future-proof for Realtime) --}}
                <span class="conv-card-activity-indicator {{ $isConversationActive ? 'is-active' : 'is-inactive' }}" aria-hidden="true"></span>
            </div>
        </div>

        <div class="conv-card-center">
            <div class="conv-card-header">
                <h3 class="conv-card-title">
                    @if($isPinned)
                        <i class="ti ti-pinned-filled conv-card-icon-pinned"></i>
                    @endif
                    {{ $conversation->subject ?? 'Untitled Conversation' }}
                </h3>
                <span class="conv-card-time">{{ $time ? $time->diffForHumans(null, true, true) : '' }}</span>
            </div>
            
            <div class="conv-card-footer">
                <div class="conv-card-preview">
                    @if($isTyping)
                        <x-admin.conversations.typing-indicator :compact="true" />
                    @elseif($lastMessage)
                        <span class="conv-preview-sender">{{ Str::limit($lastMessage->sender->name ?? 'Unknown', 15) }}:</span>
                        <span class="conv-preview-text">{{ $lastMessage->body }}</span>
                    @else
                        <span class="conv-preview-empty">No messages yet</span>
                    @endif
                </div>
                
                <div class="conv-card-meta">
                    @if($isMuted)
                        <i class="ti ti-bell-off conv-card-icon-muted"></i>
                    @endif
                    @if($showUnread && $unreadCount > 0)
                        <span class="conv-unread-badge" data-unread-badge>
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</a>

@once
@push('styles')
<style>
.conv-card-link {
    text-decoration: none;
    display: block;
    color: inherit;
}

.conv-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    background-color: transparent;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border: 1px solid transparent;
}

.conv-card:hover {
    background-color: #f8fafc;
}

.conv-card-active {
    background-color: #fff8f4;
    border-color: rgba(248, 119, 60, 0.15);
}

.conv-card-active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 24px;
    background-color: #f8773c;
    border-radius: 0 4px 4px 0;
}

.conv-card-muted {
    opacity: 0.6;
}

.conv-card-left {
    flex-shrink: 0;
    padding-top: 2px;
}

.conv-card-avatar-wrapper {
    position: relative;
    display: inline-block;
    line-height: 0;
}

.conv-card-image {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
}

.conv-card-unit-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background-color: #fff5f0;
    color: #f8773c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
}

.conv-card-activity-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.1);
    transition: background-color 0.2s ease;
    z-index: 2;
}

.conv-card-activity-indicator.is-active {
    background-color: #22C55E;
}

.conv-card-activity-indicator.is-inactive {
    background-color: #CBD5E1;
}

.conv-card-center {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.conv-card-header {
    display: flex;
    align-items: center;
    gap: 6px;
}

.conv-card-title {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.conv-card-icon-pinned {
    font-size: 14px;
    color: #f8773c;
    flex-shrink: 0;
}

.conv-card-time {
    font-size: 12px;
    font-weight: 500;
    color: #64748b;
    white-space: nowrap;
    flex-shrink: 0;
}

.conv-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.conv-card-preview {
    font-size: 13px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
    line-height: 1.4;
}

.conv-preview-sender {
    font-weight: 600;
    color: #475569;
}

.conv-preview-empty {
    font-style: italic;
    color: #94a3b8;
}

.conv-card-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}

.conv-card-icon-muted {
    font-size: 14px;
    color: #94a3b8;
}

/* Unread Badge Design */
.conv-unread-badge {
    background: #f8773c;
    color: #ffffff;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 9999px;
    min-width: 18px;
    text-align: center;
    flex-shrink: 0;
    margin-left: 8px;
    line-height: 1.2;
    display: inline-block;
}

.conv-card-active .conv-card-title {
    color: #0f172a;
}

.conv-card-active .conv-card-preview {
    color: #475569;
}
</style>
@endpush
@endonce