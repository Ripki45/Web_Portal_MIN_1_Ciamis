// ================================ Ticket Stat Cards - Area Sparklines ================================
var ticketOpenChart,
  ticketProgressChart,
  ticketResolvedChart,
  ticketClosedChart;

function makeTicketChart(selector, data, color, markerIndex) {
  var el = document.querySelector(selector);
  if (!el) return null;

  var chart = new ApexCharts(el, {
    series: [{ name: "Tickets", data: data }],
    chart: {
      type: "area",
      height: 90,
      width: "100%",
      sparkline: { enabled: true },
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [color],
    stroke: { curve: "straight", width: 2, colors: [color] },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.9,
        opacityTo: 0.02,
        stops: [0, 100],
      },
    },
    markers:
      markerIndex != null
        ? {
            size: 0,
            colors: ["#fff"],
            strokeColors: color,
            strokeWidth: 2,
            discrete: [
              {
                seriesIndex: 0,
                dataPointIndex: markerIndex,
                fillColor: color,
                strokeColor: "#fff",
                size: 5,
              },
            ],
          }
        : { size: 0 },
    dataLabels: { enabled: false },
    tooltip: { enabled: true },
    grid: { show: false },
  });

  chart.render();
  return chart;
}

// Ticket Open — uses primaryColor
ticketOpenChart = makeTicketChart(
  "#ticketOpenChart",
  [20, 36, 24, 46, 30, 50, 34, 40, 30, 52],
  primaryColor,
);

// Tickets In Progress — cyan (fixed accent, not primary)
ticketProgressChart = makeTicketChart(
  "#ticketProgressChart",
  [46, 30, 50, 34, 48, 32, 44, 30, 42, 34],
  "#06b6d4",
);

// Tickets Resolved — amber (fixed accent, not primary)
ticketResolvedChart = makeTicketChart(
  "#ticketResolvedChart",
  [30, 38, 32, 55, 40, 34, 46, 32, 44, 34],
  "#eab308",
  3,
);

// Tickets Closed — red (fixed accent, not primary)
ticketClosedChart = makeTicketChart(
  "#ticketClosedChart",
  [34, 26, 31, 23, 29, 25, 33, 28, 46, 56],
  "#ef4444",
);

// ================================ New Tickets Created - Grouped Column Chart ================================
var newTicketsChartEl = document.querySelector("#newTicketsChart");

var newTicketsChart = new ApexCharts(newTicketsChartEl, {
  series: [
    { name: "Low", data: [320, 330, 300, 330, 390, 300, 280] },
    { name: "Medium", data: [220, 180, 190, 230, 290, 200, 180] },
    { name: "High", data: [160, 230, 200, 160, 190, 150, 120] },
    { name: "Urgent", data: [100, 80, 100, 160, 45, 90, 65] },
  ],
  chart: {
    type: "bar",
    height: 340,
    toolbar: { show: false },
    zoom: { enabled: false },
  },
  colors: [primaryColor, "#22C55E", "#FDC70F", "#F6776E"],
  plotOptions: {
    bar: {
      columnWidth: "70%",
      borderRadius: 0,
      borderRadiusApplication: "end",
    },
  },
  dataLabels: { enabled: false },
  stroke: { show: true, width: 2, colors: ["transparent"] },
  xaxis: {
    categories: ["Mon", "Tue", "We", "Th", "Fr", "Sat", "Su"],
    labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
    axisBorder: { show: false },
    axisTicks: { show: false },
  },
  yaxis: {
    min: 0,
    max: 400,
    tickAmount: 4,
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
newTicketsChart.render();

// ================================ Response Time - Dual Area Chart ================================
var responseTimeChartEl = document.querySelector("#responseTimeChart");

var responseTimeChart = new ApexCharts(responseTimeChartEl, {
  series: [
    {
      name: "Ave Resolution Time",
      data: [4.5, 6.5, 8, 6, 4.5, 7, 8.2, 6, 4.5, 6.5, 8, 6.5, 5],
    },
    {
      name: "First Response Time",
      data: [2, 3, 4, 3, 2, 3.5, 4.2, 3, 2, 3, 4, 3, 2.5],
    },
  ],
  chart: {
    type: "area",
    height: 230,
    toolbar: { show: false },
    zoom: { enabled: false },
  },
  colors: ["#06b6d4", primaryColor],
  stroke: { curve: "smooth", width: 2 },
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.8,
      opacityTo: 0.05,
      stops: [0, 100],
    },
  },
  dataLabels: { enabled: false },
  markers: { size: 0 },
  xaxis: {
    labels: { show: false },
    axisBorder: { show: false },
    axisTicks: { show: false },
  },
  yaxis: {
    min: 0,
    max: 10,
    tickAmount: 10,
    labels: {
      formatter: function (val) {
        var n = Math.round(val);
        return n === 0 || n === 2 || n === 5 || n === 10 ? n + "H" : "";
      },
      style: { fontSize: "11px", colors: "#9ca3af" },
    },
  },
  grid: {
    borderColor: "#ffffff00",
    strokeDashArray: 0,
    xaxis: { lines: { show: false } },
    yaxis: { lines: { show: true } },
    padding: { left: 0, right: 0 },
  },
  legend: { show: false },
  tooltip: {
    shared: true,
    intersect: false,
    y: {
      formatter: function (val) {
        return val + "H";
      },
    },
  },
});
responseTimeChart.render();

// ================================ Clients Satisfaction - Pie Chart ================================
var clientsSatisfactionChartEl = document.querySelector(
  "#clientsSatisfactionChart",
);

var clientsSatisfactionChart = new ApexCharts(clientsSatisfactionChartEl, {
  series: [40, 30, 30],
  labels: ["Highly Satisfied", "Unsatisfied", "Satisfied"],
  chart: { type: "pie", height: 360 },
  colors: [primaryColor, "#06b6d4", "#facc15"],
  stroke: { width: 3, colors: ["#fff"] },
  dataLabels: { enabled: false },
  legend: { show: false },
  tooltip: {
    y: {
      formatter: function (val) {
        return val + "%";
      },
    },
  },
});
clientsSatisfactionChart.render();

// ================================ Tickets Solved and Created - Dual Area Chart ================================
var ticketsSolvedCreatedChartEl = document.querySelector(
  "#ticketsSolvedCreatedChart",
);

var ticketsSolvedCreatedChart = new ApexCharts(ticketsSolvedCreatedChartEl, {
  series: [
    {
      name: "Tickets Created",
      data: [10, 105, 45, 140, 55, 205, 50, 235, 65, 215, 80, 225],
    },
    {
      name: "Tickets Solved",
      data: [5, 58, 25, 75, 35, 110, 30, 120, 38, 110, 45, 118],
    },
  ],
  chart: {
    type: "area",
    height: 400,
    toolbar: { show: false },
    zoom: { enabled: false },
  },
  colors: ["#facc15", primaryColor],
  stroke: { curve: "smooth", width: 2, dashArray: [6, 0] },
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.65,
      opacityTo: 0.02,
      stops: [0, 100],
    },
  },
  dataLabels: { enabled: false },
  markers: { size: 0, hover: { size: 4 } },
  xaxis: {
    categories: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
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
  annotations: {
    xaxis: [
      { x: "Jul", borderColor: "#cbd5e1", strokeDashArray: 4, borderWidth: 1 },
    ],
  },
  legend: { show: false },
  tooltip: { shared: true, intersect: false },
});
ticketsSolvedCreatedChart.render();

