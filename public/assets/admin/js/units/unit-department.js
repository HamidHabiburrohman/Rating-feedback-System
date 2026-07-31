document.addEventListener('DOMContentLoaded', function() {
    let selectedStatus = [];
    const params = new URLSearchParams(window.location.search);
    
    function initState() {
        const status = params.get('status');
        if (status) {
            selectedStatus = status.split(',');
            selectedStatus.forEach(value => {
                if (value) {
                    document.querySelectorAll(`.filter-status[data-value="${value}"]`).forEach(btn => {
                        btn.classList.add('active-filter');
                    });
                }
            });
        }
        updateFilterStyles();
    }
    
    function updateFilterStyles() {
        document.querySelectorAll('.filter-status').forEach(btn => {
            const value = btn.dataset.value;
            if (value === '') {
                btn.style.cssText = selectedStatus.length ? 
                    'background: white; border: 1px solid #d1d5db; color: #6b7280;' : 
                    'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
            } else {
                btn.style.cssText = selectedStatus.includes(value) ? 
                    'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;' : 
                    'background: white; border: 1px solid #d1d5db; color: #6b7280;';
            }
        });
    }
    
    function bindFilterEvents() {
        document.querySelectorAll('.filter-status').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.dataset.value;
                
                if (value === '') {
                    selectedStatus = [];
                    document.querySelectorAll('.filter-status').forEach(b => b.classList.remove('active-filter'));
                } else {
                    const index = selectedStatus.indexOf(value);
                    if (index === -1) {
                        selectedStatus.push(value);
                        this.classList.add('active-filter');
                    } else {
                        selectedStatus.splice(index, 1);
                        this.classList.remove('active-filter');
                    }
                    
                    const allBtn = document.querySelector('.filter-status[data-value=""]');
                    if (selectedStatus.length === 0) {
                        allBtn?.classList.add('active-filter');
                    } else {
                        allBtn?.classList.remove('active-filter');
                    }
                }
                updateFilterStyles();
            });
        });
    }
    
    function bindActionButtons() {
        document.getElementById('applyFilter')?.addEventListener('click', function() {
            const url = new URL(window.location.href);
            if (selectedStatus.length) {
                url.searchParams.set('status', selectedStatus.join(','));
            } else {
                url.searchParams.delete('status');
            }
            url.searchParams.set('page', 1);
            window.location.href = url;
        });
        
        document.getElementById('resetFilter')?.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
        
        const searchInput = document.getElementById('searchInput');
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
    
    initState();
    bindFilterEvents();
    bindActionButtons();
    bindDropdownToggles();
    initDeleteModal();
    initAutoDismissAlerts();
    initSmoothScroll();
});