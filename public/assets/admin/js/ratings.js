document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    initializeSortDropdown();
    initializeSearch();
    initializeAutoDismissAlerts();
    initializeSmoothScroll();
    initializeTooltips();
    initializeBulkActions();
});

let selectedFilters = {
    unit_id: '',
    status: '',
    min_score: '',
    max_score: '',
    has_reports: false,
    is_comment_censored: false
};

function initializeFilters() {
    const params = new URLSearchParams(window.location.search);
    
    selectedFilters.unit_id = params.get('unit_id') || '';
    selectedFilters.status = params.get('status') || '';
    selectedFilters.min_score = params.get('min_score') || '';
    selectedFilters.max_score = params.get('max_score') || '';
    selectedFilters.has_reports = params.get('has_reports') === '1';
    selectedFilters.is_comment_censored = params.get('is_comment_censored') === '1';
    
    const unitFilter = document.getElementById('unitFilter');
    const statusFilter = document.getElementById('statusFilter');
    const minScoreFilter = document.getElementById('minScoreFilter');
    const maxScoreFilter = document.getElementById('maxScoreFilter');
    const hasReportsFilter = document.getElementById('hasReportsFilter');
    const censoredFilter = document.getElementById('censoredFilter');
    
    if (unitFilter) unitFilter.value = selectedFilters.unit_id;
    if (statusFilter) statusFilter.value = selectedFilters.status;
    if (minScoreFilter) minScoreFilter.value = selectedFilters.min_score;
    if (maxScoreFilter) maxScoreFilter.value = selectedFilters.max_score;
    if (hasReportsFilter) hasReportsFilter.checked = selectedFilters.has_reports;
    if (censoredFilter) censoredFilter.checked = selectedFilters.is_comment_censored;
    
    const dropdownMenu = document.querySelector('.dropdown-menu');
    if (dropdownMenu) {
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    if (unitFilter) {
        unitFilter.addEventListener('change', function() {
            selectedFilters.unit_id = this.value;
        });
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            selectedFilters.status = this.value;
        });
    }
    
    if (minScoreFilter) {
        minScoreFilter.addEventListener('input', function() {
            selectedFilters.min_score = this.value;
        });
    }
    
    if (maxScoreFilter) {
        maxScoreFilter.addEventListener('input', function() {
            selectedFilters.max_score = this.value;
        });
    }
    
    if (hasReportsFilter) {
        hasReportsFilter.addEventListener('change', function() {
            selectedFilters.has_reports = this.checked;
        });
    }
    
    if (censoredFilter) {
        censoredFilter.addEventListener('change', function() {
            selectedFilters.is_comment_censored = this.checked;
        });
    }
    
    const applyFilter = document.getElementById('applyFilter');
    if (applyFilter) {
        applyFilter.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            applyFilters();
        });
    }
    
    const resetFilter = document.getElementById('resetFilter');
    if (resetFilter) {
        resetFilter.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            resetFilters();
        });
    }
}

function applyFilters() {
    const url = new URL(window.location.href);
    
    if (selectedFilters.unit_id) {
        url.searchParams.set('unit_id', selectedFilters.unit_id);
    } else {
        url.searchParams.delete('unit_id');
    }
    
    if (selectedFilters.status) {
        url.searchParams.set('status', selectedFilters.status);
    } else {
        url.searchParams.delete('status');
    }
    
    if (selectedFilters.min_score) {
        url.searchParams.set('min_score', selectedFilters.min_score);
    } else {
        url.searchParams.delete('min_score');
    }
    
    if (selectedFilters.max_score) {
        url.searchParams.set('max_score', selectedFilters.max_score);
    } else {
        url.searchParams.delete('max_score');
    }
    
    url.searchParams.set('has_reports', selectedFilters.has_reports ? '1' : '0');
    url.searchParams.set('is_comment_censored', selectedFilters.is_comment_censored ? '1' : '0');
    
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = window.location.pathname;
}

function initializeSortDropdown() {
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

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (this.value.trim()) {
                url.searchParams.set('search', this.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }, 500);
    });
}

function initializeAutoDismissAlerts() {
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000);
    });
}

function initializeSmoothScroll() {
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.parentElement.classList.contains('disabled') && 
                !this.parentElement.classList.contains('active')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
}

function initializeTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
}

