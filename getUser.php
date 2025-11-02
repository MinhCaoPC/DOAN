<?php
session_start();
header('Content-Type: application/json');

if(isset($_SESSION['TenTaiKhoan'])){
    echo json_encode([
        "loggedIn" => true,
        "TenTaiKhoan" => $_SESSION['TenTaiKhoan']
    ]);
} else {
    echo json_encode([
        "loggedIn" => false
    ]);
}

?>