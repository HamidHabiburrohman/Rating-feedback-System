// ============================================
// COMPONENTS.JS - GLOBAL COMPONENT FUNCTIONS
// ============================================

window.openModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('[Components] Modal not found:', modalId);
        return;
    }

    document.body.style.overflow = 'hidden';
    modal.classList.remove('show');
    void modal.offsetHeight;
    modal.classList.add('show');
    modal.style.cssText = 'visibility:visible;opacity:1;display:flex;';

    // Init state setiap kali modal dibuka — bukan hanya sekali
    const checkbox  = modal.querySelector('.confirm-checkbox');
    const deleteBtn = modal.querySelector('.btn-delete');

    if (checkbox && deleteBtn) {
        checkbox.checked  = false;
        deleteBtn.disabled = true;

        // Pasang listener hanya jika belum ada
        if (!checkbox.dataset.listenerAttached) {
            checkbox.dataset.listenerAttached = 'true';
            checkbox.addEventListener('change', function () {
                const btn = this.closest('.custom-modal').querySelector('.btn-delete');
                if (btn) btn.disabled = !this.checked;
            });
        }
    }

    const textarea = modal.querySelector('textarea');
    if (textarea && modalId.toLowerCase().includes('reply')) {
        setTimeout(() => textarea.focus(), 300);
    }
};

window.closeModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove('show');
    modal.style.cssText = '';
    document.body.style.overflow = '';

    const btnText = modal.querySelector('.btn-text');
    const spinner = modal.querySelector('.loading-spinner');
    if (btnText) btnText.style.display = '';
    if (spinner) spinner.style.display = 'none';
};

window.initDeleteModals = function () {
    // Pasang listener submit pada form delete
    document.querySelectorAll('.delete-form').forEach(form => {
        if (form.dataset.listenerAttached) return;
        form.dataset.listenerAttached = 'true';

        form.addEventListener('submit', function (e) {
            const submitBtn = this.querySelector('.btn-delete');
            if (!submitBtn) return true;

            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }

            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.loading-spinner');
            if (btnText) btnText.style.display = 'none';
            if (spinner) spinner.style.display = 'block';
            submitBtn.disabled = true;

            return true;
        });
    });
};

window.initReplyForms = function () {
    document.querySelectorAll('.reply-form').forEach(form => {
        if (form.dataset.listenerAttached) return;
        form.dataset.listenerAttached = 'true';

        form.addEventListener('submit', function (e) {
            const submitBtn = this.querySelector('.btn-submit');
            const textarea  = this.querySelector('textarea');

            if (textarea && !textarea.value.trim()) {
                e.preventDefault();
                textarea.focus();
                return false;
            }

            if (submitBtn && !submitBtn.disabled) {
                const btnText = submitBtn.querySelector('.btn-text');
                const spinner = submitBtn.querySelector('.loading-spinner');
                if (btnText) btnText.style.display = 'none';
                if (spinner) spinner.style.display = 'block';
                submitBtn.disabled = true;
            }
        });
    });
};

function initGlobalHandlers() {
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const openModalEl = document.querySelector('.custom-modal.show');
            if (openModalEl) window.closeModal(openModalEl.id);
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initGlobalHandlers();
    if (document.querySelector('.delete-form'))  window.initDeleteModals();
    if (document.querySelector('.reply-form'))   window.initReplyForms();
});

if (typeof module !== 'undefined' && module.exports) {
    module.exports = { openModal, closeModal, initDeleteModals, initReplyForms };
}