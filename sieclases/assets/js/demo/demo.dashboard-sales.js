"use strict";
// [ Cloud Computing ] start
$(function() {
    var options = {
        chart: {
            type: 'area',
            height: 175,
            sparkline: {
                enabled: true
            }
        },
        grid: {
            padding: {
                right: -25,
                left: -25
            }
        },
        colors: ["#ff5722", "#8ac542", "#537df9"],
        stroke: {
            curve: 'straight',
            width: 1,
        },
        fill: {
            opecity: 1,
            type: 'solid',
        },
        series: [{
            name: 'series1',
            data: [60, 40, 20, 45, 55, 35, 40, 60, 40, 20, 45, 55, 35, 40, 60, 40, 20, 45, 55, 35, 40]
        }, {
            name: 'series2',
            data: [85, 72, 55, 65, 75, 65, 60, 85, 72, 55, 65, 75, 65, 60, 85, 72, 55, 65, 75, 65, 60]
        }, {
            name: 'series3',
            data: [99, 80, 60, 85, 95, 75, 80, 99, 80, 60, 85, 95, 75, 80, 99, 80, 60, 85, 95, 75, 80]
        }],
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function(seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    }
    new ApexCharts(document.querySelector("#overall-chart"), options).render();
});
// [ Cloud Computing ] end
// [ coversions-chart ] start
$(function() {
    var options1 = {
        chart: {
            type: 'bar',
            height: 100,
            sparkline: {
                enabled: true
            }
        },
        colors: ["#537df9"],
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: false
                },
                columnWidth: '75%',
                endingShape: 'rounded'
            }
        },
        series: [{
            data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54, 25, 66, 41, 89, 63, 54, 25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54, 25, 66]
        }],
        xaxis: {
            crosshairs: {
                width: 1
            },
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function(seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    }
    new ApexCharts(document.querySelector("#del-reports"), options1).render();
});
// [ coversions-chart ] end
// [ view-chart ] start
$(function() {
    var options1 = {
        chart: {
            type: 'area',
            height: 40,
            sparkline: {
                enabled: true
            }
        },
        colors: ["#ff5722"],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.8,
                opacityTo: 0.4,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2,
        },
        series: [{
            name: 'series1',
            data: [0, 55, 35, 75, 50, 90, 0]
        }],
        yaxis: {
            min: 0,
            max: 100,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false,
            },
            y: {
                title: {
                    formatter: function(seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    }
    new ApexCharts(document.querySelector("#circleProgress1"), options1).render();
    var options2 = {
        chart: {
            type: 'area',
            height: 40,
            sparkline: {
                enabled: true
            }
        },
        colors: ["#8ac542"],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.8,
                opacityTo: 0.4,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2,
        },
        series: [{
            name: 'series1',
            data: [0, 50, 90, 55, 35, 75, 0]
        }],
        yaxis: {
            min: 0,
            max: 100,
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false,
            },
            y: {
                title: {
                    formatter: function(seriesName) {
                        return ''
                    }
                }
            },
            marker: {
                show: false
            }
        }
    }
    new ApexCharts(document.querySelector("#circleProgress2"), options2).render();
});
// [ view-chart ] end
// [ market-chart ] start
$(function() {
    var options = {
        chart: {
            height: 200,
            type: 'bar',
            stacked: true,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },
            sparkline: {
                enabled: true
            }
        },
        // colors: ["#537df9", "#8ac542", "#00bcd4", "#ffc200", "#ff5722"],
        colors: ["#537df9", "#8ac542", "#eef1f5"],
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: false
                },
                columnWidth: '90%',
                endingShape: 'rounded'
            }
        },
        series: [{
            name: 'Youtube',
            data: [99, 80, 60, 85, 95, 75, 80, 99, 80, 60, 85, 95, 75, 80, 99, 80, 60, 85, 95, 75, 80]
        }, {
            name: 'Facebook',
            data: [85, 72, 55, 65, 75, 65, 60, 85, 72, 55, 65, 75, 65, 60, 85, 72, 55, 65, 75, 65, 60]
        }, {
            name: 'Twitter',
            data: [60, 40, 20, 45, 55, 35, 40, 60, 40, 20, 45, 55, 35, 40, 60, 40, 20, 45, 55, 35, 40]
        }],
        xaxis: {
            type: 'datetime',
            categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT', '01/05/2011 GMT', '01/06/2011 GMT', '01/07/2011 GMT', '01/08/2011 GMT', '01/09/2011 GMT', '01/10/2011 GMT', '01/11/2011 GMT', '01/12/2011 GMT'],
        },
        legend: {
            show: false,
        },
        fill: {
            opacity: 1
        },
    }
    var chart = new ApexCharts(document.querySelector("#barChartStacked"), options);
    chart.render();
});
// [ market-chart ] end
// [ market-chart ] start
$(function() {
    var options = {
        chart: {
            height: 260,
            type: 'donut',
            sparkline: {
                enabled: true
            }
        },
        series: [44, 55, 41, 17, 15],
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            verticalAlign: 'middle',
            floating: false,
            fontSize: '14px',
            offsetX: 0,
            offsetY: 10
        },
        labels: ["Series 1", "Series 2", "Series 3", "Series 4", "Series 5"],
        colors: ["#537df9", "#8ac542", "#00bcd4", "#ffc200", "#ff5722"],
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    height: 240
                },
                legend: {
                    show: false
                },
            }
        }]
    }
    var chart = new ApexCharts(
        document.querySelector("#productCategory"),
        options
    );
    chart.render();
});
// [ market-chart ] end
