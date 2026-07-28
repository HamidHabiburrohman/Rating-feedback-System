document.addEventListener('DOMContentLoaded', function () {
    var config = window.QrCodeConfig || {};
    var modal = document.getElementById('generateQrModal');
    var unitSelectWrapper = document.getElementById('unitSelectWrapper');
    var unitSelectTrigger = document.getElementById('unitSelectTrigger');
    var unitSelectInput = document.getElementById('generateUnitSelect');
    var unitSelectText = document.getElementById('unitSelectText');
    var unitSearchInput = document.getElementById('unitSearchInput');
    var unitSelectOptions = document.getElementById('unitSelectOptions');
    var btnGenerate = document.getElementById('btnGenerateQr');
    var errorBox = document.getElementById('generateModalError');
    var spinner = document.getElementById('generateSpinner');
    var btnText = document.getElementById('generateBtnText');
    var selectedUnitId = null;
    var searchTimeout = null;

    if (unitSelectTrigger) {
        unitSelectTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (unitSelectWrapper) unitSelectWrapper.classList.toggle('open');
            if (unitSelectWrapper && unitSelectWrapper.classList.contains('open') && unitSearchInput) {
                unitSearchInput.focus();
            }
        });
    }

    document.addEventListener('click', function (e) {
        if (unitSelectWrapper && !unitSelectWrapper.contains(e.target)) {
            unitSelectWrapper.classList.remove('open');
        }
    });

    if (unitSearchInput) {
        unitSearchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            var query = this.value.trim();
            searchTimeout = setTimeout(function () {
                fetchUnits(query);
            }, 300);
        });
    }

    function fetchUnits(query) {
        if (!unitSelectOptions) return;
        var loadingState = unitSelectOptions.querySelector('.loading-state');
        var noResults = unitSelectOptions.querySelector('.no-results');
        if (loadingState) loadingState.classList.remove('d-none');
        if (noResults) noResults.classList.add('d-none');
        unitSelectOptions.querySelectorAll('.custom-select-option').forEach(function (opt) { opt.remove(); });

        fetch(config.searchUrl + '?q=' + encodeURIComponent(query || '') + '&limit=5', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (loadingState) loadingState.classList.add('d-none');
                if (data.units && data.units.length > 0) {
                    if (noResults) noResults.classList.add('d-none');
                    data.units.forEach(function (unit) {
                        var option = document.createElement('div');
                        option.className = 'custom-select-option';
                        option.dataset.value = unit.id;
                        option.innerHTML =
                            '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                            '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>' +
                            '<polyline points="9 22 9 12 15 12 15 22"/>' +
                            '</svg>' +
                            '<span class="option-text">' + unit.name + '</span>' +
                            '<span class="option-code">' + (unit.code || 'No Code') + '</span>';
                        option.addEventListener('click', function () { selectUnit(unit.id, unit.name); });
                        unitSelectOptions.appendChild(option);
                    });
                } else {
                    if (noResults) noResults.classList.remove('d-none');
                }
            })
            .catch(function () {
                if (loadingState) loadingState.classList.add('d-none');
                if (noResults) noResults.classList.remove('d-none');
            });
    }

    function selectUnit(id, name) {
        selectedUnitId = id;
        if (unitSelectInput) unitSelectInput.value = id;
        if (unitSelectText) {
            unitSelectText.textContent = name;
            unitSelectText.style.color = '#111827';
        }
        if (unitSelectWrapper) unitSelectWrapper.classList.remove('open');
        if (unitSelectOptions) {
            unitSelectOptions.querySelectorAll('.custom-select-option').forEach(function (opt) {
                opt.classList.toggle('selected', opt.dataset.value === String(id));
            });
        }
    }

    if (btnGenerate) {
        btnGenerate.addEventListener('click', function () {
            if (errorBox) errorBox.classList.add('d-none');
            if (!selectedUnitId) {
                if (errorBox) {
                    errorBox.textContent = 'Harap pilih unit terlebih dahulu.';
                    errorBox.classList.remove('d-none');
                }
                return;
            }
            btnGenerate.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = 'Generating...';

            fetch(config.generateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ unit_id: selectedUnitId })
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        if (errorBox) {
                            errorBox.textContent = data.message || 'Gagal generate QR Code.';
                            errorBox.classList.remove('d-none');
                        }
                    }
                })
                .catch(function () {
                    if (errorBox) {
                        errorBox.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                        errorBox.classList.remove('d-none');
                    }
                })
                .finally(function () {
                    btnGenerate.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                    if (btnText) btnText.textContent = 'Generate QR Code';
                });
        });
    }

    if (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            selectedUnitId = null;
            if (unitSelectInput) unitSelectInput.value = '';
            if (unitSelectText) {
                unitSelectText.textContent = 'Pilih unit...';
                unitSelectText.style.color = '#64748b';
            }
            if (unitSearchInput) unitSearchInput.value = '';
            if (errorBox) errorBox.classList.add('d-none');
            if (unitSelectOptions) {
                unitSelectOptions.querySelectorAll('.custom-select-option').forEach(function (opt) { opt.remove(); });
            }
        });
        modal.addEventListener('shown.bs.modal', function () { fetchUnits(''); });
    }

    var currentModal = null;

    function openQrModal(action, qrId, qrCode, unitName) {
        var modalId = action + 'QrModal-' + qrId;
        var backdropId = action + 'QrBackdrop-' + qrId;
        var modalEl = document.getElementById(modalId);
        var backdropEl = document.getElementById(backdropId);

        if (!modalEl || !backdropEl) return;

        var codeEl = modalEl.querySelector('[data-modal-qr-code]');
        var unitEl = modalEl.querySelector('[data-modal-unit-name]');

        if (codeEl) codeEl.textContent = qrCode;
        if (unitEl) unitEl.textContent = unitName;

        backdropEl.classList.add('show');
        modalEl.classList.add('show');
        currentModal = { modal: modalEl, backdrop: backdropEl };
        document.body.style.overflow = 'hidden';
    }

    function closeQrModal() {
        if (!currentModal) return;
        currentModal.backdrop.classList.remove('show');
        currentModal.modal.classList.remove('show');
        document.body.style.overflow = '';

        var btn = currentModal.modal.querySelector('.conv-btn.loading');
        if (btn) {
            btn.classList.remove('loading');
            btn.disabled = false;
        }
        currentModal = null;
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action]');
        if (!btn) return;

        var action = btn.dataset.action;
        var qrId = btn.dataset.qrId;
        var qrCode = btn.dataset.qrCode;
        var unitName = btn.dataset.unitName;

        if (action && qrId) {
            e.preventDefault();
            openQrModal(action, qrId, qrCode, unitName);
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-modal-close]')) {
            closeQrModal();
        }
    });

    document.addEventListener('click', function (e) {
        if (currentModal && e.target === currentModal.backdrop) {
            closeQrModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && currentModal) {
            closeQrModal();
        }
    });

    document.addEventListener('submit', function (e) {
        var form = e.target.closest('[data-modal-form]');
        if (!form || !currentModal) return;

        e.preventDefault();
        var submitBtn = form.querySelector('button[type="submit"]');

        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        var formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(function (response) {
                if (!response.ok) throw new Error('HTTP_ERROR_' + response.status);
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    showNotification(data.message || 'Action completed successfully.', 'success');
                    closeQrModal();
                    if (typeof fetchData === 'function') {
                        fetchData(window.location.href);
                    } else {
                        window.location.reload();
                    }
                } else {
                    throw new Error(data.message || 'Action failed.');
                }
            })
            .catch(function (error) {
                showNotification(error.message || 'An error occurred. Please try again.', 'error');
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            });
    });

    function showNotification(message, type) {
        if (typeof window.showAlert === 'function') {
            window.showAlert(message, type);
            return;
        }

        var toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:' + (type === 'success' ? '#22c55e' : '#ef4444') + ';color:white;padding:12px 20px;border-radius:12px;font-family:Plus Jakarta Sans,sans-serif;font-size:14px;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:9999;animation:slideIn 0.3s ease;';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(function () { toast.remove(); }, 300);
        }, 3000);
    }

    initializeSearch();
    initializeFilters();
    initializePerPage();
    initializePagination();
    initializeSort();
    initializeAlerts();
    initializeDropdowns();

    function generateSkeletonRows(count) {
        var html = '';
        for (var i = 0; i < count; i++) {
            html +=
                '<tr>' +
                '<td class="ps-4"><div class="d-flex align-items-center gap-3">' +
                '<div class="skeleton" style="width:40px;height:40px;border-radius:8px;"></div>' +
                '<div class="skeleton" style="width:120px;height:16px;"></div>' +
                '</div></td>' +
                '<td><div class="skeleton" style="width:140px;height:16px;"></div></td>' +
                '<td><div class="skeleton" style="width:80px;height:24px;border-radius:12px;"></div></td>' +
                '<td><div class="skeleton" style="width:100px;height:16px;"></div></td>' +
                '<td class="text-center pe-4"><div class="d-flex justify-content-center gap-2">' +
                '<div class="skeleton" style="width:34px;height:34px;border-radius:8px;"></div>' +
                '<div class="skeleton" style="width:34px;height:34px;border-radius:8px;"></div>' +
                '</div></td></tr>';
        }
        return html;
    }

    function generateSkeletonPagination() {
        return '<div class="d-flex justify-content-start py-3"><div class="skeleton" style="width:250px;height:40px;"></div></div>';
    }

    function fetchData(url) {
        var tableBody = document.getElementById('qrCodesTable');
        var paginationContainer = document.getElementById('qrCodesPaginationContainer');
        if (!tableBody || !paginationContainer) return;

        tableBody.innerHTML = generateSkeletonRows(10);
        paginationContainer.innerHTML = generateSkeletonPagination();

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.html !== undefined) tableBody.innerHTML = data.html;
                if (data.pagination !== undefined) paginationContainer.innerHTML = data.pagination;
                initializeTooltips();
            })
            .catch(function () {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-danger">Failed to load data.</td></tr>';
            });
    }

    function initializeTooltips() {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
            if (!bootstrap.Tooltip.getInstance(el)) new bootstrap.Tooltip(el);
        });
    }

    function initializeSearch() {
        var searchInput = document.getElementById('searchInput') || document.querySelector('input[name="search"]');
        if (!searchInput) return;
        var searchTimeoutLocal;
        var performSearch = function () {
            var url = new URL(window.location.href);
            if (searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.set('page', '1');
            window.history.pushState({}, '', url);
            fetchData(url);
        };
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeoutLocal);
                performSearch();
            }
        });
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeoutLocal);
            searchTimeoutLocal = setTimeout(performSearch, 800);
        });
    }

    function initializePerPage() {
        document.addEventListener('click', function (e) {
            var perPageLink = e.target.closest('.per-page-link');
            if (!perPageLink) return;
            e.preventDefault();
            var perPage = perPageLink.dataset.perPage;
            var url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', '1');
            window.history.pushState({}, '', url);
            var perPageText = document.getElementById('perPageText');
            if (perPageText) perPageText.textContent = perPage + ' Rows';
            fetchData(url);
        });
    }

    function initializeFilters() {
        var filterContainer = document.getElementById('filterContainer');
        if (!filterContainer) return;
        var params = new URLSearchParams(window.location.search);
        var selectedStatus = params.get('status') || '';
        var selectedUnitId = params.get('unit_id') || '';
        var activeStyle = 'background:#f8773c;border:none;color:white;';
        var inactiveStyle = 'background:white;border:1px solid #d1d5db;color:#6b7280;';
        var unitBtns = document.querySelectorAll('.unit-btn');
        var unitSearchInput = document.getElementById('unitSearchInput');

        function updateUnitVisibility() {
            if (!unitSearchInput) return;
            var searchTerm = unitSearchInput.value.toLowerCase();
            var visibleCount = 0;
            unitBtns.forEach(function (btn) {
                var isAllBtn = btn.dataset.value === '';
                var name = btn.dataset.name || '';
                var isActive = btn.classList.contains('filter-active');
                if (searchTerm !== '') {
                    btn.classList.toggle('d-none', !(name.includes(searchTerm) || isAllBtn));
                } else {
                    if (isAllBtn || isActive) {
                        btn.classList.remove('d-none');
                        if (!isAllBtn) visibleCount++;
                    } else if (visibleCount < 5) {
                        btn.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        btn.classList.add('d-none');
                    }
                }
            });
        }

        function updateUI() {
            filterContainer.querySelectorAll('.filter-status').forEach(function (btn) {
                btn.style.cssText = btn.dataset.value === selectedStatus ? activeStyle : inactiveStyle;
            });
            filterContainer.querySelectorAll('.filter-unit').forEach(function (btn) {
                var value = btn.dataset.value;
                var isActive = (value === '' && selectedUnitId === '') || selectedUnitId === value;
                btn.style.cssText = isActive ? activeStyle : inactiveStyle;
                if (isActive) btn.classList.add('filter-active');
                else btn.classList.remove('filter-active');
            });
            var filterText = document.getElementById('filterText');
            var filterBtn = document.getElementById('filterDropdown');
            if (filterBtn && filterText) {
                var existingBadge = filterBtn.querySelector('.filter-badge');
                if (existingBadge) existingBadge.remove();
                var filterCount = 0;
                if (selectedStatus) filterCount++;
                if (selectedUnitId) filterCount++;
                if (filterCount > 0) {
                    var parts = [];
                    if (selectedStatus) parts.push(selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1));
                    if (selectedUnitId) parts.push('Unit');
                    filterText.textContent = 'Filter: ' + parts.join(', ');
                    var badge = document.createElement('span');
                    badge.className = 'filter-badge';
                    badge.style.cssText = 'width:8px;height:8px;background:#f8773c;border-radius:50%;margin-left:6px;display:inline-block;';
                    filterBtn.appendChild(badge);
                } else {
                    filterText.textContent = 'Filter';
                }
            }
        }

        if (unitSearchInput) {
            unitSearchInput.addEventListener('input', function (e) {
                e.stopPropagation();
                updateUnitVisibility();
            });
            updateUnitVisibility();
        }

        filterContainer.querySelectorAll('.filter-status').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selectedStatus = this.dataset.value;
                updateUI();
            });
        });

        filterContainer.querySelectorAll('.filter-unit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var value = this.dataset.value;
                if (value === '') {
                    selectedUnitId = '';
                    unitBtns.forEach(function (b) {
                        b.style.cssText = inactiveStyle;
                        b.classList.remove('filter-active');
                    });
                    this.style.cssText = activeStyle;
                    this.classList.add('filter-active');
                } else {
                    var allBtn = document.querySelector('.filter-unit[data-value=""]');
                    if (allBtn) {
                        allBtn.style.cssText = inactiveStyle;
                        allBtn.classList.remove('filter-active');
                    }
                    selectedUnitId = value;
                    unitBtns.forEach(function (b) {
                        if (b.dataset.value === value) {
                            b.style.cssText = activeStyle;
                            b.classList.add('filter-active');
                        } else {
                            b.style.cssText = inactiveStyle;
                            b.classList.remove('filter-active');
                        }
                    });
                }
                updateUI();
                updateUnitVisibility();
            });
        });

        var applyFilterBtn = document.getElementById('applyFilter');
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var url = new URL(window.location.href);
                if (selectedStatus) url.searchParams.set('status', selectedStatus);
                else url.searchParams.delete('status');
                if (selectedUnitId) url.searchParams.set('unit_id', selectedUnitId);
                else url.searchParams.delete('unit_id');
                url.searchParams.set('page', '1');
                window.history.pushState({}, '', url);
                fetchData(url);
                var dropdownToggle = document.getElementById('filterDropdown');
                var dropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (dropdown) dropdown.hide();
            });
        }

        var resetFilterBtn = document.getElementById('resetFilter');
        if (resetFilterBtn) {
            resetFilterBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selectedStatus = '';
                selectedUnitId = '';
                updateUI();
                unitBtns.forEach(function (b) {
                    b.style.cssText = inactiveStyle;
                    b.classList.remove('filter-active');
                });
                var allUnitBtn = document.querySelector('.filter-unit[data-value=""]');
                if (allUnitBtn) {
                    allUnitBtn.style.cssText = activeStyle;
                    allUnitBtn.classList.add('filter-active');
                }
                if (unitSearchInput) {
                    unitSearchInput.value = '';
                    updateUnitVisibility();
                }
                var url = new URL(window.location.href);
                url.searchParams.delete('status');
                url.searchParams.delete('unit_id');
                url.searchParams.set('page', '1');
                window.history.pushState({}, '', url);
                fetchData(url);
            });
        }

        var dropdownMenu = filterContainer.querySelector('.dropdown-menu');
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function (e) { e.stopPropagation(); });
        }
        updateUI();
    }

    function initializePagination() {
        document.addEventListener('click', function (e) {
            var paginationLink = e.target.closest('.pagination .page-link');
            if (!paginationLink) return;
            e.preventDefault();
            var url = new URL(paginationLink.href);
            window.history.pushState({}, '', url);
            fetchData(url);
        });
    }

    function initializeSort() {
        document.addEventListener('click', function (e) {
            var sortLink = e.target.closest('#sortDropdown .dropdown-item');
            if (!sortLink) return;
            e.preventDefault();
            var url = new URL(sortLink.href);
            window.history.pushState({}, '', url);
            fetchData(url);
            var dropdown = sortLink.closest('.dropdown');
            var buttonTextSpan = dropdown.querySelector('button .fw-medium');
            if (buttonTextSpan) buttonTextSpan.textContent = 'Sort: ' + sortLink.textContent.trim();
            dropdown.querySelectorAll('.dropdown-item').forEach(function (item) { item.classList.remove('active'); });
            sortLink.classList.add('active');
            var dropdownToggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
            var bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
            if (bsDropdown) bsDropdown.hide();
        });
    }

    function initializeDropdowns() {
        document.querySelectorAll('.dropdown-toggle-btn').forEach(function (btn) {
            var icon = btn.querySelector('.dropdown-icon');
            if (!icon) return;
            btn.addEventListener('show.bs.dropdown', function () { icon.style.transform = 'rotate(180deg)'; });
            btn.addEventListener('hide.bs.dropdown', function () { icon.style.transform = 'rotate(0)'; });
        });
    }

    function initializeAlerts() {
        document.querySelectorAll('.alert').forEach(function (alert) {
            setTimeout(function () {
                if (alert.classList.contains('show')) {
                    var instance = bootstrap.Alert.getOrCreateInstance(alert);
                    if (instance) instance.close();
                }
            }, 5000);
        });
    }
});

