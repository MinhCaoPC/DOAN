<?php
session_start();
header('Content-Type: application/json');

// Kết nối CSDL
require_once 'config.php'; 

// 1. Kiểm tra đăng nhập (qua MaSoTK đã lưu từ getUser.php)
if (!isset($_SESSION['MaSoTK'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để đặt tour.', 'needLogin' => true]);
    exit;
}

// 2. Lấy MaSoTK từ session
$maSoTK = $_SESSION['MaSoTK'];

// 3. Lấy dữ liệu JSON từ JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// 4. Validate dữ liệu đầu vào
if (empty($data['MaTour']) || empty($data['HoVaTen']) || empty($data['SDT']) || empty($data['Email']) || empty($data['SoLuong'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc.']);
    exit;
}

// 5. Gán biến
$maTour = (int)$data['MaTour'];
$hoVaTen = $data['HoVaTen'];
$sdt = $data['SDT'];
$email = $data['Email'];
$diaChi = $data['DiaChi'] ?? null; // Có thể null
$soLuongKhach = (int)$data['SoLuong'];

if ($soLuongKhach <= 0) {
     echo json_encode(['success' => false, 'message' => 'Số lượng khách phải lớn hơn 0.']);
     exit;
}

// 6. ⭐ LẤY GIÁ TOUR TỪ CSDL (Bảo mật - Không tin giá từ client)
// (Giả sử bảng TOUR của bạn có cột GiaTour)
$stmt_gia = $conn->prepare("SELECT GiaTour FROM TOUR WHERE MaTour = ?");
$stmt_gia->bind_param("i", $maTour);
$stmt_gia->execute();
$result_gia = $stmt_gia->get_result();

if ($result_gia->num_rows === 0) {
     echo json_encode(['success' => false, 'message' => 'Tour không hợp lệ.']);
     exit;
}
$tour = $result_gia->fetch_assoc();
// Lấy giá từ CSDL (ví dụ: 1000000)
$giaTour = (float)$tour['GiaTour']; 

// 7. ⭐ TÍNH TỔNG TIỀN (Theo yêu cầu của bạn)
$tongTien = $giaTour * $soLuongKhach;

// 8. INSERT vào bảng LICHSU
try {
    $trangThai = 'CXN'; // Mặc định là 'Chờ Xác Nhận'

    $stmt_insert = $conn->prepare("
        INSERT INTO LICHSU 
        (MaSoTK, MaTour, HoVaTenT, SDTT, EmailT, DiaChiT, SoLuongKhach, TongTien, TrangThai, ThoiGian)
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt_insert->bind_param(
        "sissssiis", // s = string, i = integer
        $maSoTK,
        $maTour,
        $hoVaTen,
        $sdt,
        $email,
        $diaChi,
        $soLuongKhach,
        $tongTien,
        $trangThai
    );

    if ($stmt_insert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đặt tour thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu vào CSDL: ' . $stmt_insert->error]);
    }

    $stmt_insert->close();
    $stmt_gia->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ: ' . $e->getMessage()]);
}
?>