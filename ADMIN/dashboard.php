<?php

ob_start(); 


error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'conn.php'; 


$stats = [
    'total_tours' => 0,
    'total_customers' => 0,
    'new_contacts' => 0,
    'total_locations' => 0
];

$output = ['status' => 'error', 'message' => 'Lỗi không xác định.']; 

try {
    
    $sql_tours = "SELECT COUNT(MaTour) AS total FROM TOUR";
    $result_tours = $conn->query($sql_tours);
    $stats['total_tours'] = $result_tours->fetch_assoc()['total'];

    
    $sql_customers = "SELECT COUNT(MaSoTK) AS total FROM TAIKHOAN WHERE LoaiTaiKhoan = 'KH'";
    $result_customers = $conn->query($sql_customers);
    $stats['total_customers'] = $result_customers->fetch_assoc()['total'];

    
    $sql_contacts = "SELECT COUNT(MaTV) AS total FROM THONGTINTUVAN WHERE ThoiGianTao >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $result_contacts = $conn->query($sql_contacts);
    $stats['new_contacts'] = $result_contacts->fetch_assoc()['total'];

    
    $total_dd = $conn->query("SELECT COUNT(MaDD) AS total FROM DIADANH")->fetch_assoc()['total'];
    $total_ma = $conn->query("SELECT COUNT(MaMonAn) AS total FROM MONAN")->fetch_assoc()['total'];
    $total_knd = $conn->query("SELECT COUNT(MaKND) AS total FROM KHUNGHIDUONG")->fetch_assoc()['total'];
    $stats['total_locations'] = $total_dd + $total_ma + $total_knd;

    
    $output = ['status' => 'success', 'data' => $stats];

} catch (Exception $e) {
    
    http_response_code(500);
    $output = ['status' => 'error', 'message' => 'Lỗi truy vấn CSDL: ' . $e->getMessage()];
}


ob_clean(); 

header('Content-Type: application/json');


echo json_encode($output);


$conn->close();
exit; 
?>