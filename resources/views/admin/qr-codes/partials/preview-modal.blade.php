<div class="qr-preview-overlay" id="previewQrModal">
    <div class="qr-preview-backdrop"></div>
    <div class="qr-preview-container">
        <div class="qr-preview-header">
            <div>
                <h3 class="qr-preview-title">QR Code Preview</h3>
                <p class="qr-preview-subtitle">View complete QR code information</p>
            </div>
            <button type="button" class="qr-preview-close" id="closePreviewModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="qr-preview-body" id="previewModalBody">
            <div class="qr-loading-spinner">
                <div class="qr-spinner-large"></div>
            </div>
        </div>

        <div class="qr-preview-footer" id="previewModalFooter" style="display: none;">
            <button type="button" class="qr-btn qr-btn-secondary" id="cancelPreviewModal">Close</button>
            <a href="#" class="qr-btn qr-btn-primary" id="downloadPreviewModal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download PNG
            </a>
        </div>
    </div>
</div>

<style>
.qr-preview-overlay {
    position: fixed;
    inset: 0;
    z-index: 1050;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}

.qr-preview-overlay.is-open {
    display: flex;
}

.qr-preview-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(8px);
    animation: fadeIn 0.2s ease;
}

.qr-preview-container {
    position: relative;
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;          /* Allows internal vertical scrolling */
    scrollbar-width: none;     /* Hides scrollbar in Firefox */
    -ms-overflow-style: none;  /* Hides scrollbar in IE/Edge */
}

.qr-preview-container::-webkit-scrollbar {
    display: none;
}

.qr-preview-header {
    padding: 2rem 2rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}

.qr-preview-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin: 0 0 0.25rem 0;
}

.qr-preview-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

.qr-preview-close {
    background: transparent;
    border: none;
    color: #94a3b8;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.qr-preview-close:hover {
    background: #f1f5f9;
    color: #475569;
}

.qr-preview-body {
    padding: 2rem;
    overflow-y: auto;
    flex: 1;
}

.qr-preview-footer {
    padding: 1.5rem 2rem;
    background: #f8fafc;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    border-radius: 0 0 24px 24px;
}

.qr-loading-spinner {
    display: flex;
    justify-content: center;
    padding: 4rem;
}

.qr-spinner-large {
    width: 48px;
    height: 48px;
    border: 3px solid #f1f5f9;
    border-radius: 50%;
    border-top-color: #f8773c;
    animation: spin 0.8s linear infinite;
}

.qr-preview-layout {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.qr-preview-image-wrapper {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 18px;
    padding: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-preview-image {
    width: 100%;
    max-width: 240px;
    height: auto;
    border-radius: 12px;
}

.qr-preview-details {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
}

.qr-preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}

.qr-preview-row:last-child {
    border-bottom: none;
}

.qr-preview-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #64748b;
}

.qr-preview-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #0f172a;
    text-align: right;
}

.qr-preview-code {
    font-family: 'SF Mono', 'Menlo', monospace;
    background: #f8fafc;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8125rem;
}

.qr-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.875rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.qr-badge-active {
    background: #d1fae5;
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.qr-badge-inactive {
    background: #f3f4f6;
    color: #4b5563;
    border: 1px solid rgba(15, 23, 42, 0.06);
}

.qr-badge-expired {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.qr-actions-group {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-top: 1.5rem;
}

.qr-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    text-decoration: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.qr-btn-primary {
    background: #f8773c;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(248, 119, 60, 0.25);
}

.qr-btn-primary:hover:not(:disabled) {
    background: #e55a2b;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(248, 119, 60, 0.35);
}

.qr-btn-secondary {
    background: #ffffff;
    color: #334155;
    border: 1px solid rgba(15, 23, 42, 0.06);
}

.qr-btn-secondary:hover:not(:disabled) {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.qr-error-msg {
    background: #fef2f2;
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #dc2626;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .qr-preview-layout {
        grid-template-columns: 1fr;
    }
    
    .qr-preview-container {
        max-width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('previewQrModal');
    const closeBtn = document.getElementById('closePreviewModal');
    const cancelBtn = document.getElementById('cancelPreviewModal');
    const downloadBtn = document.getElementById('downloadPreviewModal');
    const modalBody = document.getElementById('previewModalBody');
    const modalFooter = document.getElementById('previewModalFooter');

    function openModal(id) {
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        modalFooter.style.display = 'none';
        modalBody.innerHTML = '<div class="qr-loading-spinner"><div class="qr-spinner-large"></div></div>';
        fetchPreviewData(id);
    }

    function closeModal() {
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.open-preview-modal-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            openModal(this.dataset.id);
        });
    });

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', function(e) {
        if (e.target === overlay || e.target.classList.contains('qr-preview-backdrop')) {
            closeModal();
        }
    });

    async function fetchPreviewData(id) {
        try {
            const response = await fetch(`/admin/qr-codes/${id}/preview-data`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const res = await response.json();

            if (res.success) {
                const d = res.data;
                let badgeClass = 'qr-badge-inactive';
                let badgeText = 'Inactive';
                
                if (d.status === 'active') {
                    badgeClass = 'qr-badge-active';
                    badgeText = 'Active';
                } else if (d.status === 'expired') {
                    badgeClass = 'qr-badge-expired';
                    badgeText = 'Expired';
                }

                modalBody.innerHTML = `
                    <div class="qr-preview-layout">
                        <div class="qr-preview-image-wrapper">
                            ${d.image_url 
                                ? `<img src="${d.image_url}" alt="QR Code" class="qr-preview-image">` 
                                : `<div class="qr-preview-image" style="background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                                        <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                        <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                        <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                        <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                    </svg>
                                   </div>`
                            }
                        </div>
                        <div class="qr-preview-details">
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Unit Name</span>
                                <span class="qr-preview-value">${d.unit_name}</span>
                            </div>
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Unit Code</span>
                                <span class="qr-preview-value qr-preview-code">${d.unit_code}</span>
                            </div>
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Department</span>
                                <span class="qr-preview-value">${d.department}</span>
                            </div>
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Status</span>
                                <span class="qr-badge ${badgeClass}">${badgeText}</span>
                            </div>
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Generated By</span>
                                <span class="qr-preview-value">${d.generated_by}</span>
                            </div>
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Generated At</span>
                                <span class="qr-preview-value">${d.generated_at}</span>
                            </div>
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Expiration Date</span>
                                <span class="qr-preview-value">${d.expiration_date}</span>
                            </div>
                            <div class="qr-preview-row">
                                <span class="qr-preview-label">Total Scans</span>
                                <span class="qr-preview-value">${d.total_scans}</span>
                            </div>
                        </div>
                    </div>
                    <div class="qr-actions-group">
                        <button type="button" class="qr-btn qr-btn-secondary" onclick="copyToClipboard('${d.code}', 'QR Code')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            Copy QR Code
                        </button>
                        <button type="button" class="qr-btn qr-btn-secondary" onclick="copyToClipboard('${d.scan_url}', 'Scan URL')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                            Copy Scan URL
                        </button>
                    </div>
                `;
                
                downloadBtn.href = d.download_url;
                modalFooter.style.display = 'flex';
            } else {
                modalBody.innerHTML = '<div class="qr-error-msg">Failed to load preview data.</div>';
            }
        } catch (err) {
            modalBody.innerHTML = '<div class="qr-error-msg">An error occurred while loading preview.</div>';
        }
    }

    window.copyToClipboard = function(text, label) {
        navigator.clipboard.writeText(text).then(() => {
            alert(`${label} copied to clipboard!`);
        });
    };
});
</script>