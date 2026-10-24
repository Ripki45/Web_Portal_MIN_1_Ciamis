// ================================ Crypto Overview - sparklines ================================
function makeCryptoSpark(selector, data, color) {
  var el = document.querySelector(selector);
  if (!el) return null;
  var chart = new ApexCharts(el, {
    series: [{ data: data }],
    chart: {
      type: "line",
      height: 50,
      width: 100,
      sparkline: { enabled: true },
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    stroke: { curve: "straight", width: 2.5, colors: [color] },
    colors: [color],
    markers: { size: 0 },
    dataLabels: { enabled: false },
    tooltip: { enabled: false },
    grid: { show: false },
  });
  chart.render();
  return chart;
}

var cryptoAdaChart = makeCryptoSpark("#cryptoAdaChart", [18, 22, 19, 24, 21, 27, 25, 30, 28, 34], primaryColor);
makeCryptoSpark("#cryptoEthChart", [15, 18, 16, 20, 19, 24, 22, 27, 25, 30], "#22c55e");
makeCryptoSpark("#cryptoUsdtChart", [30, 26, 28, 22, 25, 20, 24, 18, 21, 16], "#ef4444");
makeCryptoSpark("#cryptoBtcChart", [26, 30, 24, 28, 22, 26, 20, 24, 18, 22], "#a16207");


// ================================ Crypto Statistics - Candlestick Chart ================================
var cryptoStatsChart;
(function () {
  var el = document.querySelector("#cryptoStatsChart");
  if (!el) return;

  // Deterministic pseudo-random OHLC walk that dips mid-day then recovers
  var seed = 73;
  function rnd() {
    seed = (seed * 9301 + 49297) % 233280;
    return seed / 233280;
  }
  function r2(n) { return Math.round(n * 100) / 100; }

  var start = Date.UTC(2024, 9, 6, 0, 0, 0);
  var data = [];
  for (var i = 0; i < 24; i++) {
    var mid = 6610 + Math.sin((i / 23) * Math.PI) * -22;
    var o = mid + (rnd() - 0.5) * 14;
    var c = mid + (rnd() - 0.5) * 14;
    var h = Math.max(o, c) + rnd() * 8;
    var l = Math.min(o, c) - rnd() * 8;
    data.push({ x: start + i * 3600 * 1000, y: [r2(o), r2(h), r2(l), r2(c)] });
  }

  cryptoStatsChart = new ApexCharts(el, {
    series: [{ data: data }],
    chart: {
      type: "candlestick",
      height: 390,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    plotOptions: {
      candlestick: {
        colors: { upward: "#22d3ee", downward: primaryColor },
        wick: { useFillColor: true },
      },
    },
    xaxis: {
      type: "datetime",
      tickAmount: 5,
      labels: {
        datetimeUTC: true,
        format: "dd MMM HH:mm",
        style: { fontSize: "11px", colors: "#9ca3af" },
      },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      min: 6560,
      max: 6660,
      tickAmount: 5,
      labels: {
        formatter: function (val) { return val.toFixed(2); },
        style: { fontSize: "11px", colors: "#9ca3af" },
      },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: false } },
      yaxis: { lines: { show: true } },
    },
  });
  cryptoStatsChart.render();
})();


// ================================ Assets Allocation - Donut Chart ================================
(function () {
  var el = document.querySelector("#assetsAllocationDonut");
  if (!el) return;
  // Clockwise from top: red, yellow, cyan, green
  new ApexCharts(el, {
    series: [20, 35, 15, 30],
    labels: ["Ethereum", "Bitcoin", "Litecoin", "Uniswap"],
    chart: { type: "donut", height: 300 },
    colors: ["#f87171", "#facc15", "#06b6d4", "#22c55e"],
    stroke: { width: 3, colors: ["#fff"] },
    dataLabels: { enabled: false },
    legend: { show: false },
    plotOptions: { pie: { donut: { size: "76%" } } },
    tooltip: { y: { formatter: function (val) { return val + "%"; } } },
  }).render();
})();


// ================================================================================================
//  Update all charts primary color — ONE unified function
// ================================================================================================
function updateChartColors(newColor) {
  if (cryptoAdaChart) {
    cryptoAdaChart.updateOptions({ colors: [newColor], stroke: { colors: [newColor] } });
  }
  if (cryptoStatsChart) {
    cryptoStatsChart.updateOptions({
      plotOptions: { candlestick: { colors: { upward: "#22d3ee", downward: newColor } } },
    });
  }
}


// ================================ Daily Average Overview - progress rings ================================
(function () {
  function makeAvgRing(selector, value, color) {
    var el = document.querySelector(selector);
    if (!el) return;
    new ApexCharts(el, {
      series: [value],
      chart: { type: "radialBar", height: 90, width: 90, sparkline: { enabled: true } },
      colors: [color],
      plotOptions: {
        radialBar: {
          hollow: { size: "55%" },
          track: { background: "#eef1f6", strokeWidth: "100%" },
          dataLabels: {
            name: { show: false },
            value: { show: true, offsetY: 5, fontSize: "13px", fontWeight: 700, color: "#1f2937", formatter: function (v) { return v + "%"; } },
          },
        },
      },
      stroke: { lineCap: "round", dashArray: 3 },
    }).render();
  }

  makeAvgRing("#avgSalesRing", 53, "#6366f1");
  makeAvgRing("#avgReturnsRing", 82, "#b8860b");
  makeAvgRing("#avgProfitRing", 35, "#14b8a6");
  makeAvgRing("#avgOrdersRing", 67, "#22c55e");
})();


// ================================ Crypto Market Overview - row sparklines + DataTable ================================
(function () {
  function makeMarketSpark(selector, data, color) {
    var el = document.querySelector(selector);
    if (!el) return;
    new ApexCharts(el, {
      series: [{ data: data }],
      chart: { type: "line", height: 44, width: 100, sparkline: { enabled: true }, animations: { enabled: false } },
      stroke: { curve: "straight", width: 2, colors: [color] },
      colors: [color],
      markers: { size: 0 },
      dataLabels: { enabled: false },
      tooltip: { enabled: false },
      grid: { show: false },
    }).render();
  }

  var green = "#22c55e";
  var red = "#ef4444";
  makeMarketSpark("#marketGraph1", [12, 18, 14, 22, 17, 25, 20, 28], green);
  makeMarketSpark("#marketGraph2", [14, 12, 20, 16, 24, 18, 26, 22], green);
  makeMarketSpark("#marketGraph3", [26, 20, 24, 18, 22, 16, 20, 14], red);
  makeMarketSpark("#marketGraph4", [24, 22, 26, 18, 22, 16, 19, 15], red);
  makeMarketSpark("#marketGraph5", [13, 19, 15, 23, 18, 26, 21, 28], green);
  makeMarketSpark("#marketGraph6", [12, 16, 14, 20, 18, 24, 22, 27], green);
  makeMarketSpark("#marketGraph7", [25, 21, 24, 19, 22, 17, 20, 16], red);
  makeMarketSpark("#marketGraph8", [14, 18, 15, 22, 19, 25, 21, 27], green);
})();

$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#marketOverviewTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: [3, 7] }],
  });

  var denseSwitch = document.getElementById("marketOverviewDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});
