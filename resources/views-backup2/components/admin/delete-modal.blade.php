@props([
    'id'           => '',
    'title'        => 'Delete Confirmation',
    'itemName'     => '',
    'itemType'     => 'item',
    'deleteRoute'  => '',
    'deleteMethod' => 'DELETE'
])

<div class="custom-modal" id="{{ $id }}">
    <div class="custom-modal-backdrop"></div>
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
                    <p class="modal-subtitle">
                        Anda akan menghapus <strong>"{{ $itemName }}"</strong>
                    </p>
                </div>
                <button type="button" class="modal-close-btn"
                    onclick="window.closeModal('{{ $id }}')">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <div class="custom-modal-body">
                <p class="warning-text">
                    Tindakan ini tidak dapat dibatalkan. Semua data yang terkait dengan
                    {{ $itemType }} ini akan dihapus permanen.
                </p>

                <div class="confirmation-checkbox mb-3">
                    <input type="checkbox"
                        id="confirmDelete{{ $id }}"
                        class="confirm-checkbox rounded-3">
                    <label class="ms-3" for="confirmDelete{{ $id }}">
                        Ya, saya mengerti dan ingin melanjutkan
                    </label>
                </div>
            </div>

            <div class="custom-modal-footer">
                <div class="footer-buttons">
                    <button type="button" class="btn-cancel"
                        onclick="window.closeModal('{{ $id }}')">
                        Batal
                    </button>
                    <form method="POST"
                        action="{{ $deleteRoute }}"
                        class="delete-form"
                        id="deleteForm{{ $id }}">
                        @csrf
                        @method($deleteMethod)
                        <button type="submit"
                            class="btn-delete"
                            id="deleteBtn{{ $id }}"
                            disabled>
                            <span class="btn-text">Hapus</span>
                            <span class="loading-spinner" style="display:none;"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>