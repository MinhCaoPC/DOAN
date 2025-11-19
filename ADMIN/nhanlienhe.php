<?php
ob_start();
require_once 'conn.php';
// check_admin_login(); // Giữ nguyên hàm kiểm tra đăng nhập

$response = ['status' => 'error', 'message' => 'Không có dữ liệu.'];

try {
    // Lấy tất cả yêu cầu tư vấn mới nhất
    $sql = "SELECT * FROM THONGTINTUVAN ORDER BY ThoiGianTao DESC";
    $result = $conn->query($sql);
    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
    
    $response = ['status' => 'success', 'data' => $contacts];

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Lỗi truy vấn CSDL: ' . $e->getMessage()];
}

// BƯỚC QUAN TRỌNG: Xóa mọi thứ đã được in ra (bao gồm cả Warning/Notice nếu có)
ob_clean();

// Gửi Header ngay trước khi gửi JSON
header('Content-Type: application/json');

// Chỉ in ra JSON từ biến $response
echo json_encode($response);

$conn->close();
exit; // Dừng việc thực thi file
?>