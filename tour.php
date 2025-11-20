<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; 

function executeAndFetchMap($conn, $procedureName, $idColumn, $nameColumn, $linkPrefix) {
    $map = [];
    
    if ($conn->multi_query("CALL $procedureName()")) {
        
        $result = $conn->store_result();
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $map[$row[$nameColumn]] = $linkPrefix . $row[$idColumn];
            }
            $result->free();
        }
        
        while ($conn->next_result()) {
        }
    } else {
        error_log("Lỗi khi gọi SP $procedureName: " . $conn->error);
    }
    return $map;
}

// =========================================================================
// BƯỚC 1: GỌI CÁC STORED PROCEDURE ĐỂ TẠO "BẢN ĐỒ" TỪ KHÓA
// =========================================================================

$mapDiaDanh = executeAndFetchMap($conn, 'GetDiaDanhMap', 'MaDD', 'TenDD', 'diaDanh.html#item-dd-');
$mapMonAn = executeAndFetchMap($conn, 'GetMonAnMap', 'MaMonAn', 'TenMonAn', 'Food.html#item-monan-');
$mapKND = executeAndFetchMap($conn, 'GetKhuNghiDuongMap', 'MaKND', 'TenKND', 'nghiDuong.html#item-knd-');

$masterMap = $mapDiaDanh + $mapMonAn + $mapKND;

$keywords = array_keys($masterMap);

$replacements = [];
foreach ($masterMap as $keyword => $link) {
    $replacements[] = '<a href="' . $link . '" class="itinerary-link">' . htmlspecialchars($keyword) . '</a>';
}

// =========================================================================
// BƯỚC 2: GỌI STORED PROCEDURE LẤY DANH SÁCH TOUR
// =========================================================================
$tourList = [];

if ($conn->multi_query("CALL GetTourList()")) {
    $resultTour = $conn->store_result();

    if ($resultTour) {
        while ($tour = $resultTour->fetch_assoc()) {
            
            $lichTrinhText = $tour['LichTrinhTour'] ?? '';
            $lines = explode("\n", $lichTrinhText);
            $processedLichTrinh = [];
            
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                $processedLine = str_replace($keywords, $replacements, $line);
                
                $processedLichTrinh[] = $processedLine;
            }

            $tourList[] = [
                "id" => (int)$tour['MaTour'],
                "ten" => $tour['TenTour'],
                "anh" => $tour['ImageTourMain'], 
                "anhSub" => $tour['ImageTourSub'],
                "thoiGian" => $tour['ThoiGianTour'],
                "gia" => number_format($tour['GiaTour'], 0, ',', '.') . ' VNĐ',
                "doiTuong" => $tour['DoiTuong'],
                "khachSan" => $tour['KhachSan'],
                "lichTrinh" => $processedLichTrinh
            ];
        }
        $resultTour->free();
    }
    
    while ($conn->next_result()) {
    }
} else {
    error_log("Lỗi khi gọi SP GetTourList: " . $conn->error);
}


$conn->close();

echo json_encode([
    'status' => 'success',
    'tourList' => $tourList
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>