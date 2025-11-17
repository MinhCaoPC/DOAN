<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; // Kết nối CSDL

// ⭐ SỬA LẠI SQL: Lấy thêm GiaTour và ThoiGianTour
$sql = "SELECT MaTour, TenTour, ImageTourMain, GiaTour, ThoiGianTour 
        FROM TOUR 
        WHERE LaNoiBat = 1";

$result = $conn->query($sql);
if (!$result) {
    // Xử lý lỗi nếu query thất bại
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi truy vấn CSDL: ' . $conn->error
    ]);
    exit;
}

$featuredTours = [];
while ($tour = $result->fetch_assoc()) {
    $featuredTours[] = [
        "id" => (int)$tour['MaTour'],
        "ten" => $tour['TenTour'],
        "anh" => $tour['ImageTourMain'], // Dùng ảnh chính
        
        // ⭐ THÊM VÀO: Giá và Thời gian
        "gia" => number_format($tour['GiaTour'], 0, ',', '.') . ' VNĐ',
        "thoiGian" => $tour['ThoiGianTour']
    ];
}

$conn->close();

// Trả về JSON
echo json_encode([
    'status' => 'success',
    'featuredTours' => $featuredTours
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>