// public/assets/custom/js/dashboard.js

window.profitChart = null;
window.gradeChart = null;
window.earningChart = null;

$(document).ready(function () {
    const overviewRoute = document.body.dataset.dashboardOverview;
    const statsRoute = document.body.dataset.dashboardStats;
    const chartsRoute = document.body.dataset.dashboardCharts;

    initStaticCharts();
    loadTopUnits(overviewRoute);
    loadDashboardStats(statsRoute);
    loadChartsData(chartsRoute);

    function loadChartsData(route) {
        fetch(route)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateChartsWithRealData(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading charts:', error);
            });
    }

    function updateChartsWithRealData(chartData) {
        if (chartData.visitation_trend && document.getElementById("profit")) {
            updateVisitorChart(chartData.visitation_trend);
        }

        if (chartData.rating_distribution && document.getElementById("grade")) {
            updateRatingChart(chartData.rating_distribution);
        }
    }

    function updateVisitorChart(visitationData) {
        if (!visitationData || visitationData.length === 0) return;

        const recentData = visitationData.slice(-10);
        const categories = recentData.map(item => item.day || item.month);
        const visitorData = recentData.map(item => item.visitors);

        const profitOptions = {
            series: [{
                name: "Visitors",
                data: visitorData
            }],
            chart: {
                fontFamily: "Poppins,sans-serif",
                type: "bar",
                height: 350,
                offsetY: 10,
                toolbar: {
                    show: false
                }
            },
            grid: {
                show: true,
                strokeDashArray: 3,
                borderColor: "rgba(0,0,0,.1)"
            },
            colors: ["#1e88e5"],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "30%",
                    endingShape: "flat"
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 5,
                colors: ["transparent"]
            },
            xaxis: {
                type: "category",
                categories: categories,
                axisTicks: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
                labels: {
                    style: {
                        colors: "#a1aab2"
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: "#a1aab2"
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                theme: "dark",
                y: {
                    formatter: function (val) {
                        return val + " visitors";
                    }
                }
            },
            legend: {
                show: false
            }
        };

        if (window.profitChart) {
            try {
                window.profitChart.destroy();
            } catch (e) { }
        }

        setTimeout(() => {
            const profitElement = document.getElementById("profit");
            if (profitElement) {
                try {
                    window.profitChart = new ApexCharts(profitElement, profitOptions);
                    window.profitChart.render();
                } catch (e) {
                    console.error('Error rendering visitor chart:', e);
                }
            }
        }, 100);

        setupChartDropdown(visitationData);
    }

    function setupChartDropdown(visitationData) {
        const dropdownItems = document.querySelectorAll('#dropdownMenuButton1 + .dropdown-menu .dropdown-item');

        if (dropdownItems.length > 2) {
            dropdownItems[0].addEventListener('click', function (e) {
                e.preventDefault();
                const last30Days = visitationData.slice(-30);
                updateVisitorChart(last30Days);
            });

            dropdownItems[1].addEventListener('click', function (e) {
                e.preventDefault();
                const last90Days = visitationData.slice(-90);
                updateVisitorChart(last90Days);
            });

            dropdownItems[2].addEventListener('click', function (e) {
                e.preventDefault();
                const chartsRoute = document.body.dataset.dashboardCharts;
                fetch(chartsRoute + '?type=monthly')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data.visitation_trend) {
                            updateVisitorChart(data.data.visitation_trend);
                        }
                    });
            });
        }
    }

    function updateRatingChart(ratingData) {
        if (!ratingData || !ratingData.data || ratingData.data.length === 0) return;

        const gradeOptions = {
            series: ratingData.data,
            labels: ratingData.labels,
            chart: {
                height: 200,
                type: "donut",
                fontFamily: "'Plus Jakarta Sans', sans-serif",
                foreColor: "#c6d1e9"
            },
            tooltip: {
                theme: "dark",
                fillSeriesColor: false,
                y: {
                    formatter: function (val) {
                        return val + " ratings";
                    }
                }
            },
            colors: ["#FF5D5D", "#FF9F43", "#FFC107", "#4CAF50", "#2196F3"],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            stroke: {
                show: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        background: "none",
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: "12px",
                                offsetY: 5,
                                color: "#a1aab2"
                            },
                            value: {
                                show: true,
                                fontSize: "20px",
                                color: "#333",
                                formatter: function (val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#666',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            }
        };

        if (window.gradeChart) {
            try {
                window.gradeChart.destroy();
            } catch (e) { }
        }

        setTimeout(() => {
            const gradeElement = document.getElementById("grade");
            if (gradeElement) {
                try {
                    window.gradeChart = new ApexCharts(gradeElement, gradeOptions);
                    window.gradeChart.render();
                } catch (e) {
                    console.error('Error rendering rating chart:', e);
                }
            }
        }, 100);
    }

    function initStaticCharts() {
        setTimeout(() => {
            const profitElement = document.getElementById("profit");
            if (profitElement && !window.profitChart) {
                const staticProfit = {
                    series: [{
                        name: "Visitors",
                        data: [9, 5, 3, 7, 5, 10, 3]
                    }],
                    chart: {
                        fontFamily: "Poppins,sans-serif",
                        type: "bar",
                        height: 350,
                        offsetY: 10,
                        toolbar: {
                            show: false
                        }
                    },
                    grid: {
                        show: true,
                        strokeDashArray: 3,
                        borderColor: "rgba(0,0,0,.1)"
                    },
                    colors: ["#1e88e5"],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "30%",
                            endingShape: "flat"
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 5,
                        colors: ["transparent"]
                    },
                    xaxis: {
                        type: "category",
                        categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                        axisTicks: {
                            show: false
                        },
                        axisBorder: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: "#a1aab2"
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: "#a1aab2"
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        theme: "dark"
                    },
                    legend: {
                        show: false
                    }
                };

                try {
                    window.profitChart = new ApexCharts(profitElement, staticProfit);
                    window.profitChart.render();
                } catch (e) { }
            }

            const gradeElement = document.getElementById("grade");
            if (gradeElement && !window.gradeChart) {
                const staticGrade = {
                    series: [15, 25, 35, 20, 5],
                    labels: ["1 Star", "2 Stars", "3 Stars", "4 Stars", "5 Stars"],
                    chart: {
                        height: 200,
                        type: "donut",
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        foreColor: "#c6d1e9"
                    },
                    tooltip: {
                        theme: "dark",
                        fillSeriesColor: false
                    },
                    colors: ["#FF5D5D", "#FF9F43", "#FFC107", "#4CAF50", "#2196F3"],
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    },
                    stroke: {
                        show: false
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                background: "none",
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: "12px",
                                        offsetY: 5,
                                        color: "#a1aab2"
                                    },
                                    value: {
                                        show: true,
                                        fontSize: "20px",
                                        color: "#333"
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        color: '#666',
                                        formatter: function (w) {
                                            return '100';
                                        }
                                    }
                                }
                            }
                        }
                    }
                };

                try {
                    window.gradeChart = new ApexCharts(gradeElement, staticGrade);
                    window.gradeChart.render();
                } catch (e) { }
            }

            const earningElement = document.getElementById("earning");
            if (earningElement && !window.earningChart) {
                const staticEarning = {
                    chart: {
                        type: "area",
                        height: 60,
                        sparkline: {
                            enabled: true
                        },
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        foreColor: "#adb0bb"
                    },
                    series: [{
                        name: "Earnings",
                        data: [25, 66, 20, 40, 12, 58, 20]
                    }],
                    colors: ["#8763da"],
                    stroke: {
                        curve: "smooth",
                        width: 2
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.1,
                            stops: [0, 90, 100]
                        }
                    },
                    tooltip: {
                        theme: "dark",
                        x: {
                            show: false
                        }
                    }
                };

                try {
                    window.earningChart = new ApexCharts(earningElement, staticEarning);
                    window.earningChart.render();
                } catch (e) { }
            }
        }, 500);
    }

    function loadTopUnits(route) {
        const tableBody = document.querySelector('.table tbody');

        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading top units...</p>
                    </td>
                </tr>
            `;
        }

        fetch(route)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.top_rated_units) {
                    renderTopUnits(data.data.top_rated_units);
                } else {
                    showError('No data available. ' + (data.message || ''));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showError('Network error: ' + error.message);
            });
    }

    function loadDashboardStats(route) {
        fetch(route)
            .then(response => {
                if (!response.ok) {
                    return {
                        success: true,
                        data: {
                            pengunjung: { hari_ini: 0, minggu_ini: 0 },
                            rating: { hari_ini: 0, rata_rata: 0 },
                            laporan: { baru: 0 },
                            unit: { aktif: 0 }
                        }
                    };
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateStats(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading stats:', error);
                updateStats({
                    pengunjung: { hari_ini: 0, minggu_ini: 0 },
                    rating: { hari_ini: 0, rata_rata: 0 },
                    laporan: { baru: 0 },
                    unit: { aktif: 0 }
                });
            });
    }

    function renderTopUnits(units) {
        const tableBody = document.querySelector('.table tbody');

        if (!tableBody) return;

        if (!units || units.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="text-muted">
                            <i class="ti ti-info-circle fs-5"></i>
                            <p class="mt-2">No rating data available yet</p>
                            <small class="text-muted">Start collecting ratings to see top units here</small>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = '';

        units.forEach(unit => {
            const row = document.createElement('tr');

            let fotoHtml;
            if (unit.foto_unit && !unit.foto_unit.includes('placeholder.jpg')) {
                fotoHtml = `<img src="${unit.foto_unit}" width="50" height="50" class="rounded-circle object-fit-cover" alt="${unit.nama_unit}" />`;
            } else {
                fotoHtml = `
                    <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="ti ti-building fs-4 text-primary"></i>
                    </div>
                `;
            }

            const ratingDisplay = unit.rata_rata_rating > 0 ?
                `${unit.rata_rata_rating}/5.0` : 'No ratings';

            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            ${fotoHtml}
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bolder">${unit.nama_unit}</h6>
                            <p class="fs-3 mb-0 text-muted">${unit.total_rating} Ratings</p>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        ${unit.rata_rata_rating > 0 ? `
                            <span class="me-2 fs-3 text-warning">
                                <i class="ti ti-star-filled"></i>
                            </span>
                            <p class="fs-3 fw-normal mb-0">${ratingDisplay}</p>
                        ` : `
                            <span class="me-2 fs-3 text-muted">
                                <i class="ti ti-star"></i>
                            </span>
                            <p class="fs-3 fw-normal mb-0 text-muted">${ratingDisplay}</p>
                        `}
                    </div>
                </td>
                <td>
                    <p class="fs-3 fw-normal mb-0 ${unit.total_rating > 0 ? 'text-primary fw-bold' : 'text-muted'}">
                        ${unit.total_rating}
                    </p>
                </td>
                <td>
                    <span class="badge ${unit.badge_class} rounded-pill px-3 py-2 fs-3">
                        ${unit.status}
                    </span>
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    function updateStats(stats) {
        if (stats.pengunjung) {
            const todayVisitors = document.getElementById('visitors-today');
            if (todayVisitors) todayVisitors.textContent = stats.pengunjung.hari_ini || 0;

            const weekVisitors = document.getElementById('visitors-week');
            if (weekVisitors) weekVisitors.textContent = stats.pengunjung.minggu_ini || 0;
        }

        if (stats.rating) {
            const todayRatings = document.getElementById('ratings-today');
            if (todayRatings) todayRatings.textContent = stats.rating.hari_ini || 0;

            const avgRating = document.getElementById('avg-rating');
            if (avgRating) avgRating.textContent = stats.rating.rata_rata ? stats.rating.rata_rata.toFixed(1) : '0.0';
        }

        if (stats.laporan) {
            const pendingReports = document.getElementById('pending-reports');
            if (pendingReports) pendingReports.textContent = stats.laporan.baru || 0;
        }

        if (stats.unit) {
            const activeUnits = document.getElementById('active-units');
            if (activeUnits) activeUnits.textContent = stats.unit.aktif || 0;
        }
    }

    function showError(message) {
        const tableBody = document.querySelector('.table tbody');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-5 text-danger">
                        <i class="ti ti-alert-triangle fs-5"></i>
                        <p class="mt-2">${message}</p>
                        <button onclick="location.reload()" class="btn btn-sm btn-primary mt-2">
                            <i class="ti ti-refresh me-1"></i> Retry
                        </button>
                    </td>
                </tr>
            `;
        }
    }

    window.loadFilteredUnits = function (type) {
        const tableBody = document.querySelector('.table tbody');

        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading ${type} units...</p>
                    </td>
                </tr>
            `;
        }

        fetch(`/admin/dashboard/top-units/${type}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    renderTopUnits(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading filtered units:', error);
            });
    };
});