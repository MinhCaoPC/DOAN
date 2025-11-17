<?php
session_start();
header('Content-Type: application/json');
require 'config.php'; // File kết nối CSDL của bạn

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['MaSoTK'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.', 'needLogin' => true]);
    exit;
}
$maSoTK = $_SESSION['MaSoTK'];

// 2. Lấy MaDatTour từ JSON mà JavaScript gửi lên
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['maDatTour'])) {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
    exit;
}
$maDatTour = (int)$data['maDatTour'];

// 3. Cập nhật trạng thái
// Chỉ cho phép hủy khi trạng thái là 'CXN' (Chờ xác nhận)
// Thêm "AND MaSoTK = ?" để bảo mật, đảm bảo người dùng chỉ hủy được tour của chính mình
$sql = "UPDATE LICHSU 
        SET TrangThai = 'DH' 
        WHERE MaDatTour = ? 
          AND MaSoTK = ? 
          AND TrangThai = 'CXN'"; 
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $maDatTour, $maSoTK);
$stmt->execute();

// 4. Kiểm tra xem cập nhật có thành công không
if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Đã hủy tour thành công.']);
} else {
    // Lỗi (có thể do tour đã được xác nhận, đã hủy trước đó, hoặc không phải của họ)
    echo json_encode(['success' => false, 'message' => 'Không thể hủy tour này. Vui lòng liên hệ hỗ trợ.']);
}
$stmt->close();
$conn->close();
?>