@props(['message', 'isOwn', 'isGrouped' => false])
@php
    $sender = $message->sender;
    $senderName = $sender->name ?? 'Unknown';
    $senderRole = class_basename($message->sender_type);
    $isEdited = $message->edited_at !== null;
    $replyTo = $message->replyTo;
@endphp
<div class="conv-msg-row {{ $isOwn ? 'conv-msg-own' : 'conv-msg-incoming' }} {{ $isGrouped ? 'conv-msg-grouped' : '' }}"
     data-message-id="{{ $message->id }}"
     data-sender-name="{{ $senderName }}">
    <div class="conv-msg-avatar-col">
        @if(!$isGrouped && !$isOwn)
            <x-admin.conversations.participant-avatar :participant="$sender" size="md" />
        @endif
    </div>
    <div class="conv-msg-content-col">
        @if(!$isGrouped)
            <div class="conv-msg-header">
                <span class="conv-msg-sender">{{ $senderName }}</span>
                <span class="conv-msg-role">{{ $senderRole }}</span>
                <span class="conv-msg-time">{{ $message->created_at->format('H:i') }}</span>
            </div>
        @else
            <span class="conv-msg-hover-time">{{ $message->created_at->format('H:i') }}</span>
        @endif

        @if($replyTo)
            @php
                $replySender = $replyTo->sender ? ($replyTo->sender->name ?? 'Unknown') : 'Unknown';
                $replyText = 'Original message unavailable';
                
                if (!$replyTo->trashed()) {
                    $replyText = $replyTo->body ?: ($replyTo->attachments && $replyTo->attachments->count() > 0 ? 'Attachment' : 'Message');
                    $replyText = \Illuminate\Support\Str::limit($replyText, 80);
                }
            @endphp
            <div class="conv-msg-reply-preview" 
                 data-reply-to-id="{{ $replyTo->id }}" 
                 onclick="window.Conversation.Workspace.scrollToAndHighlightMessage({{ $replyTo->id }})"
                 title="Go to message">
                <i class="ti ti-corner-up-left"></i>
                <span class="conv-msg-reply-sender">{{ $replySender }}:</span>
                <span class="conv-msg-reply-text">{{ $replyText }}</span>
            </div>
        @endif

        <div class="conv-msg-bubble {{ $isOwn ? 'conv-msg-bubble-own' : 'conv-msg-bubble-incoming' }}">
            @if($message->body)
                <div class="conv-msg-text">{!! nl2br(e($message->body)) !!}</div>
            @endif
            @if($message->attachments && $message->attachments->count() > 0)
                <div class="conv-msg-attachments">
                    @foreach($message->attachments as $index => $attachment)
                        <x-admin.conversations.attachment-card
                            :attachment="$attachment"
                            :gallery="$message->attachments"
                            :index="$index"
                            :compact="true"
                            :show-actions="false"
                        />
                    @endforeach
                </div>
            @endif
        </div>
        <div class="conv-msg-footer">
            @if($isOwn)
                <span class="conv-msg-time-own">{{ $message->created_at->format('H:i') }}</span>
                @if($isEdited)
                    <span class="conv-msg-edited">(edited)</span>
                @endif
                <x-admin.conversations.message-status :message="$message" size="xs" :light="false" />
            @else
                @if($isEdited)
                    <span class="conv-msg-edited">(edited)</span>
                @endif
            @endif
        </div>
    </div>
    <div class="conv-msg-actions">
        <button class="conv-msg-action-btn" title="Reply" data-action="reply" data-message-id="{{ $message->id }}">
            <i class="ti ti-corner-up-left"></i>
        </button>
        <button class="conv-msg-action-btn" title="Copy" data-action="copy">
            <i class="ti ti-copy"></i>
        </button>
        <button class="conv-msg-action-btn" title="React" data-action="react">
            <i class="ti ti-mood-smile"></i>
        </button>
        @if($isOwn)
            <button class="conv-msg-action-btn conv-msg-action-danger" title="Delete" data-action="delete">
                <i class="ti ti-trash"></i>
            </button>
        @else
            <button class="conv-msg-action-btn" title="Pin" data-action="pin">
                <i class="ti ti-pin"></i>
            </button>
        @endif
    </div>
</div>