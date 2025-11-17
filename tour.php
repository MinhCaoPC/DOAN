<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; // Kết nối CSDL

// BƯỚC 1: TẠO CÁC "BẢN ĐỒ" TỪ KHÓA
// Lấy tất cả địa danh, món ăn, KND để biết tên và ID của chúng
$mapDiaDanh = [];
$mapMonAn = [];
$mapKND = [];

// Lấy Địa danh (sắp xếp từ dài đến ngắn để thay thế chính xác)
$resultDD = $conn->query("SELECT MaDD, TenDD FROM DIADANH ORDER BY LENGTH(TenDD) DESC");
while ($row = $resultDD->fetch_assoc()) {
    // Key là tên (ví dụ: 'Bà Nà Hills'), Value là link
    $mapDiaDanh[$row['TenDD']] = "diaDanh.html#item-dd-" . $row['MaDD'];
}

// Lấy Món ăn (sắp xếp từ dài đến ngắn)
$resultMA = $conn->query("SELECT MaMonAn, TenMonAn FROM MONAN ORDER BY LENGTH(TenMonAn) DESC");
while ($row = $resultMA->fetch_assoc()) {
    $mapMonAn[$row['TenMonAn']] = "Food.html#item-monan-" . $row['MaMonAn'];
}

// Lấy Khu nghỉ dưỡng (sắp xếp từ dài đến ngắn)
$resultKND = $conn->query("SELECT MaKND, TenKND FROM KHUNGHIDUONG ORDER BY LENGTH(TenKND) DESC");
while ($row = $resultKND->fetch_assoc()) {
    $mapKND[$row['TenKND']] = "nghiDuong.html#item-knd-" . $row['MaKND'];
}

// Gộp tất cả bản đồ lại, ưu tiên Địa danh > Món Ăn > KND
$masterMap = $mapDiaDanh + $mapMonAn + $mapKND;

// Lấy các keys (từ khóa) và các values (links)
$keywords = array_keys($masterMap);
$links = array_values($masterMap);

// Tạo mảng thẻ <a> để thay thế
// Ví dụ: "Bà Nà Hills" sẽ được thay bằng "<a href='diaDanh.html#item-dd-1'>Bà Nà Hills</a>"
$replacements = [];
foreach ($masterMap as $keyword => $link) {
    // Thêm class 'itinerary-link' để bạn có thể CSS nếu muốn
    $replacements[] = '<a href="' . $link . '" class="itinerary-link">' . htmlspecialchars($keyword) . '</a>';
}


$sql = "SELECT MaTour, TenTour, MoTaTour, GiaTour, ThoiGianTour, DoiTuong, KhachSan, LichTrinhTour, ImageTourMain, ImageTourSub FROM TOUR WHERE LaNoiBat = 0";
$resultTour = $conn->query($sql);

$tourList = [];
while ($tour = $resultTour->fetch_assoc()) {
    
    // Xử lý lịch trình (LichTrinhTour)
    $lichTrinhText = $tour['LichTrinhTour'] ?? '';
    
    // Tách lịch trình theo từng dòng (xuống hàng)
    $lines = explode("\n", $lichTrinhText);
    
    $processedLichTrinh = [];
    foreach ($lines as $line) {
        if (empty(trim($line))) continue;
        
        // Đây là phép màu:
        // Thay thế tất cả $keywords (ví dụ: "Bà Nà Hills")
        // bằng $replacements (ví dụ: "<a href=...'>Bà Nà Hills</a>")
        // trong dòng ($line) này.
        $processedLine = str_replace($keywords, $replacements, $line);
        
        $processedLichTrinh[] = $processedLine; // Thêm dòng đã xử lý vào mảng
    }

    // Tạo mảng dữ liệu trả về cho JS
    $tourList[] = [
        "id" => (int)$tour['MaTour'],
        "ten" => $tour['TenTour'],
        "anh" => $tour['ImageTourMain'], // Dùng ImageTourMain cho ảnh chính của tour
        "anhSub" => $tour['ImageTourSub'],
        "thoiGian" => $tour['ThoiGianTour'],
        "gia" => number_format($tour['GiaTour'], 0, ',', '.') . ' VNĐ', // Định dạng giá tiền
        "doiTuong" => $tour['DoiTuong'],
        "khachSan" => $tour['KhachSan'],
        "lichTrinh" => $processedLichTrinh // Trả về lịch trình ĐÃ CHỨA link HTML
    ];
}

$conn->close();

// Trả về JSON
echo json_encode([
    'status' => 'success',
    'tourList' => $tourList
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>