const qrActionModal = document.getElementById('qrActionModal');
const qrActionBackdrop = document.getElementById('qrActionBackdrop');
const qrActionForm = document.getElementById('qrActionForm');
const qrActionTitle = document.getElementById('qrActionTitle');
const qrActionDesc = document.getElementById('qrActionDesc');
const qrActionCode = document.getElementById('qrActionCode');
const qrActionUnit = document.getElementById('qrActionUnit');
const qrActionIcon = document.getElementById('qrActionIcon');
const qrActionSubmitBtn = document.getElementById('qrActionSubmitBtn');

const modalConfig = {
    regenerate: {
        title: 'Regenerate QR Code',
        desc: 'This will invalidate the current QR Code and generate a new one. Any existing printed materials will no longer work.',
        icon: 'ti-refresh',
        iconClass: 'primary',
        btnClass: 'conv-btn-primary',
        btnText: 'Regenerate',
        method: 'POST' // Route menggunakan POST
    },
    activate: {
        title: 'Activate QR Code',
        desc: 'Are you sure you want to activate this QR Code? It will become available for student scanning immediately.',
        icon: 'ti-circle-check',
        iconClass: 'success',
        btnClass: 'conv-btn-success',
        btnText: 'Activate Now',
        method: 'PATCH'
    },
    deactivate: {
        title: 'Deactivate QR Code',
        desc: 'Are you sure you want to deactivate this QR Code? Students will no longer be able to scan it until reactivated.',
        icon: 'ti-circle-off',
        iconClass: 'warning',
        btnClass: 'conv-btn-danger',
        btnText: 'Deactivate',
        method: 'PATCH'
    }
};

