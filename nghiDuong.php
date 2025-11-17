<?php
// nghiDuong.php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $sql = "SELECT MaKND, TenKND, DiaChiKND, MapLinkKND, LoaiKHD, MoTaKND, ImageKND
            FROM KHUNGHIDUONG
            ORDER BY MaKND ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->get_result();

    $diaDanhList = [];
    $mapLinks    = [];

    while ($row = $res->fetch_assoc()) {
        $imgPath = $row['ImageKND'] ?? '';
        $fileKey = basename($imgPath ?: '');

        if ($fileKey !== '') {
            $mapLinks[$fileKey] = $row['MapLinkKND'] ?? '';
        }

        // ⭐ --- SỬA LẠI LOGIC SWITCH TẠI ĐÂY --- ⭐
        // Chúng ta sẽ lấy LoaiKHD (vuichoi, nghiduong,...)
        $loaiRaw = strtolower(trim($row['LoaiKHD'] ?? 'all'));
        
        // Không cần switch/case nữa, chỉ cần dùng trực tiếp
        // (JavaScript đã xử lý việc map 'haiSan' -> 'vuichoi' rồi)
        $nhom = $loaiRaw; 
        // ⭐ --- HẾT PHẦN SỬA --- ⭐

        $diaDanhList[] = [
            'id'     => (int)$row['MaKND'], // JS đọc 'id'
            'ten'    => $row['TenKND'] ?? '', // JS đọc 'ten'
            'moTa'   => $row['MoTaKND'] ?? '', // JS đọc 'moTa'
            'anh'    => $imgPath,              // JS đọc 'anh'
            'nhom'   => $nhom,                 // JS đọc 'nhom'
            'diaChi' => $row['DiaChiKND'] ?? '' // JS đọc 'diaChi'
        ];
    }

    echo json_encode([
        'status'      => 'success',
        'diaDanhList' => $diaDanhList,
        'mapLinks'    => $mapLinks
    ], JSON_UNESCAPED_UNICODE);

} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg'    => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>