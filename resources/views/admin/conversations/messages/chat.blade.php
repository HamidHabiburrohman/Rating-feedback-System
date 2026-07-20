@props(['messages', 'conversation'])

<div class="conv-chat-area">
    <div class="conv-chat-scroll" id="convChatScroll">
        <div class="conv-chat-list" id="convChatList">
            @if($messages->isEmpty())
            <div class="conv-chat-empty">
                <div class="conv-chat-empty-icon">
                    <i class="ti ti-messages"></i>
                </div>
                <h3 class="conv-chat-empty-title">No messages yet</h3>
                <p class="conv-chat-empty-desc">Start the conversation by sending a message below.</p>
            </div>
            @else
            @php
            $currentUser = auth('admin')->user();
            $currentDate = null;
            $previousSenderId = null;
            $previousTime = null;
            @endphp

            @foreach($messages as $message)
            @php
            $messageDate = $message->created_at->format('Y-m-d');
            $isOwn = $currentUser &&
            $message->sender_id === $currentUser->getAuthIdentifier() &&
            $message->sender_type === get_class($currentUser);

            $isGrouped = false;
            if ($previousSenderId === $message->sender_id && $currentDate === $messageDate) {
            if ($previousTime && $message->created_at->diffInMinutes($previousTime)
            < 5) { $isGrouped=true; } } @endphp @if($currentDate !==$messageDate) @php $currentDate=$messageDate; @endphp <x-admin.conversations.date-divider :date="$message->created_at" />
            @endif

            @include('admin.conversations.messages.bubble', [
            'message' => $message,
            'isOwn' => $isOwn,
            'isGrouped' => $isGrouped
            ])

            @php
            $previousSenderId = $message->sender_id;
            $previousTime = $message->created_at;
            @endphp
            @endforeach

            <div id="convTypingIndicator" class="conv-msg-row conv-msg-incoming" style="display: none;">
                <div class="conv-msg-avatar-col"></div>
                <div class="conv-msg-content-col">
                    <x-admin.conversations.typing-indicator :compact="true" />
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<template id="convOwnBubbleTemplate">
    <div class="conv-msg-row conv-msg-own" data-temp-id="">
        <div class="conv-msg-avatar-col"></div>
        <div class="conv-msg-content-col">
            <div class="conv-msg-header">
                <span class="conv-msg-sender" data-tpl-sender></span>
                <span class="conv-msg-role" data-tpl-role></span>
                <span class="conv-msg-time" data-tpl-time></span>
            </div>
            <div class="conv-msg-bubble conv-msg-bubble-own">
                <div class="conv-msg-text" data-tpl-body></div>
                <div class="conv-msg-attachments" data-tpl-attachments style="display: none;"></div>
            </div>
            <div class="conv-msg-footer">
                <span class="conv-msg-time-own" data-tpl-time></span>
                <span class="conv-msg-status conv-status-sm conv-msg-status-sending" data-tpl-status>
                    <i class="ti ti-clock"></i>
                </span>
            </div>
        </div>
        <div class="conv-msg-actions">
            <button class="conv-msg-action-btn" title="Reply" data-action="reply">
                <i class="ti ti-arrow-back-up"></i>
            </button>
            <button class="conv-msg-action-btn" title="Copy" data-action="copy">
                <i class="ti ti-copy"></i>
            </button>
            <button class="conv-msg-action-btn" title="React" data-action="react">
                <i class="ti ti-mood-smile"></i>
            </button>
            <button class="conv-msg-action-btn conv-msg-action-danger" title="Delete" data-action="delete">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    </div>
</template>
