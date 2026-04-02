document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const searchInput = document.getElementById('searchInput');
    
    function bindSearch() {
        if (searchInput) {
            let timeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    if (this.value) {
                        url.searchParams.set('search', this.value);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('page', 1);
                    window.location.href = url;
                }, 500);
            });
        }
    }
    
    function bindDropdownToggles() {
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
    
    function initDeleteModal() {
        window.openDeleteModal = function(id, name, route) {
            const modal = document.getElementById('deleteModal');
            if (!modal) return;
            
            document.getElementById('deleteModalText').textContent = `Are you sure you want to delete "${name}"?`;
            document.getElementById('deleteForm').action = route;
            
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        };
    }
    
    function initAutoDismissAlerts() {
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                if (alert.classList.contains('show')) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    }
    
    function initSmoothScroll() {
        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.parentElement.classList.contains('disabled') && 
                    !this.parentElement.classList.contains('active')) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }
    
    bindSearch();
    bindDropdownToggles();
    initDeleteModal();
    initAutoDismissAlerts();
    initSmoothScroll();
});