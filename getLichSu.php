<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'config.php'; // File kết nối CSDL của bạn

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['MaSoTK'])) {
    echo json_encode(['loggedIn' => false, 'history' => []]);
    exit;
}
$maSoTK = $_SESSION['MaSoTK'];

// 2. Truy vấn CSDL
// Chúng ta JOIN LICHSU và TOUR để lấy thông tin chi tiết
$sql = "SELECT 
            l.MaDatTour,
            l.ThoiGian AS NgayDat,
            l.SoLuongKhach,
            l.TongTien,
            l.TrangThai,
            t.TenTour,
            t.ThoiGianTour,
            t.LichTrinhTour,
            t.GiaTour
        FROM LICHSU l
        JOIN TOUR t ON l.MaTour = t.MaTour
        WHERE l.MaSoTK = ?
        ORDER BY l.ThoiGian DESC"; // Sắp xếp theo ngày mới nhất

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $maSoTK);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    
    // 3. Định dạng dữ liệu cho dễ đọc ở frontend
    $row['TongTienFormatted'] = number_format($row['TongTien'], 0, ',', '.') . ' VNĐ';
    $row['NgayDatFormatted'] = date('d/m/Y H:i', strtotime($row['NgayDat']));
    $row['GiaTourFormatted'] = number_format($row['GiaTour'], 0, ',', '.') . ' VNĐ';
    
    // 4. Tạo chuỗi "chiTiet" cho modal
    $row['chiTiet'] = "TOUR: " . $row['TenTour'] . "\n\n"
                   . "⏰ Thời gian: " . $row['ThoiGianTour'] . "\n"
                   . "💰 Giá gốc: " . $row['GiaTourFormatted'] . "/người\n\n"
                   . "🗓 Lịch trình:\n" . $row['LichTrinhTour'];
                   
    $history[] = $row;
}

echo json_encode(['loggedIn' => true, 'history' => $history]);
$stmt->close();
$conn->close();
?>