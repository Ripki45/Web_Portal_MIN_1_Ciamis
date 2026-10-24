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



// ================================================================================
//  Portfolio Tabs (Overview) — Portfolio Performance + Asset Allocation
//  Loaded only on index-3.html. `primaryColor` / `getCssVar` come from the
//  inline script in _template-bottom.html.
// ================================================================================
(function () {
  var perfEl = document.querySelector("#portfolioPerformanceChart");
  var allocEl = document.querySelector("#assetAllocationChart");
  if (!perfEl || !allocEl) return; // page doesn't have these cards

  var brand = (typeof primaryColor !== "undefined" && primaryColor) ? primaryColor : "#4F46E5";
  var greenColor = "#22c55e";
  var solColor = "#facc15";
  var othersColor = "#06b6d4";

  // Theme-aware center text colors (read once at load)
  var textPrimary = getCssVar("--text-primary-light") || "#1F2937";
  var textSecondary = getCssVar("--text-secondary-light") || "#6B7280";

  // -------------------------------------------------------------- Portfolio Performance
  var portfolioPerformanceChart = new ApexCharts(perfEl, {
    series: [
      { name: "Portfolio", data: [335, 310, 290, 365, 380, 555, 520] },
      { name: "Benchmark", data: [113, 125, 97, 128, 85, 225, 205] },
    ],
    chart: {
      type: "area",
      height: 360,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [brand, greenColor],
    stroke: { curve: "straight", width: 2 },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.45,
        opacityTo: 0.02,
        stops: [0, 100],
      },
    },
    markers: {
      size: 4,
      strokeWidth: 2,
      strokeColors: "#fff",
      colors: [brand, greenColor],
      hover: { size: 6 },
    },
    dataLabels: { enabled: false },
    xaxis: {
      categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 0,
      max: 600,
      tickAmount: 6,
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
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
      markers: { width: 8, height: 8, radius: 12 },
      itemMargin: { horizontal: 12, vertical: 8 },
      labels: { colors: "#6B7280" },
    },
    tooltip: { shared: true, intersect: false },
  });
  portfolioPerformanceChart.render();

  // -------------------------------------------------------------- Asset Allocation (donut)
  var assetAllocationChart = new ApexCharts(allocEl, {
    series: [48, 12, 25, 15],
    labels: ["BTC", "ETH", "SOL", "Others"],
    chart: { type: "donut", height: 280 },
    colors: [brand, greenColor, solColor, othersColor],
    stroke: { width: 0 },
    dataLabels: { enabled: false },
    legend: { show: false },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
  });
  assetAllocationChart.render();

  // -------------------------------------------------------------- Refresh button
  var refreshBtn = document.getElementById("portfolioRefreshBtn");
  if (refreshBtn) {
    refreshBtn.addEventListener("click", function () {
      var icon = refreshBtn.querySelector("iconify-icon, i");
      if (icon) {
        icon.style.transition = "transform .6s ease";
        icon.style.transform = "rotate(360deg)";
        setTimeout(function () {
          icon.style.transition = "none";
          icon.style.transform = "rotate(0deg)";
        }, 650);
      }
      portfolioPerformanceChart.updateSeries([
        { name: "Portfolio", data: [335, 310, 290, 365, 380, 555, 520] },
        { name: "Benchmark", data: [113, 125, 97, 128, 85, 225, 205] },
      ], true);
      assetAllocationChart.updateSeries([48, 12, 25, 15], true);
    });
  }

  // -------------------------------------------------------------- Color-picker integration
  // Extend (don't replace) the customizer's updateChartColors so these charts
  // also recolor without breaking homeTwoChart.js's own charts.
  var prevUpdate = (typeof updateChartColors === "function") ? updateChartColors : null;
  updateChartColors = function (newColor) {
    if (prevUpdate) prevUpdate(newColor);
    portfolioPerformanceChart.updateOptions({
      colors: [newColor, greenColor],
      markers: { colors: [newColor, greenColor] },
    });
    assetAllocationChart.updateOptions({
      colors: [newColor, greenColor, solColor, othersColor],
    });
    defiYieldChart.updateOptions({
      colors: [newColor]
    });
  };
})();


