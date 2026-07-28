<div class="conv-modal-backdrop" id="qrActionBackdrop"></div>
<div class="conv-modal" id="qrActionModal">
    <div class="conv-modal-content">
        <div class="conv-modal-header">
            <div class="conv-modal-icon" id="qrActionIcon">
                <i class="ti ti-refresh"></i>
            </div>
            <h3 class="conv-modal-title" id="qrActionTitle">Action</h3>
            <button class="conv-modal-close" data-modal-close>
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="conv-modal-body">
            <p class="conv-modal-desc" id="qrActionDesc">Description</p>
            <div class="conv-modal-info">
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">QR Code</span>
                    <span class="conv-modal-info-value" id="qrActionCode">-</span>
                </div>
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">Unit</span>
                    <span class="conv-modal-info-value" id="qrActionUnit" style="font-family:'Plus Jakarta Sans',sans-serif;">-</span>
                </div>
            </div>
        </div>
        <div class="conv-modal-footer">
            <button class="conv-btn conv-btn-secondary" data-modal-close>
                <span class="conv-btn-text">Cancel</span>
            </button>
            <form id="qrActionForm" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="conv-btn" id="qrActionSubmitBtn">
                    <span class="conv-btn-loader"></span>
                    <span class="conv-btn-text">Confirm</span>
                </button>
            </form>
        </div>
    </div>
</div>