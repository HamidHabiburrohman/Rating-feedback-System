document.addEventListener('DOMContentLoaded', function() {
    let selectedAction = '';
    let selectedTargetType = '';
    let selectedAdmin = '';
    const params = new URLSearchParams(window.location.search);
    
    function initState() {
        selectedAction = params.get('action') || '';
        selectedTargetType = params.get('target_type') || '';
        selectedAdmin = params.get('admin_id') || '';
        
        if (selectedAction) {
            document.getElementById('actionFilter').value = selectedAction;
        }
        if (selectedTargetType) {
            document.getElementById('targetTypeFilter').value = selectedTargetType;
        }
        if (selectedAdmin) {
            document.getElementById('adminFilter').value = selectedAdmin;
        }
    }
    
    function bindFilterEvents() {
        const actionFilter = document.getElementById('actionFilter');
        const targetTypeFilter = document.getElementById('targetTypeFilter');
        const adminFilter = document.getElementById('adminFilter');
        
        if (actionFilter) {
            actionFilter.addEventListener('change', function() {
                selectedAction = this.value;
            });
        }
        
        if (targetTypeFilter) {
            targetTypeFilter.addEventListener('change', function() {
                selectedTargetType = this.value;
            });
        }
        
        if (adminFilter) {
            adminFilter.addEventListener('change', function() {
                selectedAdmin = this.value;
            });
        }
    }
    
    function bindActionButtons() {
        document.getElementById('applyFilter')?.addEventListener('click', function() {
            const url = new URL(window.location.href);
            
            if (selectedAction) {
                url.searchParams.set('action', selectedAction);
            } else {
                url.searchParams.delete('action');
            }
            
            if (selectedTargetType) {
                url.searchParams.set('target_type', selectedTargetType);
            } else {
                url.searchParams.delete('target_type');
            }
            
            if (selectedAdmin) {
                url.searchParams.set('admin_id', selectedAdmin);
            } else {
                url.searchParams.delete('admin_id');
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
    
    function initCleanupModal() {
        const cleanupBtn = document.querySelector('[onclick="cleanupLogs()"]');
        if (cleanupBtn) {
            cleanupBtn.addEventListener('click', function(e) {
                e.preventDefault();
                new bootstrap.Modal(document.getElementById('cleanupModal')).show();
            });
        }
        
        document.getElementById('confirmCleanup')?.addEventListener('click', function() {
            const days = document.getElementById('cleanupDays')?.value || 90;
            const url = `/admin/moderation-logs/cleanup?days=${days}`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Cleanup failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Cleanup error:', error);
                alert('Cleanup failed');
            });
        });
    }
    
    function initViewLogModal() {
        window.viewLogDetails = function(id) {
            // Fetch log details via AJAX if needed
            // For now, just show a basic modal
            const modal = document.getElementById('viewLogModal');
            if (!modal) return;
            
            // You can implement AJAX fetch here if needed
            new bootstrap.Modal(modal).show();
        };
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
            
            document.getElementById('deleteModalText').textContent = `Are you sure you want to delete ${name}?`;
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
    initCleanupModal();
    initViewLogModal();
    bindDropdownToggles();
    initDeleteModal();
    initAutoDismissAlerts();
    initSmoothScroll();
});