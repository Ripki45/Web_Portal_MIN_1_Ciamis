// ================================ Projects Roadmap - Horizontal Bar Chart ================================
var projectsRoadmapChart;
(function () {
  var el = document.querySelector("#projectsRoadmapChart");
  if (!el) return;

  var barColors = [primaryColor, "#06b6d4", "#22c55e", "#facc15", "#f87171", "#a855f7"];

  projectsRoadmapChart = new ApexCharts(el, {
    series: [{ name: "Duration", data: [128, 28, 22, 7, 6, 6] }],
    chart: {
      type: "bar",
      height: 300,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: barColors,
    plotOptions: {
      bar: {
        horizontal: true,
        barHeight: "55%",
        borderRadius: 4,
        borderRadiusApplication: "end",
        distributed: true,
      },
    },
    dataLabels: { enabled: false },
    legend: { show: false },
    xaxis: {
      categories: ["Project Planning", "Requirement", "Design", "Development", "Testing and QA", "Post-Launch"],
      min: 0,
      max: 140,
      tickAmount: 7,
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      labels: { style: { fontSize: "12px", colors: "#64748b" } },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: true } },
      yaxis: { lines: { show: false } },
    },
    tooltip: { y: { formatter: function (val) { return val + " days"; } } },
  });
  projectsRoadmapChart.render();
})();


// ================================================================================================
//  Update all charts primary color — ONE unified function
// ================================================================================================
function updateChartColors(newColor) {
  if (projectsRoadmapChart) {
    projectsRoadmapChart.updateOptions({
      colors: [newColor, "#06b6d4", "#22c55e", "#facc15", "#f87171", "#a855f7"],
    });
  }
}


// ================================ Projects Progress - Donut Chart ================================
(function () {
  var el = document.querySelector("#projectsProgressChart");
  if (!el) return;
  // Clockwise from top: Cancelled (red), In Progress (yellow, large), Pending (cyan), Completed (green)
  new ApexCharts(el, {
    series: [15, 48, 12, 25],
    labels: ["Cancelled", "In Progress", "Pending", "Completed"],
    chart: { type: "donut", height: 280 },
    colors: ["#f87171", "#facc15", "#06b6d4", "#22c55e"],
    stroke: { width: 0 },
    dataLabels: { enabled: false },
    legend: { show: false },
    plotOptions: { pie: { donut: { size: "78%" } } },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
  }).render();
})();


