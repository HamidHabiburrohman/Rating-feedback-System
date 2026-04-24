const THEME = {
    primary: '#f8773c',
    primaryLight: '#fff5f0',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    text: '#111827',
    textSecondary: '#6b7280',
    border: '#e5e7eb',
    font: "'Plus Jakarta Sans', sans-serif"
};

let charts = {};
let currentFilter = 'all';
let currentPage = 1;
let totalItems = 0;
let itemsPerPage = 10;
let allUnits = [];
let filteredUnits = [];

$(document).ready(function () {
    const container = document.querySelector('.dashboard-container');

    const routes = {
        overview: container?.dataset.dashboardOverview,
        stats: container?.dataset.dashboardStats,
        charts: container?.dataset.dashboardCharts,
        auditLogs: container?.dataset.auditLogs,
        recentRated: container?.dataset.recentRated,
        topUnits: container?.dataset.topUnits
    };

    if (!routes.stats) {
        console.error('Routes not found!');
        return;
    }

    initCharts();
    initEventListeners(routes);
    loadAllData(routes);

    $('.time-filter').on('click', function () {
        $('.time-filter').removeClass('active');
        $(this).addClass('active');
    });
});

function initEventListeners(routes) {
    $('.filter-tab').on('click', function () {
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        currentPage = 1;

        console.log('Filter changed to:', currentFilter);
        loadTopUnits(currentFilter, routes);
    });

    $(document).on('click', '.btn-page:not(.disabled)', function () {
        const page = $(this).data('page');
        if (page === 'prev') {
            if (currentPage > 1) currentPage--;
        } else if (page === 'next') {
            const totalPages = Math.ceil(filteredUnits.length / itemsPerPage);
            if (currentPage < totalPages) currentPage++;
        } else {
            currentPage = parseInt(page);
        }
        renderTablePage();
    });
}

function initCharts() {
    const unitsMonthlyOptions = {
        series: [{
            name: 'New Units',
            data: []
        }, {
            name: 'Cumulative',
            type: 'line',
            data: []
        }],
        chart: {
            type: 'bar',
            height: 320,
            fontFamily: THEME.font,
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: [THEME.primary, '#9ca3af'],
        plotOptions: {
            bar: {
                columnWidth: '60%',
                borderRadius: 8,
                borderRadiusApplication: 'end'
            }
        },
        stroke: {
            width: [0, 3],
            curve: 'smooth'
        },
        grid: {
            show: true,
            borderColor: '#f3f4f6',
            strokeDashArray: 4,
            yaxis: { lines: { show: true } },
            xaxis: { lines: { show: false } }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
                style: {
                    colors: THEME.textSecondary,
                    fontSize: '12px'
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            show: true,
            labels: {
                style: {
                    colors: THEME.textSecondary,
                    fontSize: '12px'
                },
                formatter: function (val) {
                    return Math.round(val);
                }
            }
        },
        dataLabels: { enabled: false },
        tooltip: {
            theme: 'light',
            shared: true,
            intersect: false,
            y: {
                formatter: function (val, { seriesIndex }) {
                    return val + (seriesIndex === 0 ? ' new units' : ' total units');
                }
            }
        },
        legend: { show: false }
    };

    charts.unitsMonthly = new ApexCharts(
        document.querySelector('#chart-units-monthly'),
        unitsMonthlyOptions
    );
    charts.unitsMonthly.render();
}

function initStudentsChart(data, categories) {
    const options = {
        series: [{
            name: 'Students',
            data: data
        }],
        chart: {
            type: 'area',
            height: 320,
            fontFamily: THEME.font,
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: [THEME.primary],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        grid: {
            show: true,
            borderColor: '#f3f4f6',
            strokeDashArray: 4,
            yaxis: { lines: { show: true } },
            xaxis: { lines: { show: false } }
        },
        xaxis: {
            categories: categories,
            labels: {
                style: {
                    colors: THEME.textSecondary,
                    fontSize: '12px'
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            show: true,
            labels: {
                style: {
                    colors: THEME.textSecondary,
                    fontSize: '12px'
                }
            }
        },
        dataLabels: { enabled: false },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function (val) {
                    return val + ' students';
                }
            }
        },
        markers: {
            size: 4,
            colors: [THEME.primary],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: { size: 6 }
        }
    };

    if (charts.students) {
        charts.students.destroy();
    }

    charts.students = new ApexCharts(document.querySelector('#chart-students'), options);
    charts.students.render();
}

function loadAllData(routes) {
    showLoadingStates();

    fetch(routes.stats)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateStats(data.data);
                updateUnitsChart(data.data);
                checkAlerts(data.data);
            }
        })
        .catch(error => console.error('Error loading stats:', error));

    fetch(routes.charts)
        .then(res => res.json())
        .then(data => {
            if (data.success) updateCharts(data.data);
        })
        .catch(error => console.error('Error loading charts:', error));

    loadTopUnits(currentFilter, routes);
    loadAuditLogs(routes);
    loadRecentRated(routes);
}

