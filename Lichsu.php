<?php 
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php'; 

if (!$conn || $conn->connect_error) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connect failed', 'detail' => $conn?->connect_error], JSON_UNESCAPED_UNICODE);
  exit;
}
$conn->set_charset('utf8mb4');


