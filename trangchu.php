<?php
session_start();

// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// KHẮC PHỤC: Cần include các file quan trọng để dùng cho Navbar/Sidebar và DB
// Điều chỉnh đường dẫn nếu các file này không nằm cùng cấp với trangchu.php
require_once('./functions/auth.php'); 
require_once('./database/db_connection.php'); 
$conn = getDbConnection();

// --- DATA INITIALIZATION (CHỈ LÀ PLACEHOLDER, CẦN THAY BẰNG QUERY THỰC TẾ) ---

// 1. KPI THÔNG SỐ CHÍNH
$total_cars = 0; 
$total_bookings_month = 0; 
$total_customers = 0; 
$total_revenue_month = "0 ₫"; 
$percent_change = "0%"; // Giả định
$trend_icon = 'fa-solid fa-minus'; // Giả định không thay đổi

// 2. KPI THÔNG SỐ PHỤ
$utilization_rate = '0%';
$utilization_change = '0';
$avg_rental_days = '0 ngày';
$avg_rental_change = '0';
$avg_rating = '0/5';
$avg_rating_change = '0';
$cancellation_rate = '0%';
$cancellation_change = '0';

// 3. DỮ LIỆU BIỂU ĐỒ (Set thành mảng rỗng [] cho Chart.js)
$months_label = "['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12']";

// Dữ liệu doanh thu (đơn vị: triệu)
$revenue_data = '[]'; 
// Dữ liệu đơn đặt
$booking_data = '[]';
// Dữ liệu tỷ lệ sử dụng xe
$utilization_data = '[]'; 
// Dữ liệu phân loại xe (Cho thuê, Sẵn sàng, Bảo trì)
$pie_data = '[]'; 