function initializeBulkActions() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const ratingCheckboxes = document.querySelectorAll('.rating-checkbox');
    const bulkActionsBtn = document.getElementById('bulkActionsBtn');
    const selectedCountSpan = document.querySelector('#bulkActionsBtn .badge');
    const applyBulkAction = document.getElementById('applyBulkAction');
    
    if (!bulkActionsBtn || !selectedCountSpan) return;
    
    let selectedIds = new Set();
    
    function updateBulkActionsButton() {
        const count = selectedIds.size;
        
        if (count > 0) {
            bulkActionsBtn.classList.remove('d-none');
            selectedCountSpan.textContent = count;
            const btnText = bulkActionsBtn.querySelector('span:not(.badge)');
            if (btnText) {
                btnText.textContent = count + ' Selected';
            }
        } else {
            bulkActionsBtn.classList.add('d-none');
        }
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = ratingCheckboxes.length > 0 && ratingCheckboxes.length === selectedIds.size;
            selectAllCheckbox.indeterminate = selectedIds.size > 0 && selectedIds.size < ratingCheckboxes.length;
        }
    }
    
    ratingCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const ratingId = this.value;
            
            if (this.checked) {
                selectedIds.add(ratingId);
            } else {
                selectedIds.delete(ratingId);
            }
            
            updateBulkActionsButton();
        });
    });
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            ratingCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                const ratingId = checkbox.value;
                
                if (isChecked) {
                    selectedIds.add(ratingId);
                } else {
                    selectedIds.delete(ratingId);
                }
            });
            
            updateBulkActionsButton();
        });
    }
    
    bulkActionsBtn.addEventListener('click', function() {
        if (selectedIds.size === 0) return;
        
        const bulkModal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
        document.getElementById('selectedCount').textContent = selectedIds.size;
        document.getElementById('bulkActionModal').dataset.selectedIds = JSON.stringify([...selectedIds]);
        bulkModal.show();
    });
    
    if (applyBulkAction) {
        applyBulkAction.addEventListener('click', function() {
            const modal = document.getElementById('bulkActionModal');
            const selectedIdsArray = JSON.parse(modal.dataset.selectedIds || '[]');
            const action = document.getElementById('bulkActionSelect').value;
            
            if (!action) {
                alert('Please select an action');
                return;
            }
            
            const originalButtonText = this.textContent;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            
            fetch('/admin/ratings/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rating_ids: selectedIdsArray,
                    action: action
                })
            })
            .then(async response => {
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(`HTTP error ${response.status}: ${text.substring(0, 100)}`);
                }
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    throw new Error('Response is not JSON: ' + text.substring(0, 100));
                }
                
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkActionModal'));
                    if (modal) modal.hide();
                    
                    showAlert('success', data.message || 'Bulk action completed successfully');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', data.message || 'Failed to process bulk action');
                    this.disabled = false;
                    this.textContent = originalButtonText;
                }
            })
            .catch(error => {
                console.error('Bulk action error:', error);
                
                let errorMessage = 'Terjadi kesalahan saat memproses request';
                
                if (error.message.includes('HTTP error 419')) {
                    errorMessage = 'Session expired. Please refresh the page.';
                } else if (error.message.includes('HTTP error 401')) {
                    errorMessage = 'Unauthorized. Please login again.';
                } else if (error.message.includes('HTTP error 403')) {
                    errorMessage = 'Forbidden. You don\'t have permission.';
                } else if (error.message.includes('HTTP error 404')) {
                    errorMessage = 'API endpoint not found. Please check URL.';
                } else if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Network error. Please check your connection.';
                }
                
                showAlert('danger', errorMessage);
                
                this.disabled = false;
                this.textContent = originalButtonText;
            });
        });
    }
    
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', function() {
            const applyBtn = document.getElementById('applyBulkAction');
            if (applyBtn) {
                applyBtn.disabled = !this.value;
            }
        });
    }
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                stroke="currentColor" stroke-width="2" class="me-2">
                ${type === 'success' 
                    ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'
                    : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'
                }
            </svg>
            ${message}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid.px-4.py-4 > div:nth-child(3)');
    if (container) {
        container.insertAdjacentHTML('beforebegin', alertHtml);
    } else {
        // Fallback ke body
        document.body.insertAdjacentHTML('afterbegin', alertHtml);
    }
    

    setTimeout(() => {
        const alert = document.querySelector('.alert:last-child');
        if (alert && alert.classList.contains('show')) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 5000);
}