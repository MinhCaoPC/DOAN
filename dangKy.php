<?php
session_start();
include("config.php"); // $conn = new mysqli(...)

// Bắt mysqli ném exception để vào khối catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

header('Content-Type: application/json; charset=utf-8');

$response = ['status' => '', 'code' => 0];

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'code' => 0]);
        exit;
    }

    // Lấy input
    $TenTaiKhoan = trim($_POST['TenTaiKhoan'] ?? '');
    $Email       = trim($_POST['Email'] ?? '');
    $MatKhau     = trim($_POST['MatKhau'] ?? '');
    $MatKhau2    = trim($_POST['MatKhau2'] ?? '');

    // 1) Mật khẩu không khớp → code 1
    if ($MatKhau !== $MatKhau2) {
        echo json_encode(['status' => 'error', 'code' => 1]);
        exit;
    }

    // (tuỳ chọn) Check rỗng sớm ở PHP để UX nhanh hơn
    if ($Email === '') {
        echo json_encode(['status' => 'error', 'code' => 4, 'reason' => 'EMAIL_EMPTY']);
        exit;
    }
    if ($TenTaiKhoan === '') {
        echo json_encode(['status' => 'error', 'code' => 5, 'reason' => 'USERNAME_EMPTY']);
        exit;
    }

    // 2) Hash mật khẩu
    $MatKhauHash = password_hash($MatKhau, PASSWORD_BCRYPT);

    // 3) Insert TAIKHOAN (trigger sẽ tự set MaSoTK nếu KH)
    $stmt = $conn->prepare("INSERT INTO TAIKHOAN (TenTaiKhoan, Email, MatKhau, LoaiTaiKhoan) VALUES (?, ?, ?, 'KH')");
    $stmt->bind_param("sss", $TenTaiKhoan, $Email, $MatKhauHash);
    $stmt->execute();

    // 4) Lấy MaSoTK vừa tạo
    $stmt_get = $conn->prepare("SELECT MaSoTK FROM TAIKHOAN WHERE Email=? LIMIT 1");
    $stmt_get->bind_param("s", $Email);
    $stmt_get->execute();
    $res = $stmt_get->get_result();
    $row = $res->fetch_assoc();
    $MaSoTK = $row['MaSoTK'];

    // 5) Thêm KHÁCH HÀNG
    $stmt_kh = $conn->prepare("INSERT INTO KHACHHANG (HoTen, Email, MaSoTK) VALUES (?, ?, ?)");
    $stmt_kh->bind_param("sss", $TenTaiKhoan, $Email, $MaSoTK);
    $stmt_kh->execute();

    // 6) Lưu session + trả OK
    $_SESSION['MaSoTK']      = $MaSoTK;
    $_SESSION['TenTaiKhoan'] = $TenTaiKhoan;
    $_SESSION['LoaiTaiKhoan']= 'KH';

    echo json_encode(['status' => 'success']);
    exit;

} catch (mysqli_sql_exception $e) {
    // Parse lỗi do TRIGGER quy ước: APP:CODE=...;MSG=...
    $msg  = $e->getMessage();
    $num  = $e->getCode(); // 1062 (duplicate) hoặc 1644 (SIGNAL) v.v.

    $code = 0;      // default
    $reason = null; // tuỳ chọn cho FE

    if (preg_match('/APP:CODE=(\d+);MSG=([A-Z_]+)/', $msg, $m)) {
        $code = (int)$m[1];
        $reason = $m[2];
    } elseif ($num == 1062) {
        // Duplicate key — map theo tên unique/column trong thông báo
        if (stripos($msg, 'uq_taikhoan_email') !== false || stripos($msg, 'Email') !== false) {
            $code = 3; $reason = 'EMAIL_EXISTS';
        } elseif (stripos($msg, 'uq_taikhoan_tentk') !== false || stripos($msg, 'TenTaiKhoan') !== false) {
            $code = 2; $reason = 'USERNAME_EXISTS';
        } else {
            $code = 0;
        }
    }

    http_response_code(400);
    echo json_encode(['status' => 'error', 'code' => $code, 'reason' => $reason]);
    exit;
}