// 4. ĐƠN ĐẶT GẦN ĐÂY (Cần thay thế bằng query: SELECT * FROM bookings ORDER BY booking_date DESC LIMIT 5)
$recent_bookings = []; // Mảng rỗng hoặc kết quả của query
// Ví dụ khi dùng query thực tế: $recent_bookings = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard · Quản lý tổng quan</title>
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
    /* Sidebar Fixes: Bắt buộc fixed cho style mới */
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
        padding: 20px 15px; 
        width: var(--sidebar-w);
        background: linear-gradient(160deg, var(--sky) 0%, var(--sky-600) 100%);
        color: #fff;
    }
    .brand { font-weight: 800; letter-spacing: .3px; }
    .sidebar .nav-link { color: #fff; border-radius: 10px; }
    .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,.18); }
    /* Topbar Fixes: Cần fixed và margin-left */
    .topbar { 
        position: fixed;
        top: 0; 
        left: var(--sidebar-w);
        right: 0;
        background: #fff; 
        border-bottom: 1px solid #e2e8f0; 
        height: 70px; 
        display: flex;
        justify-content: flex-end; 
        align-items: center;
        padding: 0 25px;
        z-index: 100;
    }
    .main-content { /* Tên class mới cho div chứa nội dung chính để tránh xung đột */
        margin-left: var(--sidebar-w); 
        padding: 90px 30px 30px 30px; 
        flex-grow: 1;
    }
    /* End Fixes */
    .chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; background: #fff; color: #000; font-weight: 700; font-size: 12px; }
    .tile { border: 0; border-radius: 18px; background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%); box-shadow: 0 12px 30px rgba(14,165,233,.12); }
    .tile .number { font-size: 40px; font-weight: 800; color:rgb(64, 138, 175); }
    .tile .compare { background: #fff; color: #000; border: 1px solid #e2e8f0; border-radius: 10px; padding: 4px 8px; font-weight: 800; font-size: 12px; }
    .tile .bubble { width: 48px; height: 48px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg,#38bdf8,#0ea5e9); color: #fff; box-shadow: 0 10px 20px rgba(14,165,233,.3); }
    .metric-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.08); }
    .metric-icon { width: 50px; height: 50px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; background: #e0f2fe; color: #0284c7; }
    .section-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.06); }
    .table thead th { background: #f8fafc; color: #000; }
    .text-muted { color: var(--muted) !important; opacity: 1; } 
    .nav-link, .card, .card-header, .card-body, .badge, .btn { color: #000; }
    .hero { border: 0; border-radius: 16px; background: linear-gradient(145deg, #e0f2fe 0%, #ffffff 60%); box-shadow: inset 0 1px 0 rgba(255,255,255,.6), 0 10px 22px rgba(2,132,199,.12); }
    .hero h2 { font-weight: 800; color: #0ea5e9; }
    .kpi-card { border: 0; border-radius: 14px; background: #fff; box-shadow: 0 8px 18px rgba(2,132,199,.06); }
    .kpi-icon { width: 42px; height: 42px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; }
    .trend-up { color: #16a34a; }
    .trend-down { color: #dc2626; }
    @media (max-width: 992px) { .sidebar { display: none; } .topbar { left: 0; } }
  </style>
</head>
<body>
  <div class="layout">
    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-grow-1 d-flex flex-column main-content">
      <?php include 'includes/navbar.php'; ?>

      <div class="p-3 p-lg-4">
        <div class="card hero mb-4">
          <div class="card-body d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
            <div class="mb-3 mb-lg-0">
              <h2 class="mb-1">Báo cáo thống kê <span class="ms-1"></span></h2>
            </div>
            <div class="d-flex align-items-center gap-3">

            </div>
          </div>
        </div>

        <div class="row g-3 g-lg-4 mb-4">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card tile">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small">TỔNG SỐ XE</div>
                  <div class="number"><?= $total_cars ?></div>
                  <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="compare"><i class="<?= $trend_icon ?> me-1"></i><?= $percent_change ?></span>
                    <span class="text-muted small">so với tháng trước</span>
                  </div>
                </div>
                <div class="bubble"><i class="fa-solid fa-car"></i></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card tile">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small">ĐẶT XE THÁNG NÀY</div>
                  <div class="number"><?= $total_bookings_month ?></div>
                  <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="compare"><i class="<?= $trend_icon ?> me-1"></i><?= $percent_change ?></span>
                    <span class="text-muted small">so với tháng trước</span>
                  </div>
                </div>
                <div class="bubble"><i class="fa-regular fa-calendar"></i></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card tile">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small">KHÁCH HÀNG</div>
                  <div class="number"><?= $total_customers ?></div>
                  <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="compare"><i class="<?= $trend_icon ?> me-1"></i><?= $percent_change ?></span>
                    <span class="text-muted small">so với tháng trước</span>
                  </div>
                </div>
                <div class="bubble"><i class="fa-solid fa-user-group"></i></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card tile">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small">DOANH THU THÁNG NÀY</div>
                  <div class="number"><?= $total_revenue_month ?></div>
                  <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="compare"><i class="<?= $trend_icon ?> me-1"></i><?= $percent_change ?></span>
                    <span class="text-muted small">so với tháng trước</span>
                  </div>
                </div>
                <div class="bubble"><i class="fa-solid fa-dollar-sign"></i></div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 g-lg-4 mb-4">
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Tỷ lệ sử dụng xe</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold"><?= $utilization_rate ?></div>
                  <div class="kpi-icon"><i class="fa-solid fa-chart-simple"></i></div>
                </div>
                <div class="small mt-1 trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i>+<?= $utilization_change ?></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Thời gian thuê TB</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold"><?= $avg_rental_days ?></div>
                  <div class="kpi-icon"><i class="fa-solid fa-clock"></i></div>
                </div>
                <div class="small mt-1 trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i>+<?= $avg_rental_change ?></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Đánh giá trung bình</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold"><?= $avg_rating ?></div>
                  <div class="kpi-icon"><i class="fa-solid fa-star"></i></div>
                </div>
                <div class="small mt-1 trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i>+<?= $avg_rating_change ?></div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
            <div class="card kpi-card">
              <div class="card-body">
                <div class="text-muted small">Tỷ lệ hủy</div>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="fs-5 fw-bold"><?= $cancellation_rate ?></div>
                  <div class="kpi-icon"><i class="fa-solid fa-ban"></i></div>
                </div>
                <div class="small mt-1 trend-down"><i class="fa-solid fa-arrow-trend-down me-1"></i><?= $cancellation_change ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 g-lg-4 mb-4">
          <div class="col-12 col-lg-7">
            <div class="card section-card h-100">
              <div class="card-header bg-white fw-semibold">Doanh thu theo tháng</div>
              <div class="card-body">
                <canvas id="lineChart" height="120"></canvas>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-5">
            <div class="card section-card h-100">
              <div class="card-header bg-white fw-semibold">Đơn đặt theo tháng</div>
              <div class="card-body">
                <canvas id="barChart" height="120"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 g-lg-4 mb-4">
          <div class="col-12 col-lg-7">
            <div class="card section-card h-100">
              <div class="card-header bg-white fw-semibold">Tỷ lệ sử dụng đội xe</div>
              <div class="card-body">
                <canvas id="areaChart" height="120"></canvas>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-5">
            <div class="card section-card h-100">
              <div class="card-header bg-white fw-semibold">Phân loại xe</div>
              <div class="card-body">
                <div class="text-muted mb-2">Đang cho thuê 0%</div>
                <canvas id="pieChart" height="180"></canvas>
              </div>
            </div>
          </div>
        </div>

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
                    <?php if (empty($recent_bookings)): ?>
                        <tr><td colspan='7' class='text-center text-muted py-4'>Chưa có dữ liệu đơn đặt hàng gần đây.</td></tr>
                    <?php else: ?>
                        <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="text-muted small">Mẹo: Bạn có thể tích hợp Chart.js và DataTables để tăng tính trực quan.</div>
      </div>

      <footer class="mt-auto py-3 text-center text-muted small">
        © <?php echo date('Y'); ?> CarRent Admin
      </footer>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" crossorigin="anonymous"></script>
  <script>
    (function(){
      // Doanh thu theo tháng
      const ctx = document.getElementById('lineChart');
      if (ctx) {
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: <?= $months_label ?>,
            datasets: [{
              label: 'Doanh thu (triệu ₫)',
              data: <?= $revenue_data ?>,
              tension: 0.35,
              fill: true,
              backgroundColor: 'rgba(56,189,248,0.16)',
              borderColor: '#38bdf8',
              pointRadius: 0
            }]
          },
          options: {
            plugins: { legend: { display: false } },
            scales: {
              x: { ticks: { color: '#000' }, grid: { display: false } },
              y: { ticks: { color: '#000' }, grid: { color: 'rgba(56,189,248,.15)' } }
            }
          }
        });
      }

      // Đơn đặt theo tháng
      const bc = document.getElementById('barChart');
      if (bc) {
        new Chart(bc, {
          type: 'bar',
          data: {
            labels: <?= $months_label ?>,
            datasets: [{
              label: 'Đơn đặt',
              data: <?= $booking_data ?>,
              backgroundColor: 'rgba(125,211,252,0.8)',
              borderRadius: 8
            }]
          },
          options: { plugins: { legend: { display: false } }, scales: { x: { ticks: { color: '#000' }, grid: { display: false } }, y: { ticks: { color: '#000' }, grid: { color: 'rgba(56,189,248,.15)' } } } }
        });
      }

      // Tỷ lệ sử dụng đội xe
      const ac = document.getElementById('areaChart');
      if (ac) {
        new Chart(ac, {
          type: 'line',
          data: {
            labels: <?= $months_label ?>,
            datasets: [{
              label: 'Sử dụng (%)',
              data: <?= $utilization_data ?>,
              tension: 0.35,
              fill: true,
              backgroundColor: 'rgba(125,211,252,0.18)',
              borderColor: '#7dd3fc',
              pointRadius: 0
            }]
          },
          options: { plugins: { legend: { display: false } }, scales: { x: { ticks: { color: '#000' }, grid: { display: false } }, y: { ticks: { color: '#000' }, grid: { color: 'rgba(56,189,248,.15)' } } } }
        });
      }

      // Phân loại xe
      const p = document.getElementById('pieChart');
      if (p) {
        new Chart(p, {
          type: 'pie',
          data: {
            labels: ['Cho thuê','Sẵn sàng','Bảo trì'],
            datasets: [{
              data: <?= $pie_data ?>,
              backgroundColor: ['#7dd3fc','#86efac','#fde68a'],
              borderWidth: 0
            }]
          },
          options: { plugins: { legend: { position: 'bottom' } } }
        });
      }
    })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>