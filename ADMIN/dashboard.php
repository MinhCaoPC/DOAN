<?php
// ADMIN/dashboard.php
ob_start();
require_once 'conn.php'; 

header('Content-Type: application/json');

$stats = [
    'total_accounts' => 0, // Tổng tài khoản
    'total_tours'    => 0, // Tổng tour
    'total_bookings' => 0, // Tổng đơn đặt tour
    'total_contacts' => 0, // Tổng liên hệ
    'total_diadanh'  => 0, // Tổng địa danh
    'total_monan'    => 0, // Tổng món ăn
    'total_resorts'  => 0  // Tổng nghỉ dưỡng
];

$response = ['status' => 'error', 'message' => 'Lỗi không xác định.']; 

try {
    // 1. Đếm Tài khoản (Bao gồm cả KH và AD)
    $res = $conn->query("SELECT COUNT(*) AS total FROM TAIKHOAN");
    $stats['total_accounts'] = $res->fetch_assoc()['total'];

    // 2. Đếm Tour
    $res = $conn->query("SELECT COUNT(*) AS total FROM TOUR");
    $stats['total_tours'] = $res->fetch_assoc()['total'];

    // 3. Đếm Đơn đặt tour (Booking)
    $res = $conn->query("SELECT COUNT(*) AS total FROM LICHSU");
    $stats['total_bookings'] = $res->fetch_assoc()['total'];

    // 4. Đếm Yêu cầu tư vấn
    $res = $conn->query("SELECT COUNT(*) AS total FROM THONGTINTUVAN");
    $stats['total_contacts'] = $res->fetch_assoc()['total'];

    // 5. Đếm Địa danh
    $res = $conn->query("SELECT COUNT(*) AS total FROM DIADANH");
    $stats['total_diadanh'] = $res->fetch_assoc()['total'];

    // 6. Đếm Món ăn
    $res = $conn->query("SELECT COUNT(*) AS total FROM MONAN");
    $stats['total_monan'] = $res->fetch_assoc()['total'];

    // 7. Đếm Khu nghỉ dưỡng
    $res = $conn->query("SELECT COUNT(*) AS total FROM KHUNGHIDUONG");
    $stats['total_resorts'] = $res->fetch_assoc()['total'];

    $response = ['status' => 'success', 'data' => $stats];

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

ob_clean();
echo json_encode($response);
$conn->close();
?>