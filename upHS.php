<?php
session_start();
header('Content-Type: application/json; charset=utf-8'); // Đảm bảo header JSON
require_once 'config.php';

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

// Lấy dữ liệu (dùng toán tử ?? để tránh lỗi nếu null)
$tenTaiKhoan = $data['tenTaiKhoan'] ?? '';
$email       = $data['email']       ?? '';
$hoTen       = $data['hoTen']       ?? '';
$gioiTinh    = $data['gioiTinh']    ?? '';
$ngaySinh    = $data['ngaySinh']    ?? null; // Để null nếu rỗng để SQL xử lý date
$diaChi      = $data['diaChi']      ?? '';
$soDienThoai = $data['soDienThoai'] ?? '';

// Xử lý ngày sinh: nếu rỗng thì gửi NULL vào SQL, nếu không thì giữ nguyên
if (empty($ngaySinh)) {
    $ngaySinh = NULL;
}

// BƯỚC 3: GỌI STORED PROCEDURE
try {
    // Đảm bảo kết nối dùng đúng bảng mã
    $conn->set_charset("utf8mb4");

    $sql = "CALL UpdateUserProfile(?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Chuẩn bị statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    // Bind tham số: (s=string, s, s, s, s, s, s, s)
    // Lưu ý: NgaySinh dù là Date nhưng truyền string 'YYYY-MM-DD' vào vẫn ok, 
    // hoặc null thì bind sẽ xử lý.
    $stmt->bind_param("ssssssss", 
        $maSoTK, 
        $tenTaiKhoan, 
        $email, 
        $hoTen, 
        $gioiTinh, 
        $ngaySinh, 
        $diaChi, 
        $soDienThoai
    );

    // Thực thi
    if (!$stmt->execute()) {
        throw new Exception("Lỗi thực thi: " . $stmt->error);
    }

    // Lấy kết quả trả về từ Procedure
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $status = $row['result'] ?? 'ERROR';
    
    $stmt->close();
    // Dọn dẹp buffer để tránh lỗi cho các lệnh sau (nếu có)
    while($conn->more_results()) { $conn->next_result(); }

    // BƯỚC 4: PHẢN HỒI DỰA TRÊN KẾT QUẢ SQL
    switch ($status) {
        case 'SUCCESS':
            // Cập nhật lại Session tên mới nếu thành công
            $_SESSION['TenTaiKhoan'] = $tenTaiKhoan;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Cập nhật hồ sơ thành công!'
            ]);
            break;

        case 'DUPLICATE_USERNAME':
            echo json_encode([
                'success' => false, 
                'message' => 'Tên tài khoản này đã được người khác sử dụng.'
            ]);
            break;

        case 'DUPLICATE_EMAIL':
            echo json_encode([
                'success' => false, 
                'message' => 'Email này đã được người khác sử dụng.'
            ]);
            break;

        default:
            echo json_encode([
                'success' => false, 
                'message' => 'Lỗi không xác định khi cập nhật.'
            ]);
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ]);
}

$conn->close();
?>