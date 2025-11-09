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
    MaMonAn,
    TenMonAn,
    DiaChiMonAn,
    MapLinkMonAn,
    MoTaMonAn,
    ImageLinkMonAn,
    LoaiMonAn,
    GiaMonAn
  FROM MONAN
  ORDER BY TenMonAn
";


$rs = $conn->query($sql);
if ($rs === false) {
  http_response_code(500);
  echo json_encode([
    'error'  => 'Query failed',
    'detail' => $conn->error,
    'sql'    => $sql
  ], JSON_UNESCAPED_UNICODE);
  exit;
}


$data = [];
while ($row = $rs->fetch_assoc()) {

$loai = strtolower(trim($row['LoaiMonAn'] ?? ''));

// giữ nguyên nếu là 'thuongthuc'
if (in_array($loai, ['thuongthuc','thuong thuc','thuong-thuc'])) {
    $loai = 'thuongthuc';
}
elseif (in_array($loai, ['đặc sản','dac san','dac-san','dacsan'])) {
    $loai = 'dacSan';
}
elseif (in_array($loai, ['hải sản','hai san','hai-san','haisan'])) {
    $loai = 'haiSan';
}
elseif (in_array($loai, ['đường phố','duong pho','duong-pho','duongpho'])) {
    $loai = 'duongPho';
}
elseif (in_array($loai, ['quà','qua','dac san qua','đặc sản quà','dacsanqua','qua-tang'])) {
    $loai = 'dacSanQua';
}
else {

    $loai = $loai ?: 'dacSan';
}


  $data[] = [
    'MaMonAn'      => $row['MaMonAn'],
    'TenMonAn'     => $row['TenMonAn'],
    'DiaChiMonAn'  => $row['DiaChiMonAn'],
    'MapLinkMonAn' => $row['MapLinkMonAn'],
    'MoTaMonAn'    => $row['MoTaMonAn'],
    'ImageLinkMonAn'=> $row['ImageLinkMonAn'],
    'LoaiMonAn'    => $loai,
    'GiaMonAn'     => $row['GiaMonAn'],
  ];
}
$rs->free();


echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);





