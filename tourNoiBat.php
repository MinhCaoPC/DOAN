<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; // Kết nối CSDL

$featuredTours = [];

// GỌI STORED PROCEDURE GetFeaturedTours
if ($conn->multi_query("CALL GetFeaturedTours()")) {
    $result = $conn->store_result();
    
    if ($result) {
        while ($tour = $result->fetch_assoc()) {
            $featuredTours[] = [
                "id" => (int)$tour['MaTour'],
                "ten" => $tour['TenTour'],
                "anh" => $tour['ImageTourMain'],
                "gia" => number_format($tour['GiaTour'], 0, ',', '.') . ' VNĐ',
                "thoiGian" => $tour['ThoiGianTour']
            ];
        }
        $result->free();
    }
    
    // Rất quan trọng: Xóa bộ đệm kết quả cuối cùng để tránh lỗi truy vấn sau
    while ($conn->next_result()) {
    }
} else {
    // Xử lý lỗi nếu việc gọi Procedure thất bại
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi gọi Stored Procedure: ' . $conn->error
    ]);
    $conn->close();
    exit;
}

$conn->close();

// Trả về JSON
echo json_encode([
    'status' => 'success',
    'featuredTours' => $featuredTours
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>