<?php
session_start();
header('Content-Type: application/json');

include 'config.php';
if (isset($_SESSION['TenTaiKhoan'])) {
    
    $tenTaiKhoan = $_SESSION['TenTaiKhoan'];

    // ⭐ SỬA LẠI CÂU SELECT: Thêm k.DiaChi
    $stmt = $conn->prepare("
        SELECT 
            t.MaSoTK, 
            t.Email,
            t.TenTaiKhoan, -- Lấy cả TenTaiKhoan để dùng cho fallback
            k.HoVaTen, 
            k.SDT,
            k.DiaChi 
        FROM TAIKHOAN t
        LEFT JOIN KHACHHANG k ON t.MaSoTK = k.MaSoTK
        WHERE t.TenTaiKhoan = ?
    ");
    
    if ($stmt === false) {
        // (code xử lý lỗi giữ nguyên)
        echo json_encode([ "loggedIn" => false, "error" => "Lỗi query" ]);
        exit;
    }

    $stmt->bind_param("s", $tenTaiKhoan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $_SESSION['MaSoTK'] = $user['MaSoTK'];

        // ⭐ SỬA LẠI JSON: Thêm DiaChi
        echo json_encode([
            "loggedIn" => true,
            "TenTaiKhoan" => $user['TenTaiKhoan'], // Giữ nguyên
            "Email" => $user['Email'],
            "HoVaTen" => $user['HoVaTen'], // Có thể là NULL
            "SDT" => $user['SDT'],         // Có thể là NULL
            "DiaChi" => $user['DiaChi']     // Có thể là NULL
        ]);

    } else {
        // (code xử lý lỗi giữ nguyên)
        session_unset();
        session_destroy();
        echo json_encode(["loggedIn" => false]);
    }
    
    $stmt->close();

} else {
    echo json_encode(["loggedIn" => false]);
}
?>