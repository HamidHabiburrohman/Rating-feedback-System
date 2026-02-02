@props([
    'id' => '',
    'title' => 'Delete Confirmation',
    'itemName' => '',
    'itemType' => 'item',
    'deleteRoute' => '',
    'deleteMethod' => 'DELETE'
])

<div class="custom-modal" id="{{ $id }}">
    <div class="custom-modal-backdrop" onclick="closeModal('{{ $id }}')"></div>
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <div class="header-content">
                    <div class="warning-icon-container">
                        <svg class="warning-icon" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h2 class="modal-title">{{ $title }}</h2>
                    <p class="modal-subtitle">You're going to delete 
                        <strong>"{{ $itemName }}"</strong>
                    </p>
                </div>
                <button class="modal-close-btn" onclick="closeModal('{{ $id }}')">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <div class="custom-modal-body">
                <p class="warning-text">This action cannot be undone. All data associated with this {{ $itemType }} will be permanently deleted.</p>
            </div>

            <div class="custom-modal-footer">
                <div class="footer-buttons">
                    <button class="btn-cancel" onclick="closeModal('{{ $id }}')">
                        No, keep it.
                    </button>
                    <form method="POST" action="{{ $deleteRoute }}" class="delete-form">
                        @csrf
                        @method($deleteMethod)
                        <button type="submit" class="btn-delete">
                            <span class="btn-text">Yes, Delete!</span>
                            <span class="loading-spinner" style="display: none;"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        visibility: hidden;
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s ease;
        pointer-events: none;
    }

    .custom-modal.show {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
    }

    .custom-modal-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .custom-modal-dialog {
        position: relative;
        width: 90%;
        max-width: 420px;
        z-index: 10000;
        margin: auto;
        transform: scale(0.9) translateY(10px);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .custom-modal.show .custom-modal-dialog {
        transform: scale(1) translateY(0);
    }

    .custom-modal-content {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .custom-modal-header {
        padding: 32px 28px 16px;
        position: relative;
        text-align: center;
    }

    .warning-icon-container {
        width: 72px;
        height: 72px;
        margin: 0 auto 20px;
        background: #fee2e2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        animation: iconPulse 2s infinite ease-in-out;
    }

    .warning-icon {
        width: 36px;
        height: 36px;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px;
        letter-spacing: -0.01em;
    }

    .modal-subtitle {
        font-size: 15px;
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }

    .modal-close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        background: #f3f4f6;
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .modal-close-btn:hover {
        background: #e5e7eb;
        color: #374151;
        transform: rotate(90deg);
    }

    .custom-modal-body {
        padding: 0 32px;
    }

    .warning-text {
        font-size: 14px;
        color: #6b7280;
        text-align: center;
        line-height: 1.6;
        margin: 0;
        padding: 0 0 28px;
    }

    .custom-modal-footer {
        padding: 20px 32px 32px;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
    }

    .footer-buttons {
        display: flex;
        gap: 12px;
    }

    .btn-cancel,
    .btn-delete {
        flex: 1;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-cancel {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-cancel:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .btn-delete {
        background: #dc2626;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);
    }

    .btn-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(220, 38, 38, 0.4);
    }

    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes iconPulse {
        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.1);
        }

        50% {
            transform: scale(1.05);
        }

        70% {
            box-shadow: 0 0 0 12px rgba(239, 68, 68, 0);
        }
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 480px) {
        .custom-modal-dialog {
            width: 95%;
        }

        .footer-buttons {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-delete {
            width: 100%;
        }
    }
</style>

<script>
    // Fungsi global yang bisa dipakai di semua modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            document.body.style.overflow = 'hidden';
            modal.classList.add('show');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    // Inisialisasi form submit untuk semua modal
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitButton = this.querySelector('.btn-delete');
                const buttonText = submitButton.querySelector('.btn-text');
                const spinner = submitButton.querySelector('.loading-spinner');

                if (submitButton && !submitButton.disabled) {
                    buttonText.style.display = 'none';
                    spinner.style.display = 'block';
                    submitButton.disabled = true;
                    submitButton.style.opacity = '0.7';
                    this.submit();
                }
            });
        });

        // Close modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModalEl = document.querySelector('.custom-modal.show');
                if (openModalEl) {
                    closeModal(openModalEl.id);
                }
            }
        });
    });
</script>