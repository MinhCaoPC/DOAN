<?php
$servername = "localhost";
$username   = "root";
$password   = "26042005";
$dbname     = "QuanLyDuLich";

// Rất quan trọng: Bỏ bất kỳ ký tự trắng, dòng trống nào trước <?php

mysqli_report(MYSQLI_REPORT_OFF); 

global $conn;
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
   // Nếu lỗi kết nối, dừng ngay lập tức và trả về JSON lỗi
   // Client JS sẽ nhận response này và hiển thị lỗi chung (code 0)
   http_response_code(500); 
   die(json_encode(['status' => 'error', 'code' => 0, 'reason' => 'Lỗi kết nối CSDL: ' . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");

?>