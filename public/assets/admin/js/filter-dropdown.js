document.addEventListener('DOMContentLoaded', function() {
    const filterContainer = document.getElementById('filterContainer');
    if (!filterContainer) return;
    
    const dropdownMenu = document.getElementById('statusFilterDropdown');
    if (!dropdownMenu) return;
    
    const filterButtons = document.querySelectorAll('.filter-status');
    const filterText = document.getElementById('filterText');
    
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('status');
    const currentStatus = statusParam ? statusParam.split(',') : [];
    
    function updateButtonStyles() {
        filterButtons.forEach(button => {
            const value = button.dataset.value;
            
            let isActive = false;
            if (value === '') {
                isActive = currentStatus.length === 0;
            } else {
                isActive = currentStatus.includes(value);
            }
            
            if (isActive) {
                button.style.background = 'linear-gradient(135deg, #f1c3ae, #f8773c)';
                button.style.border = 'none';
                button.style.color = 'white';
            } else {
                button.style.background = 'white';
                button.style.border = '1px solid #d1d5db';
                button.style.color = '#6b7280';
            }
        });
        
        if (filterText) {
            if (currentStatus.length === 0) {
                filterText.textContent = 'Filter';
            } else if (currentStatus.length === 1) {
                const statusMap = { '1': 'Active', '0': 'Inactive' };
                filterText.textContent = statusMap[currentStatus[0]] || 'Filter';
            } else {
                filterText.textContent = `${currentStatus.length} filters`;
            }
        }
    }
    
    dropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    const applyBtn = document.getElementById('applyFilter');
    if (applyBtn) {
        applyBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const activeStatus = [];
            filterButtons.forEach(btn => {
                if (btn.style.background.includes('linear-gradient')) {
                    const value = btn.dataset.value;
                    if (value !== '') {
                        activeStatus.push(value);
                    }
                }
            });
            
            const url = new URL(window.location.href);
            
            if (activeStatus.length > 0) {
                url.searchParams.set('status', activeStatus.join(','));
            } else {
                url.searchParams.delete('status');
            }
            
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }
    
    const resetBtn = document.getElementById('resetFilter');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const url = new URL(window.location.href);
            url.searchParams.delete('status');
            url.searchParams.delete('page');
            
            window.location.href = url.toString();
        });
    }
    
    updateButtonStyles();
});