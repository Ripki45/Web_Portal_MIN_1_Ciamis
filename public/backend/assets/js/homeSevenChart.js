// ================================ Investment Overview - sparklines ================================
function makeLineChart(selector, data, color) {
  var el = document.querySelector(selector);
  if (!el) return null;
  var chart = new ApexCharts(el, {
    series: [{ data: data }],
    chart: {
      type: "line",
      height: 60,
      width: 120,
      sparkline: { enabled: true },
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    stroke: { curve: "straight", width: 2.5, colors: [color] },
    fill: { opacity: 1 },
    markers: { size: 0 },
    colors: [color],
    dataLabels: { enabled: false },
    tooltip: { enabled: true },
    grid: { show: false },
  });
  chart.render();
  return chart;
}

// Total Investment — indigo uptrend (uses primaryColor)
var totalInvestmentChart = makeLineChart(
  "#totalInvestmentChart",
  [18, 22, 19, 24, 21, 27, 25, 30, 28, 34],
  primaryColor,
);

// Total Profit/Loss — green uptrend
makeLineChart(
  "#profitLossChart",
  [15, 18, 16, 20, 19, 24, 22, 27, 25, 30],
  "#17a05b",
);

// Average ROI — red downtrend
makeLineChart(
  "#avgRoiChart",
  [30, 26, 28, 22, 25, 20, 24, 18, 21, 16],
  "#ef4444",
);

// New clients — olive/amber uptrend
makeLineChart(
  "#newClientsChart",
  [16, 14, 18, 15, 20, 17, 22, 19, 25, 28],
  "#a16207",
);


// ================================ Investment Portfolio - Line Chart ================================
var portfolioChart;
(function () {
  var el = document.querySelector("#portfolioChart");
  if (!el) return;
  portfolioChart = new ApexCharts(el, {
    series: [
      {
        name: "Portfolio",
        data: [100, 102, 99, 101, 98, 103, 100, 102, 99, 105, 108, 112, 104, 98, 96, 95, 97, 99, 98, 100, 99],
      },
    ],
    chart: {
      type: "area",
      height: 320,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [primaryColor],
    stroke: { curve: "straight", width: 2 },
    fill: {
      type: "gradient",
      gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02, stops: [0, 100] },
    },
    dataLabels: { enabled: false },
    markers: {
      size: 4,
      colors: [primaryColor],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: { size: 6 },
    },
    xaxis: {
      categories: ["Sun", "", "", "Mon", "", "", "Tue", "", "", "Wed", "", "", "Thu", "", "", "Fri", "", "", "Sat", "", ""],
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
      axisBorder: { show: false },
      axisTicks: { show: false },
      tooltip: { enabled: false },
    },
    yaxis: {
      min: 85,
      max: 135,
      tickAmount: 5,
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: true } },
      yaxis: { lines: { show: true } },
    },
    legend: { show: false },
    tooltip: { shared: true, intersect: false, x: { show: false } },
  });
  portfolioChart.render();
})();



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


// ================================================================================================
//  Update all charts primary color — ONE unified function
// ================================================================================================
function updateChartColors(newColor) {
  if (totalInvestmentChart) {
    totalInvestmentChart.updateOptions({
      colors: [newColor],
      stroke: { colors: [newColor] },
    });
  }

  if (portfolioChart) {
    portfolioChart.updateOptions({
      colors: [newColor],
      markers: { colors: [newColor] },
    });
  }
}


// ================================ Location By Session - World Map ================================
(function () {
  var el = document.querySelector("#sessionWorldMap");
  if (!el || typeof $ === "undefined" || !$.fn || !$.fn.vectorMap) return;

  $(el).vectorMap({
    map: "world_mill_en",
    backgroundColor: "transparent",
    zoomOnScroll: false,
    zoomButtons: false,
    regionStyle: {
      initial: { fill: "#c7d2fe", "fill-opacity": 1, stroke: "none" },
      hover: { fill: "#a5b4fc" },
    },
    markerStyle: {
      initial: { r: 6, fill: "#6366f1", stroke: "#fff", "stroke-width": 2, "fill-opacity": 1 },
      hover: { stroke: "#fff", "fill-opacity": 1 },
    },
    markers: [
      { latLng: [37.09, -95.71], name: "USA" },
      { latLng: [35.86, 104.19], name: "China" },
      { latLng: [-25.27, 133.77], name: "Australia" },
      { latLng: [51.16, 10.45], name: "Germany" },
      { latLng: [55.75, 37.61], name: "Russia" },
    ],
  });
})();


// ================================ Latest Investment - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#latestInvestmentTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: 7 }],
  });

  var denseSwitch = document.getElementById("latestInvestmentDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});
