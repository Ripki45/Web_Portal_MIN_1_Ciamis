// ================================ Total Revenue - Bar chart with one highlighted bar ================================
var barChartOptions = {
  series: [
    {
      data: [20, 30, 25, 35, 90, 25],
    },
  ],
  chart: {
    type: "bar",
    height: 80,
    width: 130,
    sparkline: { enabled: true },
  },
  plotOptions: {
    bar: {
      columnWidth: "65%",
      borderRadius: 3,
      distributed: true,
    },
  },
  colors: [
    trackColor,
    trackColor,
    trackColor,
    trackColor,
    primaryColor,
    trackColor,
  ],
  dataLabels: { enabled: false },
  tooltip: { enabled: false },
};
var barChart = new ApexCharts(
  document.querySelector("#barChart"),
  barChartOptions,
);
barChart.render();

// ================================ Total Orders - Area sparkline ================================
var lineChartOptions = {
  series: [
    {
      data: [30, 45, 32, 50, 40, 60, 38, 55, 42, 58, 35, 48, 52, 40, 46],
    },
  ],
  chart: {
    type: "area",
    height: 80,
    sparkline: { enabled: true },
  },
  stroke: {
    curve: "smooth",
    width: 2,
  },
  fill: {
    type: "gradient",
    gradient: {
      opacityFrom: 0.4,
      opacityTo: 0,
    },
  },
  colors: [primaryColor],
  tooltip: { enabled: true },
};
var lineChart = new ApexCharts(
  document.querySelector("#lineChart"),
  lineChartOptions,
);
lineChart.render();

// ================================ Conversion Rate - Radial bar ================================
var radialChartOptions = {
  series: [35.5],
  chart: {
    type: "radialBar",
    height: 90,
    width: 90,
    sparkline: { enabled: true },
  },
  plotOptions: {
    radialBar: {
      startAngle: 0,
      endAngle: 360,
      hollow: {
        size: "70%",
      },
      track: {
        background: trackColor,
        strokeWidth: "100%",
        margin: 0,
      },
      dataLabels: {
        name: { show: false },
        value: {
          fontSize: "12px",
          fontWeight: 500,
          color: primaryColor,
          offsetY: 0,
          show: true,
          formatter: function (val) {
            return val + "%";
          },
        },
      },
    },
  },
  fill: {
    colors: [primaryColor],
  },
  stroke: {
    lineCap: "round",
  },
  labels: ["Conversion Rate"],
};
var radialChart = new ApexCharts(
  document.querySelector("#radialChart"),
  radialChartOptions,
);
radialChart.render();

// Second "Conversion Rate" card — same radial gauge, unique id (avoids duplicate id).
var radialChart2El = document.querySelector("#radialChart2");
if (radialChart2El) {
  new ApexCharts(radialChart2El, radialChartOptions).render();
}

// ================================ Total Customers - Column sparkline with alternating opacity ================================
var columnChartOptions = {
  series: [
    {
      data: [60, 40, 75, 35, 90, 50, 70],
    },
  ],
  chart: {
    type: "bar",
    height: 80,
    sparkline: { enabled: true },
  },
  plotOptions: {
    bar: {
      columnWidth: "40%",
      borderRadius: 3,
    },
  },
  colors: [primaryColor],
  fill: {
    opacity: [1, 0.3, 1, 0.3, 1, 0.3, 1],
  },
  dataLabels: { enabled: false },
  tooltip: { enabled: false },
};
var columnChart = new ApexCharts(
  document.querySelector("#columnChart"),
  columnChartOptions,
);
columnChart.render();
// ========================== Home One chart js end ======================================

