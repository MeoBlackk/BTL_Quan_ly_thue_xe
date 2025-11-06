<?php
require_once __DIR__ . '/../functions/quanlyxe_functions.php';

// Xử lý xóa
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (deleteCar($id)) {
        header('Location: ../quanlyxe.php?success=Xóa xe thành công!');
    } else {
        header('Location: ../quanlyxe.php?error=Xóa xe thất bại!');
    }
    exit();
}

// Xử lý thêm/sửa từ form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Thu thập dữ liệu từ form
    $data = [
        'ten_xe' => $_POST['ten_xe'] ?? '',
        'hang_xe' => $_POST['hang_xe'] ?? '',
        'model' => $_POST['model'] ?? '',
        'bien_so' => $_POST['bien_so'] ?? '',
        'loai_xe' => $_POST['loai_xe'] ?? '',
        'so_ghe' => $_POST['so_ghe'] ?? '',
        'gia_thue' => $_POST['gia_thue'] ?? 0,
        'trang_thai' => $_POST['trang_thai'] ?? 'Sẵn sàng',
        'mo_ta' => $_POST['mo_ta'] ?? '',
        'hinh_anh' => $_POST['hinh_anh'] ?? '' // Sẽ cần xử lý upload file thực tế
    ];

    if ($action === 'create') {
        if (createCar($data)) {
            header('Location: ../quanlyxe.php?success=Thêm xe thành công!');
        } else {
            header('Location: ../quanlyxe.php?error=Thêm xe thất bại!');
        }
        exit();
    }

    if ($action === 'edit' && isset($_POST['id'])) {
        $id = $_POST['id'];
        if (updateCar($id, $data)) {
            header('Location: ../quanlyxe.php?success=Cập nhật thông tin xe thành công!');
        } else {
            header('Location: ../quanlyxe.php?error=Cập nhật thất bại!');
        }
        exit();
    }
}

// Nếu không có action hợp lệ, chuyển hướng về trang chính
header('Location: ../quanlyxe.php');
exit();
?>