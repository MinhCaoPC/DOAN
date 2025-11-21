<?php
// ADMIN/add_nghiduong.php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'conn.php'; 

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if (empty($action)) {
    echo json_encode(['status' => 'info', 'message' => 'API Quản lý Khu Nghỉ Dưỡng']);
    exit;
}

$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];
$fs_upload_dir = __DIR__ . '/../anh/'; // Thư mục lưu ảnh trên server
$db_image_prefix = '/anh/'; // Đường dẫn ảnh lưu vào DB

try {
    // ==========================================
    // READ: Lấy danh sách Nghỉ Dưỡng
    // ==========================================
    if ($action == 'read') {
        $sql = "SELECT * FROM KHUNGHIDUONG ORDER BY MaKND DESC";
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
    // CREATE: Thêm Nghỉ Dưỡng Mới
    // ==========================================
    else if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $tenKND    = $_POST['tenKND'] ?? '';
        $loaiKHD   = $_POST['loaiKHD'] ?? ''; // Lưu ý: Cột trong DB là LoaiKHD
        $diaChiKND = $_POST['diaChiKND'] ?? '';
        $mapLinkKND= $_POST['mapLinkKND'] ?? '';
        $moTaKND   = $_POST['moTaKND'] ?? '';
        
        if (empty($tenKND) || empty($moTaKND) || empty($_FILES['imageKND']['name'])) {
            $response = ['status' => 'error', 'message' => 'Vui lòng điền đủ Tên, Mô tả và Ảnh.'];
            goto end_script;
        }

        $image_link = '';
        if (isset($_FILES['imageKND']) && $_FILES['imageKND']['error'] == UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imageKND']['name'], PATHINFO_EXTENSION);
            $new_name = uniqid('knd_', true) . '.' . strtolower($ext); // Prefix knd_
            $target = $fs_upload_dir . $new_name;
            
            if (!is_dir($fs_upload_dir)) @mkdir($fs_upload_dir, 0755, true);
            
            if (move_uploaded_file($_FILES['imageKND']['tmp_name'], $target)) {
                $image_link = $db_image_prefix . $new_name;
            } else {
                $response = ['status' => 'error', 'message' => 'Lỗi upload ảnh.'];
                goto end_script;
            }
        }

        $stmt = $conn->prepare("INSERT INTO KHUNGHIDUONG (TenKND, DiaChiKND, MapLinkKND, MoTaKND, ImageKND, LoaiKHD) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $tenKND, $diaChiKND, $mapLinkKND, $moTaKND, $image_link, $loaiKHD);
        
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Thêm "' . $tenKND . '" thành công.'];
        } else {
            if (file_exists($target)) @unlink($target);
            $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }
    
    // ==========================================
    // UPDATE: Sửa Nghỉ Dưỡng
    // ==========================================
    else if ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maKND     = $_POST['MaKND'] ?? '';
        $tenKND    = $_POST['tenKND'] ?? '';
        $loaiKHD   = $_POST['loaiKHD'] ?? '';
        $diaChiKND = $_POST['diaChiKND'] ?? '';
        $mapLinkKND= $_POST['mapLinkKND'] ?? '';
        $moTaKND   = $_POST['moTaKND'] ?? '';

        if (empty($maKND) || empty($tenKND)) {
            $response = ['status' => 'error', 'message' => 'Thiếu thông tin cập nhật.'];
            goto end_script;
        }

        $has_new_img = false;
        $image_link = '';
        
        if (isset($_FILES['imageKND']) && $_FILES['imageKND']['error'] == UPLOAD_ERR_OK) {
            $has_new_img = true;
            $ext = pathinfo($_FILES['imageKND']['name'], PATHINFO_EXTENSION);
            $new_name = uniqid('knd_', true) . '.' . strtolower($ext);
            $target = $fs_upload_dir . $new_name;
            
            if (move_uploaded_file($_FILES['imageKND']['tmp_name'], $target)) {
                $image_link = $db_image_prefix . $new_name;
            }
        }

        if ($has_new_img) {
            // Lấy ảnh cũ để xóa
            $stmt_get = $conn->prepare("SELECT ImageKND FROM KHUNGHIDUONG WHERE MaKND=?");
            $stmt_get->bind_param("i", $maKND);
            $stmt_get->execute();
            $old_img = $stmt_get->get_result()->fetch_assoc()['ImageKND'] ?? '';
            
            // Update có ảnh mới
            $stmt = $conn->prepare("UPDATE KHUNGHIDUONG SET TenKND=?, DiaChiKND=?, MapLinkKND=?, MoTaKND=?, LoaiKHD=?, ImageKND=? WHERE MaKND=?");
            $stmt->bind_param("ssssssi", $tenKND, $diaChiKND, $mapLinkKND, $moTaKND, $loaiKHD, $image_link, $maKND);
            
            if ($stmt->execute()) {
                if (!empty($old_img)) {
                    $old_file = $fs_upload_dir . basename($old_img);
                    if (file_exists($old_file)) @unlink($old_file);
                }
                $response = ['status' => 'success', 'message' => 'Cập nhật thành công.'];
            }
        } else {
            // Update không đổi ảnh
            $stmt = $conn->prepare("UPDATE KHUNGHIDUONG SET TenKND=?, DiaChiKND=?, MapLinkKND=?, MoTaKND=?, LoaiKHD=? WHERE MaKND=?");
            $stmt->bind_param("sssssi", $tenKND, $diaChiKND, $mapLinkKND, $moTaKND, $loaiKHD, $maKND);
            if ($stmt->execute()) $response = ['status' => 'success', 'message' => 'Cập nhật thông tin thành công.'];
        }
    }

    // ==========================================
    // DELETE: Xóa Nghỉ Dưỡng
    // ==========================================
    else if ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maKND = $_POST['MaKND'] ?? '';
        $imagePath = $_POST['ImagePath'] ?? '';

        $stmt = $conn->prepare("DELETE FROM KHUNGHIDUONG WHERE MaKND = ?");
        $stmt->bind_param("i", $maKND);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            if (!empty($imagePath)) {
                $file_del = $fs_upload_dir . basename($imagePath);
                if (file_exists($file_del)) @unlink($file_del);
            }
            $response = ['status' => 'success', 'message' => 'Đã xóa thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Xóa thất bại.'];
        }
    }

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()];
}

end_script:
ob_clean();
echo json_encode($response);
$conn->close();
exit;
?>