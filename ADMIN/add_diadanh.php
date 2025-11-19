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

// Nếu không có action, hiển thị hướng dẫn
if (empty($action)) {
    $response = [
        'status' => 'info', 
        'message' => 'API Quản lý Địa danh',
        'endpoints' => [
            'read' => 'GET add_diadanh.php?action=read',
            'add' => 'POST add_diadanh.php?action=add (với form data)',
            'delete' => 'POST add_diadanh.php?action=delete (với MaDD và ImagePath)'
        ]
    ];
    goto end_script;
}

$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];

// ✅ ĐÚNG: Đường dẫn HỆ THỐNG để PHP upload file (từ ADMIN/ lên cấp cha rồi vào anh/)
$fs_upload_dir = __DIR__ . '/../anh/'; 

// ✅ ĐÚNG: Đường dẫn TRÌNH DUYỆT để hiển thị ảnh (từ root domain)
// Sẽ lưu vào DB: /anh/ten_file.jpg (Đây là đường dẫn tối ưu nhất)
$db_image_prefix = '/anh/'; 

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
        
        if (empty($tenDD) || empty($moTaDD) || empty($_FILES['imageDD']['name']) || empty($loaiDD)) {
            $response = ['status' => 'error', 'message' => 'Vui lòng điền đầy đủ Tên, Loại, Mô tả và chọn Ảnh Địa danh.'];
            goto end_script;
        }

        $image_link = '';
        
        // 1. Xử lý File Upload
        if (isset($_FILES['imageDD']) && $_FILES['imageDD']['error'] == UPLOAD_ERR_OK) {
            $file_info = $_FILES['imageDD'];
            $file_extension = pathinfo($file_info['name'], PATHINFO_EXTENSION);
            
            // Kiểm tra định dạng file hợp lệ
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                $response = ['status' => 'error', 'message' => 'Chỉ chấp nhận file ảnh: JPG, PNG, GIF, WEBP'];
                goto end_script;
            }
            
            // Tạo tên file duy nhất 
            $new_file_name = uniqid('dd_', true) . '.' . strtolower($file_extension);
            
            // Đường dẫn FILE SYSTEM để lưu file
            $target_file = $fs_upload_dir . $new_file_name; 

            // Kiểm tra và tạo thư mục anh/ nếu chưa tồn tại
            if (!is_dir($fs_upload_dir)) {
                if (!@mkdir($fs_upload_dir, 0755, true)) {
                    $response = ['status' => 'error', 'message' => 'Không thể tạo thư mục anh/. Kiểm tra quyền thư mục.'];
                    goto end_script;
                }
            }
            
            // Di chuyển file tạm sang thư mục đích
            if (move_uploaded_file($file_info['tmp_name'], $target_file)) {
                // Lưu đường dẫn WEB vào CSDL
                $image_link = $db_image_prefix . $new_file_name; 
            } else {
                $response = ['status' => 'error', 'message' => 'Lỗi khi di chuyển file ảnh. Kiểm tra quyền ghi của thư mục anh/'];
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
            $response = ['status' => 'success', 'message' => 'Thêm Địa danh "' . htmlspecialchars($tenDD) . '" thành công.'];
        } else {
            // Xóa file ảnh nếu insert DB thất bại
            if (!empty($target_file) && file_exists($target_file)) {
                @unlink($target_file);
            }
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
            // Chuyển đổi đường dẫn WEB (/anh/...) sang đường dẫn FILE SYSTEM
            if (!empty($imagePath) && strpos($imagePath, $db_image_prefix) === 0) {
                // Lấy tên file từ đường dẫn web: /anh/dd_xxx.jpg -> dd_xxx.jpg
                $filename = basename($imagePath);
                // Tạo đường dẫn file system
                $file_to_delete = $fs_upload_dir . $filename;
                
                if (file_exists($file_to_delete)) {
                    @unlink($file_to_delete); 
                }
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