<?php
session_start();
// Đảm bảo file config.php là file conn.php
include("ADMIN/conn.php"); // Thay đổi 'config.php' thành 'conn.php' nếu đó là file kết nối

header('Content-Type: application/json');

$response = ['status' => '', 'code' => 0, 'redirect' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenTaiKhoan = trim($_POST['TenTaiKhoan']);
    $MatKhau = trim($_POST['MatKhau']);

    // Sử dụng prepare statement để ngăn ngừa SQL Injection
    $stmt = $conn->prepare("SELECT MaSoTK, MatKhau, TenTaiKhoan, LoaiTaiKhoan FROM TAIKHOAN WHERE TenTaiKhoan=? OR Email=? LIMIT 1");
    $stmt->bind_param("ss", $TenTaiKhoan, $TenTaiKhoan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // 1. Kiểm tra mật khẩu bằng hàm bảo mật (từ conn.php)
        if (verify_password($MatKhau, $row['MatKhau'])) { 
            // 2. Đăng nhập thành công
            $_SESSION['MaSoTK'] = $row['MaSoTK'];
            $_SESSION['TenTaiKhoan'] = $row['TenTaiKhoan'];
            $_SESSION['LoaiTaiKhoan'] = $row['LoaiTaiKhoan'];

            $response['status'] = 'success';
            
            if ($row['LoaiTaiKhoan'] == 'AD') {
            // Thay vì admin.html, chuyển hướng đến file PHP xử lý bảo mật
            $response['redirect'] = 'admin_check.php'; 
            } 
            else {
            $response['redirect'] = 'TrangChu.html';
            }
        } else {
            $response['status'] = 'error';
            $response['code'] = 4; // Sai mật khẩu
        }
    } else {
        $response['status'] = 'error';
        $response['code'] = 4; // Tài khoản không tồn tại (giữ mã 4 để tránh leak thông tin)
    }

    echo json_encode($response);
    exit;
}
?>