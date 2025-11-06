<?php


require_once('./functions/auth.php'); 
require_once('./database/db_connection.php'); 
$conn = getDbConnection();

// --- Thêm khách hàng ---
if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $rental_date = $_POST['rental_date'];
    // Sử dụng prepare statement để ngăn chặn SQL Injection
    $stmt = $conn->prepare("INSERT INTO customers (name, phone, email, address, rental_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $phone, $email, $address, $rental_date);
    $stmt->execute();
    $stmt->close();
    header("Location: customers.php");
    exit();
}

// --- Xóa khách hàng ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: customers.php");
    exit();
}

// --- Sửa khách hàng ---
if (isset($_POST['edit_customer'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $rental_date = $_POST['rental_date'];
    $stmt = $conn->prepare("UPDATE customers SET name=?, phone=?, email=?, address=?, rental_date=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $phone, $email, $address, $rental_date, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: customers.php");
    exit();
}

// --- Lấy dữ liệu khách hàng ---
$customers_list = $conn->query("SELECT * FROM customers ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Khách hàng - CarRent Admin</title>
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

        /* Style cho Modal (overlay) */
        .modal {
            display: none; /* Mặc định ẩn */
            position: fixed;
            z-index: 1050; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center; 
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 450px;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold"><i class="fa-solid fa-user-group me-2 text-sky-600"></i>Quản lý Khách hàng</h4>
        <button class="btn btn-primary btn-sm" onclick="openAddModal()">
            <i class="fa-solid fa-plus me-1"></i> Thêm Khách hàng
        </button>
    </div>

    <div class="card p-3 section-card">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Ngày thuê gần nhất</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($customers_list->num_rows > 0) {
                        while ($row = $customers_list->fetch_assoc()) {
                            // Escape HTML entities cho dữ liệu
                            $name = htmlspecialchars($row['name']);
                            $phone = htmlspecialchars($row['phone']);
                            $email = htmlspecialchars($row['email']);
                            $address = htmlspecialchars($row['address']);
                            $rental_date = htmlspecialchars($row['rental_date']);
                            
                            echo "
                            <tr>
                                <td>{$row['id']}</td>
                                <td>{$name}</td>
                                <td>{$phone}</td>
                                <td>{$email}</td>
                                <td>{$address}</td>
                                <td>{$rental_date}</td>
                                <td>
                                    <button class='btn btn-sm btn-warning me-2' onclick='openEditModal(\"{$row['id']}\", \"{$name}\", \"{$phone}\", \"{$email}\", \"{$address}\", \"{$rental_date}\")'>
                                        <i class='fa-solid fa-edit'></i> Sửa
                                    </button>
                                    <a href='customers.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Bạn có chắc chắn muốn xóa khách hàng này không?');\">
                                        <i class='fa-solid fa-trash-alt'></i> Xóa
                                    </a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-muted py-4'>Chưa có khách hàng nào.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="small mt-4">© <?php echo date('Y'); ?> CarRent Admin</footer>
</div>

<div id="addModal" class="modal">
    <div class="modal-content">
        <h5 class="fw-bold mb-3">Thêm Khách hàng Mới</h5>
        <form method="POST" action="customers.php">
            <input type="text" name="name" class="form-control mb-2" placeholder="Tên khách hàng" required>
            <input type="text" name="phone" class="form-control mb-2" placeholder="Số điện thoại" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="Email">
            <input type="text" name="address" class="form-control mb-2" placeholder="Địa chỉ">
            <input type="date" name="rental_date" class="form-control mb-3" required>
            <div class="d-flex justify-content-between">
                <button type="submit" name="add_customer" class="btn btn-primary">Thêm</button>
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Đóng</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <h5 class="fw-bold mb-3">Sửa Thông tin Khách hàng</h5>
        <form method="POST" action="customers.php">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="name" id="edit_name" class="form-control mb-2" required>
            <input type="text" name="phone" id="edit_phone" class="form-control mb-2" required>
            <input type="email" name="email" id="edit_email" class="form-control mb-2">
            <input type="text" name="address" id="edit_address" class="form-control mb-2">
            <input type="date" name="rental_date" id="edit_rental_date" class="form-control mb-3" required>
            <div class="d-flex justify-content-between">
                <button type="submit" name="edit_customer" class="btn btn-warning text-dark">Cập nhật</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Đóng</button>
            </div>
        </form>
    </div>
</div>

<script>
    const addModal = document.getElementById("addModal");
    const editModal = document.getElementById("editModal");

    function openAddModal() { addModal.style.display = "flex"; }
    function closeAddModal() { addModal.style.display = "none"; }

    function openEditModal(id, name, phone, email, address, rental_date) {
        document.getElementById("edit_id").value = id;
        document.getElementById("edit_name").value = name;
        document.getElementById("edit_phone").value = phone;
        document.getElementById("edit_email").value = email;
        document.getElementById("edit_address").value = address;
        document.getElementById("edit_rental_date").value = rental_date;
        editModal.style.display = "flex"; 
    }
    function closeEditModal() { editModal.style.display = "none"; }

    // Đóng modal khi click ra ngoài
    window.onclick = function(event) {
        if (event.target == addModal) {
            closeAddModal();
        }
        if (event.target == editModal) {
            closeEditModal();
        }
    }
</script>

</body>
</html>