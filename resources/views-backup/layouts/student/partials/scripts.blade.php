<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="{{ asset('assets/student/js/Student.js') }}"></script>

<script>
    function openAuthModal(modalId = 'auth-modal') {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.remove('closing');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeAuthModal(modalId = 'auth-modal') {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.add('closing');

        setTimeout(() => {
            modal.classList.remove('active', 'closing');
            document.body.style.overflow = '';
        }, 280);
    }

    window.triggerAuthModal = function(modalId = 'auth-modal') {
        openAuthModal(modalId);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModals = document.querySelectorAll('.auth-modal-overlay.active');
            activeModals.forEach(modal => closeAuthModal(modal.id));
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const moreBtn = document.querySelectorAll('[data-dropdown-btn]');
        moreBtn.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const target = document.querySelector(btn.dataset.dropdownTarget);
                if (target) target.classList.toggle('hidden');
            });
        });
    });
</script>