// ================================================================================
//  Active Trading Bots — mini bar sparklines (index-3 only)
// ================================================================================
(function () {
  if (!document.querySelector("#btcSignalBotChart")) return; // page guard

  function makeBotBars(selector, data, colors) {
    var el = document.querySelector(selector);
    if (!el) return;
    new ApexCharts(el, {
      series: [{ data: data }],
      chart: {
        type: "bar",
        height: 56,
        width: 130,
        sparkline: { enabled: true },
        toolbar: { show: false },
        animations: { enabled: false },
      },
      plotOptions: {
        bar: {
          columnWidth: "60%",
          borderRadius: 2,
          borderRadiusApplication: "end",
          distributed: true,
        },
      },
      colors: colors,
      dataLabels: { enabled: false },
      legend: { show: false },
      tooltip: { enabled: false },
      states: {
        hover: { filter: { type: "none" } },
        active: { filter: { type: "none" } },
      },
      grid: { show: false },
    }).render();
  }

  var cyan = "#22d3ee";
  var indigo = "#6366f1";
  var amber = "#facc15";
  var green = "#22c55e";
  var coral = "#f87171";

  // Card 1 — BTC Signal Bot: teal bars with an indigo spike cluster
  makeBotBars(
    "#btcSignalBotChart",
    [9, 11, 8, 13, 10, 15, 12, 20, 34, 46, 40, 26, 14, 10, 8, 7],
    [cyan, cyan, cyan, cyan, cyan, cyan, cyan, indigo, indigo, indigo, indigo, indigo, cyan, cyan, cyan, cyan]
  );

  // Card 2 — ETH DCA Bot: alternating amber / green
  makeBotBars(
    "#ethDcaBotChart",
    [18, 12, 22, 15, 25, 14, 20, 17, 23, 13, 19, 16, 24, 12, 21, 15],
    [amber, green, amber, green, amber, green, amber, green, amber, green, amber, green, amber, green, amber, green]
  );

  // Card 3 — Arbitrage Bot: rising green bars ending in tall indigo bars
  makeBotBars(
    "#arbitrageBotChart",
    [7, 9, 8, 11, 10, 13, 12, 15, 14, 17, 19, 30, 42],
    [green, green, green, green, green, green, green, green, green, green, green, indigo, indigo]
  );

  // Card 4 — Pump Screener: coral bars
  makeBotBars(
    "#pumpScreenerChart",
    [16, 22, 14, 24, 18, 26, 15, 23, 17, 25, 19, 21, 16, 24, 20],
    [coral, coral, coral, coral, coral, coral, coral, coral, coral, coral, coral, coral, coral, coral, coral]
  );
})();


