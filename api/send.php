<?php
header('Content-Type: application/json; charset=utf-8');


require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../config.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Bật log debug (in ra terminal đang chạy php -S)
ini_set('log_errors','1'); ini_set('display_errors','0'); error_reporting(E_ALL);


$raw  = file_get_contents('php://input');
$json = json_decode($raw, true);
$email = '';
if (is_array($json) && isset($json['email'])) $email = trim($json['email']);
elseif (isset($_POST['email']))               $email = trim($_POST['email']);
elseif (isset($_GET['email']))                $email = trim($_GET['email']);


error_log("[send.php] raw=".substr($raw,0,200)." | parsed email=".$email);


if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["status"=>"error","message"=>"Email không hợp lệ!"]);
  exit;
}


// Tồn tại?
$stmt = $conn->prepare("SELECT 1 FROM TAIKHOAN WHERE Email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
  echo json_encode(["status"=>"error","message"=>"Email chưa đăng ký trong hệ thống!"]);
  exit;
}
$stmt->close();


// Chống spam
$check = $conn->prepare("
  SELECT 1 FROM TAIKHOAN
  WHERE Email=? AND ThoiGianXacNhan IS NOT NULL
    AND TIMESTAMPDIFF(SECOND, ThoiGianXacNhan, NOW()) < 60
");
$check->bind_param("s", $email);
$check->execute();
if ($check->get_result()->num_rows > 0) {
  echo json_encode(["status"=>"error","message"=>"Vui lòng thử lại sau ít phút."]);
  exit;
}
$check->close();


$code = random_int(100000, 999999);


// Hàm gửi thử 465 rồi 587
$sendWith = function(int $port, string $secure) use ($email, $code) {
  $m = new PHPMailer(true);


  // BẬT khi cần nhìn hội thoại SMTP (xong test xóa đi cho sạch)
  // $m->SMTPDebug = 2; $m->Debugoutput = 'error_log';
  error_log("[send.php] SMTP try port=$port secure=$secure");


  $m->isSMTP();
  $m->Host       = 'smtp.gmail.com';
  $m->SMTPAuth   = true;
  $m->Username   = 'danangtravelcntt2311@gmail.com';
  $m->Password   = 'bklvblopiwvehgzs'; // 16 ký tự liền
  $m->AuthType   = 'LOGIN';
  $m->CharSet    = 'UTF-8';
  $m->Timeout    = 30;
  $m->SMTPKeepAlive = false;


  if ($secure === 'SMTPS') {
    $m->SMTPSecure  = PHPMailer::ENCRYPTION_SMTPS;
    $m->SMTPAutoTLS = false;
  } else {
    $m->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS;
    $m->SMTPAutoTLS = true;
    $m->SMTPOptions = ['ssl' => [
      'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT
    ]];
  }
  $m->Port = $port;


  $m->setFrom('danangtravelcntt2311@gmail.com', 'Đà Nẵng Travel');
  $m->addAddress($email);
  $m->isHTML(true);
  $m->Subject = 'Mã xác nhận đặt lại mật khẩu - Đà Nẵng Travel';
  $m->Body = "
    <div style='font-family:Arial,sans-serif;line-height:1.6'>
      <h3>Xin chào!</h3>
      <p>Mã xác nhận đặt lại mật khẩu của bạn là:</p>
      <h2 style='color:#1e88e5;letter-spacing:2px'>{$code}</h2>
      <p>Mã có hiệu lực trong <b>10 phút</b>.</p>
      <p>Nếu không phải bạn yêu cầu, vui lòng bỏ qua email này.</p>
    </div>
  ";


  $m->send();
};


try {
  $sendWith(465, 'SMTPS');
} catch (Exception $e1) {
  try {
    $sendWith(587, 'STARTTLS');
  } catch (Exception $e2) {
    error_log("[send.php] send fail: ".$e2->getMessage());
    echo json_encode(["status"=>"error","message"=>"Lỗi gửi email: ".$e2->getMessage()]);
    exit;
  }
}


// Lưu mã khi gửi OK
$upd = $conn->prepare("UPDATE TAIKHOAN SET MaXacNhan=?, ThoiGianXacNhan=NOW() WHERE Email=?");
$upd->bind_param("ss", $code, $email);
$upd->execute();
$upd->close();


echo json_encode(["status"=>"success","message"=>"Mã xác nhận đã được gửi đến $email"]);
$conn->close();





