<?php
session_start();
// Thiết lập header JSON để đảm bảo trình duyệt biết nội dung là JSON
header('Content-Type: application/json; charset=utf-8');


require 'config.php'; // Đảm bảo file config.php kết nối CSDL đúng

// 1. Xử lý trường hợp chưa đăng nhập
if (!isset($_SESSION['MaSoTK'])) {
    echo json_encode([
        'loggedIn' => false,
        'items' => [],
        'error' => 'Người dùng chưa đăng nhập.'
    ]);
    exit;
}

$maSoTK = $_SESSION['MaSoTK'];

// 2. Chuẩn bị lệnh gọi Stored Procedure
$sql = "CALL GetFavoriteItems(?)";

try {
    // Sử dụng prepared statement để truyền tham số
    if (!($stmt = $conn->prepare($sql))) {
        // Lỗi chuẩn bị statement (thường do Stored Procedure không tồn tại)
        throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }
    
    // Bind tham số
    $stmt->bind_param("s", $maSoTK);

    // Thực thi
    if (!$stmt->execute()) {
        // Lỗi thực thi
        throw new Exception("Lỗi thực thi Stored Procedure: " . $stmt->error);
    }

    // Lấy kết quả
    $result = $stmt->get_result();
    
    // Xử lý kết quả
    $items = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $result->free(); // Giải phóng bộ nhớ kết quả
    }

    // Đóng statement
    $stmt->close();
    
    // QUAN TRỌNG: Trong mysqli, sau khi gọi Stored Procedure,
    // cần gọi $conn->next_result() để chuẩn bị cho truy vấn tiếp theo (nếu có)
    // hoặc chỉ đơn giản là dọn dẹp bộ đệm.
    while ($conn->next_result()) {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    }
    
    // 3. Trả về JSON thành công
    echo json_encode([
        'loggedIn' => true,
        'items' => $items
    ]);

} catch (Exception $e) {
    // 4. Bắt lỗi và trả về JSON lỗi
    http_response_code(500); // Thiết lập mã lỗi HTTP
    echo json_encode([
        'loggedIn' => true, // Giả định là đã đăng nhập, nhưng lỗi ở truy vấn
        'items' => [],
        'error' => 'Lỗi CSDL khi lấy yêu thích: ' . $e->getMessage()
    ]);
    
    // Ghi log lỗi để debug
    error_log("YTList.php Error: " . $e->getMessage()); 
}

?>