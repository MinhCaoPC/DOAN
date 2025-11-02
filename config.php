<?php
// config.php
$servername = "localhost"; // hoặc IP server MySQL
$username = "root";        // username MySQL của bạn
$password = "26042005";            // password MySQL của bạn
$dbname = "QuanLyDuLich";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("❌ Kết nối thất bại: " . $conn->connect_error);
}


// Thiết lập charset UTF-8 để tránh lỗi tiếng Việt
$conn->set_charset("utf8mb4");
?>