function showLoadingStates() {
    $('#units-table-body').html(`
        <tr>
            <td colspan="6" class="text-center py-5">
                <div class="loading-state">
                    <div class="spinner-border text-orange" role="status"></div>
                    <p>Loading data...</p>
                </div>
            </td>
        </tr>
    `);
}

function updateStats(stats) {
    animateValue('#students-today', stats.students?.today || 0);
    animateValue('#ratings-today', stats.ratings?.today || 0);
    animateValue('#students-week', stats.students?.this_week || 0);
    animateValue('#active-units', stats.units?.active || 0);

    updateTrendIndicator('.kpi-card:first-child .kpi-trend', stats.students?.trend?.daily);
    updateTrendIndicator('.kpi-card:nth-child(2) .kpi-trend', stats.ratings?.trend);
    updateTrendIndicator('.kpi-card:nth-child(3) .kpi-trend', stats.students?.trend?.weekly);
    updateTrendIndicator('.kpi-card:last-child .kpi-trend', stats.units?.trend);
}

function updateTrendIndicator(selector, trendValue) {
    const element = $(selector);
    if (!element.length) return;

    const isPositive = trendValue >= 0;
    element.removeClass('up down').addClass(isPositive ? 'up' : 'down');
    element.html(`
        <i class="ti ti-${isPositive ? 'trending-up' : 'trending-down'}"></i> 
        ${isPositive ? '+' : ''}${trendValue}%
    `);
}

function checkAlerts(data) {
    fetch('/admin/dashboard/attention-units')
        .then(res => res.json())
        .then(response => {
            if (response.success && response.data.length > 0) {
                $('#alert-banner').show();
                $('#alert-banner .alert-content').html(`
                    <strong>Attention Needed:</strong> ${response.data.length} unit(s) have rating below 2.5.
                    <a href="#" class="alert-link" onclick="filterLowRated()">Review now →</a>
                `);
            }
        });
}

function updateUnitsChart(stats) {
    const monthlyData = stats.unit_growth?.monthly || generateMockMonthlyData(stats.units?.total || 0);
    const cumulativeData = calculateCumulative(monthlyData);

    charts.unitsMonthly.updateSeries([
        { data: monthlyData },
        { data: cumulativeData }
    ]);
}

function calculateCumulative(data) {
    let sum = 0;
    return data.map(val => {
        sum += val;
        return sum;
    });
}

function updateCharts(data) {
    if (data.student_login_trend) {
        const recent = data.student_login_trend.slice(-12);
        const categories = recent.map(d => d.day);
        const values = recent.map(d => d.students);

        $('#peak-students').text(Math.max(...values).toLocaleString());
        initStudentsChart(values, categories);
    }

    if (data.unit_growth) {
        charts.unitsMonthly.updateOptions({
            xaxis: { categories: data.unit_growth.months }
        });
        charts.unitsMonthly.updateSeries([
            { data: data.unit_growth.new_units },
            { data: data.unit_growth.cumulative }
        ]);
    }
}

