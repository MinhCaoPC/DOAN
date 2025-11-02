<?php
session_start();
include("config.php"); // kết nối DB

header('Content-Type: application/json');

$response = ['status' => '', 'code' => 0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenTaiKhoan = trim($_POST['TenTaiKhoan']);
    $Email = trim($_POST['Email']);
    $MatKhau = trim($_POST['MatKhau']);
    $MatKhau2 = trim($_POST['MatKhau2']);

    // 1️⃣ Mật khẩu không khớp → code 1
    if ($MatKhau !== $MatKhau2) {
        $response['status'] = 'error';
        $response['code'] = 1;
        echo json_encode($response);
        exit;
    }

    // 2️⃣ Mã hóa mật khẩu
    $MatKhauHash = password_hash($MatKhau, PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO TAIKHOAN (TenTaiKhoan, Email, MatKhau) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $TenTaiKhoan, $Email, $MatKhauHash);
        $stmt->execute();

        $stmt_get = $conn->prepare("SELECT MaSoTK FROM TAIKHOAN WHERE Email=?");
        $stmt_get->bind_param("s", $Email);
        $stmt_get->execute();
        $res = $stmt_get->get_result();
        $row = $res->fetch_assoc();
        $MaSoTK = $row['MaSoTK'];

        $stmt_kh = $conn->prepare("INSERT INTO KHACHHANG (HoTen, Email, MaSoTK) VALUES (?, ?, ?)");
        $stmt_kh->bind_param("sss", $TenTaiKhoan, $Email, $MaSoTK);
        $stmt_kh->execute();

        $_SESSION['MaSoTK'] = $MaSoTK;
        $_SESSION['TenTaiKhoan'] = $TenTaiKhoan;
        $_SESSION['LoaiTaiKhoan'] = 'KH';

        $response['status'] = 'success';
        echo json_encode($response);
        exit;

    } catch (mysqli_sql_exception $e) {
        $err = $e->getMessage();
        $response['status'] = 'error';
        if (str_contains($err, 'Email')) {
            $response['code'] = 3; // email đã tồn tại
        } elseif (str_contains($err, 'Ten tai khoan')) {
            $response['code'] = 2; // tên tài khoản tồn tại
        } else {
            $response['code'] = 0; // lỗi khác
        }
        echo json_encode($response);
        exit;
    }
}
?>
