<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h2 class="h6 fw-semibold mb-0"><?= $pageTitle ?? '' ?></h2>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium"><?= $activePage ?? '' ?></li>
  </ul>
</div>