<div class="conv-workspace-view" data-conversation-id="{{ $conversation->id }}">
    @include('admin.conversations.partials.header', ['conversation' => $conversation])

    @include('admin.conversations.messages.chat', [
        'messages' => $messages ?? collect(),
        'conversation' => $conversation
    ])

    @include('admin.conversations.messages.composer', [
        'conversation' => $conversation
    ])
</div>

@include('admin.conversations.attachments.preview-modal')
@include('admin.conversations.attachments.upload-modal')