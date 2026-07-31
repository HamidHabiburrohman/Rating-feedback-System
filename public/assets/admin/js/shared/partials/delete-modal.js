// Delete Modal JavaScript - SIMPLIFIED VERSION
document.addEventListener('DOMContentLoaded', function() {
    setupDeleteModals();
});

function setupDeleteModals() {
    // Setup open modal buttons for onclick attribute
    document.querySelectorAll('[onclick*="openModal(\'deleteModal"]').forEach(button => {
        const onclickAttr = button.getAttribute('onclick');
        const modalIdMatch = onclickAttr.match(/openModal\('(deleteModal[\w]+)'\)/);
        
        if (modalIdMatch) {
            const modalId = modalIdMatch[1];
            button.addEventListener('click', function(e) {
                e.preventDefault();
                openModal(modalId);
            });
        }
    });

    // Setup open modal buttons for report with data attribute
    document.querySelectorAll('.open-delete-modal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const reportId = this.getAttribute('data-report-id');
            if (reportId) {
                const modalId = 'deleteModalReport' + reportId;
                openModal(modalId);
            }
        });
    });

    // Setup confirmation checkboxes - INI SAJA YANG DIPERTAHANKAN
    document.querySelectorAll('.confirm-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const modalId = this.id.replace('confirmDelete', '');
            const deleteBtn = document.getElementById('deleteBtn' + modalId);
            if (deleteBtn) {
                deleteBtn.disabled = !this.checked;
            }
        });
    });

    // Setup loading spinner on form submit - TANPA MENCEGAH SUBMIT
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('.btn-delete');
            if (submitButton) {
                const buttonText = submitButton.querySelector('.btn-text');
                const spinner = submitButton.querySelector('.loading-spinner');
                
                // Tampilkan loading spinner
                if (buttonText) buttonText.style.display = 'none';
                if (spinner) spinner.style.display = 'inline-block';
                
                // Disable button untuk mencegah double click
                submitButton.disabled = true;
            }
        });
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('custom-modal-backdrop')) {
            const modal = e.target.closest('.custom-modal');
            if (modal) {
                closeModal(modal.id);
            }
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.custom-modal.show');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });
}

window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Reset checkbox state
        const checkbox = modal.querySelector('.confirm-checkbox');
        const deleteBtn = modal.querySelector('.btn-delete');
        if (checkbox && deleteBtn) {
            checkbox.checked = false;
            deleteBtn.disabled = true;
        }
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
};