// ================================ All Projects - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#allProjectsTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: 6 }],
  });

  var denseSwitch = document.getElementById("allProjectsDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});


// ================================ Team Members - mini progress rings ================================
(function () {
  function makeProgressRing(selector, value, color) {
    var el = document.querySelector(selector);
    if (!el) return;
    new ApexCharts(el, {
      series: [value],
      chart: { type: "radialBar", height: 56, width: 56, sparkline: { enabled: true } },
      colors: [color],
      plotOptions: {
        radialBar: {
          hollow: { size: "48%" },
          track: { background: "#eef1f6", strokeWidth: "100%" },
          dataLabels: {
            name: { show: false },
            value: { show: true, offsetY: 4, fontSize: "11px", fontWeight: 600, color: "#1f2937", formatter: function (v) { return v + "%"; } },
          },
        },
      },
      stroke: { lineCap: "round" },
    }).render();
  }

  var rings = [
    ["#teamProgress1", 53, "#06b6d4"],
    ["#teamProgress2", 73, "#f87171"],
    ["#teamProgress3", 20, "#f87171"],
    ["#teamProgress4", 53, "#06b6d4"],
    ["#teamProgress5", 73, "#8b5cf6"],
    ["#teamProgress6", 36, "#facc15"],
    ["#teamProgress7", 92, "#22c55e"],
    ["#teamProgress8", 20, "#f87171"],
    ["#teamProgress9", 53, "#06b6d4"],
    ["#teamProgress10", 92, "#22c55e"],
    ["#teamProgress11", 73, "#8b5cf6"],
    ["#teamProgress12", 20, "#f87171"],
  ];
  rings.forEach(function (r) { makeProgressRing(r[0], r[1], r[2]); });
})();


// ================================ Working Schedule - Functional Calendar ================================
(function () {
  var calEl = document.getElementById("scheduleCalendar");
  var labelEl = document.getElementById("scheduleMonthLabel");
  var prevBtn = document.getElementById("schedulePrevMonth");
  var nextBtn = document.getElementById("scheduleNextMonth");
  if (!calEl || !labelEl) return;

  var MONTHS = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December",
  ];

  var today = new Date();
  var view = new Date(today.getFullYear(), today.getMonth(), 1);

  function pad(n) {
    return n < 10 ? "0" + n : "" + n;
  }

  function sameDay(y, m, d) {
    return (
      y === today.getFullYear() &&
      m === today.getMonth() &&
      d === today.getDate()
    );
  }

  function render() {
    var year = view.getFullYear();
    var month = view.getMonth();

    labelEl.innerHTML =
      MONTHS[month] + " " + year + ' <i class="ph ph-caret-down text-sm"></i>';

    // clear previous day cells (keep the 7 weekday headers)
    var olds = calEl.querySelectorAll(".cal-day");
    for (var i = 0; i < olds.length; i++) olds[i].remove();

    var firstWeekday = new Date(year, month, 1).getDay(); // 0 = Sun
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var daysInPrev = new Date(year, month, 0).getDate();

    var cells = [];
    // leading days from previous month
    for (var l = firstWeekday - 1; l >= 0; l--) {
      cells.push({ day: daysInPrev - l, muted: true });
    }
    // current month days
    for (var d = 1; d <= daysInMonth; d++) {
      cells.push({ day: d, muted: false, today: sameDay(year, month, d) });
    }
    // trailing days from next month to complete the last week
    var trailing = 1;
    while (cells.length % 7 !== 0) {
      cells.push({ day: trailing++, muted: true });
    }

    var frag = document.createDocumentFragment();
    cells.forEach(function (cell) {
      var span = document.createElement("span");
      span.className = "cal-day" + (cell.muted ? " is-muted" : "") + (cell.today ? " is-active" : "");
      span.textContent = pad(cell.day);
      frag.appendChild(span);
    });
    calEl.appendChild(frag);
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", function () {
      view.setMonth(view.getMonth() - 1);
      render();
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener("click", function () {
      view.setMonth(view.getMonth() + 1);
      render();
    });
  }

  render();
})();


// ================================ Projects Analysis - Grouped Column Chart ================================
var projectsAnalysisChart;
(function () {
  var el = document.querySelector("#projectsAnalysisChart");
  if (!el) return;
  projectsAnalysisChart = new ApexCharts(el, {
    series: [
      { name: "Projects", data: [315, 325, 295, 325, 385, 295, 275] },
      { name: "Progress", data: [215, 185, 195, 230, 285, 200, 185] },
      { name: "Tasks", data: [150, 230, 200, 155, 190, 140, 120] },
      { name: "Revenue", data: [95, 75, 100, 95, 35, 90, 60] },
    ],
    chart: {
      type: "bar",
      height: 440,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [primaryColor, "#22c55e", "#facc15", "#f87171"],
    plotOptions: {
      bar: { columnWidth: "60%", borderRadius: 4, borderRadiusApplication: "end" },
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
  projectsAnalysisChart.render();
})();


// ================================ To Do List - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#toDoListTable");
  if (!el) return;
  var dt = $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: [0, 5] }],
  });

  // Header "select all" checkbox
  var checkAll = document.getElementById("toDoCheckAll");
  if (checkAll) {
    checkAll.addEventListener("change", function () {
      var boxes = el.querySelectorAll("tbody input[type='checkbox']");
      boxes.forEach(function (b) { b.checked = checkAll.checked; });
    });
  }

  var denseSwitch = document.getElementById("toDoListDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});


// ================================================================================================
//  Extend updateChartColors for Projects Analysis
// ================================================================================================
(function () {
  var prev = (typeof updateChartColors === "function") ? updateChartColors : null;
  updateChartColors = function (newColor) {
    if (prev) prev(newColor);
    if (projectsAnalysisChart) {
      projectsAnalysisChart.updateOptions({
        colors: [newColor, "#22c55e", "#facc15", "#f87171"],
      });
    }
  };
})();
