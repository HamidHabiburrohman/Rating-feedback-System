document.addEventListener('DOMContentLoaded', () => {
    let status = new Set();
    let type = new Set();
    const params = new URLSearchParams(location.search);

    function initState() {
        const s = params.get('status');
        const t = params.get('type');
        if (s) s.split(',').forEach(v => v && status.add(v));
        if (t) t.split(',').forEach(v => v && type.add(v));
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

    function bind() {
        document.querySelectorAll('.filter-status').forEach(b => b.addEventListener('click', e => toggle(e, status)));
        document.querySelectorAll('.filter-type').forEach(b => b.addEventListener('click', e => toggle(e, type)));
        document.getElementById('applyFilter')?.addEventListener('click', apply);
        document.getElementById('resetFilter')?.addEventListener('click', reset);

        const search = document.getElementById('searchInput');
        if (search) {
            let t;
            search.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(searchNow, 500);
            });
        }
    }

    function toggle(e, set) {
        e.stopPropagation();
        const v = e.currentTarget.dataset.value;
        v === '' ? set.clear() : set.has(v) ? set.delete(v) : set.add(v);
        paint();
    }

    function paint() {
        const on = 'background:linear-gradient(135deg,#f1c3ae,#f8773c);border:none;color:white;';
        const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

        document.querySelectorAll('.filter-status').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (status.size ? off : on) : (status.has(v) ? on : off);
        });

        document.querySelectorAll('.filter-type').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (type.size ? off : on) : (type.has(v) ? on : off);
        });
    }

    function apply() {
        const url = new URL(location.href);
        status.size ? url.searchParams.set('status', [...status].join(',')) : url.searchParams.delete('status');
        type.size ? url.searchParams.set('type', [...type].join(',')) : url.searchParams.delete('type');
        url.searchParams.set('page', 1);
        location.href = url;
    }

    function reset() {
        const url = new URL(location.href);
        status.clear();
        type.clear();
        url.searchParams.delete('status');
        url.searchParams.delete('type');
        url.searchParams.set('page', 1);
        location.href = url;
    }

    function searchNow() {
        const v = document.getElementById('searchInput').value;
        const url = new URL(location.href);
        v ? url.searchParams.set('search', v) : url.searchParams.delete('search');
        url.searchParams.set('page', 1);
        location.href = url;
    }

    function updateLabel() {
        const btn = document.getElementById('filterDropdown');
        const text = document.getElementById('filterText');
        if (!btn || !text) return;
        let badge = btn.querySelector('.badge');
        const s = params.get('status');
        const t = params.get('type');
        if (s || t) {
            let v = 'Filter';
            if (s) v += ': ' + s.split(',').map(label).join(', ');
            if (t) v += s ? ', ' + t : ': ' + t;
            text.textContent = v;
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'badge bg-primary rounded-circle ms-1';
                badge.style = 'width:6px;height:6px;';
                btn.appendChild(badge);
            }
        } else {
            text.textContent = 'Filter';
            badge && badge.remove();
        }
    }

    function label(v) {
        return { OPEN: 'Open', CLOSED: 'Closed', FULL: 'Full' }[v] || v;
    }

    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.parentElement.classList.contains('disabled') && !this.parentElement.classList.contains('active')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    initState();
    initIcons();
    bind();
    updateLabel();
});