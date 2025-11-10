<?php
// tour.php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; // $conn = new mysqli(...)

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Lấy 1 tour cụ thể (?id=) hoặc tất cả
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if ($id) {
        $sql = "SELECT MaTour, TenTour, MoTaTour, GiaTour, ThoiGianTour, DoiTuong, KhachSan, LichTrinhTour, ImageTour
                FROM TOUR WHERE MaTour = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
    } else {
        $sql = "SELECT MaTour, TenTour, MoTaTour, GiaTour, ThoiGianTour, DoiTuong, KhachSan, LichTrinhTour, ImageTour
                FROM TOUR ORDER BY MaTour ASC";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $tours = [];

    while ($row = $res->fetch_assoc()) {
        // Parse lịch trình: nếu cột LichTrinhTour là JSON array thì decode,
        // còn không thì tách theo dòng/newline.
        $lichTrinhRaw = trim((string)($row['LichTrinhTour'] ?? ''));
        $lichTrinh = [];

        if ($lichTrinhRaw !== '') {
            // Thử decode JSON
            $decoded = json_decode($lichTrinhRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $lichTrinh = array_values(array_filter(array_map('trim', $decoded), fn($s) => $s !== ''));
            } else {
                // Tách newline
                $lichTrinh = preg_split("/\r\n|\r|\n/", $lichTrinhRaw);
                $lichTrinh = array_values(array_filter(array_map('trim', $lichTrinh), fn($s) => $s !== ''));
            }
        }

        $tours[] = [
            'ten'       => $row['TenTour'] ?? '',
            'moTa'      => $row['MoTaTour'] ?? '',
            'thoiGian'  => $row['ThoiGianTour'] ?? '',
            'gia'       => $row['GiaTour'] ?? '',
            'doiTuong'  => $row['DoiTuong'] ?? '',
            'khachSan'  => $row['KhachSan'] ?? '',
            'anh'       => $row['ImageTour'] ?? '',   // đường dẫn ảnh
            'lichTrinh' => $lichTrinh
        ];
    }

    echo json_encode(['status' => 'success', 'tourList' => $tours], JSON_UNESCAPED_UNICODE);

} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}