function loadTopUnits(filter, routes) {
    const tbody = document.querySelector('#units-table-body');
    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center py-5">
                <div class="loading-state">
                    <div class="spinner-border text-orange" role="status"></div>
                    <p>Loading units...</p>
                </div>
            </td>
        </tr>
    `;

    let url;
    switch (filter) {
        case 'popularity':
            url = '/admin/dashboard/top-units/popularity';
            break;
        case 'quality':
            url = '/admin/dashboard/top-units/quality';
            break;
        case 'attention':
            url = '/admin/dashboard/attention-units';
            break;
        case 'all':
        default:
            url = '/admin/dashboard/top-units/all';
            break;
    }

    fetch(url)
        .then(res => res.json())
        .then(response => {
            console.log('Response for filter', filter, ':', response);

            if (response && response.success && Array.isArray(response.data)) {
                allUnits = response.data;
            } else if (Array.isArray(response)) {
                allUnits = response;
            } else if (response && response.data && Array.isArray(response.data)) {
                allUnits = response.data;
            } else {
                allUnits = [];
                console.warn('Unexpected response format:', response);
            }

            filteredUnits = [...allUnits];
            currentPage = 1;
            renderTablePage();

            if (allUnits.length === 0 && filter === 'attention') {
                console.log('No units need attention at this time');
            }
        })
        .catch(error => {
            console.error('Error loading units:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-danger">
                        <i class="ti ti-alert-circle" style="font-size: 48px; opacity: 0.5; display: block; margin-bottom: 16px;"></i>
                        <p>Failed to load units data</p>
                        <button onclick="loadTopUnits('${filter}', routes)" class="btn btn-sm btn-outline-orange mt-2">
                            <i class="ti ti-refresh"></i> Retry
                        </button>
                    </td>
                </tr>
            `;
        });
}

function renderTablePage() {
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageUnits = filteredUnits.slice(start, end);

    renderTable(pageUnits);
    updatePagination();
}

