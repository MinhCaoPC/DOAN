<?php
// ADMIN/booking_manager.php
ob_start();
require_once 'conn.php'; 

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];

try {
    // ==========================================
    // 1. READ: Lấy danh sách & Tự động duyệt
    // ==========================================
    if ($action == 'read') {
        // --- LOGIC TỰ ĐỘNG DUYỆT SAU 2 NGÀY ---
        // Cập nhật các đơn 'CXN' đã tạo quá 2 ngày (48 giờ) thành 'TC' và bật CanLuuY = 1
        $autoSql = "UPDATE LICHSU 
                    SET TrangThai = 'TC', CanLuuY = 1 
                    WHERE TrangThai = 'CXN' 
                    AND ThoiGian <= DATE_SUB(NOW(), INTERVAL 2 DAY)";
        $conn->query($autoSql);
        // ---------------------------------------

        // Lấy danh sách kèm tên Tour
        $sql = "SELECT L.*, T.TenTour 
                FROM LICHSU L 
                LEFT JOIN TOUR T ON L.MaTour = T.MaTour 
                ORDER BY L.ThoiGian DESC";
        
        $result = $conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $response = ['status' => 'success', 'data' => $data];
        } else {
             $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

    // ==========================================
    // 2. UPDATE: Sửa trạng thái hoặc thông tin
    // ==========================================
    else if ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maDatTour = $_POST['MaDatTour'] ?? '';
        $trangThai = $_POST['TrangThai'] ?? ''; // TC, CXN, DH, YCH
        
        // Các thông tin khách hàng có thể sửa
        $hoTen     = $_POST['HoVaTenT'] ?? '';
        $sdt       = $_POST['SDTT'] ?? '';
        $email     = $_POST['EmailT'] ?? '';
        $diaChi    = $_POST['DiaChiT'] ?? '';
        $soLuong   = $_POST['SoLuongKhach'] ?? 0;
        
        // Tính lại tổng tiền nếu số lượng khách thay đổi (cần lấy giá tour cũ)
        // Tuy nhiên, để đơn giản ta chỉ cho sửa thông tin liên lạc và trạng thái trước.
        // Nếu muốn tính lại tiền, cần query bảng TOUR lấy GiaTour * SoLuongKhach.
        
        if (empty($maDatTour)) {
            $response = ['status' => 'error', 'message' => 'Thiếu mã đặt tour.'];
            goto end_script;
        }

        $stmt = $conn->prepare("UPDATE LICHSU SET TrangThai=?, HoVaTenT=?, SDTT=?, EmailT=?, DiaChiT=?, SoLuongKhach=? WHERE MaDatTour=?");
        $stmt->bind_param("sssssii", $trangThai, $hoTen, $sdt, $email, $diaChi, $soLuong, $maDatTour);

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Cập nhật đơn hàng thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

    // ==========================================
    // 3. DELETE: Xóa lịch sử
    // ==========================================
    else if ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maDatTour = $_POST['MaDatTour'] ?? '';
        if (empty($maDatTour)) {
            $response = ['status' => 'error', 'message' => 'Thiếu ID.'];
            goto end_script;
        }

        $stmt = $conn->prepare("DELETE FROM LICHSU WHERE MaDatTour = ?");
        $stmt->bind_param("i", $maDatTour);
        
        if ($stmt->execute()) {
             $response = ['status' => 'success', 'message' => 'Đã xóa đơn đặt tour.'];
        } else {
             $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()];
}

end_script:
ob_clean();
echo json_encode($response);
$conn->close();
?>