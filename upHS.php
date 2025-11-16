<?php
session_start();
require_once 'config.php';

// BƯỚC 1: Kiểm tra đăng nhập
if (!isset($_SESSION['MaSoTK'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập.']);
    exit;
}

$maSoTK = $_SESSION['MaSoTK'];

// BƯỚC 2: Lấy dữ liệu JSON từ JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Gán biến từ dữ liệu (Thêm TenTaiKhoan và Email)
$tenTaiKhoan = $data['tenTaiKhoan'] ?? null;
$email = $data['email'] ?? null;
$hoTen = $data['hoTen'] ?? null;
$gioiTinh = $data['gioiTinh'] ?? null;
$ngaySinh = $data['ngaySinh'] ?? null;
$diaChi = $data['diaChi'] ?? null;
$soDienThoai = $data['soDienThoai'] ?? null;

// BƯỚC 3: KIỂM TRA LOGIC TRÙNG LẶP (QUAN TRỌNG)
// Chúng ta phải kiểm tra xem TenTaiKhoan hoặc Email mới có bị TRÙNG với
// một người dùng KHÁC (MaSoTK != $maSoTK) hay không.

try {
    // 3.1: Kiểm tra Tên Tài Khoản
    $sql_check_tk = "SELECT MaSoTK FROM TAIKHOAN WHERE TenTaiKhoan = ? AND MaSoTK != ?";
    $stmt_check_tk = mysqli_prepare($conn, $sql_check_tk);
    mysqli_stmt_bind_param($stmt_check_tk, 'ss', $tenTaiKhoan, $maSoTK);
    mysqli_stmt_execute($stmt_check_tk);
    $result_tk = mysqli_stmt_get_result($stmt_check_tk);
    
    if (mysqli_num_rows($result_tk) > 0) {
        // Nếu tìm thấy 1 dòng, tức là tên tài khoản đã tồn tại
        echo json_encode(['success' => false, 'message' => 'Tên tài khoản này đã được người khác sử dụng.']);
        mysqli_stmt_close($stmt_check_tk);
        mysqli_close($conn);
        exit;
    }
    mysqli_stmt_close($stmt_check_tk); // Đóng lại để dùng tiếp

    // 3.2: Kiểm tra Email
    $sql_check_email = "SELECT MaSoTK FROM TAIKHOAN WHERE Email = ? AND MaSoTK != ?";
    $stmt_check_email = mysqli_prepare($conn, $sql_check_email);
    mysqli_stmt_bind_param($stmt_check_email, 'ss', $email, $maSoTK);
    mysqli_stmt_execute($stmt_check_email);
    $result_email = mysqli_stmt_get_result($stmt_check_email);
    
    if (mysqli_num_rows($result_email) > 0) {
        // Nếu tìm thấy 1 dòng, tức là email đã tồn tại
        echo json_encode(['success' => false, 'message' => 'Email này đã được người khác sử dụng.']);
        mysqli_stmt_close($stmt_check_email);
        mysqli_close($conn);
        exit;
    }
    mysqli_stmt_close($stmt_check_email);

    // BƯỚC 4: Nếu vượt qua 2 bước kiểm tra, bắt đầu cập nhật CSDL
    mysqli_begin_transaction($conn);

    // 4.1: Cập nhật bảng KHACHHANG (HoVaTen, GioiTinh, NgaySinh, DiaChi, SDT)
    $sql_kh = "UPDATE KHACHHANG SET HoVaTen = ?, GioiTinh = ?, NgaySinh = ?, DiaChi = ?, SDT = ? WHERE MaSoTK = ?";
    $stmt_kh = mysqli_prepare($conn, $sql_kh);
    mysqli_stmt_bind_param($stmt_kh, 'ssssss', $hoTen, $gioiTinh, $ngaySinh, $diaChi, $soDienThoai, $maSoTK);
    mysqli_stmt_execute($stmt_kh);

    // 4.2: Cập nhật bảng TAIKHOAN (TenTaiKhoan, Email)
    $sql_tk = "UPDATE TAIKHOAN SET TenTaiKhoan = ?, Email = ? WHERE MaSoTK = ?";
    $stmt_tk = mysqli_prepare($conn, $sql_tk);
    mysqli_stmt_bind_param($stmt_tk, 'sss', $tenTaiKhoan, $email, $maSoTK);
    mysqli_stmt_execute($stmt_tk);

    // 4.3: Nếu cả 2 đều thành công, xác nhận (commit)
    mysqli_commit($conn);
    $_SESSION['TenTaiKhoan'] = $tenTaiKhoan;

    echo json_encode(['success' => true, 'message' => 'Cập nhật hồ sơ thành công!']);

} catch (mysqli_sql_exception $exception) {
    // Nếu có bất kỳ lỗi nào, hủy bỏ (rollback)
    mysqli_rollback($conn);
    
    http_response_code(500); // Lỗi máy chủ
    echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật CSDL: ' . $exception->getMessage()]);
}

// Đóng các kết nối
if (isset($stmt_kh)) mysqli_stmt_close($stmt_kh);
if (isset($stmt_tk)) mysqli_stmt_close($stmt_tk);
mysqli_close($conn);

?>