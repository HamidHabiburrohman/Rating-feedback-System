// ============================================
// COMPONENTS.JS - GLOBAL COMPONENT FUNCTIONS
// ============================================

// ============================================
// MODAL SYSTEM
// ============================================

/**
 * Open custom modal by ID
 * @param {string} modalId - ID of the modal element
 */
window.openModal = function(modalId) {
    console.log('[Components] Opening modal:', modalId);
    const modal = document.getElementById(modalId);
    if (modal) {
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Show modal
        modal.classList.add('show');
        
        // Initialize modal-specific logic
        initModalState(modal, modalId);
    } else {
        console.error('[Components] Modal not found:', modalId);
    }
};

/**
 * Close custom modal by ID
 * @param {string} modalId - ID of the modal element
 */
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        
        // Reset form state for delete modals
        resetModalForm(modal);
    }
};

/**
 * Initialize modal state when opened
 */
function initModalState(modal, modalId) {
    // For delete modals: reset checkbox and button
    if (modalId.includes('deleteModal')) {
        const checkbox = modal.querySelector('.confirm-checkbox');
        const deleteBtn = modal.querySelector('.btn-delete');
        
        if (checkbox) checkbox.checked = false;
        if (deleteBtn) deleteBtn.disabled = true;
    }
    
    // For reply modals: focus on textarea
    if (modalId.includes('replyModal') || modalId.includes('ReplyModal')) {
        const textarea = modal.querySelector('textarea');
        if (textarea) {
            setTimeout(() => textarea.focus(), 300);
        }
    }
}

/**
 * Reset modal form state
 */
function resetModalForm(modal) {
    const submitButton = modal.querySelector('.btn-delete, .btn-submit');
    if (submitButton) {
        const buttonText = submitButton.querySelector('.btn-text');
        const spinner = submitButton.querySelector('.loading-spinner');
        
        if (buttonText) buttonText.style.display = 'inline';
        if (spinner) spinner.style.display = 'none';
        submitButton.disabled = false;
    }
}

// ============================================
// DELETE MODAL INITIALIZATION
// ============================================

/**
 * Initialize all delete modals on the page
 */
window.initDeleteModals = function() {
    console.log('[Components] Initializing delete modals...');
    
    document.querySelectorAll('.custom-modal').forEach(modal => {
        const checkbox = modal.querySelector('.confirm-checkbox');
        const deleteBtn = modal.querySelector('.btn-delete');
        
        if (checkbox && deleteBtn) {
            // Set initial state
            deleteBtn.disabled = true;
            
            // Checkbox change handler
            checkbox.addEventListener('change', function() {
                deleteBtn.disabled = !this.checked;
            });
        }
    });

    // Delete form submission handler
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('.btn-delete');
            
            // NULL CHECK
            if (!submitButton) {
                console.error('[Components] Delete button not found in form');
                return true;
            }
            
            // Prevent submission if button is disabled
            if (submitButton.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const buttonText = submitButton.querySelector('.btn-text');
            const spinner = submitButton.querySelector('.loading-spinner');
            
            if (buttonText) buttonText.style.display = 'none';
            if (spinner) spinner.style.display = 'block';
            submitButton.disabled = true;
            
            return true;
        });
    });
};

// ============================================
// REPLY FORM INITIALIZATION
// ============================================

/**
 * Initialize all reply forms on the page
 */
window.initReplyForms = function() {
    console.log('[Components] Initializing reply forms...');
    
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('.btn-submit');
            const textarea = this.querySelector('textarea');

            // Validate textarea
            if (textarea && !textarea.value.trim()) {
                alert('Please enter a reply before submitting.');
                textarea.focus();
                e.preventDefault();
                return false;
            }

            if (submitButton && !submitButton.disabled) {
                // Show loading state
                const buttonText = submitButton.querySelector('.btn-text');
                const spinner = submitButton.querySelector('.loading-spinner');
                
                if (buttonText) buttonText.style.display = 'none';
                if (spinner) spinner.style.display = 'block';
                submitButton.disabled = true;
            }
        });
    });
};

// ============================================
// GLOBAL EVENT HANDLERS
// ============================================

/**
 * Initialize global event handlers
 */
function initGlobalHandlers() {
    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModalEl = document.querySelector('.custom-modal.show');
            if (openModalEl) {
                closeModal(openModalEl.id);
            }
        }
    });
    
    // Click backdrop to close modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('custom-modal-backdrop')) {
            const modal = e.target.closest('.custom-modal');
            if (modal) {
                closeModal(modal.id);
            }
        }
    });
}

// ============================================
// DEBUG & UTILITY FUNCTIONS
// ============================================

/**
 * Debug function to check all modals
 */
window.debugModals = function() {
    console.log('=== MODAL DEBUG ===');
    
    const modals = document.querySelectorAll('.custom-modal');
    console.log('Total modals:', modals.length);
    
    modals.forEach(modal => {
        const forms = modal.querySelectorAll('form');
        console.log(`Modal: ${modal.id}`, {
            forms: forms.length,
            isVisible: modal.classList.contains('show'),
            hasDeleteForm: !!modal.querySelector('.delete-form'),
            hasReplyForm: !!modal.querySelector('.reply-form')
        });
    });
    
    console.log('openModal function:', typeof openModal);
    console.log('closeModal function:', typeof closeModal);
    
    return 'Debug complete - check console';
};

// ============================================
// AUTO-INITIALIZATION
// ============================================

/**
 * Initialize all components when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('[Components] Initializing...');
    
    // Initialize global handlers
    initGlobalHandlers();
    
    // Initialize delete modals if any exist
    if (document.querySelector('.delete-form')) {
        initDeleteModals();
    }
    
    // Initialize reply forms if any exist
    if (document.querySelector('.reply-form')) {
        initReplyForms();
    }
    
    console.log('[Components] Initialization complete');
});

// Export for module systems (optional)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        openModal,
        closeModal,
        initDeleteModals,
        initReplyForms,
        debugModals
    };
}