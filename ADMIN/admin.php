<?php
// ADMIN/admin.php
ob_start();
require_once 'conn.php'; 

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Hành động không hợp lệ.'];

// --- 1. HÀM TẠO MÃ KH (PHP tự tính cho trường hợp Update từ AD -> KH) ---
function taoMaKH($conn) {
    $sql = "SELECT MaSoTK FROM TAIKHOAN WHERE LoaiTaiKhoan = 'KH' ORDER BY MaSoTK DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $lastId = $result->fetch_assoc()['MaSoTK']; 
        $number = intval(substr($lastId, 2)); 
        return 'KH' . str_pad($number + 1, 8, '0', STR_PAD_LEFT);
    }
    return 'KH00000001';
}

// --- 2. HÀM TẠO MÃ AD ---
function taoMaAD($conn) {
    $sql = "SELECT MaSoTK FROM TAIKHOAN WHERE LoaiTaiKhoan = 'AD' ORDER BY MaSoTK DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $lastId = $result->fetch_assoc()['MaSoTK']; 
        $number = intval(substr($lastId, 2)); 
        return 'AD' . str_pad($number + 1, 8, '0', STR_PAD_LEFT);
    }
    return 'AD00000001';
}

try {
    // ==========================================
    // 1. READ: Lấy TOÀN BỘ danh sách
    // ==========================================
    if ($action == 'read') {
        $sql = "SELECT T.MaSoTK, T.TenTaiKhoan, T.Email, T.LoaiTaiKhoan, K.HoVaTen, K.SDT 
                FROM TAIKHOAN T 
                LEFT JOIN KHACHHANG K ON T.MaSoTK = K.MaSoTK
                ORDER BY T.LoaiTaiKhoan ASC, T.MaSoTK DESC"; 
        
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
    // 2. ADD: Thêm Tài khoản
    // ==========================================
    else if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $tenTK = $_POST['TenTaiKhoan'] ?? '';
        $matKhau = $_POST['MatKhau'] ?? '';
        $email = $_POST['Email'] ?? '';
        $hoTen = $_POST['HoVaTen'] ?? ''; 
        $sdt = $_POST['SDT'] ?? '';

        if (empty($tenTK) || empty($matKhau) || empty($email)) {
            $response = ['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc.'];
            goto end_script;
        }

        $check = $conn->prepare("SELECT MaSoTK FROM TAIKHOAN WHERE TenTaiKhoan = ? OR Email = ?");
        $check->bind_param("ss", $tenTK, $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $response = ['status' => 'error', 'message' => 'Tên đăng nhập hoặc Email đã tồn tại.'];
            goto end_script;
        }

        $passHash = md5($matKhau); 
        $loaiTK = 'KH'; 

        $conn->begin_transaction();
        try {
            // Insert TAIKHOAN (Để Trigger tự sinh MaSoTK)
            $stmt1 = $conn->prepare("INSERT INTO TAIKHOAN (TenTaiKhoan, MatKhau, Email, LoaiTaiKhoan) VALUES (?, ?, ?, ?)");
            $stmt1->bind_param("ssss", $tenTK, $passHash, $email, $loaiTK);
            
            if (!$stmt1->execute()) throw new Exception($stmt1->error);

            // Lấy lại MaSoTK vừa sinh
            $stmtGetID = $conn->prepare("SELECT MaSoTK FROM TAIKHOAN WHERE TenTaiKhoan = ?");
            $stmtGetID->bind_param("s", $tenTK);
            $stmtGetID->execute();
            $newID = $stmtGetID->get_result()->fetch_assoc()['MaSoTK'];

            // Insert KHACHHANG
            $stmt2 = $conn->prepare("INSERT INTO KHACHHANG (MaSoTK, HoVaTen, SDT) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $newID, $hoTen, $sdt);
            if (!$stmt2->execute()) throw new Exception("Lỗi thêm chi tiết: " . $stmt2->error);

            $conn->commit();
            $response = ['status' => 'success', 'message' => "Đã thêm tài khoản $newID."];
        } catch (Exception $ex) {
            $conn->rollback();
            $msg = $ex->getMessage();
            if (strpos($msg, 'EMAIL_EXISTS') !== false) $msg = "Email đã tồn tại.";
            if (strpos($msg, 'USERNAME_EXISTS') !== false) $msg = "Tên đăng nhập đã tồn tại.";
            $response = ['status' => 'error', 'message' => $msg];
        }
    }

    // ==========================================
    // 3. UPDATE: Sửa & Phân quyền (2 CHIỀU)
    // ==========================================
    else if ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maSoTK  = $_POST['MaSoTK'] ?? '';
        $email   = $_POST['Email'] ?? '';
        $hoTen   = $_POST['HoVaTen'] ?? ''; 
        $sdt     = $_POST['SDT'] ?? '';
        $matKhau = $_POST['MatKhau'] ?? '';
        $loaiTK_Moi = $_POST['LoaiTaiKhoan'] ?? 'KH'; 

        if (empty($maSoTK) || empty($email)) {
            $response = ['status' => 'error', 'message' => 'Thiếu Mã TK hoặc Email.'];
            goto end_script;
        }

        $conn->begin_transaction();
        try {
            $changeID = false;
            $newMaSoTK = $maSoTK; // Mặc định giữ nguyên
            $msg = "Cập nhật thành công.";

            // --- LOGIC ĐỔI MÃ 2 CHIỀU ---
            // 1. Nếu đang KH -> chuyển sang AD
            if (strpos($maSoTK, 'KH') === 0 && $loaiTK_Moi == 'AD') {
                $changeID = true;
                $newMaSoTK = taoMaAD($conn); 
                $msg = "Đã nâng cấp lên Admin ($newMaSoTK).";
            }
            // 2. Nếu đang AD -> chuyển về KH
            else if (strpos($maSoTK, 'AD') === 0 && $loaiTK_Moi == 'KH') {
                $changeID = true;
                $newMaSoTK = taoMaKH($conn); 
                $msg = "Đã chuyển về Khách hàng ($newMaSoTK).";
            }

            // 1. Update TAIKHOAN
            // Xây dựng câu SQL dựa trên việc có đổi ID và có đổi Pass không
            $sqlSet = "Email=?";
            $params = [$email];
            $types = "s";

            if (!empty($matKhau)) { // Có đổi pass
                $sqlSet .= ", MatKhau=?";
                $params[] = md5($matKhau);
                $types .= "s";
            }

            if ($changeID) { // Có đổi quyền & đổi mã
                $sqlSet .= ", LoaiTaiKhoan=?, MaSoTK=?";
                $params[] = $loaiTK_Moi;
                $params[] = $newMaSoTK;
                $types .= "ss";
            }

            // WHERE MaSoTK cũ
            $sql = "UPDATE TAIKHOAN SET $sqlSet WHERE MaSoTK=?";
            $params[] = $maSoTK;
            $types .= "s";

            $stmt1 = $conn->prepare($sql);
            $stmt1->bind_param($types, ...$params);

            if (!$stmt1->execute()) throw new Exception("Lỗi update Tài khoản: " . $stmt1->error);

            // 2. Update KHACHHANG
            // Do có ON UPDATE CASCADE, ID bên KHACHHANG đã tự đổi theo $newMaSoTK
            // Ta cần đảm bảo dữ liệu KHACHHANG tồn tại (đặc biệt khi từ AD -> KH, có thể AD chưa có row trong KHACHHANG)
            
            $checkKH = $conn->query("SELECT MaSoTK FROM KHACHHANG WHERE MaSoTK = '$newMaSoTK'");
            
            if ($checkKH->num_rows > 0) {
                // Đã có row -> Update
                $stmt2 = $conn->prepare("UPDATE KHACHHANG SET HoVaTen=?, SDT=? WHERE MaSoTK=?");
                $stmt2->bind_param("sss", $hoTen, $sdt, $newMaSoTK);
                $stmt2->execute();
            } else {
                // Chưa có row -> Insert mới
                $stmt2 = $conn->prepare("INSERT INTO KHACHHANG (MaSoTK, HoVaTen, SDT) VALUES (?, ?, ?)");
                $stmt2->bind_param("sss", $newMaSoTK, $hoTen, $sdt);
                $stmt2->execute();
            }

            $conn->commit();
            $response = ['status' => 'success', 'message' => $msg];

        } catch (Exception $ex) {
            $conn->rollback();
            $response = ['status' => 'error', 'message' => $ex->getMessage()];
        }
    }

    // ==========================================
    // 4. DELETE
    // ==========================================
    else if ($action == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $maSoTK = $_POST['MaSoTK'] ?? '';
        if (empty($maSoTK)) {
            $response = ['status' => 'error', 'message' => 'Thiếu ID.'];
            goto end_script;
        }
        $stmt = $conn->prepare("DELETE FROM TAIKHOAN WHERE MaSoTK = ?");
        $stmt->bind_param("s", $maSoTK);
        
        if ($stmt->execute()) $response = ['status' => 'success', 'message' => 'Đã xóa.'];
        else $response = ['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error];
    }

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
}

end_script:
ob_clean();
echo json_encode($response);
$conn->close();
?>