document.addEventListener('DOMContentLoaded', function () {
    initializeSearch();
    initializeFilters();
    initializeStatusToggle();
    initializeAlerts();
    initializeDropdowns();

    // HAPUS: setTimeout initDeleteModals — sudah dihandle components.js
});

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    let searchTimeout;

    const performSearch = () => {
        const url = new URL(window.location.href);
        if (searchInput.value.trim()) {
            url.searchParams.set('search', searchInput.value.trim());
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    };

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch();
        }
    });

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 800);
    });
}

function initializeFilters() {
    const filterContainer = document.getElementById('filterContainer');
    if (!filterContainer) return;

    const params = new URLSearchParams(window.location.search);
    let selectedStatus = (params.get('status') || '').split(',').filter(s => s !== '');

    const activeStyle   = 'background:#f8773c;border:none;color:white;';
    const inactiveStyle = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

    function updateUI() {
        filterContainer.querySelectorAll('.filter-status').forEach(btn => {
            const value = btn.dataset.value;
            btn.style.cssText = (value === '' && selectedStatus.length === 0) || selectedStatus.includes(value)
                ? activeStyle
                : inactiveStyle;
        });

        const filterText = document.getElementById('filterText');
        const filterBtn  = document.getElementById('filterDropdown');

        if (filterBtn && filterText) {
            filterBtn.querySelector('.filter-badge')?.remove();

            if (selectedStatus.length > 0) {
                const labels = selectedStatus.map(s => ({ '1': 'Active', '0': 'Inactive' }[s] || s)).join(', ');
                filterText.textContent = `Filter: ${labels}`;

                const badge = document.createElement('span');
                badge.className = 'filter-badge';
                badge.style.cssText = 'width:8px;height:8px;background:#f8773c;border-radius:50%;margin-left:6px;display:inline-block;';
                filterBtn.appendChild(badge);
            } else {
                filterText.textContent = 'Filter';
            }
        }
    }

    filterContainer.querySelectorAll('.filter-status').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const value = this.dataset.value;
            if (value === '') {
                selectedStatus = [];
            } else {
                const index = selectedStatus.indexOf(value);
                index === -1 ? selectedStatus.push(value) : selectedStatus.splice(index, 1);
            }
            updateUI();
        });
    });

    document.getElementById('applyFilter')?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const url = new URL(window.location.href);
        selectedStatus.length > 0
            ? url.searchParams.set('status', selectedStatus.join(','))
            : url.searchParams.delete('status');
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    });

    document.getElementById('resetFilter')?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        selectedStatus = [];
        updateUI();
        const url = new URL(window.location.href);
        url.searchParams.delete('status');
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    });

    filterContainer.querySelector('.dropdown-menu')?.addEventListener('click', e => e.stopPropagation());
    updateUI();
}

function initializeDropdowns() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
        const icon = btn.querySelector('.dropdown-icon');
        if (!icon) return;
        btn.addEventListener('show.bs.dropdown', () => icon.style.transform = 'rotate(180deg)');
        btn.addEventListener('hide.bs.dropdown', () => icon.style.transform = 'rotate(0)');
    });
}

function initializeStatusToggle() {
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const typeId    = this.dataset.id;
            const isActive  = this.dataset.active === 'true';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            fetch(`/admin/unit-types/${typeId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (!data.success) return;

                const newActive = !isActive;
                this.dataset.active = String(newActive);
                const badge = document.getElementById(`status_badge_${typeId}`);

                if (newActive) {
                    this.classList.replace('btn-outline-secondary', 'btn-success');
                    this.textContent = 'Active';
                    if (badge) {
                        badge.className  = 'badge rounded-pill px-3 ms-2 bg-success-subtle text-success';
                        badge.textContent = 'Active';
                    }
                } else {
                    this.classList.replace('btn-success', 'btn-outline-secondary');
                    this.textContent = 'Inactive';
                    if (badge) {
                        badge.className  = 'badge rounded-pill px-3 ms-2 bg-danger-subtle text-danger';
                        badge.textContent = 'Inactive';
                    }
                }
            })
            .catch(err => console.error('Toggle status error:', err));
        });
    });
}

function initializeAlerts() {
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                bootstrap.Alert.getOrCreateInstance(alert)?.close();
            }
        }, 5000);
    });
}