// ================================ SLA Monitoring - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#slaMonitoringTable");
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
});

// ================================ Performance of Agents - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#agentsPerformanceTable");
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

  var denseSwitch = document.getElementById("agentsDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});

// ================================ Tickets by Channel - Polar Area Chart ================================
var ticketsByChannelChartEl = document.querySelector("#ticketsByChannelChart");

var ticketsByChannelChart = new ApexCharts(ticketsByChannelChartEl, {
  series: [80, 55, 60, 65, 70],
  labels: ["Email", "App", "Web", "Chat", "Tab"],
  chart: { type: "polarArea", height: 380 },
  colors: [primaryColor, "#22c55e", "#f87171", "#06b6d4", "#facc15"],
  stroke: { colors: ["#fff"], width: 2 },
  fill: { opacity: 0.9 },
  dataLabels: { enabled: false },
  legend: { show: false },
  yaxis: { show: false },
  plotOptions: {
    polarArea: {
      rings: { strokeColor: "#e5e7eb" },
      spokes: { strokeColor: "#e5e7eb" },
    },
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return val + " tickets";
      },
    },
  },
});
ticketsByChannelChart.render();

// ================================ Tickets by Type - Donut Chart ================================
var ticketsByTypeChartEl = document.querySelector("#ticketsByTypeChart");

var ticketsByTypeChart = new ApexCharts(ticketsByTypeChartEl, {
  series: [12, 35, 23, 30],
  labels: [
    "Product Support",
    "General Inquiry",
    "Billing Inquiry",
    "Technical Issue",
  ],
  chart: { type: "donut", height: 370 },
  colors: ["#f87171", "#06b6d4", "#facc15", primaryColor],
  stroke: { width: 3, colors: ["#fff"] },
  dataLabels: { enabled: false },
  legend: { show: false },
  plotOptions: { pie: { donut: { size: "68%" } } },
  tooltip: {
    y: {
      formatter: function (val) {
        return val + "%";
      },
    },
  },
});
ticketsByTypeChart.render();

// ================================ Recent Customer Rating - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#customerRatingTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: 4 }],
  });
});

// ================================================================================================
//  Update all charts primary color — ONE unified function
// ================================================================================================
function updateChartColors(newColor) {
  // Sparkline stat cards — only ticketOpen uses primaryColor
  if (ticketOpenChart) {
    ticketOpenChart.updateOptions({
      colors: [newColor],
      stroke: { colors: [newColor] },
    });
  }

  newTicketsChart.updateOptions({
    colors: [newColor, "#22c55e", "#facc15", "#f87171"],
  });

  responseTimeChart.updateOptions({
    colors: ["#06b6d4", newColor],
  });

  clientsSatisfactionChart.updateOptions({
    colors: [newColor, "#06b6d4", "#facc15"],
  });

  ticketsSolvedCreatedChart.updateOptions({
    colors: ["#facc15", newColor],
  });

  ticketsByChannelChart.updateOptions({
    colors: [newColor, "#22c55e", "#f87171", "#06b6d4", "#facc15"],
  });

  ticketsByTypeChart.updateOptions({
    colors: ["#f87171", "#06b6d4", "#facc15", newColor],
  });
}
