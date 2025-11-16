<?php
session_start();
require_once 'config.php';

// BƯỚC 1: Luôn kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['MaSoTK'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit;
}

$maSoTK = $_SESSION['MaSoTK'];

// BƯỚC 2: Thêm "t.TenTaiKhoan" vào câu SELECT
$sql = "SELECT t.TenTaiKhoan, t.Email, k.HoVaTen, k.GioiTinh, k.NgaySinh, k.DiaChi, k.SDT
        FROM TAIKHOAN t
        LEFT JOIN KHACHHANG k ON t.MaSoTK = k.MaSoTK
        WHERE t.MaSoTK = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $maSoTK);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if ($data) {
    // Trả về dữ liệu dưới dạng JSON
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    http_response_code(404); // Không tìm thấy
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin tài khoản.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>