// ============================================
// ADMIN RATINGS - MAIN JAVASCRIPT FILE
// ============================================

// ============================================
// SECTION 1: GLOBAL MODAL FUNCTIONS
// ============================================

// Global modal functions (used by delete-modal component and rows)
if (!window.openModal) {
    window.openModal = function(modalId) {
        console.log('Opening modal:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            document.body.style.overflow = 'hidden';
            modal.classList.add('show');

            // Reset checkbox dan button state untuk delete modal
            if (modalId.startsWith('deleteModalRating')) {
                const checkbox = modal.querySelector('.confirm-checkbox');
                const deleteBtn = modal.querySelector('.btn-delete');
                
                if (checkbox) checkbox.checked = false;
                if (deleteBtn) deleteBtn.disabled = true;
            }
            
            // Focus on textarea for reply modal
            if (modalId.startsWith('replyModalRating')) {
                const textarea = modal.querySelector('textarea');
                if (textarea) {
                    setTimeout(() => {
                        textarea.focus();
                    }, 300);
                }
            }
        }
    };
}

if (!window.closeModal) {
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    };
}

// ============================================
// SECTION 2: DELETE MODAL COMPONENT INITIALIZATION
// ============================================

function initDeleteModals() {
    console.log('Initializing delete modals...');
    
    // 1. Setup checkbox untuk setiap modal - AMAN DENGAN NULL CHECK
    document.querySelectorAll('.custom-modal').forEach(modal => {
        const checkbox = modal.querySelector('.confirm-checkbox');
        const deleteBtn = modal.querySelector('.btn-delete');
        
        if (checkbox && deleteBtn) {
            // Set initial state
            deleteBtn.disabled = true;
            
            // Add event listener untuk checkbox
            checkbox.addEventListener('change', function() {
                console.log('Checkbox changed:', this.checked, 'for modal:', modal.id);
                deleteBtn.disabled = !this.checked;
            });
        }
    });

    // 2. Form submission handler yang AMAN
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('Form submit triggered for:', this.id);
            
            const submitButton = this.querySelector('.btn-delete');
            
            // NULL CHECK
            if (!submitButton) {
                console.error('Delete button not found in form:', this.id);
                return true; // Biarkan submit normal
            }
            
            // Check jika button disabled
            if (submitButton.disabled) {
                console.warn('Button is disabled, preventing submit');
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const buttonText = submitButton.querySelector('.btn-text');
            const spinner = submitButton.querySelector('.loading-spinner');
            
            if (buttonText) buttonText.style.display = 'none';
            if (spinner) spinner.style.display = 'block';
            
            submitButton.disabled = true;
            console.log('Form submitting...');
            
            // Biarkan form submit secara normal
            return true;
        });
    });
}

// ============================================
// SECTION 3: REPLY FORM HANDLING
// ============================================

function initReplyForms() {
    console.log('Setting up reply forms...');
    
    // Add submit handler for reply forms ONLY
    const replyForms = document.querySelectorAll('.reply-form');
    replyForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitButton = this.querySelector('.btn-submit');
            const buttonText = submitButton?.querySelector('.btn-text');
            const spinner = submitButton?.querySelector('.loading-spinner');
            const textarea = this.querySelector('textarea');

            // Validate textarea
            if (textarea && !textarea.value.trim()) {
                alert('Silakan masukkan balasan terlebih dahulu.');
                textarea.focus();
                return;
            }

            if (submitButton && !submitButton.disabled) {
                // Show loading state
                if (buttonText) buttonText.style.display = 'none';
                if (spinner) spinner.style.display = 'block';
                submitButton.disabled = true;
                submitButton.style.opacity = '0.7';

                // Submit the form
                this.submit();
            }
        });
    });
}

// ============================================
// SECTION 4: FILTERING & SEARCH FUNCTIONALITY
// ============================================

