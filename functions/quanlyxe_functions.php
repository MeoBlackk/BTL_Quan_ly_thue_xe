<?php
require_once __DIR__ . '/../database/db_connection.php';

/**
 * Lấy tất cả xe từ cơ sở dữ liệu
 * @return array
 */
function getAllCars() {
    $conn = getDbConnection();
    $sql = "SELECT * FROM quanlyxe ORDER BY Id DESC";
    $result = mysqli_query($conn, $sql);
    $cars = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $cars[] = $row;
        }
    }
    mysqli_close($conn);
    return $cars;
}

/**
 * Lấy thông tin một xe bằng ID
 * @param int $id
 * @return array|null
 */
function getCarById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM quanlyxe WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $car = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $car;
}

/**
 * Thêm một xe mới
 * @param array $data
 * @return bool
 */
function createCar($data) {
    $conn = getDbConnection();
    $sql = "INSERT INTO quanlyxe (ten_xe, hang_xe, model, bien_so, loai_xe, so_ghe, gia_thue, trang_thai, mo_ta, hinh_anh, ngay_tao, ngay_cap_nhat) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssisss", 
        $data['ten_xe'],
        $data['hang_xe'],
        $data['model'],
        $data['bien_so'],
        $data['loai_xe'],
        $data['so_ghe'],
        $data['gia_thue'],
        $data['trang_thai'],
        $data['mo_ta'],
        $data['hinh_anh']
    );
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

/**
 * Cập nhật thông tin xe
 * @param int $id
 * @param array $data
 * @return bool
 */
function updateCar($id, $data) {
    $conn = getDbConnection();
    $sql = "UPDATE quanlyxe SET 
                ten_xe = ?, 
                hang_xe = ?, 
                model = ?, 
                bien_so = ?, 
                loai_xe = ?, 
                so_ghe = ?, 
                gia_thue = ?, 
                trang_thai = ?, 
                mo_ta = ?, 
                hinh_anh = ?, 
                ngay_cap_nhat = NOW() 
            WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssisssi", 
        $data['ten_xe'],
        $data['hang_xe'],
        $data['model'],
        $data['bien_so'],
        $data['loai_xe'],
        $data['so_ghe'],
        $data['gia_thue'],
        $data['trang_thai'],
        $data['mo_ta'],
        $data['hinh_anh'],
        $id
    );
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

/**
 * Xóa một xe
 * @param int $id
 * @return bool
 */
function deleteCar($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM quanlyxe WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>