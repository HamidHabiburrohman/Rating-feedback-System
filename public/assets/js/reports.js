document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusButtons = document.querySelectorAll('.filter-status');
    const typeButtons = document.querySelectorAll('.filter-tipe');
    const applyFilter = document.getElementById('applyFilter');
    const resetFilter = document.getElementById('resetFilter');
    const params = new URLSearchParams(location.search);
    
    let selectedStatus = new Set();
    let selectedTypes = new Set();

    function initState() {
        const s = params.get('status');
        const t = params.get('tipe');
        if (s) s.split(',').forEach(v => v && selectedStatus.add(v));
        if (t) t.split(',').forEach(v => v && selectedTypes.add(v));
        paint();
        updateLabel();
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
        document.querySelectorAll('.filter-status').forEach(b => 
            b.addEventListener('click', e => toggle(e, selectedStatus, 'status')));
        document.querySelectorAll('.filter-tipe').forEach(b => 
            b.addEventListener('click', e => toggle(e, selectedTypes, 'tipe')));
        
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

    function toggle(e, set, type) {
        e.stopPropagation();
        const v = e.currentTarget.dataset.value;
        v === '' ? set.clear() : set.has(v) ? set.delete(v) : set.add(v);
        paint();
        updateLabel();
    }

    function paint() {
        const on = 'background:linear-gradient(135deg,#f1c3ae,#f8773c);border:none;color:white;';
        const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

        document.querySelectorAll('.filter-status').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (selectedStatus.size ? off : on) : (selectedStatus.has(v) ? on : off);
        });

        document.querySelectorAll('.filter-tipe').forEach(b => {
            const v = b.dataset.value;
            b.style = v === '' ? (selectedTypes.size ? off : on) : (selectedTypes.has(v) ? on : off);
        });
    }

    function apply() {
        const url = new URL(location.href);
        
        selectedStatus.size ? url.searchParams.set('status', [...selectedStatus].join(',')) : url.searchParams.delete('status');
        selectedTypes.size ? url.searchParams.set('tipe', [...selectedTypes].join(',')) : url.searchParams.delete('tipe');
        
        url.searchParams.set('page', 1);
        location.href = url;
    }

    function reset() {
        const url = new URL(location.href);
        
        selectedStatus.clear();
        selectedTypes.clear();
        
        url.searchParams.delete('status');
        url.searchParams.delete('tipe');
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
        const t = params.get('tipe');
        
        if (s || t) {
            let v = 'Filter';
            
            if (s) {
                const statusLabels = s.split(',').map(statusLabel);
                v += ': ' + statusLabels.join(', ');
            }
            
            if (t) {
                const typeLabels = t.split(',').map(typeLabel);
                v += s ? ', ' + typeLabels.join(', ') : ': ' + typeLabels.join(', ');
            }
            
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

    function statusLabel(v) {
        const labels = {
            'baru': 'Baru',
            'diproses': 'Diproses',
            'selesai': 'Selesai',
            'ditolak': 'Ditolak'
        };
        return labels[v] || v;
    }

    function typeLabel(v) {
        const labels = {
            'masalah': 'Masalah',
            'saran': 'Saran',
            'keluhan': 'Keluhan',
            'lainnya': 'Lainnya'
        };
        return labels[v] || v;
    }

    // Initialize
    initState();
    initIcons();
    bind();

    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});