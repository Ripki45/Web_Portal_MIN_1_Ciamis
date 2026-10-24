// ================================ Visitors By Device - Semi Donut Gauge ================================
var visitorsDeviceGauge;
(function () {
  var el = document.querySelector("#visitorsDeviceGauge");
  if (!el) return;
  visitorsDeviceGauge = new ApexCharts(el, {
    series: [30, 12, 17, 15, 14, 12],
    labels: ["Desktop", "Mobile", "Laptop", "Tablet", "Smart TV", "Smart Watch"],
    chart: { type: "donut", height: 300 },
    colors: [primaryColor, "#d946ef", "#06b6d4", "#22c55e", "#facc15", "#f87171"],
    stroke: { width: 3, colors: ["#fff"] },
    plotOptions: {
      pie: {
        startAngle: -90,
        endAngle: 90,
        offsetY: 20,
        donut: { size: "68%" },
      },
    },
    dataLabels: { enabled: false },
    legend: { show: false },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
    grid: { padding: { bottom: -90 } },
  });
  visitorsDeviceGauge.render();
})();


// ================================ Sales Revenue - Dual Area Chart ================================
var salesRevenueChart;
(function () {
  var el = document.querySelector("#salesRevenueChart");
  if (!el) return;
  salesRevenueChart = new ApexCharts(el, {
    series: [
      { name: "Delivered", data: [10, 95, 40, 130, 55, 200, 60, 235, 70, 210, 90, 225] },
      { name: "Cancelled", data: [5, 60, 30, 80, 40, 110, 45, 120, 50, 110, 55, 118] },
    ],
    chart: {
      type: "area",
      height: 440,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: ["#06b6d4", "#f87171"],
    stroke: { curve: "smooth", width: 2, dashArray: [6, 0] },
    fill: {
      type: "gradient",
      gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.02, stops: [0, 100] },
    },
    dataLabels: { enabled: false },
    markers: { size: 0, hover: { size: 4 } },
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 0,
      max: 300,
      tickAmount: 5,
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 4,
      xaxis: { lines: { show: false } },
      yaxis: { lines: { show: true } },
    },
    legend: { show: false },
    tooltip: { shared: true, intersect: false },
  });
  salesRevenueChart.render();
})();


// ================================================================================================
//  Update all charts primary color — ONE unified function
// ================================================================================================
function updateChartColors(newColor) {
  if (visitorsDeviceGauge) {
    visitorsDeviceGauge.updateOptions({
      colors: [newColor, "#d946ef", "#06b6d4", "#22c55e", "#facc15", "#f87171"],
    });
  }
  if (electronicsGauge) {
    electronicsGauge.updateOptions({ colors: [newColor] });
  }
}


// ================================ Top Categories By Sales - RadialBar gauges ================================
function makeCategoryGauge(selector, value, color) {
  var el = document.querySelector(selector);
  if (!el) return null;
  var chart = new ApexCharts(el, {
    series: [value],
    chart: { type: "radialBar", height: 96, width: 96, sparkline: { enabled: true } },
    colors: [color],
    plotOptions: {
      radialBar: {
        hollow: { size: "54%" },
        track: { background: "#eef0f3", strokeWidth: "100%", margin: 2 },
        dataLabels: {
          name: { show: false },
          value: {
            show: true,
            offsetY: 5,
            fontSize: "13px",
            fontWeight: 700,
            color: "#1f2937",
            formatter: function (v) { return Math.round(v) + "%"; },
          },
        },
      },
    },
    stroke: { lineCap: "round" },
  });
  chart.render();
  return chart;
}

var electronicsGauge = makeCategoryGauge("#electronicsGauge", 53, primaryColor);
makeCategoryGauge("#fashionGauge", 82, "#eab308");
makeCategoryGauge("#toysGauge", 35, "#14b8a6");
makeCategoryGauge("#booksGauge", 67, "#22c55e");


// ================================ Browser Activity - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#browserActivityTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
  });
});


// ================================ Recent Orders - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#recentOrdersTable");
  if (!el) return;
  var table = $(el).DataTable({
    ordering: true,
    info: false,
    searching: true,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: 7 }], // Action column
  });

  var search = document.getElementById("recentOrdersSearch");
  if (search) {
    search.addEventListener("keyup", function () {
      table.search(this.value).draw();
    });
  }

  var denseSwitch = document.getElementById("recentOrdersDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});
