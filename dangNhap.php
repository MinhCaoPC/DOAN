<?php
session_start();
include("config.php");

header('Content-Type: application/json');

$response = ['status' => '', 'code' => 0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenTaiKhoan = trim($_POST['TenTaiKhoan']);
    $MatKhau = trim($_POST['MatKhau']);

    $stmt = $conn->prepare("SELECT * FROM TAIKHOAN WHERE TenTaiKhoan=? OR Email=? LIMIT 1");
    $stmt->bind_param("ss", $TenTaiKhoan, $TenTaiKhoan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($MatKhau, $row['MatKhau'])) {
            $_SESSION['MaSoTK'] = $row['MaSoTK'];
            $_SESSION['TenTaiKhoan'] = $row['TenTaiKhoan'];
            $_SESSION['LoaiTaiKhoan'] = $row['LoaiTaiKhoan'];

            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
            $response['code'] = 4; // sai mật khẩu
        }
    } else {
        $response['status'] = 'error';
        $response['code'] = 4; // tài khoản không tồn tại -> vẫn mã 4
    }

    echo json_encode($response);
    exit;
}
?>
