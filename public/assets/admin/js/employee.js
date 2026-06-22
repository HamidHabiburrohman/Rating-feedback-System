document.addEventListener('DOMContentLoaded', function () {
    initializeSearch();
    initializeFilterDropdown();
    initializeSortDropdown();
    initializePagination();
    initializePerPageDropdown();
    initializeAlerts();
});

let selectedUnits = [];
let selectedStatus = [];
let currentSort = 'name_asc';
let currentPage = 1;
let perPage = 10;

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

function initializeFilterDropdown() {
    const params = new URLSearchParams(window.location.search);

    function initState() {
        const unitId = params.get('unit_id');
        if (unitId) {
            selectedUnits = unitId.split(',');
        }

        const status = params.get('status');
        if (status) {
            selectedStatus = status.split(',');
        }

        updateFilterStyles();
    }

    function updateFilterStyles() {
        const activeStyle = 'background: #f8773c !important; border: none !important; color: white !important;';
        const inactiveStyle = 'background: white !important; border: 1px solid #d1d5db !important; color: #6b7280 !important;';

        document.querySelectorAll('.filter-unit').forEach(btn => {
            const value = btn.dataset.value;
            if (value === '') {
                btn.style.cssText = selectedUnits.length ? inactiveStyle : activeStyle;
            } else {
                btn.style.cssText = selectedUnits.includes(value) ? activeStyle : inactiveStyle;
            }
        });

        document.querySelectorAll('.filter-status').forEach(btn => {
            const value = btn.dataset.value;
            if (value === '') {
                btn.style.cssText = selectedStatus.length ? inactiveStyle : activeStyle;
            } else {
                btn.style.cssText = selectedStatus.includes(value) ? activeStyle : inactiveStyle;
            }
        });
    }

    function bindFilterEvents() {
        const dropdownMenu = document.querySelector('#filterContainer .dropdown-menu');
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        document.querySelectorAll('.filter-unit').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const value = this.dataset.value;

                if (value === '') {
                    selectedUnits = [];
                } else {
                    const index = selectedUnits.indexOf(value);
                    if (index === -1) {
                        selectedUnits.push(value);
                    } else {
                        selectedUnits.splice(index, 1);
                    }
                }

                updateFilterStyles();
            });
        });

        document.querySelectorAll('.filter-status').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const value = this.dataset.value;

                if (value === '') {
                    selectedStatus = [];
                } else {
                    const index = selectedStatus.indexOf(value);
                    if (index === -1) {
                        selectedStatus.push(value);
                    } else {
                        selectedStatus.splice(index, 1);
                    }
                }

                updateFilterStyles();
            });
        });
    }

    function bindApplyFilter() {
        const applyBtn = document.getElementById('applyFilter');
        if (applyBtn) {
            applyBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const url = new URL(window.location.href);

                if (selectedUnits.length) {
                    url.searchParams.set('unit_id', selectedUnits.join(','));
                } else {
                    url.searchParams.delete('unit_id');
                }

                if (selectedStatus.length) {
                    url.searchParams.set('status', selectedStatus.join(','));
                } else {
                    url.searchParams.delete('status');
                }

                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            });
        }
    }

    function bindResetFilter() {
        const resetBtn = document.getElementById('resetFilter');
        if (resetBtn) {
            resetBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                window.location.href = window.location.pathname;
            });
        }
    }

    const filterDropdown = document.getElementById('filterDropdown');
    if (filterDropdown) {
        filterDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    initState();
    bindFilterEvents();
    bindApplyFilter();
    bindResetFilter();
}

function initializeSortDropdown() {
    const params = new URLSearchParams(window.location.search);
    const sortParam = params.get('sort');
    if (sortParam) {
        currentSort = sortParam;
    }

    document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
        const icon = btn.querySelector('.dropdown-icon');
        if (!icon) return;

        btn.addEventListener('show.bs.dropdown', () => {
            icon.style.transform = 'rotate(180deg)';
        });

        btn.addEventListener('hide.bs.dropdown', () => {
            icon.style.transform = 'rotate(0)';
        });

        btn.addEventListener('mouseenter', function () {
            this.style.backgroundColor = '#f9fafb';
            this.style.borderColor = '#d1d5db';
        });

        btn.addEventListener('mouseleave', function () {
            this.style.backgroundColor = 'white';
            this.style.borderColor = '#d1d5db';
        });
    });

    document.querySelectorAll('.sort-option').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const sortValue = this.dataset.sort;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortValue);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    });
}

function initializePagination() {
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function (e) {
            if (!this.parentElement.classList.contains('disabled') &&
                !this.parentElement.classList.contains('active')) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });
}

function initializePerPageDropdown() {
    const params = new URLSearchParams(window.location.search);
    const perPageParam = params.get('per_page');
    if (perPageParam) {
        perPage = parseInt(perPageParam);
    }

    document.querySelectorAll('.dropdown-item[data-per-page]').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const newPerPage = this.dataset.perPage;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', newPerPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    });
}

function initializeAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) bsAlert.close();
            }
        }, 5000);
    });
}

function initializeUnitSearch() {
    const unitSearchInput = document.getElementById('unitSearchInput');
    if (!unitSearchInput) return;

    unitSearchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const unitButtons = document.querySelectorAll('.filter-unit');

        unitButtons.forEach(btn => {
            const unitName = btn.dataset.name || btn.textContent.toLowerCase();
            if (unitName.includes(searchTerm)) {
                btn.style.display = '';
            } else {
                btn.style.display = 'none';
            }
        });
    });
}