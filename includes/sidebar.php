<?php 
$currentPage = basename($_SERVER['SCRIPT_FILENAME']); 
$currentDir = basename(dirname($_SERVER['SCRIPT_FILENAME']));
?>
<!-- Sidebar -->
<aside class="sidebar p-3 d-flex flex-column">
  <div class="d-flex align-items-center mb-4 brand">
    <div class="me-2 d-inline-flex align-items-center justify-content-center bg-white bg-opacity-20 rounded-circle" style="width:40px;height:40px;">
      <i class="fa-solid fa-car-side"></i>
    </div>
    <div class="d-flex flex-column lh-1">
      <span>CarRent</span>
      <small class="text-white-50">Admin Panel</small>
    </div>
  </div>
  <div class="text-white-50 fw-semibold mb-2 small">MENU CHÍNH</div>
  <nav class="nav flex-column gap-1">
    <a class="nav-link <?= ($currentPage == 'trangchu.php') ? 'active' : '' ?>" href="/Nhom21PMMNM/trangchu.php"><i class="fa-solid fa-house me-2"></i>Trang chủ</a>
    <a class="nav-link <?= ($currentPage == 'quanlyxe.php' || $currentDir == 'quanlyxe') ? 'active' : '' ?>" href="/Nhom21PMMNM/quanlyxe.php"><i class="fa-solid fa-car me-2"></i>Quản lý xe</a>
    <a class="nav-link" href="#bookings"><i class="fa-solid fa-calendar-check me-2"></i>Đơn đặt</a>
    <a class="nav-link" href="./customers.php"><i class="fa-solid fa-user-group me-2"></i>Khách hàng</a>
    <a class="nav-link" href="./revenue.php"><i class="fa-solid fa-sack-dollar me-2"></i>Doanh thu</a>
    <a class="nav-link" href="#reports"><i class="fa-solid fa-chart-line me-2"></i>Báo cáo</a>
    <a class="nav-link" href="#settings"><i class="fa-solid fa-gear me-2"></i>Cài đặt</a>
    <a class="nav-link" href="../login.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a>
  </nav>
  <div class="mt-auto small text-white-50">© <?php echo date('Y'); ?> CarRent</div>
</aside>