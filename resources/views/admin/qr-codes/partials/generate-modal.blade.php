<div class="qr-modal-overlay" id="generateQrModal">
    <div class="qr-modal-backdrop"></div>
    <div class="qr-modal-container">
        <div class="qr-modal-header">
            <div>
                <h3 class="qr-modal-title">Generate QR Code</h3>
                <p class="qr-modal-subtitle">Create a new QR code for the selected unit</p>
            </div>
            <button type="button" class="qr-modal-close" id="closeGenerateModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="qr-modal-body">
            <div class="qr-error-msg" id="generateErrorMsg"></div>

            <div class="qr-form-group">
                <label class="qr-form-label">Select Unit <span class="text-danger">*</span></label>
                <select class="qr-form-select" id="generateUnitSelect">
                    <option value="">-- Choose a unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" 
                                data-name="{{ $unit->name }}" 
                                data-code="{{ $unit->code ?? 'N/A' }}"
                                data-dept="{{ $unit->unitDepartment->name ?? 'N/A' }}">
                            {{ $unit->name }} ({{ $unit->code ?? 'No Code' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="qr-info-grid" id="generateInfoGrid">
                <div class="qr-info-card">
                    <span class="qr-info-label">Unit Name</span>
                    <span class="qr-info-value" id="genUnitName">-</span>
                </div>
                <div class="qr-info-card">
                    <span class="qr-info-label">Unit Code</span>
                    <span class="qr-info-value qr-code-text" id="genUnitCode">-</span>
                </div>
                <div class="qr-info-card">
                    <span class="qr-info-label">Department</span>
                    <span class="qr-info-value" id="genDepartment">-</span>
                </div>
                <div class="qr-info-card">
                    <span class="qr-info-label">Current QR</span>
                    <span class="qr-info-value" id="genCurrentQr">None</span>
                </div>
            </div>

            <div class="qr-warning-card">
                <svg class="qr-warning-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.345 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="qr-warning-title">Important Notice</h4>
                    <p class="qr-warning-text">Generating a new QR code will automatically deactivate the current active QR code for this unit.</p>
                </div>
            </div>
        </div>

        <div class="qr-modal-footer">
            <button type="button" class="qr-btn qr-btn-secondary" id="cancelGenerateModal">Cancel</button>
            <button type="button" class="qr-btn qr-btn-primary" id="submitGenerateModal">
                <span class="qr-spinner"></span>
                <span class="qr-btn-text">Generate QR Code</span>
            </button>
        </div>
    </div>
</div>

<style>
.qr-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1050;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}

.qr-modal-overlay.is-open {
    display: flex;
}

.qr-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(8px);
    animation: fadeIn 0.2s ease;
}

.qr-modal-container {
    position: relative;
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 640px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.qr-modal-header {
    padding: 2rem 2rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}

.qr-modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin: 0 0 0.25rem 0;
}

.qr-modal-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

.qr-modal-close {
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

.qr-modal-close:hover {
    background: #f1f5f9;
    color: #475569;
}

.qr-modal-body {
    padding: 1.5rem 2rem;
    overflow-y: auto;
    flex: 1;
}

.qr-modal-footer {
    padding: 1.5rem 2rem;
    background: #f8fafc;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    border-radius: 0 0 24px 24px;
}

.qr-error-msg {
    background: #fef2f2;
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #dc2626;
    padding: 0.875rem 1rem;
    border-radius: 12px;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
    display: none;
}

.qr-error-msg.is-visible {
    display: block;
}

.qr-form-group {
    margin-bottom: 1.5rem;
}

.qr-form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}

.qr-form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 12px;
    font-size: 0.875rem;
    color: #0f172a;
    background: #ffffff;
    transition: all 0.2s ease;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.qr-form-select:focus {
    outline: none;
    border-color: #f8773c;
    box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1);
}

.qr-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.qr-info-card {
    background: #f8fafc;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 14px;
    padding: 1rem;
}

.qr-info-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.375rem;
}

.qr-info-value {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #0f172a;
    word-break: break-word;
}

.qr-code-text {
    font-family: 'SF Mono', 'Menlo', monospace;
    background: #ffffff;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8125rem;
}

.qr-warning-card {
    background: #fef3c7;
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: 14px;
    padding: 1.25rem;
    display: flex;
    gap: 0.875rem;
}

.qr-warning-icon {
    color: #f59e0b;
    flex-shrink: 0;
    margin-top: 2px;
}

.qr-warning-title {
    font-size: 0.875rem;
    font-weight: 700;
    color: #d97706;
    margin: 0 0 0.375rem 0;
}

.qr-warning-text {
    font-size: 0.875rem;
    color: #d97706;
    margin: 0;
    line-height: 1.6;
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

.qr-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.qr-spinner {
    display: none;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #ffffff;
    animation: spin 0.8s linear infinite;
}

.qr-btn.is-loading .qr-spinner {
    display: block;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.text-danger {
    color: #ef4444;
}

@media (max-width: 768px) {
    .qr-modal-container {
        max-width: 100%;
    }
    
    .qr-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('generateQrModal');
    const closeBtn = document.getElementById('closeGenerateModal');
    const cancelBtn = document.getElementById('cancelGenerateModal');
    const submitBtn = document.getElementById('submitGenerateModal');
    const selectInput = document.getElementById('generateUnitSelect');
    const errorMsg = document.getElementById('generateErrorMsg');
    const btnText = submitBtn.querySelector('.qr-btn-text');

    function openModal() {
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
        errorMsg.classList.remove('is-visible');
        submitBtn.classList.remove('is-loading');
        submitBtn.disabled = false;
        btnText.textContent = 'Generate QR Code';
    }

    document.querySelectorAll('.open-generate-modal-btn').forEach(btn => {
        btn.addEventListener('click', openModal);
    });

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', function(e) {
        if (e.target === overlay || e.target.classList.contains('qr-modal-backdrop')) {
            closeModal();
        }
    });

    selectInput.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        document.getElementById('genUnitName').textContent = selected.dataset.name || '-';
        document.getElementById('genUnitCode').textContent = selected.dataset.code || '-';
        document.getElementById('genDepartment').textContent = selected.dataset.dept || '-';
    });

    submitBtn.addEventListener('click', async function() {
        const unitId = selectInput.value;
        errorMsg.classList.remove('is-visible');

        if (!unitId) {
            errorMsg.textContent = 'Please select a unit first.';
            errorMsg.classList.add('is-visible');
            return;
        }

        this.classList.add('is-loading');
        this.disabled = true;
        btnText.textContent = 'Generating...';

        try {
            const response = await fetch('{{ route("admin.qr-codes.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ unit_id: unitId })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeModal();
                window.location.reload();
            } else {
                errorMsg.textContent = data.message || 'Failed to generate QR Code.';
                errorMsg.classList.add('is-visible');
                this.classList.remove('is-loading');
                this.disabled = false;
                btnText.textContent = 'Generate QR Code';
            }
        } catch (err) {
            errorMsg.textContent = 'An unexpected error occurred. Please try again.';
            errorMsg.classList.add('is-visible');
            this.classList.remove('is-loading');
            this.disabled = false;
            btnText.textContent = 'Generate QR Code';
        }
    });
});
</script>