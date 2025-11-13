<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'config.php';


// 🧑‍💻 Bắt buộc phải đăng nhập
if (!isset($_SESSION['MaSoTK'])) {
    echo json_encode([
        'success'   => false,
        'needLogin' => true,
        'message'   => 'Bạn cần đăng nhập.'
    ]);
    exit;
}


$maSoTK = $_SESSION['MaSoTK'];


// Lấy body JSON
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);


$action = $data['action'] ?? 'add';
$loai   = $data['loai']   ?? '';
$id     = (int)($data['id'] ?? 0);


if (!$loai || !$id) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu tham số.'
    ]);
    exit;
}


// Xác định cột ID tương ứng theo loại
$col = null;
switch ($loai) {
    case 'DIADANH':
        $col = 'MaDiaDanh';
        break;
    case 'MONAN':
        $col = 'MaMonAn';
        break;
    case 'KND':
        $col = 'MaKND';
        break;
    case 'TOUR':
        $col = 'MaTour';
        break;
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Loại không hợp lệ.'
        ]);
        exit;
}


if ($action === 'add') {


    // 🔹 Check giới hạn 99 mục yêu thích / tài khoản
    $check = $conn->prepare("SELECT COUNT(*) AS total FROM MUCYEUTHICH WHERE MaSoTK = ?");
    $check->bind_param("s", $maSoTK);
    $check->execute();
    $rs  = $check->get_result();
    $row = $rs->fetch_assoc();
    $check->close();


    if ((int)$row['total'] >= 99) {
        echo json_encode([
            'success' => false,
            'message' => 'Bạn đã đạt tối đa 99 mục yêu thích. Vui lòng xóa bớt trước khi thêm mới.'
        ]);
        exit;
    }


    // 🔹 Kiểm tra đã tồn tại chưa (tự xử lý, không dùng ON DUPLICATE KEY)
    $checkEx = $conn->prepare("
        SELECT MaYeuThich 
        FROM MUCYEUTHICH 
        WHERE MaSoTK = ? AND Loai = ? AND $col = ?
        LIMIT 1
    ");
    $checkEx->bind_param("ssi", $maSoTK, $loai, $id);
    $checkEx->execute();
    $rsEx = $checkEx->get_result();


    if ($rsEx->num_rows > 0) {
        // Đã tồn tại
        echo json_encode([
            'success' => false,
            'reason'  => 'exists',
            'message' => 'Mục này đã có trong danh sách yêu thích.'
        ]);
        exit;
    }
    $checkEx->close();


    // 🔹 Thêm mới
    $sql = "INSERT INTO MUCYEUTHICH (MaSoTK, Loai, $col)
            VALUES (?, ?, ?)";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $maSoTK, $loai, $id);


    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi SQL: ' . $stmt->error
        ]);
    }
    $stmt->close();


} elseif ($action === 'remove') {


    // 🔹 Xóa mục yêu thích
    $sql = "DELETE FROM MUCYEUTHICH 
            WHERE MaSoTK = ? AND Loai = ? AND $col = ?";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $maSoTK, $loai, $id);
    $stmt->execute();


    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy mục cần xóa (có thể đã bị xóa trước đó).'
        ]);
    }


    $stmt->close();


} else {
    echo json_encode([
        'success' => false,
        'message' => 'Action không hợp lệ.'
    ]);
}





