<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; // Hoặc 'ADMIN/conn.php' nếu cần dùng hàm verify_password

// BƯỚC 1: Kiểm tra đăng nhập
if (!isset($_SESSION['MaSoTK'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập.']);
    exit;
}

$maSoTK = $_SESSION['MaSoTK'];

// BƯỚC 2: Lấy dữ liệu JSON
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Lấy mật khẩu người dùng gửi lên để xác thực
$passwordCheck = $data['passwordCheck'] ?? '';

if (empty($passwordCheck)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mật khẩu xác nhận.']);
    exit;
}

// BƯỚC 2.5: KIỂM TRA MẬT KHẨU (LOGIC MỚI THÊM)
try {
    // Lấy mật khẩu đã mã hóa trong DB của user này
    $stmtCheck = $conn->prepare("SELECT MatKhau FROM TAIKHOAN WHERE MaSoTK = ?");
    $stmtCheck->bind_param("s", $maSoTK);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();
    
    if ($resCheck->num_rows === 0) {
        throw new Exception("Tài khoản không tồn tại.");
    }

    $rowAcc = $resCheck->fetch_assoc();
    $hashInDB = $rowAcc['MatKhau'];
    $stmtCheck->close();

    // Kiểm tra mật khẩu
    // Lưu ý: Nếu file config.php của bạn KHÔNG có hàm verify_password, 
    // hãy dùng hàm chuẩn của PHP là password_verify($passwordCheck, $hashInDB)
    // Dưới đây tôi dùng password_verify cho chuẩn PHP:
    if (!password_verify($passwordCheck, $hashInDB)) {
        // Nếu bạn dùng hàm tự viết verify_password thì thay dòng trên bằng:
        // if (!verify_password($passwordCheck, $hashInDB)) {
        
        echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không đúng!']);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi xác thực: ' . $e->getMessage()]);
    exit;
}

// --- NẾU MẬT KHẨU ĐÚNG THÌ MỚI CHẠY TIẾP XUỐNG DƯỚI ---

$tenTaiKhoan = $data['tenTaiKhoan'] ?? '';
$email       = $data['email']       ?? '';
$hoTen       = $data['hoTen']       ?? '';
$gioiTinh    = $data['gioiTinh']    ?? '';
$ngaySinh    = $data['ngaySinh']    ?? null;
$diaChi      = $data['diaChi']      ?? '';
$soDienThoai = $data['soDienThoai'] ?? '';

if (empty($ngaySinh)) {
    $ngaySinh = NULL;
}

// BƯỚC 3: GỌI STORED PROCEDURE (Giữ nguyên logic cũ)
try {
    $conn->set_charset("utf8mb4");

    $sql = "CALL UpdateUserProfile(?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $stmt->bind_param("ssssssss", 
        $maSoTK, $tenTaiKhoan, $email, $hoTen, $gioiTinh, $ngaySinh, $diaChi, $soDienThoai
    );

    if (!$stmt->execute()) {
        throw new Exception("Lỗi thực thi: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $status = $row['result'] ?? 'ERROR';
    
    $stmt->close();
    while($conn->more_results()) { $conn->next_result(); }

    switch ($status) {
        case 'SUCCESS':
            $_SESSION['TenTaiKhoan'] = $tenTaiKhoan;
            echo json_encode(['success' => true, 'message' => 'Cập nhật hồ sơ thành công!']);
            break;

        case 'DUPLICATE_USERNAME':
            echo json_encode(['success' => false, 'message' => 'Tên tài khoản này đã được sử dụng.']);
            break;

        case 'DUPLICATE_EMAIL':
            echo json_encode(['success' => false, 'message' => 'Email này đã được sử dụng.']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Lỗi không xác định khi cập nhật.']);
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}

$conn->close();
?>