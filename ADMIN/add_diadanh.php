<?php
// Bật Output Buffering ngay từ đầu để tránh lỗi JSON
ob_start();

// Bật thông báo lỗi để dễ debug (Tắt khi deploy thực tế)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bao gồm file kết nối CSDL
require_once 'conn.php'; 

header('Content-Type: application/json'); // Thiết lập kiểu trả về JSON

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];

// Đường dẫn hệ thống: Dùng '../pic/' để đi lên một cấp từ ADMIN/ rồi vào pic/
$fs_upload_dir = '../pic/'; 
// Đường dẫn CSDL/Trình duyệt: Dùng '../pic/' để trình duyệt truy cập từ ADMIN/
$db_image_prefix = '../pic/'; 

try {
    
    // ==========================================
    // READ: Lấy danh sách Địa danh
    // ==========================================
    if ($action == 'read') {
        $sql = "SELECT * FROM DIADANH ORDER BY MaDD DESC";
        $result = $conn->query($sql);
        $locations = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $locations[] = $row;
            }
            $response = ['status' => 'success', 'data' => $locations];
        } else {
             $response = ['status' => 'error', 'message' => 'Lỗi truy vấn CSDL: ' . $conn->error];
        }
    }
    
    // ==========================================
    // CREATE: Thêm Địa danh mới (Bao gồm upload ảnh)
    // ==========================================
    else if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $tenDD     = $_POST['tenDD'] ?? '';
        $loaiDD    = $_POST['loaiDD'] ?? '';
        $diaChiDD  = $_POST['diaChiDD'] ?? '';
        $mapLinkDD = $_POST['mapLinkDD'] ?? '';
        $moTaDD    = $_POST['moTaDD'] ?? '';
        
        if (empty($tenDD) || empty($moTaDD) || empty($_FILES['imageDD']['name'])) {
            $response = ['status' => 'error', 'message' => 'Vui lòng điền đầy đủ Tên, Mô tả và chọn Ảnh Địa danh.'];
            goto end_script;
        }

        $image_link = '';
        
        // 1. Xử lý File Upload
        if (isset($_FILES['imageDD']) && $_FILES['imageDD']['error'] == UPLOAD_ERR_OK) {
            $file_info = $_FILES['imageDD'];
            $file_extension = pathinfo($file_info['name'], PATHINFO_EXTENSION);
            
            // Tạo tên file duy nhất 
            $new_file_name = uniqid('dd_', true) . '.' . strtolower($file_extension);
            // Đường dẫn file TẠM THỜI trên server
            $target_file = $fs_upload_dir . $new_file_name; 

            // Kiểm tra và tạo thư mục pic/ nếu chưa tồn tại
            if (!is_dir($fs_upload_dir)) {
                @mkdir($fs_upload_dir, 0777, true); 
            }
            
            // Di chuyển file tạm sang thư mục đích
            if (move_uploaded_file($file_info['tmp_name'], $target_file)) {
                // Lưu đường dẫn TƯƠNG ĐỐI TRÌNH DUYỆT vào CSDL
                $image_link = $db_image_prefix . $new_file_name; 
            } else {
                $response = ['status' => 'error', 'message' => 'Lỗi khi di chuyển file ảnh. Kiểm tra quyền ghi của thư mục `../pic/`.'];
                goto end_script;
            }
        } else {
             $response = ['status' => 'error', 'message' => 'Lỗi upload file: ' . ($_FILES['imageDD']['error'] ?? 'Không có file')];
             goto end_script;
        }

        // 2. Chèn dữ liệu vào CSDL
        $stmt = $conn->prepare("INSERT INTO DIADANH (TenDD, DiaChiDD, MapLinkDD, MoTaDD, ImageDD, LoaiDD) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssss", $tenDD, $diaChiDD, $mapLinkDD, $moTaDD, $image_link, $loaiDD);
        
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Thêm Địa danh **' . htmlspecialchars($tenDD) . '** thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Lỗi CSDL khi chèn Địa danh: ' . $conn->error];
        }
    }
    
    // ==========================================
    // DELETE: Xóa Địa danh
    // ==========================================
    else if ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maDD = $_POST['MaDD'] ?? '';
        $imagePath = $_POST['ImagePath'] ?? ''; 

        if (empty($maDD)) {
            $response = ['status' => 'error', 'message' => 'Thiếu Mã Địa danh để xóa.'];
            goto end_script;
        }

        // 1. Xóa Địa danh khỏi CSDL
        $stmt = $conn->prepare("DELETE FROM DIADANH WHERE MaDD = ?");
        $stmt->bind_param("i", $maDD);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // 2. Xóa file ảnh trên server
            // Dùng $imagePath (ví dụ: ../pic/dd_...jpg) để xóa file vật lý
            if (!empty($imagePath) && strpos($imagePath, $db_image_prefix) === 0 && file_exists($imagePath)) {
                @unlink($imagePath); 
            }
            $response = ['status' => 'success', 'message' => 'Xóa Địa danh ' . $maDD . ' thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Không tìm thấy Địa danh để xóa hoặc lỗi CSDL: ' . $conn->error];
        }
    }


} catch (Exception $e) {
    // Xử lý lỗi PHP chung
    $response = ['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
}

end_script: 
// Xóa mọi nội dung dư thừa khỏi buffer
ob_clean();

// Trả về kết quả JSON cuối cùng
echo json_encode($response);
$conn->close();
exit;
?>