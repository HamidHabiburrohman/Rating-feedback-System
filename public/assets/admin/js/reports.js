document.addEventListener('DOMContentLoaded', function () {
    initializeFilters();
    initializeSortDropdown();
    initializeSearch();
    initializeAutoDismissAlerts();
    initializeSmoothScroll();
    initializeTooltips();
    initializeReplyModal();
    initializeQuickResponses();
    initializeCharacterCounter();
});

let selectedFilters = {
    status: '',
    priority: '',
    unit_id: ''
};

function initializeFilters() {
    const params = new URLSearchParams(window.location.search);

    selectedFilters.status = params.get('status') || '';
    selectedFilters.priority = params.get('priority') || '';
    selectedFilters.unit_id = params.get('unit_id') || '';

    const unitFilter = document.getElementById('unitFilter');
    if (unitFilter) {
        unitFilter.value = selectedFilters.unit_id;
    }

    const dropdownMenu = document.querySelector('.dropdown-menu');
    if (dropdownMenu) {
        dropdownMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    document.querySelectorAll('.filter-status').forEach(btn => {
        const value = btn.dataset.value;
        if (selectedFilters.status.split(',').includes(value)) {
            btn.style = 'background: #f8773c; border: none; color: white;';
        } else if (value === '' && !selectedFilters.status) {
            btn.style = 'background: #f8773c; border: none; color: white;';
        } else {
            btn.style = 'background:white;border:1px solid #d1d5db;color:#6b7280;';
        }

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleFilter(this, 'status');
        });
    });

    document.querySelectorAll('.filter-priority').forEach(btn => {
        const value = btn.dataset.value;
        if (selectedFilters.priority.split(',').includes(value)) {
            btn.style = 'background: #f8773c; border: none; color: white;';
        } else if (value === '' && !selectedFilters.priority) {
            btn.style = 'background: #f8773c; border: none; color: white;';
        } else {
            btn.style = 'background:white;border:1px solid #d1d5db;color:#6b7280;';
        }

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleFilter(this, 'priority');
        });
    });

    if (unitFilter) {
        unitFilter.addEventListener('change', function () {
            selectedFilters.unit_id = this.value;
        });
    }

    document.getElementById('applyFilter')?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        applyFilters();
    });

    document.getElementById('resetFilter')?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        resetFilters();
    });
}

function toggleFilter(btn, type) {
    const value = btn.dataset.value;
    const on = 'background: #f8773c; border: none; color: white;';
    const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

    if (value === '') {
        if (type === 'status') {
            selectedFilters.status = '';
            document.querySelectorAll('.filter-status').forEach(b => {
                if (b.dataset.value !== '') {
                    b.style = off;
                } else {
                    b.style = on;
                }
            });
        } else {
            selectedFilters.priority = '';
            document.querySelectorAll('.filter-priority').forEach(b => {
                if (b.dataset.value !== '') {
                    b.style = off;
                } else {
                    b.style = on;
                }
            });
        }
    } else {
        let currentValues = type === 'status' ? selectedFilters.status : selectedFilters.priority;
        let values = currentValues ? currentValues.split(',') : [];

        if (values.includes(value)) {
            values = values.filter(v => v !== value);
        } else {
            values.push(value);
        }

        if (type === 'status') {
            selectedFilters.status = values.join(',');
            document.querySelector('.filter-status[data-value=""]').style = off;
        } else {
            selectedFilters.priority = values.join(',');
            document.querySelector('.filter-priority[data-value=""]').style = off;
        }

        if (values.length === 0) {
            if (type === 'status') {
                document.querySelector('.filter-status[data-value=""]').style = on;
            } else {
                document.querySelector('.filter-priority[data-value=""]').style = on;
            }
        }

        btn.style = values.includes(value) ? on : off;
    }

    updateFilterLabel();
}

function applyFilters() {
    const url = new URL(window.location.href);

    if (selectedFilters.status) {
        url.searchParams.set('status', selectedFilters.status);
    } else {
        url.searchParams.delete('status');
    }

    if (selectedFilters.priority) {
        url.searchParams.set('priority', selectedFilters.priority);
    } else {
        url.searchParams.delete('priority');
    }

    if (selectedFilters.unit_id) {
        url.searchParams.set('unit_id', selectedFilters.unit_id);
    } else {
        url.searchParams.delete('unit_id');
    }

    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = window.location.pathname;
}

function updateFilterLabel() {
    const filterText = document.getElementById('filterText');
    const filterDropdown = document.getElementById('filterDropdown');

    if (!filterText || !filterDropdown) return;

    let badge = filterDropdown.querySelector('.filter-badge');
    const statusValues = selectedFilters.status ? selectedFilters.status.split(',') : [];
    const priorityValues = selectedFilters.priority ? selectedFilters.priority.split(',') : [];

    if (statusValues.length > 0 || priorityValues.length > 0 || selectedFilters.unit_id) {
        let text = 'Filter';
        const parts = [];

        if (statusValues.length > 0) {
            const statusLabels = statusValues.map(v => {
                const labels = {
                    'new': 'New',
                    'in_progress': 'In Progress',
                    'resolved': 'Resolved',
                    'rejected': 'Rejected'
                };
                return labels[v] || v;
            });
            parts.push(statusLabels.join(', '));
        }

        if (priorityValues.length > 0) {
            const priorityLabels = priorityValues.map(v => {
                const labels = {
                    'low': 'Low',
                    'medium': 'Medium',
                    'high': 'High',
                    'critical': 'Critical'
                };
                return labels[v] || v;
            });
            parts.push(priorityLabels.join(', '));
        }

        if (selectedFilters.unit_id) {
            const unitSelect = document.getElementById('unitFilter');
            const unitText = unitSelect.options[unitSelect.selectedIndex]?.text;
            if (unitText && unitText !== 'All Units') {
                parts.push(unitText);
            }
        }

        if (parts.length > 0) {
            text += ': ' + parts.join(', ');
        }

        filterText.textContent = text;

        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'filter-badge';
            badge.style.cssText = 'width:8px;height:8px;background-color:#f8773c;border-radius:50%;margin-left:6px;';
            filterDropdown.appendChild(badge);
        }
    } else {
        filterText.textContent = 'Filter';
        if (badge) {
            badge.remove();
        }
    }
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
    searchInput.addEventListener('input', function () {
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
        link.addEventListener('click', function (e) {
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

function initializeReplyModal() {
    window.openReplyReportModal = function (reportId, title, trackingCode, description, priority) {
        const modalId = `replyReportModal${reportId}`;
        const modalElement = document.getElementById(modalId);

        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            console.error('Modal not found:', modalId);
        }
    };
}

function initializeQuickResponses() {
    document.querySelectorAll('.quick-response-report').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const response = this.dataset.response;
            const textarea = document.getElementById(targetId);
            if (textarea) {
                textarea.value = response;
                textarea.dispatchEvent(new Event('input'));
            }
        });
    });
}

function initializeCharacterCounter() {
    // Listen for dynamically added textareas
    document.addEventListener('input', function (e) {
        if (e.target && e.target.id && e.target.id.startsWith('admin_response_')) {
            const textarea = e.target;
            const count = textarea.value.length;

        }
    });
}