document.addEventListener('DOMContentLoaded', () => {
    let selectedActions = new Set();
    let selectedModels = new Set();
    const params = new URLSearchParams(location.search);

    function initState() {
        const actions = params.get('action');
        const models = params.get('model_type');
        if (actions) actions.split(',').forEach(v => v && selectedActions.add(v));
        if (models) models.split(',').forEach(v => v && selectedModels.add(v));
        paintButtons();
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

    function bindEvents() {
        document.querySelectorAll('.filter-action').forEach(b => b.addEventListener('click', e => toggle(e, selectedActions)));
        document.querySelectorAll('.filter-model').forEach(b => b.addEventListener('click', e => toggle(e, selectedModels)));
        document.getElementById('applyFilter')?.addEventListener('click', applyFilters);
        document.getElementById('resetFilter')?.addEventListener('click', resetFilters);

        const search = document.getElementById('searchInput');
        if (search) {
            let timeout;
            search.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(searchNow, 500);
            });
        }

        document.querySelectorAll('.view-changes').forEach(btn => {
            btn.addEventListener('click', async function () {
                await viewChanges(this.dataset.logId);
            });
        });

        document.getElementById('export-logs')?.addEventListener('click', exportLogs);
        document.getElementById('refresh-logs')?.addEventListener('click', refreshPage);
        document.getElementById('cleanup-logs')?.addEventListener('click', openCleanupModal);
        document.getElementById('confirmCleanup')?.addEventListener('change', toggleCleanupButton);
        document.getElementById('confirmCleanupBtn')?.addEventListener('click', confirmCleanup);

        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function (e) {
                if (!this.parentElement.classList.contains('disabled') && !this.parentElement.classList.contains('active')) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }

    function toggle(e, set) {
        e.stopPropagation();
        const v = e.currentTarget.dataset.value;
        v === '' ? set.clear() : set.has(v) ? set.delete(v) : set.add(v);
        paintButtons();
        updateFilterLabel();
    }

    function paintButtons() {
        const on = 'background:linear-gradient(135deg,#f1c3ae,#f8773c);border:none;color:white;';
        const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

        document.querySelectorAll('.filter-action').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (selectedActions.size ? off : on) : (selectedActions.has(v) ? on : off);
        });

        document.querySelectorAll('.filter-model').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (selectedModels.size ? off : on) : (selectedModels.has(v) ? on : off);
        });
    }

    function applyFilters() {
        const url = new URL(location.href);

        if (selectedActions.size) {
            url.searchParams.set('action', [...selectedActions].join(','));
        } else {
            url.searchParams.delete('action');
        }

        if (selectedModels.size) {
            url.searchParams.set('model_type', [...selectedModels].join(','));
        } else {
            url.searchParams.delete('model_type');
        }

        const dateFrom = document.getElementById('dateFrom')?.value;
        const dateTo = document.getElementById('dateTo')?.value;

        if (dateFrom) {
            url.searchParams.set('date_from', dateFrom);
        } else {
            url.searchParams.delete('date_from');
        }

        if (dateTo) {
            url.searchParams.set('date_to', dateTo);
        } else {
            url.searchParams.delete('date_to');
        }

        const search = document.getElementById('searchInput')?.value;
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        url.searchParams.set('page', '1');
        location.href = url;
    }

    function resetFilters() {
        selectedActions.clear();
        selectedModels.clear();

        const url = new URL(location.href);
        url.searchParams.delete('action');
        url.searchParams.delete('model_type');
        url.searchParams.delete('date_from');
        url.searchParams.delete('date_to');
        url.searchParams.delete('search');
        url.searchParams.set('page', '1');

        location.href = url;
    }

    function searchNow() {
        const v = document.getElementById('searchInput')?.value;
        const url = new URL(location.href);
        v ? url.searchParams.set('search', v) : url.searchParams.delete('search');
        url.searchParams.set('page', 1);
        location.href = url;
    }

    function updateFilterLabel() {
        const btn = document.getElementById('filterDropdown');
        const text = document.getElementById('filterText');
        if (!btn || !text) return;

        let badge = btn.querySelector('.badge');

        const hasActionFilter = selectedActions.size > 0;
        const hasModelFilter = selectedModels.size > 0;
        const dateFrom = params.get('date_from');
        const dateTo = params.get('date_to');
        const hasDateFilter = dateFrom || dateTo;

        const hasAnyFilter = hasActionFilter || hasModelFilter || hasDateFilter;

        text.textContent = 'Filter';

        if (hasAnyFilter) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'badge bg-primary rounded-circle ms-1';
                badge.style.cssText = 'width:6px;height:6px;padding:0;';
                btn.appendChild(badge);
            }
        } else {
            badge?.remove();
        }
    }

    async function viewChanges(logId) {
        try {
            const response = await fetch(`/admin/audit-logs/${logId}/changes`);
            const data = await response.json();

            const oldValues = document.getElementById('oldValues');
            const newValues = document.getElementById('newValues');

            if (oldValues) {
                oldValues.innerHTML = data.old_values ?
                    `<pre class="mb-0">${JSON.stringify(data.old_values, null, 2)}</pre>` :
                    '<p class="text-muted mb-0 fst-italic">No old values</p>';
            }

            if (newValues) {
                newValues.innerHTML = data.new_values ?
                    `<pre class="mb-0">${JSON.stringify(data.new_values, null, 2)}</pre>` :
                    '<p class="text-muted mb-0 fst-italic">No new values</p>';
            }

            new bootstrap.Modal(document.getElementById('changesModal')).show();
        } catch (error) {
            alert('Error loading changes');
        }
    }

    async function exportLogs() {
        const exportBtn = document.getElementById('export-logs');
        const originalText = exportBtn.innerHTML;

        exportBtn.disabled = true;
        exportBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Exporting...';

        try {
            const response = await fetch('/admin/audit-logs/export?' + params.toString());

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `audit-logs-${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } else {
                alert('Error exporting logs');
            }
        } catch (error) {
            alert('Error exporting audit logs');
        } finally {
            exportBtn.disabled = false;
            exportBtn.innerHTML = originalText;
        }
    }

    function refreshPage() {
        const refreshBtn = document.getElementById('refresh-logs');
        if (refreshBtn) {
            // Tambahkan class spinning ke button
            refreshBtn.classList.add('spinning');

            // Disable button agar tidak diklik berkali-kali
            refreshBtn.disabled = true;
            refreshBtn.style.opacity = '0.7';

            // Reload setelah animasi berjalan sebentar (600ms)
            setTimeout(() => {
                window.location.reload();
            }, 600);
        }
    }

    function openCleanupModal() {
        new bootstrap.Modal(document.getElementById('cleanupModal')).show();
    }

    function toggleCleanupButton() {
        const confirmBtn = document.getElementById('confirmCleanupBtn');
        const checkbox = document.getElementById('confirmCleanup');
        if (confirmBtn && checkbox) {
            confirmBtn.disabled = !checkbox.checked;
        }
    }

    async function confirmCleanup() {
        const daysInput = document.getElementById('cleanupDays');
        if (!daysInput) return;

        const days = daysInput.value;

        if (!days || days < 1) {
            alert('Please enter a valid number of days');
            return;
        }

        if (!confirm(`Are you sure you want to delete audit logs older than ${days} days? This action cannot be undone.`)) {
            return;
        }

        const confirmBtn = document.getElementById('confirmCleanupBtn');
        const originalText = confirmBtn.innerHTML;

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const response = await fetch('/admin/audit-logs/cleanup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ days: parseInt(days) })
            });

            const data = await response.json();

            if (data.success) {
                alert(`Successfully deleted ${data.deleted_count} audit logs`);
                window.location.reload();
            } else {
                alert(data.message || 'Error cleaning up logs');
            }
        } catch (error) {
            alert('Error cleaning up audit logs');
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
            bootstrap.Modal.getInstance(document.getElementById('cleanupModal')).hide();
        }
    }

    function initTooltips() {
        const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    function initAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert.classList.contains('show')) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    }

    function initSpinAnimation() {
        if (!document.querySelector('#spin-style')) {
            const style = document.createElement('style');
            style.id = 'spin-style';
            style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .spinning svg {
                animation: spin 0.6s linear infinite;
            }
        `;
            document.head.appendChild(style);
        }
    }

    initState();
    initIcons();
    initTooltips();
    initAlerts();
    initSpinAnimation();
    bindEvents();
    updateFilterLabel();
});

function openModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        new bootstrap.Modal(modalElement).show();
    }
}