function initFilters() {
    let status = new Set();
    const params = new URLSearchParams(location.search);

    initState();
    initIcons();
    bindFilters();
    updateLabel();

    function initState() {
        const s = params.get('status');
        if (s) s.split(',').forEach(v => v && status.add(v));
        paint();
    }

    function initIcons() {
        document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
            const icon = btn.querySelector('.dropdown-icon');
            if (!icon) return;
            btn.addEventListener('show.bs.dropdown', () => {
                icon.style.transform = 'rotate(180deg)';
            });
            btn.addEventListener('hide.bs.dropdown', () => {
                icon.style.transform = 'rotate(0)';
            });
        });
    }

    function bindFilters() {
        document.querySelectorAll('.filter-status')
            .forEach(b => b.addEventListener('click', e => toggle(e, status)));
        document.getElementById('applyFilter')?.addEventListener('click', apply);
        document.getElementById('resetFilter')?.addEventListener('click', reset);

        const search = document.getElementById('searchInput');
        if (search) {
            let t;
            search.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(searchNow, 500);
            });
        }
    }

    function toggle(e, set) {
        e.stopPropagation();
        const v = e.currentTarget.dataset.value;
        v === '' ? set.clear() :
            set.has(v) ? set.delete(v) :
                set.add(v);
        paint();
    }

    function paint() {
        const on = 'background:linear-gradient(135deg,#f1c3ae,#f8773c);border:none;color:white;';
        const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

        document.querySelectorAll('.filter-status').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (status.size ? off : on) : (status.has(v) ? on : off);
        });
    }

    function apply() {
        const url = new URL(location.href);
        status.size ? url.searchParams.set('status', [...status].join(',')) : url.searchParams.delete('status');

        const df = document.getElementById('dateFrom').value;
        const dt = document.getElementById('dateTo').value;
        df ? url.searchParams.set('date_from', df) : url.searchParams.delete('date_from');
        dt ? url.searchParams.set('date_to', dt) : url.searchParams.delete('date_to');

        url.searchParams.set('page', 1);
        location.href = url;
    }

    function reset() {
        const url = new URL(location.href);
        status.clear();
        url.searchParams.delete('status');
        url.searchParams.delete('date_from');
        url.searchParams.delete('date_to');
        url.searchParams.delete('search');
        url.searchParams.set('page', 1);
        location.href = url;
    }

    function searchNow() {
        const v = document.getElementById('searchInput').value;
        const url = new URL(location.href);
        v ? url.searchParams.set('search', v) : url.searchParams.delete('search');
        url.searchParams.set('page', 1);
        location.href = url;
    }

    function updateLabel() {
        const btn = document.getElementById('filterDropdown');
        const text = document.getElementById('filterText');
        if (!btn || !text) return;
        let badge = btn.querySelector('.badge');

        const s = params.get('status');
        const df = params.get('date_from');
        const dt = params.get('date_to');

        if (s || df || dt) {
            let v = 'Filter';
            if (s) v += ': ' + s.split(',').map(label).join(', ');
            if (df || dt) v += (s) ? ', date' : ': date';
            text.textContent = v;

            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'badge bg-primary rounded-circle ms-1';
                badge.style = 'width:6px;height:6px;background: linear-gradient(135deg, #f1c3ae, #f8773c);';
                btn.appendChild(badge);
            }
        } else {
            text.textContent = 'Filter';
            badge && badge.remove();
        }
    }

    function label(v) {
        const labels = {
            'pending': 'Pending',
            'dibalas': 'Responded',
            'selesai': 'Completed'
        };
        return labels[v] || v;
    }
}

// ============================================
// SECTION 5: PAGINATION & UI ENHANCEMENTS
// ============================================

function initUIEnhancements() {
    // Smooth scroll for pagination
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.parentElement.classList.contains('disabled') && 
                !this.parentElement.classList.contains('active')) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Animation for rating stars
    const stars = document.querySelectorAll('.rating-visual svg');
    stars.forEach((star, index) => {
        star.style.transition = 'all 0.3s ease';
        star.style.animationDelay = `${index * 0.1}s`;
    });

    // Initialize Bootstrap modal focus for reply
    const replyModal = document.getElementById('replyModal');
    if (replyModal) {
        replyModal.addEventListener('shown.bs.modal', function () {
            const textarea = document.getElementById('reply_message');
            if (textarea) textarea.focus();
        });
    }
}

// ============================================
// SECTION 6: ESC KEY HANDLER FOR MODALS
// ============================================

function initEscKeyHandler() {
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const openModalEl = document.querySelector('.custom-modal.show');
            if (openModalEl) {
                closeModal(openModalEl.id);
            }
        }
    });
}

// ============================================
// SECTION 7: DEBUG HELPER FUNCTIONS
// ============================================

// Debug function untuk delete modals
window.debugDeleteModal = function() {
    console.log('=== DEBUG DELETE MODAL ===');
    
    // Cek semua modal
    const modals = document.querySelectorAll('.custom-modal');
    console.log('Total modals:', modals.length);
    
    modals.forEach(modal => {
        const form = modal.querySelector('.delete-form');
        const btn = modal.querySelector('.btn-delete');
        const checkbox = modal.querySelector('.confirm-checkbox');
        
        console.log(`Modal: ${modal.id}`, {
            hasForm: !!form,
            hasButton: !!btn,
            hasCheckbox: !!checkbox,
            buttonId: btn ? btn.id : 'none',
            checkboxId: checkbox ? checkbox.id : 'none'
        });
    });
    
    // Cek semua delete forms
    const forms = document.querySelectorAll('.delete-form');
    console.log('Total delete forms:', forms.length);
    
    return 'Debug complete - lihat console untuk details';
}

// ============================================
// SECTION 8: MAIN INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    console.log('Admin Ratings JS Initializing...');
    
    // Initialize all modules
    initDeleteModals();
    initReplyForms();
    initFilters();
    initUIEnhancements();
    initEscKeyHandler();
    
    console.log('Admin Ratings JS Initialization Complete');
});

// Export functions if using modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        openModal,
        closeModal,
        debugDeleteModal
    };
}