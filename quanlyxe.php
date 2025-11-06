<?php
session_start();
// Adjust according to your authentication logic
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard · Quản lý xe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link rel="icon" href="img/a1.png" />
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <meta name="robots" content="noindex,nofollow" />
  <style>
    :root {
      --sky: #7dd3fc; /* lighter */
      --sky-600: #38bdf8;
      --sky-50: #ecfeff;
      --text-dark: #0f172a;
      --muted: #64748b;
      --sidebar-w: 260px;
      --radius: 14px;
    }
    body { background: linear-gradient(180deg, var(--sky-50) 0%, #ffffff 40%, #f0f9ff 100%); color: #000; font-family: 'Be Vietnam Pro', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; }
    .layout { display: flex; min-height: 100vh; }
    .sidebar {
      width: var(--sidebar-w);
      background: linear-gradient(160deg, var(--sky) 0%, var(--sky-600) 100%);
      color: #fff;
    }
    .brand { font-weight: 800; letter-spacing: .3px; }
    .sidebar .nav-link { color: #fff; border-radius: 10px; }
    .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,.18); }
    .topbar { background: #fff; border-bottom: 1px solid #e2e8f0; }
    .section-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.06); }
    .table thead th { background: #f8fafc; color: #000; }
    .text-muted { color: #000 !important; opacity: .70; }
    .nav-link, .card, .card-header, .card-body, .badge, .btn { color: #000; }
    @media (max-width: 992px) { .sidebar { display: none; } }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main -->
    <main class="flex-grow-1 d-flex flex-column">
      <?php include 'includes/navbar.php'; ?>

      <!-- Content -->
      <div class="p-3 p-lg-4">
        <!-- Welcome hero -->
        <div class="card hero mb-4">
          <div class="card-body d-flex flex-column flex-lg-row align-items-center justify-content-between">
            <div class="mb-3 mb-lg-0">
              <h2 class="mb-1">Quản lý xe</h2>
              <p class="text-muted mb-0">Quản lý thông tin và trạng thái các xe trong hệ thống</p>
            </div>
            <div>
              <a href="quanlyxe/create.php" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i>Thêm xe mới
              </a>
            </div>
          </div>
        </div>

        <?php
        // Lấy dữ liệu xe ngay từ đầu
        require_once 'functions/quanlyxe_functions.php';
        $cars = getAllCars();
        ?>
        <!-- Statistics cards -->
        <div class="row g-3 g-lg-4 mb-4">
          <?php
          $totalCars = count($cars);
          $availableCars = 0;
          $rentedCars = 0;
          $maintenanceCars = 0;

          foreach($cars as $car) {
              if($car['trang_thai'] === 'Sẵn sàng') $availableCars++;
              if($car['trang_thai'] === 'Đang cho thuê') $rentedCars++;
              if($car['trang_thai'] === 'Bảo trì') $maintenanceCars++;
          }
          ?>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Tổng số xe</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-4 fw-bold"><?= $totalCars ?></div>
                  <div class="kpi-icon"><i class="fa-solid fa-car"></i></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Xe sẵn sàng</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-4 fw-bold text-success"><?= $availableCars ?></div>
                  <div class="kpi-icon bg-success-subtle text-success">
                    <i class="fa-solid fa-check"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Xe đang cho thuê</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-4 fw-bold text-warning"><?= $rentedCars ?></div>
                  <div class="kpi-icon bg-warning-subtle text-warning">
                    <i class="fa-solid fa-key"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Xe đang bảo trì</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-4 fw-bold text-danger"><?= $maintenanceCars ?></div>
                  <div class="kpi-icon bg-danger-subtle text-danger">
                    <i class="fa-solid fa-wrench"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main content card -->
        <div class="card section-card">
          <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0">Danh sách xe</h5>
            <div class="d-flex gap-2">
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                  <i class="fa-solid fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Tìm kiếm xe...">
              </div>
            </div>
          </div>
          <div class="card-body">
            <?php
            // Hiển thị thông báo thành công
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_GET['success']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
            }
            
            // Hiển thị thông báo lỗi
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_GET['error']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
            }
            ?>
            <script>
            // Sau 3 giây sẽ tự động ẩn alert
            setTimeout(() => {
                let alertNode = document.querySelector('.alert');
                if (alertNode) {
                    let bsAlert = new bootstrap.Alert(alertNode);
                    if (bsAlert) {
                        bsAlert.close();
                    }
                }
            }, 3000);
            </script>
            <div class="table-responsive">
              <table class="table table-hover align-middle m-0">
                <thead class="bg-light">
                  <tr>
                    <th scope="col" class="ps-3">ID</th>
                    <th scope="col">Tên xe</th>
                    <th scope="col">Hãng xe</th>
                    <th scope="col">Model</th>
                    <th scope="col">Biển số</th>
                    <th scope="col">Giá thuê/ngày</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col" class="text-end pe-3">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Dữ liệu $cars đã được lấy ở trên
                  foreach($cars as $car){
                  ?>
                      <tr>
                          <td class="ps-3"><?= $car["Id"] ?></td>
                          <td>
                            <div class="d-flex align-items-center gap-2">
                              <div class="car-icon rounded-3 p-2 bg-light">
                                <i class="fa-solid fa-car text-muted"></i>
                              </div>
                              <div>
                                <div class="fw-medium"><?= htmlspecialchars($car["ten_xe"]) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($car["so_ghe"]) ?> chỗ</div>
                              </div>
                            </div>
                          </td>
                          <td><?= htmlspecialchars($car["hang_xe"]) ?></td>
                          <td><?= htmlspecialchars($car["model"]) ?></td>
                          <td>
                            <span class="badge bg-light text-dark border">
                              <?= htmlspecialchars($car["bien_so"]) ?>
                            </span>
                          </td>  
                          <td>
                            <div class="fw-semibold"><?= number_format($car["gia_thue"]) ?>đ</div>
                            <div class="small text-muted">/ ngày</div>
                          </td>
                          <td>
                            <?php 
                                $status = $car["trang_thai"];
                                $badge_class = 'text-bg-secondary';
                                $icon_class = 'fa-solid ';
                                if ($status === 'Sẵn sàng') {
                                    $badge_class = 'text-bg-success';
                                    $icon_class .= 'fa-check';
                                }
                                if ($status === 'Đang cho thuê') {
                                    $badge_class = 'text-bg-warning';
                                    $icon_class .= 'fa-key';
                                }
                                if ($status === 'Bảo trì') {
                                    $badge_class = 'text-bg-danger';
                                    $icon_class .= 'fa-wrench';
                                }
                            ?>
                            <span class="badge <?= $badge_class ?>">
                              <i class="<?= $icon_class ?> me-1"></i>
                              <?= htmlspecialchars($status) ?>
                            </span>
                          </td>
                          <td class="text-end pe-3">
                              <a href="quanlyxe/edit.php?id=<?= $car["Id"] ?>" class="btn btn-light btn-sm">
                                <i class="fa-regular fa-pen-to-square me-1"></i>Sửa
                              </a>
                              <a href="handle/quanlyxe_process.php?action=delete&id=<?= $car["Id"] ?>"
                                  class="btn btn-light btn-sm text-danger"
                                  onclick="return confirm('Bạn có chắc chắn muốn xóa xe này?')">
                                  <i class="fa-regular fa-trash-can me-1"></i>Xóa
                              </a>
                          </td>
                      </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <script>
            // Tìm kiếm
            document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            });
            </script>
          </div>
        </div>
      </div>
          </div>
        </div>
      </div>

      <footer class="mt-auto py-3 text-center text-muted small">
        © <?php echo date('Y'); ?> CarRent Admin
      </footer>
    </main>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>