<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'config.php';

// Hàm ghi log để kiểm tra dữ liệu (DEBUG)
function logDebug($msg) {
    file_put_contents('debug_log.txt', date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
}

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['MaSoTK'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập.']);
    exit;
}

$maSoTK = $_SESSION['MaSoTK'];

// 2. Nhận dữ liệu JSON
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

// Ghi log dữ liệu nhận được để xem lỗi nằm ở đâu
logDebug("User: $maSoTK | Raw Input: " . $raw);

$action = $data['action'] ?? '';
$loai   = $data['loai']   ?? '';
$id     = (int)($data['id'] ?? 0);

// 3. Kiểm tra dữ liệu đầu vào kỹ hơn
if (empty($action) || empty($loai) || $id <= 0) {
    logDebug("ERROR: Thiếu tham số. Action: $action, Loai: $loai, ID: $id");
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu tham số hoặc dữ liệu không hợp lệ.',
        'debug_info' => "Received: action=$action, loai=$loai, id=$id" // Trả về để bạn thấy trên trình duyệt
    ]);
    exit;
}

try {
    // Thiết lập charset cho kết nối để tránh lỗi font/collation ở tầng PHP
    $conn->set_charset("utf8mb4");

    if ($action === 'add') {
        $sql = "CALL AddFavoriteItem(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $maSoTK, $loai, $id);
        
        if (!$stmt->execute()) { throw new Exception($stmt->error); }

        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $resultCode = $row['result'] ?? 'ERROR';
        $stmt->close();
        while($conn->more_results()) { $conn->next_result(); }

        // Phản hồi
        $msgs = [
            'SUCCESS' => 'Đã thêm vào danh sách yêu thích.',
            'LIMIT' => 'Đã đạt giới hạn 99 mục.',
            'EXISTS' => 'Mục này đã có trong danh sách.',
            'INVALID_TYPE' => 'Loại mục không hợp lệ.'
        ];
        
        echo json_encode([
            'success' => ($resultCode === 'SUCCESS'),
            'message' => $msgs[$resultCode] ?? 'Lỗi hệ thống.'
        ]);

    } elseif ($action === 'remove') {
        $sql = "CALL RemoveFavoriteItem(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $maSoTK, $loai, $id);
        
        if (!$stmt->execute()) { throw new Exception($stmt->error); }

        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $affected = (int)($row['affected_rows'] ?? 0);
        $stmt->close();
        while($conn->more_results()) { $conn->next_result(); }

        if ($affected > 0) {
            echo json_encode(['success' => true, 'message' => 'Đã xóa thành công.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy mục cần xóa.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action không hợp lệ.']);
    }

} catch (Exception $e) {
    logDebug("SQL Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Lỗi SQL: ' . $e->getMessage()]);
}
?>