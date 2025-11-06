<?php
session_start();
// auth check if needed
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thêm xe mới · CarRent Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link rel="icon" href="../img/a1.png" />
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <meta name="robots" content="noindex,nofollow" />
  <style>
    :root {
      --sky: #7dd3fc;
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
    .chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; background: #fff; color: #000; font-weight: 700; font-size: 12px; }
    .tile { border: 0; border-radius: 18px; background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%); box-shadow: 0 12px 30px rgba(14,165,233,.12); }
    .section-card { border: 0; border-radius: var(--radius); box-shadow: 0 10px 18px rgba(2,132,199,.06); }
    .table thead th { background: #f8fafc; color: #000; }
    .text-muted { color: #000 !important; opacity: .70; }
    .nav-link, .card, .card-header, .card-body, .badge, .btn { color: #000; }
    .form-control, .form-select { border: 1px solid #e2e8f0; border-radius: 10px; }
    .form-control:focus, .form-select:focus { border-color: var(--sky-600); box-shadow: 0 0 0 0.2rem rgba(56,189,248,.25); }
    .btn-primary { background: linear-gradient(135deg, var(--sky-600), var(--sky)); border: 0; border-radius: 10px; font-weight: 600; }
    .btn-primary:hover { background: linear-gradient(135deg, var(--sky), var(--sky-600)); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(56,189,248,.3); }
    .btn-danger { border-radius: 10px; }
    .btn-success { border-radius: 10px; }
    .btn-warning { border-radius: 10px; }
    .badge { border-radius: 8px; padding: 6px 12px; font-weight: 600; }
    .car-image { width: 80px; height: 60px; object-fit: cover; border-radius: 8px; }
    @media (max-width: 992px) { .sidebar { display: none; } }
  </style>
</head>
<body>
  <div class="layout">
    <?php include '../includes/sidebar.php'; ?>
    <main class="flex-grow-1 d-flex flex-column">
      <?php include '../includes/navbar.php'; ?>
      <div class="p-3 p-lg-4">

        <div class="card section-card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-car-alt me-2 text-primary"></i>
                    <h4 class="fw-semibold mb-0">Thêm xe mới</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="../handle/quanlyxe_process.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="action" value="create">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-end" for="ten_xe">Tên xe</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="ten_xe" name="ten_xe" required>
                            <div class="invalid-feedback">Vui lòng nhập tên xe</div>
                        </div>
                        <label class="col-sm-2 col-form-label text-end" for="bien_so">Biển số</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="bien_so" name="bien_so" required>
                            <div class="invalid-feedback">Vui lòng nhập biển số</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-end" for="hang_xe">Hãng xe</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="hang_xe" name="hang_xe" required>
                            <div class="invalid-feedback">Vui lòng nhập hãng xe</div>
                        </div>
                        <label class="col-sm-2 col-form-label text-end" for="model">Model</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="model" name="model" required>
                            <div class="invalid-feedback">Vui lòng nhập model</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-end" for="loai_xe">Loại xe</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="loai_xe" name="loai_xe">
                        </div>
                        <label class="col-sm-2 col-form-label text-end" for="so_ghe">Số ghế</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="so_ghe" name="so_ghe" min="1">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-end" for="gia_thue">Giá thuê/ngày</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="gia_thue" name="gia_thue" required min="0">
                        </div>
                        <label class="col-sm-2 col-form-label text-end" for="trang_thai">Trạng thái</label>
                        <div class="col-sm-4">
                            <select class="form-select" id="trang_thai" name="trang_thai">
                                <option value="Sẵn sàng">Sẵn sàng</option>
                                <option value="Đang cho thuê">Đang cho thuê</option>
                                <option value="Bảo trì">Bảo trì</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-end" for="mo_ta">Mô tả</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label text-end" for="hinh_anh">URL Hình ảnh</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="hinh_anh" name="hinh_anh">
                            <small class="form-text text-muted">Tạm thời nhập URL, sẽ nâng cấp lên upload file sau.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu
                            </button>
                            <a href="../quanlyxe.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
      <footer class="mt-auto py-3 px-3 text-center">
        <span class="text-muted small">© <?php echo date('Y'); ?> CarRent. All rights reserved.</span>
      </footer>
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>