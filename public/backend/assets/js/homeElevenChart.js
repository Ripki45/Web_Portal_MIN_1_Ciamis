// ================================ Stat card sparklines ================================
function makeStatSpark(selector, data, color) {
  var el = document.querySelector(selector);
  if (!el) return null;
  var chart = new ApexCharts(el, {
    series: [{ name: "Value", data: data }],
    chart: {
      type: "line",
      height: 48,
      width: 90,
      sparkline: { enabled: true },
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: [color],
    stroke: { curve: "smooth", width: 2.5 },
    markers: { size: 0 },
    dataLabels: { enabled: false },
    tooltip: { enabled: false },
    grid: { show: false },
  });
  chart.render();
  return chart;
}

var revenueSpark = makeStatSpark("#revenueSpark", [20, 24, 22, 28, 25, 32, 30, 36, 34, 41], primaryColor);
makeStatSpark("#artworksSpark", [15, 20, 18, 24, 22, 28, 26, 32, 30, 37], "#22c55e");
makeStatSpark("#auctionSpark", [40, 30, 36, 26, 32, 24, 28, 20, 26, 16], "#facc15");
makeStatSpark("#creatorsSpark", [18, 26, 20, 30, 24, 34, 28, 36, 30, 42], "#f87171");


// ================================ Marketplace - Grouped Bar Chart ================================
var marketplaceChart;
(function () {
  var el = document.querySelector("#marketplaceChart");
  if (!el) return;
  marketplaceChart = new ApexCharts(el, {
    series: [
      { name: "Artworks", data: [20000, 15000, 14000, 22000, 47000, 13000, 20000, 10000, 22000, 45000, 15000, 20000] },
      { name: "Auction", data: [12000, 14000, 12000, 15000, 34000, 13000, 14000, 9000, 12000, 37000, 11000, 14000] },
    ],
    chart: {
      type: "bar",
      height: 300,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: ["#7c5cfc", "#facc15"],
    plotOptions: {
      bar: { columnWidth: "45%", borderRadius: 4, borderRadiusApplication: "end" },
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
  marketplaceChart.render();
})();


// ================================================================================================
//  Update all charts primary color — ONE unified function
// ================================================================================================
function updateChartColors(newColor) {
  if (revenueSpark) {
    revenueSpark.updateOptions({ colors: [newColor] });
  }
  if (marketplaceChart) {
    marketplaceChart.updateOptions({ colors: [newColor, "#facc15"] });
  }
}


// ================================ Trending Bids - Slick slider + Bootstrap tabs ================================
$(document).ready(function () {
  if (typeof $ === "undefined" || !$.fn || !$.fn.slick) return;

  function initTrendingSlider($el) {
    if (!$el.length || $el.hasClass("slick-initialized")) return;
    $el.slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      dots: true,
      arrows: false,
      infinite: true,
      speed: 500,
      responsive: [
        { breakpoint: 1400, settings: { slidesToShow: 3 } },
        { breakpoint: 992, settings: { slidesToShow: 2 } },
        { breakpoint: 576, settings: { slidesToShow: 1 } },
      ],
    });
  }

  // Initialise the slider inside the initially active tab pane.
  $(".tab-pane.active .trending-bids-slider").each(function () {
    initTrendingSlider($(this));
  });

  // Lazy-init + fix layout when a bid tab becomes visible.
  $('.bid-tabs button[data-bs-toggle="pill"]').on("shown.bs.tab", function (e) {
    var target = $(e.target).attr("data-bs-target");
    $(target)
      .find(".trending-bids-slider")
      .each(function () {
        initTrendingSlider($(this));
        $(this).slick("setPosition");
      });
  });
});


// ================================ Recent Exclusive NFTs - DataTable ================================
$(document).ready(function () {
  if (typeof $.fn === "undefined" || !$.fn.DataTable) return;
  var el = document.querySelector("#recentNftTable");
  if (!el) return;
  $(el).DataTable({
    ordering: true,
    info: false,
    searching: false,
    paging: false,
    dom: "t",
    order: [],
    columnDefs: [{ orderable: false, targets: 6 }], // Action column
  });

  var denseSwitch = document.getElementById("recentNftDenseSwitch");
  if (denseSwitch) {
    denseSwitch.addEventListener("change", function () {
      el.classList.toggle("table-dense", denseSwitch.checked);
    });
  }
});


// ================================ Countdown timers ================================
(function () {
  function pad(n) {
    return n < 10 ? "0" + n : "" + n;
  }

  // Breaks a millisecond remainder into d / h / m / s.
  function breakdown(ms) {
    var total = Math.max(0, Math.floor(ms / 1000));
    return {
      days: Math.floor(total / 86400),
      hours: Math.floor((total % 86400) / 3600),
      minutes: Math.floor((total % 3600) / 60),
      seconds: total % 60,
    };
  }

  // ---- NFT detail card ("Trendy Fashion Portraits") ----
  var nft = document.getElementById("nftAuctionCountdown");
  if (nft) {
    var fields = {
      days: nft.querySelector('[data-cd="days"]'),
      hours: nft.querySelector('[data-cd="hours"]'),
      minutes: nft.querySelector('[data-cd="minutes"]'),
      seconds: nft.querySelector('[data-cd="seconds"]'),
    };
    var startMs =
      ((parseInt(nft.dataset.days, 10) || 0) * 86400 +
        (parseInt(nft.dataset.hours, 10) || 0) * 3600 +
        (parseInt(nft.dataset.minutes, 10) || 0) * 60 +
        (parseInt(nft.dataset.seconds, 10) || 0)) *
      1000;
    var nftTarget = Date.now() + startMs;

    var renderNft = function () {
      var t = breakdown(nftTarget - Date.now());
      fields.days.textContent = t.days;
      fields.hours.textContent = pad(t.hours);
      fields.minutes.textContent = pad(t.minutes);
      fields.seconds.textContent = pad(t.seconds);
    };
    renderNft();
    setInterval(renderNft, 1000);
  }

  // ---- Trending bid cards (auction timers) — loop when they hit zero ----
  var timers = document.querySelectorAll(".trending-bid-card__timer");
  if (timers.length) {
    var DURATION = (2 * 3600 + 45 * 60 + 12) * 1000; // 02hrs : 45m : 12s
    var bidTarget = Date.now() + DURATION;

    var renderBids = function () {
      var remaining = bidTarget - Date.now();
      if (remaining <= 0) {
        bidTarget = Date.now() + DURATION; // restart the auction clock
        remaining = DURATION;
      }
      var t = breakdown(remaining);
      var label = pad(t.hours) + "hrs : " + pad(t.minutes) + "m : " + pad(t.seconds) + "s";
      for (var i = 0; i < timers.length; i++) {
        timers[i].textContent = label;
      }
    };
    renderBids();
    setInterval(renderBids, 1000);
  }
})();
