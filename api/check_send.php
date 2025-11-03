<?php
declare(strict_types=1);


// Dọn toàn bộ buffer trước, luôn trả JSON
while (ob_get_level()) { ob_end_clean(); }
header('Content-Type: application/json; charset=utf-8');


ini_set('log_errors','1');
ini_set('display_errors','0');
error_reporting(E_ALL);


// ---- NUỐT MỌI OUTPUT TỪ config.php (BOM/khoảng trắng/echo vô ý) ----
ob_start();
require __DIR__ . '/../config.php';
$leak = ob_get_contents();
ob_end_clean();
if ($leak !== '') {
  error_log("[check_send.php] suppressed output from config.php: ".substr($leak,0,120));
}
// --------------------------------------------------------------------


$raw  = file_get_contents('php://input');
$json = json_decode($raw, true);


$email   = '';
$code    = '';
$newPass = '';


if (is_array($json)) {
  $email   = trim($json['email']   ?? '');
  $code    = trim($json['code']    ?? '');
  $newPass = trim($json['newPass'] ?? '');
} else {
  // fallback form
  $email   = trim($_POST['email']   ?? $_GET['email']   ?? '');
  $code    = trim($_POST['code']    ?? $_GET['code']    ?? '');
  $newPass = trim($_POST['newPass'] ?? $_GET['newPass'] ?? '');
}


if ($email === '' || $code === '' || $newPass === '') {
  echo json_encode(["status"=>"error","message"=>"Thiếu thông tin cần thiết!"]);
  exit;
}


$chk = $conn->prepare("
  SELECT MaSoTK
  FROM TAIKHOAN
  WHERE Email = ?
    AND MaXacNhan = ?
    AND TIMESTAMPDIFF(MINUTE, ThoiGianXacNhan, NOW()) <= 10
");
$chk->bind_param("ss", $email, $code);
$chk->execute();
$rs = $chk->get_result();
$chk->close();


if ($rs->num_rows === 0) {
  echo json_encode(["status"=>"error","message"=>"Mã xác nhận không hợp lệ hoặc đã hết hạn!"]);
  exit;
}


$hashed = password_hash($newPass, PASSWORD_BCRYPT);
$upd = $conn->prepare("
  UPDATE TAIKHOAN
  SET MatKhau = ?, MaXacNhan = NULL, ThoiGianXacNhan = NULL
  WHERE Email = ?
");
$upd->bind_param("ss", $hashed, $email);
$upd->execute();
$upd->close();


echo json_encode(["status"=>"success","message"=>"Mật khẩu đã được đặt lại thành công!"]);
$conn->close();





