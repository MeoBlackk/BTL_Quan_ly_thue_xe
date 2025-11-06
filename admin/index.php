<?php
session_start();

// Adjust according to your authentication logic

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý Thuê Xe Tự Lái - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link rel="icon" href="../img/a1.png" />
  <meta name="robots" content="noindex,nofollow" />
  <style>
    :root {
      --sky: #0ea5e9; /* sky-500 */
      --sky-600: #0284c7;
      --sky-50: #f0f9ff;
      --text-dark: #0f172a;
      --muted: #64748b;
      --sidebar-w: 260px;
      --radius: 14px;
    }
    body { background: var(--sky-50); color: var(--text-dark); }
    .layout { display: flex; min-height: 100vh; }
    .sidebar {
      width: var(--sidebar-w);
      background: linear-gradient(135deg, var(--sky) 0%, var(--sky-600) 100%);
      color: #fff;
    }
    .brand { font-weight: 800; letter-spacing: .3px; }
    .sidebar .nav-link { color: #e0f2fe; border-radius: 10px; }
    .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,.14); }
    .topbar { background: #fff; border-bottom: 1px solid #e2e8f0; }
    .chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; background: #e0f2fe; color: #0369a1; font-weight: 600; font-size: 12px; }
    .metric-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.08); }
    .metric-icon { width: 50px; height: 50px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; background: #e0f2fe; color: #0284c7; }
    .section-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.06); }
    .table thead th { background: #f8fafc; }
    .shortcut { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px; }
    .shortcut a { text-decoration: none; padding: 12px; border-radius: 12px; background: #fff; border: 1px solid #e2e8f0; color: var(--text-dark); font-weight: 600; }
    .shortcut a:hover { border-color: var(--sky); color: var(--sky-600); box-shadow: 0 8px 16px rgba(2,132,199,.08); }
    .hero { border: 0; border-radius: 16px; background: linear-gradient(145deg, #e0f2fe 0%, #ffffff 60%); box-shadow: inset 0 1px 0 rgba(255,255,255,.6), 0 10px 22px rgba(2,132,199,.12); }
    .hero h2 { font-weight: 800; color: #0ea5e9; }
    .kpi-card { border: 0; border-radius: 14px; background: #fff; box-shadow: 0 8px 18px rgba(2,132,199,.06); }
    .kpi-icon { width: 42px; height: 42px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; }
    .trend-up { color: #16a34a; }
    .trend-down { color: #dc2626; }
    @media (max-width: 992px) { .sidebar { display: none; } }
  </style>
</head>
<body>
  <div class="layout">
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
      <nav class="nav flex-column gap-1">
        <a class="nav-link active" href="#"><i class="fa-solid fa-gauge-high me-2"></i>Bảng điều khiển</a>
        <a class="nav-link" href="#fleet"><i class="fa-solid fa-car me-2"></i>Quản lý xe</a>
        <a class="nav-link" href="#bookings"><i class="fa-solid fa-calendar-check me-2"></i>Đơn đặt</a>
        <a class="nav-link" href="#customers"><i class="fa-solid fa-user-group me-2"></i>Khách hàng</a>
        <a class="nav-link" href="#revenue"><i class="fa-solid fa-sack-dollar me-2"></i>Doanh thu</a>
        <a class="nav-link" href="#reports"><i class="fa-solid fa-chart-line me-2"></i>Báo cáo</a>
        <a class="nav-link" href="#settings"><i class="fa-solid fa-gear me-2"></i>Cài đặt</a>
        <hr class="border-light border-opacity-25" />
        <a class="nav-link" href="../index.php"><i class="fa-solid fa-house me-2"></i>Trang chủ</a>
        <a class="nav-link" href="../login.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a>
      </nav>
      <div class="mt-auto small text-white-50">© <?php echo date('Y'); ?> CarRent</div>
    </aside>

    <!-- Main -->
    <main class="flex-grow-1 d-flex flex-column">
      <!-- Topbar -->
      <div class="topbar py-3 px-3 px-lg-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-outline-info d-lg-none" onclick="document.querySelector('.sidebar')?.classList.toggle('d-none');">
            <i class="fa-solid fa-bars"></i>
          </button>
          <h1 class="h5 m-0">Bảng điều khiển</h1>
          <span class="chip"><i class="fa-solid fa-cloud-sun"></i> Xanh trời & Trắng</span>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div class="text-end small">
            <div class="fw-semibold">Xin chào, Admin</div>
            <div class="text-muted">Chúc một ngày hiệu quả!</div>
          </div>
          <img src="../img/a1.png" width="36" height="36" class="rounded-circle border" alt="avatar" />
        </div>
      </div>

      <!-- Content -->
      <div class="p-3 p-lg-4">
        <!-- Shortcuts -->
        <div class="shortcut mb-4">
          <a href="#create-booking"><i class="fa-solid fa-plus me-2"></i>Tạo đơn đặt</a>
          <a href="#add-car"><i class="fa-solid fa-car-burst me-2"></i>Thêm xe mới</a>
          <a href="#customers"><i class="fa-solid fa-user-plus me-2"></i>Thêm khách hàng</a>
          <a href="#reports"><i class="fa-solid fa-chart-line me-2"></i>Xem báo cáo</a>
        </div>

        <!-- Welcome hero -->
        <div class="card hero mb-4">
          <div class="card-body d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
            <div class="mb-3 mb-lg-0">
              <h2 class="mb-1">Chào mừng trở lại! <span class="ms-1">👋</span></h2>
              <div class="text-muted">Đây là tổng quan về hoạt động kinh doanh của bạn</div>
            </div>
            <div class="d-flex align-items-center gap-3">
              <span class="chip"><i class="fa-solid fa-bolt"></i> Nhanh & trực quan</span>
              <span class="chip"><i class="fa-solid fa-shield"></i> Bảo mật</span>
            </div>
          </div>
        </div>

        <!-- Metrics -->
        <div class="row g-3 g-lg-4 mb-4">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card metric-card">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <div class="text-muted small">Tổng số xe</div>
                  <div class="fs-4 fw-bold">58</div>
                </div>
                <div class="metric-icon"><i class="fa-solid fa-car"></i></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card metric-card">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <div class="text-muted small">Đơn đặt hôm nay</div>
                  <div class="fs-4 fw-bold">24</div>
                </div>
                <div class="metric-icon"><i class="fa-solid fa-calendar-check"></i></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card metric-card">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <div class="text-muted small">Tỷ lệ sử dụng</div>
                  <div class="fs-4 fw-bold">76%</div>
                </div>
                <div class="metric-icon"><i class="fa-solid fa-gauge-high"></i></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card metric-card">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <div class="text-muted small">Doanh thu tháng</div>
                  <div class="fs-4 fw-bold">₫142.5M</div>
                </div>
                <div class="metric-icon"><i class="fa-solid fa-sack-dollar"></i></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Secondary KPIs -->
        <div class="row g-3 g-lg-4 mb-4">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Tỷ lệ sử dụng xe</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold">78%</div>
                  <div class="kpi-icon"><i class="fa-solid fa-chart-simple"></i></div>
                </div>
                <div class="small mt-1 trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i>+5</div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Thời gian thuê TB</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold">4.2 ngày</div>
                  <div class="kpi-icon"><i class="fa-solid fa-clock"></i></div>
                </div>
                <div class="small mt-1 trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i>+0.3</div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Đánh giá trung bình</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold">4.7/5</div>
                  <div class="kpi-icon"><i class="fa-solid fa-star"></i></div>
                </div>
                <div class="small mt-1 trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i>+0.2</div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Tỷ lệ hủy</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold">3.2%</div>
                  <div class="kpi-icon"><i class="fa-solid fa-ban"></i></div>
                </div>
                <div class="small mt-1 trend-down"><i class="fa-solid fa-arrow-trend-down me-1"></i>0.8</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts & Status -->
        <div class="row g-3 g-lg-4 mb-4">
          <div class="col-12 col-lg-7">
            <div class="card section-card h-100">
              <div class="card-header bg-white fw-semibold">Doanh thu 7 ngày</div>
              <div class="card-body">
                <div class="text-muted">Khu vực biểu đồ (có thể tích hợp Chart.js).</div>
                <div class="ratio ratio-21x9 border rounded-3 bg-light"></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-5">
            <div class="card section-card h-100">
              <div class="card-header bg-white fw-semibold">Tình trạng đội xe</div>
              <div class="card-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Đang cho thuê <span class="badge rounded-pill text-bg-info">32</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Sẵn sàng <span class="badge rounded-pill text-bg-success">18</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Bảo trì <span class="badge rounded-pill text-bg-warning">8</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent bookings -->
        <div class="card section-card mb-4">
          <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <span class="fw-semibold">Đơn đặt gần đây</span>
            <a href="#bookings" class="btn btn-sm btn-outline-info">Xem tất cả</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle m-0">
                <thead>
                  <tr>
                    <th>Mã</th>
                    <th>Khách hàng</th>
                    <th>Xe</th>
                    <th>Nhận - Trả</th>
                    <th>Tổng</th>
                    <th>Trạng thái</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#BK-2051</td>
                    <td>Nguyễn Văn A</td>
                    <td>Hyundai Accent</td>
                    <td>31/10 - 02/11</td>
                    <td>₫1,600,000</td>
                    <td><span class="badge text-bg-success">Xác nhận</span></td>
                    <td><button class="btn btn-sm btn-light">Chi tiết</button></td>
                  </tr>
                  <tr>
                    <td>#BK-2050</td>
                    <td>Trần Thị B</td>
                    <td>Toyota Vios</td>
                    <td>30/10 - 31/10</td>
                    <td>₫800,000</td>
                    <td><span class="badge text-bg-warning">Chờ duyệt</span></td>
                    <td><button class="btn btn-sm btn-light">Chi tiết</button></td>
                  </tr>
                  <tr>
                    <td>#BK-2049</td>
                    <td>Lê Văn C</td>
                    <td>VinFast VF5</td>
                    <td>29/10 - 01/11</td>
                    <td>₫2,100,000</td>
                    <td><span class="badge text-bg-secondary">Đã hủy</span></td>
                    <td><button class="btn btn-sm btn-light">Chi tiết</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Footer note -->
        <div class="text-muted small">Mẹo: Bạn có thể tích hợp Chart.js và DataTables để tăng tính trực quan.</div>
      </div>

      <footer class="mt-auto py-3 text-center text-muted small">
        © <?php echo date('Y'); ?> CarRent Admin
      </footer>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>