// ================================================================================
//  Bot Performance Comparison — floating (range) bar chart (index-3, Bot Performance tab)
// ================================================================================
(function () {
  var el = document.querySelector("#botPerformanceChart");
  if (!el) return; // page guard

  var brand = (typeof primaryColor !== "undefined" && primaryColor) ? primaryColor : "#6366f1";
  // Total Bot uses the brand color; the rest are fixed accent colors.
  var botColors = [brand, "#06b6d4", "#facc15", "#f87171", "#22c55e", "#d946ef"];

  var botPerformanceChart = new ApexCharts(el, {
    series: [
      {
        name: "Performance",
        data: [
          { x: "Total Bot", y: [0, 2880] },
          { x: "Signal Bot", y: [1680, 2880] },
          { x: "DCA Bot", y: [1480, 1660] },
          { x: "Arbitrage Bot", y: [1210, 1420] },
          { x: "Grid Bot", y: [280, 1180] },
          { x: "Pump Screener", y: [40, 280] },
        ],
      },
    ],
    chart: {
      type: "rangeBar",
      height: 380,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "55%",
        borderRadius: 0,
        borderRadiusApplication: "around",
        distributed: true,
      },
    },
    colors: botColors,
    dataLabels: { enabled: false },
    legend: { show: false },
    xaxis: {
      type: "category",
      labels: { style: { fontSize: "12px", colors: "#9ca3af" } },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 0,
      max: 3000,
      tickAmount: 6,
      labels: {
        formatter: function (val) {
          return new Intl.NumberFormat("en-US").format(Math.round(val));
        },
        style: { fontSize: "12px", colors: "#9ca3af" },
      },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: false } },
      yaxis: { lines: { show: true } },
    },
    states: {
      hover: { filter: { type: "none" } },
      active: { filter: { type: "none" } },
    },
    tooltip: {
      custom: function (opts) {
        var pt = opts.w.config.series[0].data[opts.dataPointIndex];
        var value = pt.y[1] - pt.y[0];
        return (
          '<div style="padding:8px 12px; font-size:12px;">' +
          "<strong>" + pt.x + "</strong><br>" +
          "Value: " + new Intl.NumberFormat("en-US").format(value) +
          "</div>"
        );
      },
    },
  });

  // The Bot Performance pane is hidden on load — rendering an ApexChart inside a
  // display:none container yields a 0-width chart. Render lazily on first reveal.
  var rendered = false;
  function renderOnce() {
    if (rendered) return;
    rendered = true;
    botPerformanceChart.render();
    botPerformanceChart.updateOptions({ colors: botColors.slice() }, false, false);
  }

  var tabBtn = document.querySelector("#bot-performance-tab");
  if (tabBtn) tabBtn.addEventListener("shown.bs.tab", renderOnce);

  // If the pane happens to be active on load, render right away.
  var pane = document.querySelector("#bot-performance-pane");
  if (pane && pane.classList.contains("active")) renderOnce();

  // -------------------------------------------------------------- Color-picker integration
  var prevUpdate = (typeof updateChartColors === "function") ? updateChartColors : null;
  updateChartColors = function (newColor) {
    if (prevUpdate) prevUpdate(newColor);
    botColors[0] = newColor;
    if (rendered) {
      botPerformanceChart.updateOptions({ colors: botColors.slice() });
    }
  };
})();


