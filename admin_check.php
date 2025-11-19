<?php
session_start(); 
require_once 'ADMIN/conn.php'; 

// === 1. LOGIC KIỂM TRA BẢO MẬT ===
// Kiểm tra điều kiện: Nếu Session không tồn tại hoặc loại tài khoản không phải là 'AD'
if (!isset($_SESSION['LoaiTaiKhoan']) || $_SESSION['LoaiTaiKhoan'] !== 'AD') {
    
    // Nếu chưa đăng nhập hoặc không phải Admin, chuyển hướng về trang chủ
    header("Location: TrangChu.html"); 
    exit(); 
}

// === 2. HIỂN THỊ NỘI DUNG HTML ===

// Thiết lập header Content-Type để trình duyệt biết đây là file HTML
header('Content-Type: text/html; charset=utf-8');

// Dùng hàm readfile() để đọc và gửi nội dung của file admin.html
// đến trình duyệt. Trình duyệt sẽ thấy nó như là admin.html.
readfile('admin.html');

exit(); 
?>