// ================================ Delivery Status - Stacked Bar (Horizontal Progress) ================================
var deliveryBarChart = new ApexCharts(
  document.querySelector("#deliveryBarChart"),
  {
    series: [
      { name: "On-Time Delivery", data: [25] },
      { name: "Delivered", data: [45] },
      { name: "In Transit", data: [15] },
      { name: "Delayed", data: [15] },
    ],
    chart: {
      type: "bar",
      height: 70,
      stacked: true,
      stackType: "100%",
      sparkline: { enabled: true },
    },
    plotOptions: {
      bar: {
        horizontal: true,
        barHeight: "16px",
        borderRadius: 4,
        borderRadiusWhenStacked: "all",
        borderRadiusApplication: "around",
      },
    },
    colors: ["#00B8D9", "#22C55E", "#FDC70F", "#F6776E"],
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        return Math.round(val) + "%";
      },
      style: {
        fontSize: "18px",
        fontWeight: 500,
        colors: ["#434956"],
      },
      offsetY: -28, // ✅ moves labels above the bar
      distributed: false,
    },
    xaxis: { labels: { show: false } },
    yaxis: { labels: { show: false } },
    grid: { show: false },
    legend: { show: false },
    tooltip: { enabled: false },
  },
);
// #deliveryBarChart has no markup on this page — guard so a missing element
// doesn't throw and halt every chart declared after it.
if (document.querySelector("#deliveryBarChart")) deliveryBarChart.render();

// ================================ Returning Clients - Area Chart ================================
var returningClientsChartOptions = {
  series: [
    {
      name: "Retention",
      data: [60, 75, 55, 80, 65, 85, 70, 90, 75, 85],
    },
  ],
  chart: {
    type: "area",
    height: 180,
    sparkline: { enabled: true },
    toolbar: { show: false },
  },
  stroke: {
    curve: "smooth",
    width: 2,
  },
  fill: {
    type: "gradient",
    gradient: {
      shade: "light",
      type: "vertical",
      shadeIntensity: 0.4,
      gradientToColors: ["#ffffff"],
      opacityFrom: 0.6,
      opacityTo: 0.05,
      stops: [0, 100],
    },
  },
  colors: [primaryColor],
  dataLabels: { enabled: false },
  tooltip: { enabled: true },
};
var returningClientsChart = new ApexCharts(
  document.querySelector("#returningClientsChart"),
  returningClientsChartOptions,
);
returningClientsChart.render();

// ================================ Sales Overview - Dual Line Chart ================================
// ================================ Sales Overview - Dual Line Chart ================================
var salesOverviewChart = new ApexCharts(
  document.querySelector("#salesOverviewChart"),
  {
    series: [
      {
        name: "This Year",
        data: [
          75, 75, 68, 38, 36, 38, 70, 70, 60, 58, 58, 45, 42, 18, 18, 18, 50,
        ],
      },
      {
        name: "Last Year",
        data: [
          33, 33, 50, 62, 62, 60, 18, 18, 60, 60, 50, 50, 33, 33, 80, 80, 80,
        ],
      },
    ],
    chart: {
      type: "line",
      height: 290,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    stroke: {
      curve: "smooth",
      width: [2, 2],
    },
    colors: [primaryColor, "#eab308"],
    xaxis: {
      categories: [
        "01 Jan",
        "02 Jan",
        "03 Jan",
        "04 Jan",
        "05 Jan",
        "06 Jan",
        "06 Jan",
        "07 Jan",
        "08 Jan",
        "09 Jan",
        "10 Jan",
        "11 Jan",
        "12 Jan",
        "12 Jan",
        "12 Jan",
        "12 Jan",
        "12 Jan",
      ],
      tickAmount: 6,
      labels: {
        style: { fontSize: "11px", colors: "#9ca3af" },
        formatter: function (val) {
          return val;
        },
      },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: {
      labels: { style: { fontSize: "11px", colors: "#9ca3af" } },
      min: 0,
      max: 100,
      tickAmount: 5,
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: false } },
      yaxis: { lines: { show: true } },
    },
    legend: { show: false },
    markers: {
      size: 0,
      strokeWidth: 2,
      hover: { size: 5 },
      discrete: [
        {
          seriesIndex: 0,
          dataPointIndex: 6,
          fillColor: primaryColor,
          strokeColor: "#fff",
          size: 5,
        },
        {
          seriesIndex: 0,
          dataPointIndex: 13,
          fillColor: primaryColor,
          strokeColor: "#fff",
          size: 5,
        },
        {
          seriesIndex: 0,
          dataPointIndex: 16,
          fillColor: primaryColor,
          strokeColor: "#fff",
          size: 5,
        },
        {
          seriesIndex: 1,
          dataPointIndex: 3,
          fillColor: "#eab308",
          strokeColor: "#fff",
          size: 5,
        },
        {
          seriesIndex: 1,
          dataPointIndex: 9,
          fillColor: "#eab308",
          strokeColor: "#fff",
          size: 5,
        },
        {
          seriesIndex: 1,
          dataPointIndex: 14,
          fillColor: "#eab308",
          strokeColor: "#fff",
          size: 5,
        },
      ],
    },
    annotations: {
      xaxis: [
        {
          x: "06 Jan",
          borderColor: "#00b8f2",
          strokeDashArray: 4,
          borderWidth: 1.5,
        },
      ],
    },
    tooltip: { shared: true },
  },
);
salesOverviewChart.render();

