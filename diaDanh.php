<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';


if (!$conn || $conn->connect_error) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connect failed', 'detail' => $conn?->connect_error], JSON_UNESCAPED_UNICODE);
  exit;
}
$conn->set_charset('utf8mb4');


$sql = "
  SELECT
    MaDD,
    TenDD,
    DiaChiDD AS DiaDanh, 
    MapLinkDD,
    MoTaDD,
    ImageDD,
    LoaiDD
  FROM DIADANH
  ORDER BY TenDD
";


$rs = $conn->query($sql);
if ($rs === false) {
  http_response_code(500);
  echo json_encode([
    'error'  => 'Query failed',
    'detail' => $conn->error,   // in rõ lỗi MySQL để debug
    'sql'    => $sql
  ], JSON_UNESCAPED_UNICODE);
  exit;
}


$data = [];
while ($row = $rs->fetch_assoc()) $data[] = $row;
$rs->free();


echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);





