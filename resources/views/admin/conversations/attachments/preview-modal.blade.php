<div x-data="{ open: false }" @open-preview-modal.window="open = true; document.body.style.overflow = 'hidden'" @keydown.escape.window="if(open) { open = false; document.body.style.overflow = '' }" x-show="open" x-cloak class="conv-preview-modal">
    <div class="conv-preview-backdrop" @click="open = false; document.body.style.overflow = ''"></div>
    <div class="conv-preview-container" @click.stop>
        <header class="conv-preview-header">
            <div class="conv-preview-info">
                <h3 class="conv-preview-title" id="convPreviewTitle">File Preview</h3>
                <span class="conv-preview-meta" id="convPreviewMeta"></span>
            </div>
            <button class="conv-preview-close" @click="open = false; document.body.style.overflow = ''">
                <i class="ti ti-x"></i>
            </button>
        </header>
        <div class="conv-preview-body">
            <div class="conv-preview-content" id="convPreviewContent"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .conv-preview-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .conv-preview-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
    }

    .conv-preview-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        z-index: 1;
    }

    .conv-preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        background: rgba(15, 23, 42, 0.5);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .conv-preview-title {
        font-size: 16px;
        font-weight: 600;
        color: #ffffff;
        margin: 0;
        max-width: 400px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-preview-meta {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
    }

    .conv-preview-close {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
    }

    .conv-preview-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .conv-preview-body {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .conv-preview-content {
        max-width: 90%;
        max-height: 90%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .conv-preview-image {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .conv-preview-pdf {
        width: 80vw;
        height: 85vh;
        border: none;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .conv-preview-unsupported {
        text-align: center;
        color: #ffffff;
        padding: 40px;
    }

    .conv-preview-unsupported i {
        font-size: 64px;
        margin-bottom: 16px;
        color: rgba(255, 255, 255, 0.5);
    }

    .conv-preview-unsupported p {
        font-size: 16px;
        margin: 0;
        color: rgba(255, 255, 255, 0.8);
    }

</style>
@endpush
