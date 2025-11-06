<?php
// Bật hiển thị lỗi để dễ debug (Chỉ dùng trong môi trường Phát triển)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// KHẮC PHỤC: Bắt buộc phải include auth.php để dùng cho Navbar/Sidebar và db_connection.php
// Điều chỉnh đường dẫn nếu các file này không nằm cùng cấp với revenue.php
require_once('./functions/auth.php'); 
require_once('./database/db_connection.php'); 
$conn = getDbConnection();

// --- Lấy dữ liệu doanh thu ---
$total_revenue = $conn->query("SELECT SUM(amount) AS total FROM revenues")->fetch_assoc()['total'] ?? 0;
$monthly_revenue = $conn->query("
    SELECT DATE_FORMAT(date, '%m/%Y') AS month, SUM(amount) AS total 
    FROM revenues 
    GROUP BY month 
    ORDER BY MAX(date) DESC
");
$revenue_list = $conn->query("SELECT * FROM revenues ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Doanh thu - CarRent Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sky: #7dd3fc; /* lighter */
            --sky-600: #38bdf8;
            --sky-50: #ecfeff;
            --text-dark: #0f172a;
            --muted: #64748b;
            --sidebar-w: 260px; /* New width: 260px */
            --radius: 14px;
        }
        body { background: linear-gradient(180deg, var(--sky-50) 0%, #ffffff 40%, #f0f9ff 100%); color: #000; font-family: 'Be Vietnam Pro', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; }
        .layout { display: flex; min-height: 100vh; }
        
        /* SIDEBAR (Đảm bảo fixed và margin-left cho main) */
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
        
        /* TOPBAR */
        .topbar { 
            position: fixed;
            top: 0; 
            left: var(--sidebar-w); /* Dùng biến mới */
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

        /* MAIN - Cập nhật margin-left để phù hợp với chiều rộng sidebar mới */
        .main {
            margin-left: var(--sidebar-w); 
            padding: 90px 30px 30px 30px; 
        }

        /* Các classes khác */
        .chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; background: #fff; color: #000; font-weight: 700; font-size: 12px; }
        .tile { border: 0; border-radius: 18px; background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%); box-shadow: 0 12px 30px rgba(rgba(2,132,199,.12)); }
        .tile .number { font-size: 40px; font-weight: 800; color:rgb(64, 138, 175); }
        .tile .compare { background: #fff; color: #000; border: 1px solid #e2e8f0; border-radius: 10px; padding: 4px 8px; font-weight: 800; font-size: 12px; }
        .tile .bubble { width: 48px; height: 48px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg,#38bdf8,#0ea5e9); color: #fff; box-shadow: 0 10px 20px rgba(14,165,233,.3); }
        .metric-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.08); }
        .metric-icon { width: 50px; height: 50px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; background: #e0f2fe; color: #0284c7; }
        .section-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.06); }
        .table thead th { background: #f8fafc; color: var(--text-dark); }
        .text-muted { color: var(--muted) !important; opacity: 1; } 
        .nav-link, .card, .card-header, .card-body, .badge, .btn { color: var(--text-dark); }
        .hero { border: 0; border-radius: 16px; background: linear-gradient(145deg, #e0f2fe 0%, #ffffff 60%); box-shadow: inset 0 1px 0 rgba(255,255,255,.6), 0 10px 22px rgba(2,132,199,.12); }
        .hero h2 { font-weight: 800; color: #0ea5e9; }
        .kpi-card { border: 0; border-radius: 14px; background: #fff; box-shadow: 0 8px 18px rgba(2,132,199,.06); }
        .kpi-icon { width: 42px; height: 42px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; }
        .trend-up { color: #16a34a; }
        .trend-down { color: #dc2626; }
        @media (max-width: 992px) { .sidebar { display: none; } }

        .card {
            border: none;
            border-radius: var(--radius); 
            box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
            background: #fff;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            color: var(--muted);
        }
    </style>
</head>
<body>

<?php 
// Giả định hai file này nằm cùng cấp với revenue.php
include './includes/sidebar.php'; 
include './includes/navbar.php'; 
?>

<div class="main">
    <h4 class="fw-semibold mb-4"><i class="fa-solid fa-sack-dollar me-2 text-sky-600"></i>Doanh thu</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3 section-card">
                <h5><i class="fa-solid fa-wallet me-2 text-sky-600"></i>Tổng doanh thu</h5>
                <h3 class="fw-bold mt-2 number">
                    <?= number_format($total_revenue, 0, ',', '.') ?> ₫
                </h3>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-3 section-card">
                <h5><i class="fa-solid fa-calendar me-2 text-sky-600"></i>Doanh thu theo tháng</h5>
                <div class="table-responsive mt-2">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($monthly_revenue->num_rows > 0) {
                                while ($m = $monthly_revenue->fetch_assoc()) {
                                    echo "<tr><td>{$m['month']}</td><td>" . number_format($m['total'], 0, ',', '.') . " ₫</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center text-muted'>Chưa có dữ liệu</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 section-card">
        <h5><i class="fa-solid fa-list me-2 text-sky-600"></i>Chi tiết giao dịch</h5>
        <div class="table-responsive mt-3">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khách hàng</th>
                        <th>Số tiền</th>
                        <th>Ngày giao dịch</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($revenue_list->num_rows > 0) {
                        while ($r = $revenue_list->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>{$r['id']}</td>
                                <td>{$r['customer_name']}</td>
                                <td>" . number_format($r['amount'], 0, ',', '.') . " ₫</td>
                                <td>{$r['date']}</td>
                                <td>{$r['note']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-muted py-4'>Chưa có dữ liệu doanh thu.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="small mt-4">© <?php echo date('Y'); ?> CarRent Admin</footer>
</div>

</body>
</html>