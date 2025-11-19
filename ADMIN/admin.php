<?php
ob_start();
require_once 'conn.php';
// check_admin_login(); // Giữ nguyên hàm kiểm tra đăng nhập

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];

// Thiết lập hàm xử lý lỗi CSDL từ Trigger
function handle_trigger_error($conn_error_message) {
    // Phân tích mã lỗi do Trigger định nghĩa (APP:CODE=X;MSG=Y)
    if (strpos($conn_error_message, 'APP:CODE=') !== false) {
        if (strpos($conn_error_message, 'EMAIL_EXISTS') !== false) {
            return ['status' => 'error', 'message' => 'Lỗi: Email đã được sử dụng.'];
        } elseif (strpos($conn_error_message, 'USERNAME_EXISTS') !== false) {
            return ['status' => 'error', 'message' => 'Lỗi: Tên tài khoản đã tồn tại.'];
        } elseif (strpos($conn_error_message, 'EMAIL_EMPTY') !== false) {
            return ['status' => 'error', 'message' => 'Lỗi: Email không được để trống.'];
        } elseif (strpos($conn_error_message, 'USERNAME_EMPTY') !== false) {
            return ['status' => 'error', 'message' => 'Lỗi: Tên tài khoản không được để trống.'];
        }
    }
    // Lỗi không phải do Trigger định nghĩa (lỗi MySQL chung)
    return ['status' => 'error', 'message' => 'Lỗi xử lý CSDL: ' . $conn_error_message];
}

try {

    // ==========================================
    // CREATE: Thêm tài khoản Khách hàng
    // **ĐÃ CẬP NHẬT để tận dụng TRIGGER**
    // ==========================================
    if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $fullname = $_POST['fullname'] ?? '';
        
        // Mã hóa mật khẩu
        $hashed_password = hash_password($password);

        // Chèn vào bảng TAIKHOAN. Trigger sẽ tự động sinh MaSoTK ('KH...') và kiểm tra trùng lặp.
        // Cột MaSoTK được SET NULL để Trigger tự sinh.
        $stmt_tk = $conn->prepare("INSERT INTO TAIKHOAN (MaSoTK, MatKhau, TenTaiKhoan, Email, LoaiTaiKhoan) VALUES (NULL, ?, ?, ?, 'KH')");
        // Tham số: MatKhau, TenTaiKhoan, Email
        $stmt_tk->bind_param("sss", $hashed_password, $username, $email);
        
        // Thực thi lệnh. Nếu có lỗi từ TRIGGER, $stmt_tk->execute() sẽ thất bại.
        if (!$stmt_tk->execute()) {
            // Xử lý lỗi từ Trigger
            $response = handle_trigger_error($conn->error);
        } else {
            // Lấy MaSoTK vừa được sinh ra (MaSoTK mới đã có trong CSDL)
            $new_MaSoTK = $conn->insert_id; // Lưu ý: Lấy ID từ cột MaSoTK VARCHAR là không chính xác, ta phải query lại.

            // *Cách lấy MaSoTK chính xác nhất sau khi INSERT bởi Trigger*
            // Giả định Email là duy nhất, ta dùng Email để truy vấn lại Mã số TK.
            $stmt_get_id = $conn->prepare("SELECT MaSoTK FROM TAIKHOAN WHERE Email = ?");
            $stmt_get_id->bind_param("s", $email);
            $stmt_get_id->execute();
            $result_id = $stmt_get_id->get_result();
            $new_MaSoTK = $result_id->fetch_assoc()['MaSoTK'];
            
            // Chèn vào bảng KHACHHANG
            $stmt_kh = $conn->prepare("INSERT INTO KHACHHANG (HoVaTen, MaSoTK) VALUES (?, ?)");
            $stmt_kh->bind_param("ss", $fullname, $new_MaSoTK);
            $stmt_kh->execute();

            $response = ['status' => 'success', 'message' => 'Thêm tài khoản khách hàng thành công. Mã số: ' . $new_MaSoTK];
        }
    }
    
    // ==========================================
    // READ, UPDATE, DELETE (Giữ nguyên logic cũ, hoặc tối ưu nếu cần)
    // ==========================================
    // ... (Thêm logic cho read, update, delete tương tự như lần trước) ...
    // READ
    else if ($action == 'read') {
        $sql = "SELECT T.MaSoTK, T.TenTaiKhoan, T.Email, K.HoVaTen, K.SDT 
                FROM TAIKHOAN T 
                LEFT JOIN KHACHHANG K ON T.MaSoTK = K.MaSoTK
                WHERE T.LoaiTaiKhoan = 'KH'";
        $result = $conn->query($sql);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $response = ['status' => 'success', 'data' => $users];
    }
    // DELETE
    else if ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maSoTK = $_POST['MaSoTK'] ?? '';
        
        $stmt = $conn->prepare("DELETE FROM TAIKHOAN WHERE MaSoTK = ? AND LoaiTaiKhoan = 'KH'");
        $stmt->bind_param("s", $maSoTK);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $response = ['status' => 'success', 'message' => 'Xóa tài khoản ' . $maSoTK . ' thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Không tìm thấy tài khoản để xóa.'];
        }
    }
    // UPDATE
    else if ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // ... (Logic cập nhật: Giữ nguyên cách dùng Transaction nếu có nhiều thao tác CSDL) ...
        $maSoTK = $_POST['MaSoTK'] ?? '';
        $email = $_POST['email'] ?? '';
        $fullname = $_POST['fullname'] ?? '';
        $sdt = $_POST['sdt'] ?? '';
        
        $conn->begin_transaction();

        // 1. Cập nhật bảng TAIKHOAN
        $stmt_tk = $conn->prepare("UPDATE TAIKHOAN SET Email = ? WHERE MaSoTK = ? AND LoaiTaiKhoan = 'KH'");
        $stmt_tk->bind_param("ss", $email, $maSoTK);
        $stmt_tk->execute();
        
        // 2. Cập nhật bảng KHACHHANG
        $stmt_kh = $conn->prepare("UPDATE KHACHHANG SET HoVaTen = ?, SDT = ? WHERE MaSoTK = ?");
        $stmt_kh->bind_param("sss", $fullname, $sdt, $maSoTK);
        $stmt_kh->execute();

        $conn->commit();
        $response = ['status' => 'success', 'message' => 'Cập nhật tài khoản ' . $maSoTK . ' thành công.'];
    }


} catch (Exception $e) {
    // Xử lý lỗi PHP chung
    if ($conn->in_transaction) {
        $conn->rollback();
    }
    $response = ['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
}
ob_clean();
// Trả về kết quả JSON
header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>