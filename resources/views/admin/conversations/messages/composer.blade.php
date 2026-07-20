@props(['conversation'])
<div class="conv-composer-wrapper" id="convComposerWrapper">
    <div class="conv-composer-typing" id="convTypingIndicator" style="display: none;">
        <div class="conv-composer-typing-dots"><span></span><span></span><span></span></div>
        <span>Someone is typing...</span>
    </div>

    <div class="conv-drag-overlay" id="convDragOverlay">
        <div class="conv-drag-content">
            <i class="ti ti-cloud-upload"></i>
            <p>Drop files here to upload</p>
        </div>
    </div>

    <!-- Reply Banner -->
    <div class="conv-reply-banner" id="convReplyBanner" style="display: none;">
        <div class="conv-reply-banner-content">
            <i class="ti ti-corner-up-left"></i>
            <div class="conv-reply-banner-text">
                <span class="conv-reply-banner-sender" id="convReplySender">Sender</span>
                <span class="conv-reply-banner-preview" id="convReplyPreview">Message preview</span>
            </div>
        </div>
        <button type="button" class="conv-reply-banner-cancel" id="convReplyCancel" title="Cancel reply">
            <i class="ti ti-x"></i>
        </button>
    </div>

    <div class="conv-composer-previews" id="convComposerPreviews" style="display: none;"></div>
    
    <form action="{{ route('admin.conversations.messages.store', $conversation) }}"
          method="POST"
          enctype="multipart/form-data"
          class="conv-composer-form"
          id="convComposerForm">
        @csrf
        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
        <input type="hidden" name="reply_to_id" id="convReplyToId" value="">
        <div id="convFileInputsContainer"></div>
        <div class="conv-composer-input-area">
            <div class="conv-composer-actions-left">
                <button type="button" class="conv-composer-icon-btn" id="convAttachBtn" title="Attach files">
                    <i class="ti ti-paperclip"></i>
                </button>
            </div>
            <div class="conv-composer-textarea-wrapper">
                <textarea
                    name="body"
                    id="convComposerTextarea"
                    class="conv-composer-textarea"
                    placeholder="Type your message..."
                    rows="1"
                    maxlength="{{ \App\Services\Admin\Conversation\MessageService::MAX_BODY_LENGTH ?? 5000 }}"
                ></textarea>
            </div>
            <div class="conv-composer-actions-right">
                <span class="conv-char-counter" id="convCharCounter">0 / 5000</span>
                <button type="button" class="conv-composer-icon-btn" data-action="emoji" title="Add emoji">
                    <i class="ti ti-mood-smile"></i>
                </button>
                <button type="submit" class="conv-composer-send-btn" id="convSendBtn" disabled title="Send message">
                    <i class="ti ti-send"></i>
                    <span class="conv-send-loader"></span>
                </button>
            </div>
        </div>
    </form>
</div>