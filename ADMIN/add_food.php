<?php
// ADMIN/add_food.php
ob_start();
require_once 'conn.php'; 

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];

// Cấu hình đường dẫn ảnh
// Lưu vào thư mục Pic ở cấp ngoài (ngang hàng với thư mục ADMIN) để khớp với data mẫu
$fs_upload_dir = __DIR__ . '/../Pic/'; 
$db_image_prefix = 'pic/'; 

try {
    // ==========================================
    // 1. READ: Lấy danh sách Món ăn
    // ==========================================
    if ($action == 'read') {
        $sql = "SELECT * FROM MONAN ORDER BY MaMonAn DESC";
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
    // 2. ADD: Thêm Món ăn
    // ==========================================
    else if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $tenMon  = $_POST['TenMonAn'] ?? '';
        $diaChi  = $_POST['DiaChiMonAn'] ?? '';
        $mapLink = $_POST['MapLinkMonAn'] ?? '';
        $loaiMon = $_POST['LoaiMonAn'] ?? '';
        $giaMon  = $_POST['GiaMonAn'] ?? '';
        $moTa    = $_POST['MoTaMonAn'] ?? '';

        if (empty($tenMon) || empty($loaiMon)) {
            $response = ['status' => 'error', 'message' => 'Vui lòng nhập Tên món và Loại món.'];
            goto end_script;
        }

        // Xử lý upload ảnh
        $image_link = '';
        if (isset($_FILES['ImageLinkMonAn']) && $_FILES['ImageLinkMonAn']['error'] == UPLOAD_ERR_OK) {
            // Tạo thư mục nếu chưa có
            if (!is_dir($fs_upload_dir)) @mkdir($fs_upload_dir, 0755, true);

            $ext = pathinfo($_FILES['ImageLinkMonAn']['name'], PATHINFO_EXTENSION);
            // Đặt tên file ngẫu nhiên để tránh trùng
            $new_name = uniqid('food_', true) . '.' . strtolower($ext);
            
            if (move_uploaded_file($_FILES['ImageLinkMonAn']['tmp_name'], $fs_upload_dir . $new_name)) {
                $image_link = $db_image_prefix . $new_name; // Lưu vào DB dạng: pic/tenfile.jpg
            } else {
                $response = ['status' => 'error', 'message' => 'Không thể lưu file ảnh.'];
                goto end_script;
            }
        }

        $stmt = $conn->prepare("INSERT INTO MONAN (TenMonAn, DiaChiMonAn, MapLinkMonAn, LoaiMonAn, GiaMonAn, MoTaMonAn, ImageLinkMonAn) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $tenMon, $diaChi, $mapLink, $loaiMon, $giaMon, $moTa, $image_link);

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Thêm món ăn thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

    // ==========================================
    // 3. UPDATE: Sửa Món ăn
    // ==========================================
    else if ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maMon   = $_POST['MaMonAn'] ?? '';
        $tenMon  = $_POST['TenMonAn'] ?? '';
        $diaChi  = $_POST['DiaChiMonAn'] ?? '';
        $mapLink = $_POST['MapLinkMonAn'] ?? '';
        $loaiMon = $_POST['LoaiMonAn'] ?? '';
        $giaMon  = $_POST['GiaMonAn'] ?? '';
        $moTa    = $_POST['MoTaMonAn'] ?? '';

        if (empty($maMon)) {
            $response = ['status' => 'error', 'message' => 'Thiếu mã món ăn.'];
            goto end_script;
        }

        // Kiểm tra ảnh mới
        $has_new_img = false;
        $image_link = '';
        if (isset($_FILES['ImageLinkMonAn']) && $_FILES['ImageLinkMonAn']['error'] == UPLOAD_ERR_OK) {
            $has_new_img = true;
            if (!is_dir($fs_upload_dir)) @mkdir($fs_upload_dir, 0755, true);
            $ext = pathinfo($_FILES['ImageLinkMonAn']['name'], PATHINFO_EXTENSION);
            $new_name = uniqid('food_', true) . '.' . strtolower($ext);
            
            if (move_uploaded_file($_FILES['ImageLinkMonAn']['tmp_name'], $fs_upload_dir . $new_name)) {
                $image_link = $db_image_prefix . $new_name;
            }
        }

        if ($has_new_img) {
            // Xóa ảnh cũ
            $oldRes = $conn->query("SELECT ImageLinkMonAn FROM MONAN WHERE MaMonAn = $maMon");
            if ($oldRow = $oldRes->fetch_assoc()) {
                $oldPath = $oldRow['ImageLinkMonAn'];
                // Chuyển đường dẫn DB (pic/...) sang đường dẫn hệ thống
                $sysPath = str_replace($db_image_prefix, $fs_upload_dir, $oldPath); 
                // Xử lý trường hợp data cũ lưu tên file trực tiếp hoặc đường dẫn khác
                if (!file_exists($sysPath)) {
                    // Thử xóa theo tên file trong folder Pic
                    $sysPath = $fs_upload_dir . basename($oldPath);
                }
                if (file_exists($sysPath)) @unlink($sysPath);
            }

            $stmt = $conn->prepare("UPDATE MONAN SET TenMonAn=?, DiaChiMonAn=?, MapLinkMonAn=?, LoaiMonAn=?, GiaMonAn=?, MoTaMonAn=?, ImageLinkMonAn=? WHERE MaMonAn=?");
            $stmt->bind_param("sssssssi", $tenMon, $diaChi, $mapLink, $loaiMon, $giaMon, $moTa, $image_link, $maMon);
        } else {
            $stmt = $conn->prepare("UPDATE MONAN SET TenMonAn=?, DiaChiMonAn=?, MapLinkMonAn=?, LoaiMonAn=?, GiaMonAn=?, MoTaMonAn=? WHERE MaMonAn=?");
            $stmt->bind_param("ssssssi", $tenMon, $diaChi, $mapLink, $loaiMon, $giaMon, $moTa, $maMon);
        }

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Cập nhật thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

    // ==========================================
    // 4. DELETE: Xóa Món ăn
    // ==========================================
    else if ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maMon = $_POST['MaMonAn'] ?? '';
        $imgPath = $_POST['ImagePath'] ?? '';

        if (empty($maMon)) {
            $response = ['status' => 'error', 'message' => 'Thiếu ID để xóa.'];
            goto end_script;
        }

        $stmt = $conn->prepare("DELETE FROM MONAN WHERE MaMonAn = ?");
        $stmt->bind_param("i", $maMon);
        
        if ($stmt->execute()) {
            // Xóa file ảnh
            if (!empty($imgPath)) {
                $sysPath = $fs_upload_dir . basename($imgPath);
                if (file_exists($sysPath)) @unlink($sysPath);
            }
            $response = ['status' => 'success', 'message' => 'Đã xóa món ăn.'];
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