<?php
// nghiDuong.php
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php'; // $conn = new mysqli(...)

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Nếu muốn xem 1 KND cụ thể, truyền ?id=...
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if ($id) {
        $sql = "SELECT MaKND, TenKND, DiaChiKND, MapLinkKND, LoaiKHD, MoTaKND, ImageKND
                FROM KHUNGHIDUONG
                WHERE MaKND = ?
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
    } else {
        $sql = "SELECT MaKND, TenKND, DiaChiKND, MapLinkKND, LoaiKHD, MoTaKND, ImageKND
                FROM KHUNGHIDUONG
                ORDER BY MaKND ASC";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $diaDanhList = [];
    $mapLinks    = [];

    while ($row = $res->fetch_assoc()) {
        // Ảnh có thể là đường dẫn tuyệt đối / tương đối; giữ nguyên
        $imgPath = $row['ImageKND'] ?? '';
        $fileKey = basename($imgPath ?: '');

        // Map link theo "tên file ảnh" giống web cũ
        if ($fileKey !== '') {
            $mapLinks[$fileKey] = $row['MapLinkKND'] ?? '';
        }

        // Nhóm hiển thị: resort/knd đều đưa về 'nghiduong' để filter khớp web cũ
        // Nếu bạn muốn chia theo LoaiKHD thì có thể map tại đây.
        $nhom = 'nghiduong';

        $diaDanhList[] = [
            'ten'  => $row['TenKND'] ?? '',
            'moTa' => $row['MoTaKND'] ?? '',
            'anh'  => $imgPath,
            'nhom' => $nhom
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