window.openQrActionModal = function(action, qrId, code, unitName) {
    const config = modalConfig[action];
    if (!config) return;

    qrActionTitle.textContent = config.title;
    qrActionDesc.textContent = config.desc;
    qrActionCode.textContent = code;
    qrActionUnit.textContent = unitName;
    
    qrActionIcon.className = `conv-modal-icon ${config.iconClass}`;
    qrActionIcon.innerHTML = `<i class="ti ${config.icon}"></i>`;
    
    qrActionSubmitBtn.className = `conv-btn ${config.btnClass}`;
    qrActionSubmitBtn.querySelector('.conv-btn-text').textContent = config.btnText;
    
    const baseUrl = window.location.origin + '/admin/qr-codes/' + qrId;
    const actionUrl = action === 'regenerate' 
        ? baseUrl + '/regenerate' 
        : baseUrl + '/' + action;
        
    qrActionForm.action = actionUrl;
    qrActionForm.querySelector('input[name="_method"]').value = config.method;

    qrActionBackdrop.classList.add('show');
    qrActionModal.classList.add('show');
    document.body.style.overflow = 'hidden';
};

function closeQrActionModal() {
    qrActionBackdrop.classList.remove('show');
    qrActionModal.classList.remove('show');
    document.body.style.overflow = '';
    qrActionSubmitBtn.classList.remove('loading');
    qrActionSubmitBtn.disabled = false;
}

