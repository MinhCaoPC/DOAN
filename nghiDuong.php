<?php

header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // 🟢 THAY ĐỔI: Gọi Stored Procedure thay vì câu SELECT dài dòng
    $sql = "CALL GetKhuNghiDuongList()";
    
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

        $loaiRaw = strtolower(trim($row['LoaiKHD'] ?? 'all'));

        $nhom = $loaiRaw; 

        $diaDanhList[] = [
            'id'     => (int)$row['MaKND'], 
            'ten'    => $row['TenKND'] ?? '', 
            'moTa'   => $row['MoTaKND'] ?? '', 
            'anh'    => $imgPath,              
            'nhom'   => $nhom,                 
            'diaChi' => $row['DiaChiKND'] ?? '' 
        ];
    }


    $stmt->close();
    while($conn->more_results()) { $conn->next_result(); }

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