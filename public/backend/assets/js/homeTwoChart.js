// ================================ Shared sparkline line config ================================
function makeLineChart(selector, data, color) {
  return new ApexCharts(document.querySelector(selector), {
    series: [{ data: data }],
    chart: {
      type: "line",
      height: 60,
      width: 120,
      sparkline: { enabled: true },
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    stroke: {
      curve: "straight",
      width: 2.5,
      colors: [color],
    },
    fill: { opacity: 1 },
    markers: { size: 0 },
    colors: [color],
    dataLabels: { enabled: false },
    tooltip: { enabled: true },
    grid: { show: false },
  });
}

// Total Visitors — purple uptrend line
var totalVisitorsChart = makeLineChart(
  "#totalVisitorsChart",
  [18, 22, 19, 24, 21, 27, 25, 30, 28, 34],
  "#6366f1",
);
totalVisitorsChart.render();

// Page Views — green uptrend line
var pageViewsChart = makeLineChart(
  "#pageViewsChart",
  [15, 18, 16, 20, 19, 24, 22, 27, 25, 30],
  "#17a05b",
);
pageViewsChart.render();

// Bounce Rate — yellow/amber downtrend line
var bounceRateChart = makeLineChart(
  "#bounceRateChart",
  [34, 30, 32, 27, 29, 24, 26, 20, 23, 18],
  "#d97706",
);
bounceRateChart.render();

// Avg. Session — red/coral uptrend line
var avgSessionChart = makeLineChart(
  "#avgSessionChart",
  [16, 14, 18, 15, 20, 17, 22, 19, 25, 28],
  "#ef4444",
);
avgSessionChart.render();



// ================================ Audience Overview - Combo Bar + Line Chart ================================
var audienceOverviewChart = new ApexCharts(
  document.querySelector("#audienceOverviewChart"),
  {
    series: [
      {
        name: "New Visitors",
        type: "column",
        data: [2, 5, 8, 25, 28, 70, 175],
      },
      {
        name: "Previous Visitors",
        type: "column",
        data: [3, 6, 10, 22, 25, 80, 135],
      },
      {
        name: "Unique Visitors",
        type: "line",
        data: [18, 20, 30, 45, 65, 102, 220],
      },
    ],
    chart: {
      height: 320,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    stroke: {
      curve: "smooth",
      width: [0, 0, 2],
    },
    colors: ["#6366f1", "#22c55e", "#ef4444"],
    plotOptions: {
      bar: {
        columnWidth: "55%",
        borderRadius: 0,
        borderRadiusApplication: "end",
      },
    },
    fill: {
      opacity: [1, 1, 1],
    },
    markers: {
      size: 0,
      hover: { size: 5 },
    },
    xaxis: {
      categories: ["Mo", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      labels: {
        style: { fontSize: "12px", colors: "#9ca3af" },
      },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 0,
      max: 250,
      tickAmount: 5,
      labels: {
        style: { fontSize: "12px", colors: "#9ca3af" },
      },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: false } },
      yaxis: { lines: { show: true } },
    },
    legend: {
      show: true,
      position: "bottom",
      horizontalAlign: "center",
      fontSize: "13px",
      fontWeight: 500,
      markers: {
        width: 8,
        height: 8,
        radius: 12,
      },
      itemMargin: {
        horizontal: 12,
        vertical: 8,
      },
      labels: {
        colors: "#6B7280",
      },
    },
    tooltip: {
      shared: true,
      intersect: false,
    },
  },
);
audienceOverviewChart.render();



// ================================ Total Subscribers - Equal Height, Varying Width Bar Chart ================================
var subscribersChart = new ApexCharts(document.querySelector("#subscribersChart"), {
  series: [{
    data: [
      { x: 'Email Marketing', y: 100, columnWidth: '100%' },
      { x: 'Social Marketing', y: 100, columnWidth: '85%' },
      { x: 'Direct', y: 100, columnWidth: '70%' },
      { x: 'Referral', y: 100, columnWidth: '50%' },
      { x: 'Organic Search', y: 100, columnWidth: '25%' }
    ]
  }],
  chart: {
    type: 'bar',
    height: 160,
    width: '100%',
    toolbar: { show: false },
    sparkline: { enabled: true },
    animations: { enabled: false }
  },
  plotOptions: {
    bar: {
      columnWidth: '96%',
      borderRadius: 6,
      distributed: true
    }
  },
  colors: ['#e0e7ff', '#c7d2fe', '#a5b4fc', '#6366f1', '#4338ca'],
  dataLabels: { enabled: false },
  legend: { show: false },
  tooltip: { enabled: false },
  states: {
    hover: { filter: { type: 'none' } },
    active: { filter: { type: 'none' } }
  },
  grid: { show: false }
});
subscribersChart.render();



// ================================ Sessions Overview - Dual Area Chart ================================
var sessionsOverviewChart = new ApexCharts(document.querySelector("#sessionsOverviewChart"), {
  series: [
    {
      name: 'Total Visitors',
      data: [18000, 19500, 18500, 17000, 17500, 14500, 16000, 17500, 18000, 17000, 19000, 19500, 18500, 19500, 21000, 20000, 21500, 19000, 20000, 23500, 23000, 20500, 20000, 19500, 20000, 21000, 19500, 20500, 21500, 22000]
    },
    {
      name: 'Page Views',
      data: [22000, 23500, 22500, 21000, 21500, 19000, 21500, 21000, 21500, 21500, 22000, 22500, 21500, 22500, 24000, 23000, 24500, 22000, 22500, 26500, 25500, 23000, 22500, 22000, 22500, 23000, 22000, 22500, 23500, 24000]
    }
  ],
  chart: {
    type: 'area',
    height: 320,
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  stroke: {
    curve: 'smooth',
    width: [2, 2]
  },
  colors: [primaryColor, '#22c55e'],
  fill: {
    type: 'gradient',
    gradient: {
      shade: 'light',
      type: 'vertical',
      shadeIntensity: 0.3,
      gradientToColors: ['#ffffff', '#ffffff'],
      opacityFrom: 0.5,
      opacityTo: 0.02,
      stops: [0, 100]
    }
  },
  markers: { size: 0 },
  dataLabels: { enabled: false },
  xaxis: {
    categories: Array.from({ length: 30 }, function (_, i) { return i + 1; }),
    labels: {
      style: { fontSize: '11px', colors: '#9ca3af' }
    },
    axisBorder: { show: false },
    axisTicks: { show: false }
  },
  yaxis: {
    min: 0,
    max: 30000,
    tickAmount: 3,
    labels: {
      formatter: function (val) {
        return (val / 1000) + 'k';
      },
      style: { fontSize: '11px', colors: '#9ca3af' }
    }
  },
  grid: {
    borderColor: '#ffffff00',
    strokeDashArray: 4,
    xaxis: { lines: { show: false } },
    yaxis: { lines: { show: true } }
  },
  annotations: {
    xaxis: [
      {
        x: 6,
        borderColor: '#ef4444',
        strokeDashArray: 4,
        borderWidth: 1
      }
    ]
  },
  legend: { show: false },
  tooltip: {
    shared: true,
    intersect: false
  }
});
sessionsOverviewChart.render();




// ================================ Support Tracker - Donut Chart ================================
var supportTrackerChart = new ApexCharts(document.querySelector("#supportTrackerChart"), {
  series: [25, 30, 20, 25],
  chart: {
    type: 'donut',
    height: 420
  },
  labels: ['New Tickets', 'Resolved Tickets', 'Open Tickets', 'Response Time'],
  colors: ['#06b6d4', primaryColor, '#22c55e', '#facc15'],
  stroke: {
    width: 0
  },
  dataLabels: { enabled: false },
  legend: { show: false },
  tooltip: {
    y: {
      formatter: function (val) { return val + '%'; }
    }
  }
});
supportTrackerChart.render();




// ================================ Average Income - Horizontal Dual Bar Chart ================================
var averageIncomeChart = new ApexCharts(document.querySelector("#averageIncomeChart"), {
  series: [
    {
      name: 'This Year',
      data: [625000, 180000, 150000, 60000, 35000, 25000]
    },
    {
      name: 'Last Year',
      data: [680000, 175000, 195000, 55000, 30000, 20000]
    }
  ],
  chart: {
    type: 'bar',
    height: 520,
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  plotOptions: {
    bar: {
      horizontal: true,
      barHeight: '80%',
      borderRadius: 0,
      borderRadiusApplication: 'end'
    }
  },
  colors: [primaryColor, '#22d3ee'],
  dataLabels: { enabled: false },
  stroke: {
    show: true,
    width: 0
  },
  xaxis: {
    categories: ['World', 'China', 'India', 'USA', 'Italy', 'Brazil'],
    labels: {
      formatter: function (val) {
        return new Intl.NumberFormat('en-US').format(val);
      },
      style: { fontSize: '10px', colors: '#9ca3af' }
    },
    axisBorder: { show: false },
    axisTicks: { show: false },
    max: 700000,
    tickAmount: 7
  },
  yaxis: {
    labels: {
      style: { fontSize: '11px', colors: '#64748b', fontWeight: 500 }
    }
  },
  grid: {
    borderColor: '#ffffff00',
    strokeDashArray: 0,
    xaxis: { lines: { show: true } },
    yaxis: { lines: { show: false } }
  },
  legend: { show: false },
  tooltip: {
    shared: false,
    y: {
      formatter: function (val) {
        return '$' + new Intl.NumberFormat('en-US').format(val);
      }
    }
  }
});
averageIncomeChart.render();


// ================================ Performance Overview - Bubble Chart ================================
var performanceOverviewChart = new ApexCharts(document.querySelector("#performanceOverviewChart"), {
  series: [
    { name: 'Email', data: [{ x: 380, y: 80000, z: 38 }] },
    { name: 'Organic Search', data: [{ x: 130, y: 21000, z: 26 }] },
    { name: 'Direct Browser', data: [{ x: 300, y: 50000, z: 34 }] },
    { name: 'Paid Search', data: [{ x: 820, y: 60000, z: 32 }] },
    { name: 'Social', data: [{ x: 600, y: 42000, z: 24 }] },
    { name: 'Referral', data: [{ x: 850, y: 21000, z: 26 }] }
  ],
  chart: {
    type: 'bubble',
    height: 410,
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  colors: ['#6366f1', '#facc15', '#4ade80', '#f87171', '#22d3ee', '#e879f9'],
  fill: {
    type: 'solid',
    opacity: 0.85
  },
  dataLabels: { enabled: false },
  stroke: { width: 0 },
  xaxis: {
    type: 'numeric',
    min: 0,
    max: 1000,
    tickAmount: 5,
    labels: {
      formatter: function (val) {
        var n = Math.round(Number(val));
        return (n === 0 || n === 1000) ? String(n) : '';
      },
      style: { fontSize: '11px', colors: '#9ca3af' }
    },
    axisBorder: { show: false },
    axisTicks: { show: false }
  },
  yaxis: {
    min: 0,
    max: 100000,
    tickAmount: 5,
    labels: {
      formatter: function (val) {
        return '$' + (val / 1000) + 'K';
      },
      style: { fontSize: '11px', colors: '#9ca3af' }
    }
  },
  grid: {
    borderColor: '#ffffff00',
    strokeDashArray: 0,
    xaxis: { lines: { show: false } },
    yaxis: { lines: { show: true } }
  },
  legend: {
    show: true,
    position: 'bottom',
    horizontalAlign: 'center',
    fontSize: '13px',
    fontWeight: 500,
    markers: {
      width: 8,
      height: 8,
      radius: 12
    },
    itemMargin: {
      horizontal: 10,
      vertical: 8
    },
    labels: {
      colors: '#6B7280'
    }
  },
  tooltip: {
    custom: function ({ series, seriesIndex, dataPointIndex, w }) {
      var name = w.config.series[seriesIndex].name;
      var data = w.config.series[seriesIndex].data[dataPointIndex];
      return '<div style="padding:8px 12px; font-size:12px;">' +
        '<strong>' + name + '</strong><br>' +
        'Visitors: ' + data.x + '<br>' +
        'Revenue: $' + (data.y / 1000) + 'K' +
        '</div>';
    }
  }
});
performanceOverviewChart.render();


// ================================ Top Browsing Pages - Column Chart ================================
var topBrowsingPagesChart = new ApexCharts(document.querySelector("#topBrowsingPagesChart"), {
  series: [{
    data: [18, 30, 42, 18, 37, 26, 11, 34, 15, 22]
  }],
  chart: {
    type: 'bar',
    height: 160,
    toolbar: { show: false },
    sparkline: { enabled: true }
  },
  plotOptions: {
    bar: {
      columnWidth: '45%',
      borderRadius: 0,
      borderRadiusApplication: 'end'
    }
  },
  colors: [primaryColor],
  dataLabels: {
    enabled: false,
    offsetY: -18,
    style: {
      fontSize: '12px',
      fontWeight: 600,
      colors: ['#1e293b']
    },
    background: { enabled: false }
  },
  tooltip: { enabled: true },
  grid: { show: false }
});
topBrowsingPagesChart.render();


// ================================ Total Orders - Dual Bar Chart with Broken Y-axis ================================
var totalOrdersChart = new ApexCharts(document.querySelector("#totalOrdersChart"), {
  series: [
    {
      name: 'Orders',
      data: [3106000, 1850000, 1900000, 2400000, 2950000, 2050000, 3300000]
    },
    {
      name: 'Refunds',
      data: [1553000, 925000, 950000, 1200000, 1475000, 1025000, 1650000]
    }
  ],
  chart: {
    type: 'bar',
    height: 390,
    toolbar: { show: false },
    zoom: { enabled: false },
    stacked: false
  },
  plotOptions: {
    bar: {
      columnWidth: '55%',
      borderRadius: 0,
      borderRadiusApplication: 'end'
    }
  },
  colors: [primaryColor, '#22c55e'],
  dataLabels: { enabled: false },
  stroke: {
    show: true,
    width: 1,
    colors: ['transparent']
  },
  xaxis: {
    categories: ['Mo', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    labels: {
      style: { fontSize: '11px', colors: '#9ca3af' }
    },
    axisBorder: { show: false },
    axisTicks: { show: false }
  },
  yaxis: {
    min: 0,
    max: 3500000,
    tickAmount: 4,
    labels: {
      formatter: function (val) {
        return new Intl.NumberFormat('en-US').format(Math.round(val));
      },
      style: { fontSize: '10px', colors: '#9ca3af' }
    }
  },
  grid: {
    borderColor: '#ffffff00',
    strokeDashArray: 3,
    xaxis: { lines: { show: false } },
    yaxis: { lines: { show: true } }
  },
  legend: { show: false },
  annotations: {
    yaxis: [
      {
        y: 700000,
        y2: 750000,
        borderColor: 'transparent',
        fillColor: 'transparent',
        label: { text: '' }
      }
    ]
  }
});
totalOrdersChart.render();


// ================================ User By Device - Radar Chart ================================
var userByDeviceChart = new ApexCharts(document.querySelector("#userByDeviceChart"), {
  series: [
    {
      name: 'Desktop',
      data: [55, 70, 35, 50, 40, 45, 90]
    },
    {
      name: 'Mobile',

      data: [60, 30, 40, 75, 95, 55, 50]
    },
    {
      name: 'Others',
      data: [40, 45, 55, 95, 30, 35, 60]
    }
  ],
  chart: {
    type: 'radar',
    height: 460,
    toolbar: { show: false }
  },
  xaxis: {
    categories: ['Jul', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    labels: {
      style: {
        fontSize: '12px',
        colors: ['#9ca3af', '#9ca3af', '#9ca3af', '#9ca3af', '#9ca3af', '#9ca3af', '#9ca3af']
      }
    }
  },
  yaxis: {
    show: false,
    min: 0,
    max: 100
  },
  colors: [primaryColor, '#22c55e', '#f87171'],
  fill: {
    opacity: 0.15
  },
  stroke: {
    width: 1.5,
    colors: [primaryColor, '#22c55e', '#f87171']
  },
  markers: {
    size: 4,
    colors: [primaryColor, '#22c55e', '#f87171'],
    strokeWidth: 0
  },
  plotOptions: {
    radar: {
      polygons: {
        strokeColors: '#e5e7eb',
        strokeWidth: 1,
        connectorColors: '#e5e7eb',
        fill: {
          colors: ['transparent', 'transparent']
        }
      }
    }
  },
  dataLabels: { enabled: false },
  legend: { show: false },
  tooltip: {
    y: {
      formatter: function (val) { return val + '%'; }
    }
  }
});
userByDeviceChart.render();


var dailyVisitInsightsChart = new ApexCharts(document.querySelector("#dailyVisitInsightsChart"), {
  series: [
    { name: 'Male', data: [20, 95, 25, 25, 55, 80, 45] },
    { name: 'Female', data: [25, 60, 70, 50, 65, 55, 25] }
  ],
  chart: {
    type: 'bar',
    height: 380,
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  plotOptions: {
    bar: {
      columnWidth: '30%',
      borderRadius: 6,
      borderRadiusApplication: 'end'
    }
  },
  colors: [primaryColor, '#facc15'],
  dataLabels: { enabled: false },
  stroke: {
    show: true,
    width: 2,
    colors: ['transparent']
  },
  xaxis: {
    categories: ['Sun', 'Mo', 'Tue', 'Wed', 'Thu', 'Fri', 'Sa'],
    labels: { style: { fontSize: '12px', colors: '#9ca3af' } },
    axisBorder: { show: false },
    axisTicks: { show: false }
  },
  yaxis: {
    show: false,
    min: 0,
    max: 100
  },
  grid: {
    borderColor: '#ffffff00',
    strokeDashArray: 0,
    xaxis: { lines: { show: true } },
    yaxis: { lines: { show: false } },
    padding: { top: 0, right: 0, bottom: 0, left: 0 }
  },
  legend: { show: false },
  // tooltip: { shared: true }
});
dailyVisitInsightsChart.render();


// ================================================================================================
//  Update all charts primary color ✅ ONE merged function — replaces BOTH previous updateChartColors definitions
// ================================================================================================
function updateChartColors(newColor) {
  topBrowsingPagesChart.updateOptions({
    colors: [newColor],
  });

  averageIncomeChart.updateOptions({
    colors: [newColor, '#22d3ee'],
  });

  totalOrdersChart.updateOptions({
    colors: [newColor, '#22c55e'],
  });

  supportTrackerChart.updateOptions({
    colors: ['#06b6d4', newColor, '#22c55e', '#facc15'],
  });

  sessionsOverviewChart.updateOptions({
    colors: [newColor, '#22c55e'],
  });

  dailyVisitInsightsChart.updateOptions({
    colors: [newColor, '#facc15'],
  });
  
  userByDeviceChart.updateOptions({
    colors: [newColor, '#22c55e', '#f87171'],
    stroke: {
      colors: [newColor, '#22c55e', '#f87171']
    },
    markers: {
      colors: [newColor, '#22c55e', '#f87171'],
    },
  });

}







// ================================ J Vector Map Start ================================
$("#world-map").vectorMap({
  map: "world_mill_en",
  backgroundColor: "transparent",
  borderColor: "#fff",
  borderOpacity: 0.25,
  borderWidth: 0,
  color: "#000000",
  regionStyle: {
    initial: {
      fill: "#D1D5DB",
    },
  },
  markerStyle: {
    initial: {
      r: 5,
      fill: "#fff",
      "fill-opacity": 1,
      stroke: "#000",
      "stroke-width": 1,
      "stroke-opacity": 0.4,
    },
  },
  markers: [
    {
      latLng: [35.8617, 104.1954],
      name: "China : 250",
    },

    {
      latLng: [25.2744, 133.7751],
      name: "AustrCalia : 250",
    },

    {
      latLng: [36.77, -119.41],
      name: "USA : 82%",
    },

    {
      latLng: [55.37, -3.41],
      name: "UK   : 250",
    },

    {
      latLng: [25.2, 55.27],
      name: "UAE : 250",
    },
  ],

  series: {
    regions: [
      {
        values: {
          US: "#487FFF ",
          SA: "#FF9F29",
          AU: "#45B369",
          CN: "#F86624",
          GB: "#487FFF",
        },
        attribute: "fill",
      },
    ],
  },
  hoverOpacity: null,
  normalizeFunction: "linear",
  zoomOnScroll: false,
  scaleColors: ["#000000", "#000000"],
  selectedColor: "#000000",
  selectedRegions: [],
  enableZoom: false,
  hoverColor: "#fff",
});
// ================================ J Vector Map End ================================


// ================================ Recent Orders - DataTable ================================
$(document).ready(function () {
  const table = $("#recentOrdersTable").DataTable({
    pageLength: 11,
    lengthMenu: [11, 25, 50, 100],
    ordering: true,
    info: false,
    searching: false,
    dom: "tp",
    language: {
      paginate: {
        previous: '<i class="ph ph-caret-left"></i>',
        next: '<i class="ph ph-caret-right"></i>',
      },
    },
  });
  // Hide pagination UI
  $(table.table().container()).find(".dt-paging").hide();
});


// ================================ Browser Used - DataTable ================================
$(document).ready(function () {
  $("#browserUsedTable").DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [], // keep the original row order on load
    columnDefs: [
      { orderable: false, targets: 4 } // Action column not sortable
    ],
  });
});