function renderTable(units) {
    const tbody = document.querySelector('#units-table-body');
    if (!tbody) return;

    if (!units || units.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <div style="padding: 40px;">
                        <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                        <p>No units found</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = units.map((unit) => {
        const rating = unit.rata_rata_rating ? parseFloat(unit.rata_rata_rating).toFixed(1) : '0.0';
        const status = unit.status || (unit.status_aktif ? 'Aktif' : 'Tidak Aktif');
        const statusClass = status === 'Aktif' ? 'active' : 'inactive';

        return `
            <tr data-unit-id="${unit.id}">
                <td>
                    <div class="unit-cell">
                        <div class="unit-avatar">
                            <i class="ti ti-building"></i>
                        </div>
                        <div class="unit-info">
                            <div class="unit-name">${unit.name || 'Unknown Unit'}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge-category">${unit.type_name || 'General'}</span>
                </td>
                <td>
                    <div class="rating-cell">
                        <i class="ti ti-star-filled"></i>
                        <span class="rating-value">${rating}</span>
                    </div>
                </td>
                <td>${unit.total_rating || 0}</td>
                <td>
                    <span class="status-badge ${statusClass}">
                        <span class="status-dot"></span>
                        ${status}
                    </span>
                </td>
                <td class="text-center">
                    <button class="btn-action" onclick="viewUnit(${unit.id})" title="View">
                        <i class="ti ti-eye"></i>
                    </button>
                    <button class="btn-action" onclick="editUnit(${unit.id})" title="Edit">
                        <i class="ti ti-edit"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function updatePagination() {
    const totalPages = Math.ceil(filteredUnits.length / itemsPerPage);
    const showingCount = Math.min(filteredUnits.length, itemsPerPage);

    $('#showing-count').text(showingCount);
    $('#total-count').text(filteredUnits.length);

    const paginationContainer = $('#pagination-container');

    if (filteredUnits.length <= itemsPerPage) {
        paginationContainer.hide();
        return;
    }

    paginationContainer.show();

    let paginationHTML = `
        <button class="btn-page ${currentPage === 1 ? 'disabled' : ''}" data-page="prev">
            <i class="ti ti-chevron-left"></i>
        </button>
    `;

    for (let i = 1; i <= totalPages; i++) {
        if (
            i === 1 ||
            i === totalPages ||
            (i >= currentPage - 1 && i <= currentPage + 1)
        ) {
            paginationHTML += `
                <button class="btn-page ${i === currentPage ? 'active' : ''}" data-page="${i}">
                    ${i}
                </button>
            `;
        } else if (
            i === currentPage - 2 ||
            i === currentPage + 2
        ) {
            paginationHTML += `<span class="pagination-dots">...</span>`;
        }
    }

    paginationHTML += `
        <button class="btn-page ${currentPage === totalPages ? 'disabled' : ''}" data-page="next">
            <i class="ti ti-chevron-right"></i>
        </button>
    `;

    paginationContainer.html(paginationHTML);
}

function loadAuditLogs(routes) {
    fetch(routes.auditLogs)
        .then(res => res.json())
        .then(data => {
            if (data.success) renderAuditLogs(data.data);
        })
        .catch(error => console.error('Error loading audit logs:', error));
}

function renderAuditLogs(logs) {
    const container = document.querySelector('#audit-log-list');
    if (!container) return;

    if (logs.length === 0) {
        container.innerHTML = '<div class="activity-item"><div class="activity-details"><span class="activity-text">No recent activity</span></div></div>';
        return;
    }

    const actionClasses = {
        'censor_comment': 'create',
        'uncensor_comment': 'update',
        'assign_report': 'update',
        'respond_report': 'update',
        'resolve_report': 'create',
        'reject_report': 'delete',
        'edit_unit': 'update',
        'toggle_status': 'update',
        'delete_unit': 'delete'
    };

    container.innerHTML = logs.slice(0, 5).map(log => {
        const initials = log.user_name
            ? log.user_name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
            : 'A';
        const actionClass = actionClasses[log.action] || 'update';

        return `
            <div class="activity-item">
                <div class="activity-avatar">${initials}</div>
                <div class="activity-details">
                    <span class="activity-text">${log.description || 'No description'}</span>
                    <span class="activity-meta">${log.time_ago || 'Just now'}</span>
                </div>
                <span class="activity-action ${actionClass}">${log.action?.replace('_', ' ') || 'update'}</span>
            </div>
        `;
    }).join('');
}

function loadRecentRated(routes) {
    fetch(routes.recentRated)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderRecentRated(data.data);
                $('#new-ratings-count').text(data.data.length || 0);
            }
        })
        .catch(error => console.error('Error loading recent ratings:', error));
}

function renderRecentRated(ratings) {
    const container = document.querySelector('#recent-rated-list');
    if (!container) return;

    if (ratings.length === 0) {
        container.innerHTML = '<div class="rated-item"><div class="rated-details"><span class="rated-name">No recent ratings</span></div></div>';
        return;
    }

    const typeIcons = {
        'Laboratorium': 'ti-flask',
        'Perpustakaan': 'ti-book-open',
        'Klinik': 'ti-heart-pulse',
        'Ruang Kelas': 'ti-presentation',
        'Auditorium': 'ti-theater',
        'Cafetaria': 'ti-coffee',
        'Sports Center': 'ti-dumbbell'
    };

    const typeColors = {
        'Laboratorium': { bg: '#fff5f0', color: '#f8773c' },
        'Perpustakaan': { bg: '#d1fae5', color: '#10b981' },
        'Klinik': { bg: '#fee2e2', color: '#ef4444' },
        'Ruang Kelas': { bg: '#dbeafe', color: '#3b82f6' },
        'Auditorium': { bg: '#f3e8ff', color: '#9333ea' },
        'Cafetaria': { bg: '#fef3c7', color: '#f59e0b' },
        'Sports Center': { bg: '#fce7f3', color: '#ec4899' }
    };

    container.innerHTML = ratings.slice(0, 5).map(rating => {
        const iconClass = typeIcons[rating.unit_type] || 'ti-building';
        const colors = typeColors[rating.unit_type] || { bg: '#fff5f0', color: '#f8773c' };

        return `
            <div class="rated-item">
                <div class="rated-icon" style="background: ${colors.bg}; color: ${colors.color};">
                    <i class="ti ${iconClass}"></i>
                </div>
                <div class="rated-details">
                    <span class="rated-name">${rating.unit_name || 'Unknown Unit'}</span>
                    <span class="rated-date">${rating.date_formatted || 'Just now'}</span>
                </div>
                <div class="rated-rating">
                    <i class="ti ti-star-filled"></i>
                    <span>${rating.rating || '0'}</span>
                </div>
            </div>
        `;
    }).join('');
}

function animateValue(selector, end) {
    const element = document.querySelector(selector);
    if (!element) return;

    const start = parseInt(element.textContent.replace(/,/g, '')) || 0;
    const duration = 1000;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(start + (end - start) * ease);

        element.textContent = current.toLocaleString();

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

function generateMockMonthlyData(total) {
    const data = [];
    let remaining = total;

    for (let i = 0; i < 11; i++) {
        const random = Math.floor(Math.random() * (remaining / 2));
        data.push(random);
        remaining -= random;
    }
    data.push(remaining);

    return data;
}

function viewUnit(id) {
    window.location.href = `/admin/units/${id}`;
}

function editUnit(id) {
    window.location.href = `/admin/units/${id}/edit`;
}

function filterLowRated() {
    $('.filter-tab[data-filter="attention"]').click();
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

window.loadDashboardData = loadAllData;
window.viewUnit = viewUnit;
window.editUnit = editUnit;
window.filterLowRated = filterLowRated;