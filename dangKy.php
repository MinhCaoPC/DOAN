<?php
// BẮT ĐẦU OUTPUT BUFFERING
ob_start();

session_start();
include("config.php"); // $conn = new mysqli(...)

// Bắt mysqli ném exception để vào khối catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Headers phải đặt sau ob_start() và sau include (để tránh lỗi Header Already Sent)
header('Content-Type: application/json; charset=utf-8');

$response = ['status' => '', 'code' => 0];

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'code' => 0]);
        exit;
    }

    // Lấy input (BẮT BUỘC)
    $TenTaiKhoan = trim($_POST['TenTaiKhoan'] ?? '');
    $Email       = trim($_POST['Email'] ?? '');
    $MatKhau     = trim($_POST['MatKhau'] ?? '');
    $MatKhau2    = trim($_POST['MatKhau2'] ?? '');
    if (strlen($MatKhau) < 8) {
        echo json_encode(['status' => 'error', 'code' => 6]); 
        exit;
    }

    // 1) Mật khẩu không khớp → code 1
    if ($MatKhau !== $MatKhau2) {
        // KHÔNG CẦN http_response_code(400) vì đây là lỗi client-side
        echo json_encode(['status' => 'error', 'code' => 1]); 
        exit;
    }

    // THÊM LẠI LOGIC CHECK RỖNG SỚM (Code 4 & 5)
    if ($Email === '') {
        http_response_code(400); // Bad Request cho lỗi validation
        echo json_encode(['status' => 'error', 'code' => 4, 'reason' => 'EMAIL_EMPTY']);
        exit;
    }
    if ($TenTaiKhoan === '') {
        http_response_code(400); // Bad Request cho lỗi validation
        echo json_encode(['status' => 'error', 'code' => 5, 'reason' => 'USERNAME_EMPTY']);
        exit;
    }



    $MatKhauHash = password_hash($MatKhau, PASSWORD_BCRYPT);

    // 3) Insert TAIKHOAN 
    $stmt = $conn->prepare("INSERT INTO TAIKHOAN (TenTaiKhoan, Email, MatKhau, LoaiTaiKhoan) VALUES (?, ?, ?, 'KH')");
    $stmt->bind_param("sss", $TenTaiKhoan, $Email, $MatKhauHash);
    $stmt->execute(); // Lỗi Duplicate/Trigger sẽ ném ra mysqli_sql_exception ở đây

    // 4) Lấy MaSoTK vừa tạo
    // ... (Giữ nguyên logic lấy MaSoTK) ...
    $stmt_get = $conn->prepare("SELECT MaSoTK FROM TAIKHOAN WHERE Email=? LIMIT 1");
    $stmt_get->bind_param("s", $Email);
    $stmt_get->execute();
    $res = $stmt_get->get_result();
    $row = $res->fetch_assoc();
    $MaSoTK = $row['MaSoTK'];

    // 5) Thêm KHÁCH HÀNG
    // ... (Giữ nguyên logic thêm KHÁCH HÀNG) ...
    $stmt_kh = $conn->prepare("
        INSERT INTO KHACHHANG (HoVaTen, GioiTinh, NgaySinh, SDT, MaSoTK)
        VALUES (?, NULL, NULL, NULL, ?)");
    $stmt_kh->bind_param("ss", $TenTaiKhoan, $MaSoTK);
    $stmt_kh->execute();

    // 6) Lưu session + trả OK
    $_SESSION['MaSoTK']      = $MaSoTK;
    $_SESSION['TenTaiKhoan'] = $TenTaiKhoan;
    $_SESSION['LoaiTaiKhoan']= 'KH';

    // XÓA MỌI OUTPUT KHÁC VÀ TRẢ VỀ JSON THÀNH CÔNG
    ob_clean(); // Xóa mọi thứ đã được buffer (nếu có)
    echo json_encode(['status' => 'success']);
    exit;

} catch (mysqli_sql_exception $e) {
    // === XỬ LÝ LỖI SQL CỤ THỂ (TRIGGER/DUPLICATE KEY) ===
    ob_clean(); // Xóa buffer để đảm bảo chỉ có JSON trả về
    $msg  = $e->getMessage();
    $num  = $e->getCode(); 

    $code = 0;      
    $reason = null; 

    if (preg_match('/APP:CODE=(\d+);MSG=([A-Z_]+)/', $msg, $m)) {
        $code = (int)$m[1];
        $reason = $m[2];
    } elseif ($num == 1062) {
        // Duplicate key
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

} catch (\Exception $e) { 
    // === KHỐI NÀY XỬ LÝ LỖI CHUNG KHÔNG XÁC ĐỊNH ===
    // Sẽ bắt lỗi PHP khác ngoài lỗi SQL
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'code'   => 0, 
        'reason' => 'Lỗi PHP không xác định: ' . $e->getMessage() 
    ]);
    exit;
}