<?php
session_start();
header('Content-Type: application/json');

// Kết nối CSDL
require_once 'config.php'; 

// 1. Lấy dữ liệu JSON từ JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// 2. Validate dữ liệu đầu vào (dựa trên các trường 'required' trong form)
if (empty($data['name']) || empty($data['sdt']) || empty($data['email']) || empty($data['message'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường bắt buộc.']);
    exit;
}

// 3. Gán biến từ JSON
$hoVaTen = $data['name'];
$sdt = $data['sdt'];
$email = $data['email'];
$noiDung = $data['message'];
$chuDe = $data['subject'] ?? null; // Chủ đề không bắt buộc

// 4. Lấy MaSoTK (nếu người dùng đã đăng nhập)
// File này sẽ tự động biết người dùng đăng nhập hay chưa
$maSoTK = $_SESSION['MaSoTK'] ?? null;

// 5. INSERT vào bảng THONGTINTUVAN
try {
    $sql = "INSERT INTO THONGTINTUVAN 
                (HoVaTenTuVan, EmailTuVan, SoDienThoaiTuVan, ChuDeQuanTam, NoiDung, MaSoTK, ThoiGianTao)
            VALUES 
                (?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    // s = string (6 tham số)
    $stmt->bind_param(
        "ssssss",
        $hoVaTen,
        $email,
        $sdt,
        $chuDe,
        $noiDung,
        $maSoTK
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Gửi yêu cầu thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu vào CSDL: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code(500); // Lỗi máy chủ
    echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ: ' . $e->getMessage()]);
}
?>