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
let itemsPerPage = 5;
let allUnits = [];
let filteredUnits = [];

$(document).ready(function () {
    const container = document.querySelector('.dashboard-container');
    if (!container) return;

    const routes = {
        overview: container.dataset.dashboardOverview,
        stats: container.dataset.dashboardStats,
        charts: container.dataset.dashboardCharts,
        auditLogs: container.dataset.auditLogs,
        recentRated: container.dataset.recentRated,
        topUnits: container.dataset.topUnits
    };

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
        series: [{ name: 'New Units', type: 'bar', data: [] }, { name: 'Cumulative', type: 'line', data: [] }],
        chart: { type: 'bar', height: 320, fontFamily: THEME.font, toolbar: { show: false } },
        colors: [THEME.primary, '#9ca3af'],
        plotOptions: { bar: { columnWidth: '60%', borderRadius: 8, borderRadiusApplication: 'end' } },
        stroke: { width: [0, 3], curve: 'smooth' },
        grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: { style: { colors: THEME.textSecondary, fontSize: '12px' } },
            axisBorder: { show: false }
        },
        yaxis: { labels: { style: { colors: THEME.textSecondary, fontSize: '12px' } } },
        dataLabels: { enabled: false },
        tooltip: { theme: 'light', shared: true, intersect: false, y: { formatter: function (val) { return Math.round(val); } } },
        legend: { show: false }
    };

    const chartUnitsEl = document.querySelector('#chart-units-monthly');
    if (chartUnitsEl) {
        charts.unitsMonthly = new ApexCharts(chartUnitsEl, unitsMonthlyOptions);
        charts.unitsMonthly.render();
    }
}

function initStudentsChart(data, categories) {
    const options = {
        series: [{ name: 'Students', data: data }],
        chart: { type: 'area', height: 320, fontFamily: THEME.font, toolbar: { show: false } },
        colors: [THEME.primary],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
        stroke: { curve: 'smooth', width: 3 },
        grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
        xaxis: {
            categories: categories,
            labels: { style: { colors: THEME.textSecondary, fontSize: '12px' } },
            axisBorder: { show: false }
        },
        yaxis: { labels: { style: { colors: THEME.textSecondary, fontSize: '12px' } } },
        dataLabels: { enabled: false },
        tooltip: { theme: 'light', shared: true, intersect: false, y: { formatter: function (val) { return val + ' students'; } } },
        markers: { size: 4, colors: [THEME.primary], strokeColors: '#fff', strokeWidth: 2 }
    };

    if (charts.students) charts.students.destroy();

    const chartStudentsEl = document.querySelector('#chart-students');
    if (chartStudentsEl) {
        charts.students = new ApexCharts(chartStudentsEl, options);
        charts.students.render();
    }
}

function loadAllData(routes) {
    fetch(routes.stats)
        .then(res => res.json())
        .then(data => { if (data.success) { updateStats(data.data); updateUnitsChart(data.data); } });

    fetch(routes.charts)
        .then(res => res.json())
        .then(data => { if (data.success) updateCharts(data.data); });

    loadTopUnits(currentFilter, routes);
    loadRecentRated(routes);
}

function updateStats(stats) {
    animateValue('#students-today', stats.students?.today || 0);
    animateValue('#ratings-today', stats.ratings?.today || 0);
    animateValue('#avg-rating', stats.avg_rating || 0);
    animateValue('#active-units', stats.active_units || 0);
}

function updateUnitsChart(stats) {
    if (!charts.unitsMonthly) return;
    const monthlyData = stats.unit_growth?.monthly || generateMockMonthlyData(stats.total_units || 0);
    const cumulativeData = calculateCumulative(monthlyData);
    charts.unitsMonthly.updateSeries([{ data: monthlyData }, { data: cumulativeData }]);
    $('#total-units-display').text(stats.total_units || 0);
}

function calculateCumulative(data) {
    let sum = 0;
    return data.map(val => { sum += val; return sum; });
}

function updateCharts(data) {
    if (data.student_login_trend) {
        const recent = data.student_login_trend.slice(-12);
        const categories = recent.map(d => d.day);
        const values = recent.map(d => d.students);
        $('#peak-students').text(Math.max(...values).toLocaleString());
        initStudentsChart(values, categories);
    }

    if (data.unit_growth && charts.unitsMonthly) {
        charts.unitsMonthly.updateOptions({ xaxis: { categories: data.unit_growth.months } });
        charts.unitsMonthly.updateSeries([
            { data: data.unit_growth.new_units },
            { data: data.unit_growth.cumulative }
        ]);
    }
}

function loadTopUnits(filter, routes) {
    const tbody = document.querySelector('#units-table-body');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><div class="loading-state"><div class="spinner-border text-primary" role="status"></div><p>Loading units...</p></div></td></tr>`;

    let url;
    switch (filter) {
        case 'popularity': url = '/admin/dashboard/top-units/popularity?limit=5'; break;
        case 'quality': url = '/admin/dashboard/top-units/quality?limit=5'; break;
        case 'attention': url = '/admin/dashboard/attention-units?limit=5'; break;
        case 'all':
        default: url = '/admin/dashboard/top-units/all?limit=5'; break;
    }

    fetch(url)
        .then(res => res.json())
        .then(response => {
            if (response && response.success && Array.isArray(response.data)) {
                allUnits = response.data;
            } else {
                allUnits = [];
            }
            filteredUnits = [...allUnits];
            currentPage = 1;
            renderTablePage();
        })
        .catch(error => {
            console.error('Error loading units:', error);
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">Failed to load units data</td></tr>`;
        });
}

