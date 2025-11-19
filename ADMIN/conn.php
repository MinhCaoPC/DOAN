<?php
$servername = "localhost";
$username   = "root";
$password   = "26042005"; 
$dbname     = "QuanLyDuLich";


mysqli_report(MYSQLI_REPORT_OFF); 


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
   throw new Exception("Lỗi kết nối CSDL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}


function verify_password($password, $hash) {
    return password_verify($password, $hash);
}


?>