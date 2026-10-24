// ================================ Total Balance - Income/Expense wave sparklines ================================
function makeWaveChart(selector, data, color) {
  var el = document.querySelector(selector);
  if (!el) return null;
  var chart = new ApexCharts(el, {
    series: [{ name: "Value", data: data }],
    chart: {
      type: "area",
      height: 90,
      width: "100%",
      sparkline: { enabled: true },
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [color],
    stroke: { curve: "smooth", width: 2, colors: [color] },
    fill: {
      type: "gradient",
      gradient: { shadeIntensity: 1, opacityFrom: 0.95, opacityTo: 0.03, stops: [0, 100] },
    },
    dataLabels: { enabled: false },
    markers: { size: 0 },
    tooltip: { enabled: false },
    grid: { show: false },
  });
  chart.render();
  return chart;
}

var totalIncomeChart = makeWaveChart("#totalIncomeChart", [20, 28, 22, 35, 26, 40, 30, 45, 32, 48], primaryColor);
makeWaveChart("#totalExpensesChart", [30, 24, 34, 26, 40, 28, 36, 24, 38, 30], "#f87171");
makeWaveChart("#averageIncomeWaveChart", [18, 30, 22, 38, 26, 44, 30, 40, 28, 46], "#22c55e");
makeWaveChart("#dailySalesChart", [22, 32, 24, 40, 28, 46, 30, 42, 26, 48], "#06b6d4");


// ================================ Revenue Vs Operating Margin - Grouped Column Chart ================================
var revenueMarginChart;
(function () {
  var el = document.querySelector("#revenueMarginChart");
  if (!el) return;

  revenueMarginChart = new ApexCharts(el, {
    series: [
      { name: "Revenue", data: [19000, 15500, 13000, 23500, 43000, 16500, 26500, 10500, 25000, 46500, 16500, 21000] },
      { name: "Operating Margin", data: [13000, 16000, 18500, 19000, 33500, 18500, 16500, 12000, 16500, 36500, 12500, 15000] },
    ],
    chart: {
      type: "bar",
      height: 240,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [primaryColor, "#ff9f29"],
    plotOptions: {
      bar: { columnWidth: "30%", borderRadius: 4},
    },
    dataLabels: { enabled: false },
    stroke: { show: true, width: 3, colors: ["transparent"] },
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 0,
      max: 50000,
      tickAmount: 5,
      labels: {
        formatter: function (val) { return Math.round(val); },
        style: { fontSize: "12px", colors: "#9ca3af" },
      },
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
  revenueMarginChart.render();
})();


// ================================ Marketing Expenses - Nested Donut (two overlaid rings) ================================
var marketingOuterChart, marketingInnerChart;
(function () {
  var outerEl = document.querySelector("#marketingOuterChart");
  var innerEl = document.querySelector("#marketingInnerChart");
  if (!outerEl || !innerEl) return;

  // Outer ring — clockwise from top: Sponsorship, Events, Marketing (large, right),
  // Ad Campaign, Social Media, Back Links, Email
  marketingOuterChart = new ApexCharts(outerEl, {
    series: [8, 12, 30, 8, 14, 14, 14],
    labels: ["Sponsorship", "Events", "Marketing", "Ad Campaign", "Social Media", "Back Links", "Email"],
    chart: { type: "donut", height: 240 },
    colors: ["#d946ef", "#facc15", "#f97316", "#f87171", "#06b6d4", "#22d3ee", primaryColor],
    stroke: { width: 2, colors: ["#fff"] },
    dataLabels: { enabled: false },
    legend: { show: false },
    plotOptions: { pie: { donut: { size: "58%" } } },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
  });
  marketingOuterChart.render();

  // Inner ring — clockwise from top: Influencer (dark), Revenue (large, right),
  // Google Ads, Audit Report
  marketingInnerChart = new ApexCharts(innerEl, {
    series: [15, 48, 15, 22],
    labels: ["Influencer", "Revenue", "Google Ads", "Audit Report"],
    chart: { type: "donut", height: 130 },
    colors: ["#1f2937", primaryColor, "#84cc16", "#22c55e"],
    stroke: { width: 2, colors: ["#fff"] },
    dataLabels: { enabled: false },
    legend: { show: false },
    plotOptions: { pie: { donut: { size: "48%" } } },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
  });
  marketingInnerChart.render();
})();


// ================================ Expense Breakdown - Donut Chart ================================
var expenseBreakdownChart;
(function () {
  var el = document.querySelector("#expenseBreakdownChart");
  if (!el) return;
  expenseBreakdownChart = new ApexCharts(el, {
    series: [48, 25, 12, 15],
    labels: ["Marketing", "Rent", "Software", "Salaries"],
    chart: { type: "donut", height: 300 },
    colors: [primaryColor, "#a5b4fc", "#818cf8", "#c7d2fe"],
    stroke: { width: 3, colors: ["#fff"] },
    dataLabels: { enabled: false },
    legend: { show: false },
    plotOptions: { pie: { donut: { size: "72%" } } },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
  });
  expenseBreakdownChart.render();
})();


// ================================ Revenue By Division - Polar Area Chart ================================
var revenueDivisionChart;
(function () {
  var el = document.querySelector("#revenueDivisionChart");
  if (!el) return;
  // Clockwise from top: Capital Solution (top-right), Credits Strategies (bottom-right),
  // Capital Opportunities (bottom-left, large), Fund Strategies (top-left)
  revenueDivisionChart = new ApexCharts(el, {
    series: [30, 55, 95, 80],
    labels: ["Capital Solution", "Credits Strategies", "Capital Opportunities", "Fund Strategies"],
    chart: { type: "polarArea", height: 260 },
    colors: [primaryColor, "#22c55e", "#facc15", "#06b6d4"],
    stroke: { colors: ["#fff"], width: 2 },
    fill: { opacity: 0.85 },
    dataLabels: { enabled: false },
    legend: { show: false },
    yaxis: { show: false },
    plotOptions: {
      polarArea: {
        rings: { strokeColor: "#e5e7eb" },
        spokes: { strokeColor: "#e5e7eb" },
      },
    },
    tooltip: { y: { formatter: function (val) { return "$" + val + "K"; } } },
  });
  revenueDivisionChart.render();
})();




// ================================ Total Receivables vs Total Payable - Grouped Columns ================================
var receivablesPayableChart;
(function () {
  var el = document.querySelector("#receivablesPayableChart");
  if (!el) return;
  receivablesPayableChart = new ApexCharts(el, {
    series: [
      { name: "Payable", data: [5, 3, 6, 22, 24, 75, 135, 162, 32, 18, 5, 3] },
      { name: "Receivables", data: [2, 8, 10, 20, 28, 68, 172, 182, 48, 22, 18, 2.3] },
    ],
    chart: {
      type: "bar",
      height: 460,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [primaryColor, "#facc15"],
    plotOptions: { bar: { columnWidth: "60%", borderRadius: 3, borderRadiusApplication: "end" } },
    dataLabels: { enabled: false },
    stroke: { show: true, width: 2, colors: ["transparent"] },
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      labels: { style: { fontSize: "11px", colors: "#9ca3af" } },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 0,
      max: 210,
      tickAmount: 7,
      labels: { style: { fontSize: "11px", colors: "#9ca3af" } },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: false } },
      yaxis: { lines: { show: true } },
    },
    annotations: {
      yaxis: [
        {
          y: 48.07,
          borderColor: "#facc15",
          strokeDashArray: 5,
          label: {
            text: "48.07", position: "right", textAnchor: "start", offsetX: 6,
            borderColor: "#facc15",
            style: { color: "#fff", background: "#facc15", fontSize: "11px", fontWeight: 600 },
          },
        },
        {
          y: 41.63,
          borderColor: primaryColor,
          strokeDashArray: 5,
          label: {
            text: "41.63", position: "right", textAnchor: "start", offsetX: 6,
            borderColor: primaryColor,
            style: { color: "#fff", background: primaryColor, fontSize: "11px", fontWeight: 600 },
          },
        },
      ],
      points: [
        {
          x: "Aug", y: 182,
          marker: { size: 5, fillColor: "#facc15", strokeColor: "#fff", strokeWidth: 2 },
          label: { text: "182.2", offsetY: -4, borderColor: "#facc15", style: { color: "#fff", background: "#facc15", fontSize: "11px", fontWeight: 600 } },
        },
        {
          x: "Aug", y: 162,
          marker: { size: 5, fillColor: primaryColor, strokeColor: "#fff", strokeWidth: 2 },
          label: { text: "62", offsetY: -4, borderColor: primaryColor, style: { color: "#fff", background: primaryColor, fontSize: "11px", fontWeight: 600 } },
        },
        {
          x: "Dec", y: 2.3,
          marker: { size: 5, fillColor: "#facc15", strokeColor: "#fff", strokeWidth: 2 },
          label: { text: "2.3", offsetY: -4, borderColor: "#facc15", style: { color: "#fff", background: "#facc15", fontSize: "11px", fontWeight: 600 } },
        },
        {
          x: "Jan", y: 5,
          marker: { size: 6, fillColor: primaryColor, strokeColor: "#fff", strokeWidth: 2 },
        },
      ],
    },
    legend: { show: false },
    tooltip: { shared: true, intersect: false },
  });
  receivablesPayableChart.render();
})();


// ================================ Latest Transaction - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#latestTransactionTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: [4, 5] }],
  });

  var denseSwitch = document.getElementById("latestTransactionDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});


// ================================ Investment - Donut Chart ================================
var investmentChart;
(function () {
  var el = document.querySelector("#investmentChart");
  if (!el) return;
  investmentChart = new ApexCharts(el, {
    series: [45, 30, 25],
    labels: ["Net Income", "Real Estate", "Business"],
    chart: { type: "donut", height: 360 },
    colors: [primaryColor, "#06b6d4", "#f87171"],
    stroke: { width: 4, colors: ["#fff"] },
    dataLabels: { enabled: false },
    legend: { show: false },
    plotOptions: { pie: { donut: { size: "70%" } } },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
  });
  investmentChart.render();
})();


// ================================ Audit Report - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#auditReportTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: 3 }],
  });

  var denseSwitch = document.getElementById("auditReportDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});


// ================================================================================================
//  Update all charts primary color — ONE unified function
// ================================================================================================
function updateChartColors(newColor) {
  // Total Income wave sparkline — only this wave uses primaryColor
  if (totalIncomeChart) {
    totalIncomeChart.updateOptions({
      colors: [newColor],
      stroke: { colors: [newColor] },
    });
  }

  if (revenueMarginChart) {
    revenueMarginChart.updateOptions({
      colors: [newColor, "#facc15"],
    });
  }

  if (marketingOuterChart) {
    marketingOuterChart.updateOptions({
      colors: ["#d946ef", "#facc15", "#f97316", "#f87171", "#06b6d4", "#22d3ee", newColor],
    });
  }

  if (marketingInnerChart) {
    marketingInnerChart.updateOptions({
      colors: ["#1f2937", newColor, "#84cc16", "#22c55e"],
    });
  }

  if (expenseBreakdownChart) {
    expenseBreakdownChart.updateOptions({
      colors: [newColor, "#a5b4fc", "#818cf8", "#c7d2fe"],
    });
  }

  if (revenueDivisionChart) {
    revenueDivisionChart.updateOptions({
      colors: [newColor, "#22c55e", "#facc15", "#06b6d4"],
    });
  }

  if (receivablesPayableChart) {
    receivablesPayableChart.updateOptions({
      colors: [newColor, "#facc15"],
    });
  }

  if (investmentChart) {
    investmentChart.updateOptions({
      colors: [newColor, "#06b6d4", "#f87171"],
    });
  }
}