document.querySelectorAll('[data-modal-close]').forEach(btn => {
    if (qrActionModal.contains(btn)) {
        btn.addEventListener('click', closeQrActionModal);
    }
});

qrActionBackdrop.addEventListener('click', closeQrActionModal);

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && qrActionModal.classList.contains('show')) {
        closeQrActionModal();
    }
});

qrActionForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    qrActionSubmitBtn.classList.add('loading');
    qrActionSubmitBtn.disabled = true;

    const formData = new FormData(qrActionForm);
    const method = qrActionForm.querySelector('input[name="_method"]').value || 'POST';

    fetch(qrActionForm.action, {
        method: method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('HTTP_ERROR_' + response.status);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeQrActionModal();
            showNotification(data.message || 'Action completed successfully.', 'success');
            if (typeof fetchData === 'function') {
                fetchData(window.location.href);
            } else {
                window.location.reload();
            }
        } else {
            throw new Error(data.message || 'Action failed.');
        }
    })
    .catch(error => {
        console.error('Modal action failed:', error);
        showNotification(error.message || 'An error occurred. Please try again.', 'error');
        qrActionSubmitBtn.classList.remove('loading');
        qrActionSubmitBtn.disabled = false;
    });
});

function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; bottom: 24px; right: 24px;
        background: ${type === 'success' ? '#22c55e' : '#ef4444'};
        color: white; padding: 12px 20px; border-radius: 12px;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}