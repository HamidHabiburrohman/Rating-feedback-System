$(function () {
    if ($("#profit").length > 0) {
        var profit = {
            series: [
                {
                    name: "Pixel",
                    data: [9, 5, 3, 7, 5, 10, 3]
                },
                {
                    name: "Ample",
                    data: [6, 3, 9, 5, 4, 6, 4]
                }
            ],
            chart: {
                fontFamily: "Poppins,sans-serif",
                type: "bar",
                height: 360,
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
            colors: ["#1e88e5", "#21c1d6"],
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

        var chart_column_basic = new ApexCharts(document.querySelector("#profit"), profit);
        chart_column_basic.render();
    }

    if ($("#grade").length > 0) {
        var grade = {
            series: [5368, 3500, 4106],
            labels: ["Direct", "Referral", "Organic"],
            chart: {
                height: 170,
                type: "donut",
                fontFamily: "Plus Jakarta Sans', sans-serif",
                foreColor: "#c6d1e9"
            },
            tooltip: {
                theme: "dark",
                fillSeriesColor: false
            },
            colors: ["#e7ecf0", "#fb977d", "#1e88e5"],
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
                        size: '80%',
                        background: "none",
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: "12px",
                                offsetY: 5
                            },
                            value: {
                                show: false,
                                color: "#98aab4"
                            }
                        }
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#grade"), grade);
        chart.render();
    }

    if ($("#earning").length > 0) {
        var earning = {
            chart: {
                type: "area",
                height: 60,
                sparkline: {
                    enabled: true
                },
                fontFamily: "Plus Jakarta Sans', sans-serif",
                foreColor: "#adb0bb"
            },
            series: [
                {
                    name: "Earnings",
                    data: [25, 66, 20, 40, 12, 58, 20]
                }
            ],
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
        
        new ApexCharts(document.querySelector("#earning"), earning).render();
    }
});