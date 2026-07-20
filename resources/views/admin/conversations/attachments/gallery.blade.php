<div class="conv-gallery-wrapper">
    <header class="conv-gallery-header">
        <div class="conv-gallery-info">
            <h2 class="conv-gallery-title">Shared Files</h2>
            <p class="conv-gallery-subtitle">{{ $attachments->count() }} {{ Str::plural('item', $attachments->count()) }} shared in this conversation</p>
        </div>
        <button class="conv-gallery-upload-btn" onclick="window.Conversation.Events.emit('open-upload-modal', {})">
            <i class="ti ti-upload"></i>
            <span>Upload Files</span>
        </button>
    </header>

    <div class="conv-gallery-body">
        @if($attachments->count() > 0)
            <div class="conv-gallery-grid" id="convGalleryGrid">
                @foreach($attachments as $index => $attachment)
                    <div class="conv-gallery-item"
                         data-action="preview-attachment"
                         data-id="{{ $attachment->id }}"
                         data-name="{{ addslashes($attachment->original_name) }}"
                         data-size="{{ $attachment->size }}"
                         data-mime="{{ $attachment->mime_type }}"
                         data-path="{{ $attachment->path }}"
                         data-url="{{ asset('storage/' . $attachment->path) }}">
                        <x-admin.conversations.attachment-card :attachment="$attachment" :show-actions="false" :compact="false" />
                    </div>
                @endforeach
            </div>
        @else
            <x-admin.conversations.empty-state
                icon="ti-paperclip"
                title="No files shared yet"
                description="Upload documents, images, or PDFs to share them with participants in this conversation."
                iconColor="#f8773c"
                iconBg="#fff5f0"
            />
        @endif
    </div>
</div>

@include('admin.conversations.attachments.preview-modal')
@include('admin.conversations.attachments.upload-modal')

@push('styles')
<style>
.conv-gallery-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
.conv-gallery-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
.conv-gallery-title { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; letter-spacing: -0.01em; }
.conv-gallery-subtitle { font-size: 13px; color: #64748b; margin: 0; }
.conv-gallery-upload-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #f8773c; color: #ffffff; border: none; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(248, 119, 60, 0.2); }
.conv-gallery-upload-btn:hover { background: #ea580c; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(248, 119, 60, 0.3); }
.conv-gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
.conv-gallery-item { position: relative; border-radius: 16px; overflow: hidden; cursor: pointer; transition: all 0.2s ease; }
.conv-gallery-item:hover { transform: translateY(-2px); }
</style>
@endpush