function renderTablePage() {
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    renderTable(filteredUnits.slice(start, end));
    updatePagination();
}

function renderTable(units) {
    const tbody = document.querySelector('#units-table-body');
    if (!tbody) return;

    if (!units || units.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted"><p>No units found for this category.</p></td></tr>`;
        return;
    }

    tbody.innerHTML = units.map((unit) => {
        const rating = unit.avg_rating ? parseFloat(unit.avg_rating).toFixed(1) : '0.0';
        const opStatus = unit.operational_status || 'open';
        const statusLabel = opStatus.charAt(0).toUpperCase() + opStatus.slice(1);

        let statusClass = 'open';
        if (opStatus === 'closed') statusClass = 'closed';
        else if (opStatus === 'maintenance') statusClass = 'maintenance';
        else if (opStatus === 'full') statusClass = 'full';

        return `
            <tr>
                <td>
                    <div class="unit-cell">
                        <div class="unit-avatar"><i class="ti ti-building"></i></div>
                        <div class="unit-info">
                            <div class="unit-name">${unit.name || 'Unknown'}</div>
                        </div>
                    </div>
                </td>
                <td><span class="badge-category">${unit.type?.name || 'General'}</span></td>
                <td>
                    <div class="rating-cell">
                        <i class="ti ti-star-filled"></i>
                        <span class="rating-value">${rating}</span>
                    </div>
                </td>
                <td>${unit.total_ratings || 0}</td>
                <td>
                    <span class="status-badge ${statusClass}">
                        <span class="status-dot"></span> ${statusLabel}
                    </span>
                </td>
                <td class="text-center">
                    <button class="btn-action" onclick="viewUnit(${unit.id})" title="View"><i class="ti ti-eye"></i></button>
                    <button class="btn-action" onclick="editUnit(${unit.id})" title="Edit"><i class="ti ti-edit"></i></button>
                </td>
            </tr>
        `;
    }).join('');
}

function updatePagination() {
    const totalPages = Math.ceil(filteredUnits.length / itemsPerPage);
    const startItem = filteredUnits.length > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
    const endItem = Math.min(currentPage * itemsPerPage, filteredUnits.length);

    $('#showing-count').text(startItem);
    $('#total-count').text(filteredUnits.length);

    const paginationContainer = $('#pagination-container');

    if (filteredUnits.length <= itemsPerPage) {
        paginationContainer.hide();
        return;
    }

    paginationContainer.show();

    let paginationHTML = `<button class="btn-page ${currentPage === 1 ? 'disabled' : ''}" data-page="prev"><i class="ti ti-chevron-left"></i></button>`;

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            paginationHTML += `<button class="btn-page ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            paginationHTML += `<span style="padding: 0 8px; color: #9ca3af;">...</span>`;
        }
    }

    paginationHTML += `<button class="btn-page ${currentPage === totalPages ? 'disabled' : ''}" data-page="next"><i class="ti ti-chevron-right"></i></button>`;

    paginationContainer.html(paginationHTML);
}

function loadRecentRated(routes) {
    const container = document.querySelector('#recent-rated-list');
    if (!container) return;

    container.innerHTML = `<div class="loading-state"><div class="spinner-border text-primary" role="status"></div><p>Loading ratings...</p></div>`;

    fetch(routes.recentRated + '?limit=5')
        .then(res => res.json())
        .then(response => {
            if (response && response.success && Array.isArray(response.data)) {
                renderRecentRated(response.data);
            } else {
                container.innerHTML = `<div class="rated-empty">No recent ratings yet.</div>`;
            }
        })
        .catch(error => {
            console.error('Error loading recent ratings:', error);
            container.innerHTML = `<div class="rated-empty">Failed to load recent ratings.</div>`;
        });
}

function renderRecentRated(ratings) {
    const container = document.querySelector('#recent-rated-list');
    if (!container) return;

    if (!ratings || ratings.length === 0) {
        container.innerHTML = `<div class="rated-empty">No recent ratings yet.</div>`;
        return;
    }

    container.innerHTML = ratings.map(rating => {
        const initials = rating.student_name
            ? rating.student_name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
            : 'AN';

        const commentHtml = rating.comment_preview
            ? `<div class="rated-comment">"${rating.comment_preview}"</div>`
            : '';

        return `
            <div class="rated-item">
                <div class="rated-avatar">${initials}</div>
                <div class="rated-content">
                    <div class="rated-unit-name">${rating.unit_name}</div>
                    <div class="rated-student">by ${rating.student_name}</div>
                    ${commentHtml}
                </div>
                <div class="rated-meta">
                    <div class="rated-score">
                        <i class="ti ti-star-filled"></i>
                        <span>${rating.overall_score}</span>
                    </div>
                    <span class="rated-time">${rating.time_ago}</span>
                </div>
            </div>
        `;
    }).join('');
}

function animateValue(selector, end) {
    const element = document.querySelector(selector);
    if (!element) return;
    const start = parseFloat(element.textContent.replace(/,/g, '')) || 0;
    const duration = 1000;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        const current = start + (end - start) * ease;

        if (selector === '#avg-rating') {
            element.textContent = current.toFixed(1);
        } else {
            element.textContent = Math.floor(current).toLocaleString();
        }

        if (progress < 1) requestAnimationFrame(update);
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

function viewUnit(id) { window.location.href = `/admin/units/${id}`; }
function editUnit(id) { window.location.href = `/admin/units/${id}/edit`; }

window.loadDashboardData = loadAllData;
window.viewUnit = viewUnit;
window.editUnit = editUnit;