<div x-data="{ open: false }"
     @open-upload-modal.window="open = true; document.body.style.overflow = 'hidden'"
     @keydown.escape.window="if(open) { open = false; document.body.style.overflow = '' }"
     x-show="open"
     x-cloak
     class="conv-upload-modal"
     id="convUploadModal">
    <div class="conv-upload-backdrop" @click="open = false; document.body.style.overflow = ''"></div>
    <div class="conv-upload-container" @click.stop>
        <header class="conv-upload-header">
            <h3 class="conv-upload-title">Attachment Queue</h3>
            <button class="conv-upload-close" @click="open = false; document.body.style.overflow = ''">
                <i class="ti ti-x"></i>
            </button>
        </header>
        <div class="conv-upload-body">
            <div class="conv-upload-queue" id="convUploadQueueList"></div>
            <div class="conv-upload-empty" id="convUploadQueueEmpty">
                <i class="ti ti-paperclip"></i>
                <p>No files selected</p>
            </div>
        </div>
        <footer class="conv-upload-footer">
            <button class="conv-btn-ghost" @click="open = false; document.body.style.overflow = ''">Close</button>
            <button class="conv-btn-primary" id="convUploadSubmitBtn" disabled>Upload Files</button>
        </footer>
    </div>
</div>

@push('styles')
<style>
[x-cloak] { display: none !important; }
.conv-upload-modal { position: fixed; inset: 0; z-index: 9998; display: flex; align-items: center; justify-content: center; font-family: 'Plus Jakarta Sans', sans-serif; }
.conv-upload-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); }
.conv-upload-container { position: relative; width: 100%; max-width: 560px; background: #ffffff; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); display: flex; flex-direction: column; max-height: 90vh; z-index: 1; overflow: hidden; }
.conv-upload-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #ECECEC; }
.conv-upload-title { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; }
.conv-upload-close { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; border-radius: 8px; color: #64748b; cursor: pointer; transition: all 0.2s; }
.conv-upload-close:hover { background: #f1f5f9; color: #0f172a; }
.conv-upload-body { padding: 24px; overflow-y: auto; flex: 1; }
.conv-upload-queue { display: flex; flex-direction: column; gap: 12px; }
.conv-upload-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; color: #94a3b8; }
.conv-upload-empty i { font-size: 32px; margin-bottom: 12px; }
.conv-upload-empty p { margin: 0; font-size: 13px; }
.conv-file-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px; background: #ffffff; border: 1px solid #ECECEC; border-radius: 12px; transition: all 0.2s; }
.conv-file-thumb { width: 40px; height: 40px; border-radius: 8px; overflow: hidden; flex-shrink: 0; }
.conv-file-thumb img { width: 100%; height: 100%; object-fit: cover; }
.conv-file-icon { width: 40px; height: 40px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; flex-shrink: 0; }
.conv-file-icon i { font-size: 20px; }
.conv-file-info { flex: 1; min-width: 0; }
.conv-file-name { font-size: 13px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0 0 2px 0; }
.conv-file-meta { font-size: 12px; color: #64748b; }
.conv-file-progress-wrap { display: flex; align-items: center; gap: 8px; margin-top: 6px; }
.conv-file-progress { flex: 1; height: 4px; background: #e2e8f0; border-radius: 4px; overflow: hidden; }
.conv-file-progress-bar { height: 100%; background: #f8773c; border-radius: 4px; transition: width 0.3s ease; }
.conv-file-progress-text { font-size: 11px; font-weight: 600; color: #64748b; min-width: 30px; text-align: right; }
.conv-file-status { font-size: 12px; font-weight: 500; color: #10b981; display: flex; align-items: center; gap: 4px; margin-top: 4px; }
.conv-file-status.error { color: #ef4444; }
.conv-file-actions { display: flex; gap: 4px; flex-shrink: 0; }
.conv-file-action { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; border-radius: 6px; color: #64748b; cursor: pointer; transition: all 0.2s; }
.conv-file-action:hover { background: #f1f5f9; color: #0f172a; }
.conv-upload-footer { display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px; border-top: 1px solid #ECECEC; background: #fafafa; }
</style>
@endpush