// ================================================================================
//  Portfolio Holdings — DataTable (index-3, Portfolio tab)
// ================================================================================
(function () {
  var tableEl = document.querySelector("#portfolioHoldingsTable");
  if (!tableEl || typeof $ === "undefined" || !$.fn || !$.fn.DataTable) return;

  var dt = $(tableEl).DataTable({
    pageLength: 8,
    lengthChange: false,
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [], // keep original order on load
    columnDefs: [
      { orderable: false, targets: 7 }, // Action column not sortable
    ],
  });

  // The Portfolio pane is hidden on load — DataTables computes column widths from
  // the visible viewport, so re-align them the first time the tab is revealed.
  var tabBtn = document.querySelector("#portfolio-pane-tab");
  if (tabBtn) {
    tabBtn.addEventListener("shown.bs.tab", function () {
      dt.columns.adjust();
    });
  }

  // Dense toggle — compact row padding
  var denseSwitch = document.getElementById("portfolioDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      tableEl.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
})();


// ================================================================================
//  DeFi Yield Performance — area chart (index-3, DeFi Analytics tab)
// ================================================================================
(function () {
  var el = document.querySelector("#defiYieldChart");
  if (!el) return; // page guard

  // ~7 points per week across 11 weeks: flat start, dip at W3, volatility, rally from W9.
  var yields = [
    32.5, 32.8, 32.3, 33.0, 32.6, 32.9, 32.4, // W1
    32.7, 33.1, 32.5, 32.0, 31.6, 32.2, 31.8, // W2
    31.2, 30.6, 30.1, 30.5, 30.0, 30.8, 31.3, // W3
    31.8, 32.4, 33.0, 32.6, 33.2, 32.8, 33.4, // W4
    33.0, 32.5, 33.1, 32.7, 32.2, 32.8, 32.4, // W5
    32.0, 31.5, 32.1, 31.7, 32.3, 32.9, 33.3, // W6
    33.6, 34.0, 33.5, 34.1, 33.7, 33.2, 33.8, // W7
    33.4, 32.9, 33.5, 33.0, 33.6, 34.0, 34.4, // W8
    34.8, 35.4, 36.0, 36.6, 37.0, 37.5, 38.0, // W9
    38.3, 38.8, 38.4, 39.0, 38.6, 39.2, 38.9, // W10
    39.3, 39.6, 39.2, 39.8, 39.4, 39.9, 40.1, // W11
  ];
  // Spread the points evenly across the 1..11 week axis.
  var data = yields.map(function (y, i) {
    return { x: 1 + (i * 10) / (yields.length - 1), y: y };
  });

  var defiYieldChart = new ApexCharts(el, {
    series: [{ name: "Yield", data: data }],
    chart: {
      type: "area",
      height: 360,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [primaryColor],
    stroke: { curve: "straight", width: 2 },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.02,
        stops: [0, 100],
      },
    },
    dataLabels: { enabled: false },
    markers: { size: 0, hover: { size: 4 } },
    xaxis: {
      type: "numeric",
      min: 1,
      max: 11,
      tickAmount: 10,
      labels: {
        formatter: function (val) {
          return "W" + Math.round(val);
        },
        style: { fontSize: "12px", colors: "#9ca3af" },
      },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 27,
      max: 42,
      tickAmount: 5,
      labels: {
        formatter: function (val) {
          return val.toFixed(2);
        },
        style: { fontSize: "12px", colors: "#9ca3af" },
      },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: false } },
      yaxis: { lines: { show: true } },
    },
    legend: { show: false },
    tooltip: {
      x: { formatter: function (val) { return "Week " + Math.round(val); } },
      y: { formatter: function (val) { return val.toFixed(2); } },
    },
  });

  // The DeFi Analytics pane is hidden on load — render lazily on first reveal so
  // the chart picks up the real container width.
  var rendered = false;
  function renderOnce() {
    if (rendered) return;
    rendered = true;
    defiYieldChart.render();
  }

  var tabBtn = document.querySelector("#defi-analytics-tab");
  if (tabBtn) tabBtn.addEventListener("shown.bs.tab", renderOnce);

  var pane = document.querySelector("#defi-analytics-pane");
  if (pane && pane.classList.contains("active")) renderOnce();

  // -------------------------------------------------------------- Color-picker integration
  var prevUpdate = (typeof updateChartColors === "function") ? updateChartColors : null;
  updateChartColors = function (newColor) {
    if (prevUpdate) prevUpdate(newColor);
    // DeFi yield line keeps its teal accent; nothing brand-tied here.
  };
})();


// ================================================================================
//  Trade History — DataTable (index-3, Recent Trades tab)
// ================================================================================
(function () {
  var tableEl = document.querySelector("#tradeHistoryTable");
  if (!tableEl || typeof $ === "undefined" || !$.fn || !$.fn.DataTable) return;

  var dt = $(tableEl).DataTable({
    pageLength: 5,
    lengthChange: false,
    ordering: true,
    info: false,
    searching: true, // enabled, but driven by the custom search box below
    paging: false,
    dom: "t",
    order: [],
  });

  // Custom search input in the card header
  var searchInput = document.getElementById("tradeHistorySearch");
  if (searchInput) {
    searchInput.addEventListener("keyup", function () {
      dt.search(this.value).draw();
    });
  }

  // The Recent Trades pane is hidden on load — re-align column widths on reveal.
  var tabBtn = document.querySelector("#recent-trades-tab");
  if (tabBtn) {
    tabBtn.addEventListener("shown.bs.tab", function () {
      dt.columns.adjust();
    });
  }

  // Dense toggle — compact row padding
  var denseSwitch = document.getElementById("tradeDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      tableEl.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
})();