// ================================ Order Summary - Donut Chart ================================
var orderSummaryChart = new ApexCharts(
  document.querySelector("#orderSummaryChart"),
  {
    series: [45, 30, 25],
    chart: { type: "donut", height: 280 },
    colors: [primaryColor, "#00b8f2", "#f87171"],
    labels: ["Completed", "New Order", "Pending"],
    plotOptions: {
      pie: {
        donut: {
          size: "65%",
          labels: {
            show: true,
            total: {
              show: true,
              label: "Total Orders",
              fontSize: "12px",
              color: "#9ca3af",
              formatter: () => "4.5K",
            },
            value: { show: false },
          },
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: (val) => Math.round(val) + "%",
      style: { fontSize: "12px", fontWeight: 500, colors: ["#fff"] },
      dropShadow: { enabled: false },
    },
    legend: { show: false },
    stroke: { width: 2 },
  },
);
orderSummaryChart.render();

// ================================ Revenue by Category - Half Donut ================================
var revenueByCategoryChart = new ApexCharts(
  document.querySelector("#revenueByCategoryChart"),
  {
    series: [30, 20, 15, 12, 13, 10],
    chart: { type: "donut", height: 320 },
    colors: [
      primaryColor,
      "#f87171",
      "#00b8f2",
      "#a855f7",
      "#22c55e",
      "#eab308",
    ],
    labels: [
      "Fashion",
      "Beauty",
      "Medical",
      "Sports",
      "Electronics",
      "Furniture",
    ],
    plotOptions: {
      pie: {
        startAngle: -90,
        endAngle: 90,
        offsetY: 60,
        donut: {
          size: "65%",
          labels: {
            show: false,
            total: {
              show: true,
              label: "Total Products",
              fontSize: "12px",
              color: "#9ca3af",
              formatter: () => "25.59K",
            },
            value: { show: false },
          },
        },
      },
    },
    dataLabels: { enabled: false },
    legend: { show: false },
    stroke: { width: 2 },
    grid: { padding: { bottom: -80 } },
  },
);
revenueByCategoryChart.render();

// ================================ Customer Growth - Area Chart ================================
var customerGrowthChart = new ApexCharts(
  document.querySelector("#customerGrowthChart"),
  {
    series: [
      {
        name: "Customers",
        data: [
          18000, 20500, 19800, 20200, 23000, 24500, 24000, 21000, 21500, 20800,
          21200, 26000, 27500, 27000, 29000, 28500, 27800, 28200, 29500, 30000,
          30200,
        ],
      },
    ],
    chart: {
      type: "area",
      height: 270,
      toolbar: { show: false },
      zoom: { enabled: false },
      fontFamily: "inherit",
    },
    stroke: {
      curve: "straight",
      width: 2,
      colors: [primaryColor],
    },
    fill: {
      type: "gradient",
      gradient: {
        shade: "light",
        type: "vertical",
        shadeIntensity: 0.3,
        gradientToColors: ["#ffffff"],
        opacityFrom: 0.95,
        opacityTo: 0.02,
        stops: [0, 100],
      },
      colors: [primaryColor],
    },
    colors: [primaryColor],
    dataLabels: { enabled: false },
    markers: { size: 0 },
    xaxis: {
      categories: [
        "Jan",
        "Jan",
        "Jan",
        "Jan",
        "Jan",
        "Jan",
        "Jan",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
        "Feb",
      ],
      labels: {
        style: {
          colors: "#94a3b8",
          fontSize: "12px",
          fontWeight: 400,
        },
      },
      axisBorder: { show: false },
      axisTicks: { show: false },
      tickAmount: 10,
      tooltip: { enabled: false },
    },
    yaxis: {
      min: 0,
      max: 30000,
      tickAmount: 6,
      labels: {
        formatter: function (val) {
          if (val === 0) return "0";
          return (val / 1000).toFixed(0) + "\nk";
        },
        style: {
          colors: "#94a3b8",
          fontSize: "12px",
          fontWeight: 400,
        },
        offsetX: -4,
      },
    },
    grid: {
      borderColor: "#ffffff00",
      strokeDashArray: 0,
      xaxis: { lines: { show: true } },
      yaxis: { lines: { show: true } },
      padding: { top: 0, right: 0, bottom: 0, left: 10 },
    },
    tooltip: {
      theme: "light",
      y: {
        formatter: function (val) {
          return (val / 1000).toFixed(1) + "k customers";
        },
      },
    },
    legend: { show: false },
  },
);
customerGrowthChart.render();

// ================================================================================================
//  Update all charts primary color ✅ ONE merged function — replaces BOTH previous updateChartColors definitions
// ================================================================================================
function updateChartColors(newColor) {
  barChart.updateOptions({
    colors: [
      trackColor,
      trackColor,
      trackColor,
      trackColor,
      newColor,
      trackColor,
    ],
  });

  lineChart.updateOptions({
    colors: [newColor],
  });

  radialChart.updateOptions({
    fill: { colors: [newColor] },
    plotOptions: {
      radialBar: {
        dataLabels: {
          value: { color: newColor },
        },
      },
    },
  });

  columnChart.updateOptions({
    colors: [newColor],
  });

  returningClientsChart.updateOptions({
    colors: [newColor],
  });

  salesOverviewChart.updateOptions({
    colors: [newColor, "#eab308"],
    markers: { fillColors: [newColor, "#eab308"] },
  });
  orderSummaryChart.updateOptions({ colors: [newColor, "#00b8f2", "#f87171"] });
  revenueByCategoryChart.updateOptions({
    colors: [newColor, "#f87171", "#00b8f2", "#a855f7", "#22c55e", "#eab308"],
  });

  customerGrowthChart.updateOptions({
    stroke: {
      colors: [newColor],
    },
    fill: {
      colors: [newColor],
    },
    colors: [newColor],
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

// ================================ Payment Methods - Donut Chart ================================
var paymentMethodsChart = new ApexCharts(
  document.querySelector("#paymentMethodsChart"),
  {
    series: [30, 25, 15, 20, 10],
    chart: {
      type: "polarArea",
      height: 380,
      toolbar: { show: false },
    },
    labels: ["Visa", "Strips", "Google Pay", "PayPal", "Apple Pay"],
    colors: ["#4F46E5", "#FDC70F", "#F6776E", "#00B8D9", "#22C55E"],
    fill: {
      opacity: 1,
    },
    stroke: {
      width: 0,
      colors: ["#fff"],
    },
    plotOptions: {
      polarArea: {
        rings: {
          strokeWidth: 0,
        },
        spokes: {
          strokeWidth: 0,
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: function (val, opts) {
        return opts.w.globals.labels[opts.seriesIndex];
      },
      style: {
        fontSize: "16px",
        fontWeight: 500,
        colors: ["#333"],
      },
      background: {
        enabled: false,
      },
      dropShadow: {
        enabled: false,
      },
    },
    legend: {
      show: true,
      position: "bottom",
      horizontalAlign: "center",
      fontSize: "13px",
      fontWeight: 500,
      markers: {
        width: 10,
        height: 10,
        radius: 50,
      },
      itemMargin: {
        horizontal: 10,
        vertical: 4,
      },
      labels: {
        colors: "#6B7280",
      },
    },
    yaxis: {
      show: false,
    },
    xaxis: {
      labels: { show: false },
    },
    grid: {
      show: false,
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return val + "%";
        },
      },
    },
    theme: {
      monochrome: { enabled: false },
    },
  },
);
paymentMethodsChart.render();

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