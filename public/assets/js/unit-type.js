
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                const url = new URL(window.location.href);

                if (this.value) {
                    url.searchParams.set('search', this.value);
                } else {
                    url.searchParams.delete('search');
                }

                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            }, 500);
        });
    }

    let status = new Set();
    const params = new URLSearchParams(location.search);

    function initState() {
        const s = params.get('status');
        if (s) s.split(',').forEach(v => v && status.add(v));
        paint();
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
        document.querySelectorAll('.filter-status')
            .forEach(b => b.addEventListener('click', function (e) {
                e.stopPropagation();
                const v = this.dataset.value;

                v === '' ? status.clear() :
                    status.has(v) ? status.delete(v) :
                        status.add(v);

                paint();
                updateFilterLabel();
            }));

        document.getElementById('applyFilter')?.addEventListener('click', applyFilter);
        document.getElementById('resetFilter')?.addEventListener('click', resetFilter);
    }

    function paint() {
        const on = 'background:linear-gradient(135deg,#f1c3ae,#f8773c);border:none;color:white;';
        const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

        document.querySelectorAll('.filter-status').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (status.size ? off : on) : (status.has(v) ? on : off);
        });
    }

    function applyFilter() {
        const url = new URL(location.href);

        status.size
            ? url.searchParams.set('status', [...status].join(','))
            : url.searchParams.delete('status');

        url.searchParams.set('page', 1);
        location.href = url;
    }

    function resetFilter() {
        const url = new URL(location.href);

        status.clear();
        url.searchParams.delete('status');
        url.searchParams.set('page', 1);

        location.href = url;
    }

    function updateFilterLabel() {
        const btn = document.getElementById('filterDropdown');
        const text = document.getElementById('filterText');

        if (!btn || !text) return;

        let badge = btn.querySelector('.badge');
        const s = params.get('status');

        if (s) {
            const statusLabels = {
                'active': 'Active',
                'inactive': 'Inactive'
            };

            const labels = s.split(',').map(status => statusLabels[status] || status).join(', ');
            text.textContent = `Filter: ${labels}`;

            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'badge bg-primary rounded-circle ms-1';
                badge.style = 'width:6px;height:6px;background: linear-gradient(135deg, #f1c3ae, #f8773c);';
                btn.appendChild(badge);
            }
        } else {
            text.textContent = 'Filter';
            badge && badge.remove();
        }
    }

    function initFilterLabel() {
        const s = params.get('status');
        const text = document.getElementById('filterText');
        const btn = document.getElementById('filterDropdown');

        if (s && text && btn) {
            const statusLabels = {
                'active': 'Active',
                'inactive': 'Inactive'
            };

            const labels = s.split(',').map(status => statusLabels[status] || status).join(', ');
            text.textContent = `Filter: ${labels}`;

            const badge = document.createElement('span');
            badge.className = 'badge bg-primary rounded-circle ms-1';
            badge.style = 'width:6px;height:6px;background: linear-gradient(135deg, #f1c3ae, #f8773c);';
            btn.appendChild(badge);
        }
    }

    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.toggle-status-form');
            const typeId = form.dataset.id;
            const isActive = this.dataset.active === 'true';

            fetch(`/admin/unit-types/${typeId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    this.dataset.active = !isActive;

                    if (!isActive) {
                        this.classList.remove('btn-outline-secondary');
                        this.classList.add('btn-success');
                        this.textContent = 'Active';
                    } else {
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-secondary');
                        this.textContent = 'Inactive';
                    }
                });
        });
    });

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const deleteModalText = document.getElementById('deleteModalText');

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;

            deleteModalText.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;

            deleteForm.action = `/admin/unit-types/${id}`;
            deleteModal.show();
        });
    });

    initState();
    initIcons();
    bindEvents();
    initFilterLabel();
});

const form = document.getElementById('unitTypeForm');

form.addEventListener('submit', function (e) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        const firstError = form.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
});

// Real-time validation for required fields
const requiredFields = form.querySelectorAll('[required]');
requiredFields.forEach(field => {
    field.addEventListener('input', function () {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
        }
    });
});


