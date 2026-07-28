@props(['qrCode'])

{{-- Activate Modal --}}
<div class="conv-modal-backdrop" id="activateQrBackdrop-{{ $qrCode->id }}"></div>
<div class="conv-modal" id="activateQrModal-{{ $qrCode->id }}">
    <div class="conv-modal-content">
        <div class="conv-modal-header">
            <div class="conv-modal-icon success">
                <i class="ti ti-circle-check"></i>
            </div>
            <h3 class="conv-modal-title">Activate QR Code</h3>
            <button class="conv-modal-close" data-modal-close>
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="conv-modal-body">
            <p class="conv-modal-desc">Are you sure you want to activate this QR Code? It will become available for student scanning immediately.</p>
            <div class="conv-modal-info">
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">QR Code</span>
                    <span class="conv-modal-info-value">{{ Str::limit($qrCode->code, 12) }}</span>
                </div>
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">Unit</span>
                    <span class="conv-modal-info-value" style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $qrCode->unit->name ?? 'Unknown' }}</span>
                </div>
            </div>
        </div>
        <div class="conv-modal-footer">
            <button class="conv-btn conv-btn-secondary" data-modal-close>
                <span class="conv-btn-text">Cancel</span>
            </button>
            <form action="{{ route('qr-codes.activate', $qrCode->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="conv-btn conv-btn-success">
                    <span class="conv-btn-loader"></span>
                    <span class="conv-btn-text">Activate Now</span>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Deactivate Modal --}}
<div class="conv-modal-backdrop" id="deactivateQrBackdrop-{{ $qrCode->id }}"></div>
<div class="conv-modal" id="deactivateQrModal-{{ $qrCode->id }}">
    <div class="conv-modal-content">
        <div class="conv-modal-header">
            <div class="conv-modal-icon warning">
                <i class="ti ti-circle-off"></i>
            </div>
            <h3 class="conv-modal-title">Deactivate QR Code</h3>
            <button class="conv-modal-close" data-modal-close>
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="conv-modal-body">
            <p class="conv-modal-desc">Are you sure you want to deactivate this QR Code? Students will no longer be able to scan it until reactivated.</p>
            <div class="conv-modal-info">
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">QR Code</span>
                    <span class="conv-modal-info-value">{{ Str::limit($qrCode->code, 12) }}</span>
                </div>
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">Unit</span>
                    <span class="conv-modal-info-value" style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $qrCode->unit->name ?? 'Unknown' }}</span>
                </div>
            </div>
        </div>
        <div class="conv-modal-footer">
            <button class="conv-btn conv-btn-secondary" data-modal-close>
                <span class="conv-btn-text">Cancel</span>
            </button>
            <form action="{{ route('qr-codes.deactivate', $qrCode->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="conv-btn conv-btn-danger">
                    <span class="conv-btn-loader"></span>
                    <span class="conv-btn-text">Deactivate</span>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Regenerate Modal --}}
<div class="conv-modal-backdrop" id="regenerateQrBackdrop-{{ $qrCode->id }}"></div>
<div class="conv-modal" id="regenerateQrModal-{{ $qrCode->id }}">
    <div class="conv-modal-content">
        <div class="conv-modal-header">
            <div class="conv-modal-icon primary">
                <i class="ti ti-refresh"></i>
            </div>
            <h3 class="conv-modal-title">Regenerate QR Code</h3>
            <button class="conv-modal-close" data-modal-close>
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="conv-modal-body">
            <p class="conv-modal-desc">This will invalidate the current QR Code and generate a new one. Any existing printed materials will no longer work.</p>
            <div class="conv-modal-info">
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">Current Code</span>
                    <span class="conv-modal-info-value">{{ Str::limit($qrCode->code, 12) }}</span>
                </div>
                <div class="conv-modal-info-row">
                    <span class="conv-modal-info-label">Unit</span>
                    <span class="conv-modal-info-value" style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $qrCode->unit->name ?? 'Unknown' }}</span>
                </div>
            </div>
        </div>
        <div class="conv-modal-footer">
            <button class="conv-btn conv-btn-secondary" data-modal-close>
                <span class="conv-btn-text">Cancel</span>
            </button>
            <form action="{{ route('qr-codes.regenerate', $qrCode->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="conv-btn conv-btn-primary">
                    <span class="conv-btn-loader"></span>
                    <span class="conv-btn-text">Regenerate</span>
                </button>
            </form>
        </div>
    </div>
</div>