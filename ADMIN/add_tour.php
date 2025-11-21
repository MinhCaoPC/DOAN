<?php
// ADMIN/add_tour.php
ob_start();
require_once 'conn.php'; 

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];

// Cấu hình đường dẫn ảnh (Lưu vào thư mục images/ ở cấp ngoài)
$fs_upload_dir = __DIR__ . '/../images/'; 
$db_image_prefix = 'images/'; 

// Hàm hỗ trợ upload ảnh
function processUpload($fileInputName, $prefix, $fs_dir, $db_prefix) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == UPLOAD_ERR_OK) {
        if (!is_dir($fs_dir)) @mkdir($fs_dir, 0755, true);
        $ext = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
        $new_name = uniqid($prefix, true) . '.' . strtolower($ext);
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $fs_dir . $new_name)) {
            return $db_prefix . $new_name;
        }
    }
    return null;
}

try {
    // ==========================================
    // 1. READ: Lấy danh sách Tour
    // ==========================================
    if ($action == 'read') {
        $sql = "SELECT * FROM TOUR ORDER BY MaTour DESC";
        $result = $conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) $data[] = $row;
            $response = ['status' => 'success', 'data' => $data];
        } else {
             $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

    // ==========================================
    // 2. ADD: Thêm Tour mới
    // ==========================================
    else if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $tenTour   = $_POST['TenTour'] ?? '';
        $giaTour   = $_POST['GiaTour'] ?? 0;
        $thoiGian  = $_POST['ThoiGianTour'] ?? '';
        $doiTuong  = $_POST['DoiTuong'] ?? '';
        $khachSan  = $_POST['KhachSan'] ?? '';
        $moTa      = $_POST['MoTaTour'] ?? '';
        $lichTrinh = $_POST['LichTrinhTour'] ?? '';
        $laNoiBat  = isset($_POST['LaNoiBat']) ? 1 : 0;

        if (empty($tenTour)) {
            $response = ['status' => 'error', 'message' => 'Tên Tour không được để trống.'];
            goto end_script;
        }

        // Upload 2 ảnh
        $imgMain = processUpload('ImageTourMain', 'tour_main_', $fs_upload_dir, $db_image_prefix) ?? '';
        $imgSub  = processUpload('ImageTourSub', 'tour_sub_', $fs_upload_dir, $db_image_prefix) ?? '';

        $stmt = $conn->prepare("INSERT INTO TOUR (TenTour, MoTaTour, GiaTour, ThoiGianTour, DoiTuong, KhachSan, LichTrinhTour, ImageTourMain, ImageTourSub, LaNoiBat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssssssi", $tenTour, $moTa, $giaTour, $thoiGian, $doiTuong, $khachSan, $lichTrinh, $imgMain, $imgSub, $laNoiBat);

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Thêm Tour thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

    // ==========================================
    // 3. UPDATE: Sửa Tour
    // ==========================================
    else if ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maTour    = $_POST['MaTour'] ?? '';
        $tenTour   = $_POST['TenTour'] ?? '';
        $giaTour   = $_POST['GiaTour'] ?? 0;
        $thoiGian  = $_POST['ThoiGianTour'] ?? '';
        $doiTuong  = $_POST['DoiTuong'] ?? '';
        $khachSan  = $_POST['KhachSan'] ?? '';
        $moTa      = $_POST['MoTaTour'] ?? '';
        $lichTrinh = $_POST['LichTrinhTour'] ?? '';
        $laNoiBat  = isset($_POST['LaNoiBat']) ? 1 : 0;

        if (empty($maTour)) {
            $response = ['status' => 'error', 'message' => 'Thiếu ID Tour.'];
            goto end_script;
        }

        // Xử lý ảnh (Chỉ update nếu có file mới)
        $newImgMain = processUpload('ImageTourMain', 'tour_main_', $fs_upload_dir, $db_image_prefix);
        $newImgSub  = processUpload('ImageTourSub', 'tour_sub_', $fs_upload_dir, $db_image_prefix);

        // Lấy thông tin cũ để xóa ảnh cũ nếu cần (Optional - ở đây mình làm đơn giản là update đè)
        // Xây dựng câu query động
        $sql = "UPDATE TOUR SET TenTour=?, MoTaTour=?, GiaTour=?, ThoiGianTour=?, DoiTuong=?, KhachSan=?, LichTrinhTour=?, LaNoiBat=?";
        $params = [$tenTour, $moTa, $giaTour, $thoiGian, $doiTuong, $khachSan, $lichTrinh, $laNoiBat];
        $types = "ssdssssi";

        if ($newImgMain) {
            $sql .= ", ImageTourMain=?";
            $params[] = $newImgMain;
            $types .= "s";
        }
        if ($newImgSub) {
            $sql .= ", ImageTourSub=?";
            $params[] = $newImgSub;
            $types .= "s";
        }

        $sql .= " WHERE MaTour=?";
        $params[] = $maTour;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Cập nhật Tour thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
        }
    }

    // ==========================================
    // 4. DELETE: Xóa Tour
    // ==========================================
    else if ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maTour = $_POST['MaTour'] ?? '';
        if (empty($maTour)) {
            $response = ['status' => 'error', 'message' => 'Thiếu ID.'];
            goto end_script;
        }

        // Lấy đường dẫn ảnh để xóa file
        $res = $conn->query("SELECT ImageTourMain, ImageTourSub FROM TOUR WHERE MaTour = $maTour");
        $imgs = $res->fetch_assoc();

        $stmt = $conn->prepare("DELETE FROM TOUR WHERE MaTour = ?");
        $stmt->bind_param("i", $maTour);
        
        if ($stmt->execute()) {
            // Xóa file vật lý
            if ($imgs) {
                if ($imgs['ImageTourMain']) @unlink($fs_upload_dir . basename($imgs['ImageTourMain']));
                if ($imgs['ImageTourSub']) @unlink($fs_upload_dir . basename($imgs['ImageTourSub']));
            }
            $response = ['status' => 'success', 'message' => 'Đã xóa Tour.'];
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