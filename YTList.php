<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'config.php';

if (!isset($_SESSION['MaSoTK'])) {
    echo json_encode([
        'loggedIn' => false,
        'items' => []
    ]);
    exit;
}

$maSoTK = $_SESSION['MaSoTK'];

$sql = "
   SELECT 'DIADANH' AS loai, m.MaYeuThich, d.MaDD AS id,
          d.TenDD AS ten, d.MoTaDD AS moTa, d.ImageDD AS anh
   FROM MUCYEUTHICH m
   JOIN DIADANH d ON m.MaDiaDanh = d.MaDD
   WHERE m.MaSoTK = ? AND m.Loai = 'DIADANH'

   UNION ALL

   SELECT 'MONAN' AS loai, m.MaYeuThich, a.MaMonAn AS id,
          a.TenMonAn AS ten, a.MoTaMonAn AS moTa, a.ImageLinkMonAn AS anh
   FROM MUCYEUTHICH m
   JOIN MONAN a ON m.MaMonAn = a.MaMonAn
   WHERE m.MaSoTK = ? AND m.Loai = 'MONAN'

   UNION ALL

   SELECT 'KND' AS loai, m.MaYeuThich, k.MaKND AS id,
          k.TenKND AS ten, k.MoTaKND AS moTa, k.ImageKND AS anh
   FROM MUCYEUTHICH m
   JOIN KHUNGHIDUONG k ON m.MaKND = k.MaKND
   WHERE m.MaSoTK = ? AND m.Loai = 'KND'

   UNION ALL

   SELECT 'TOUR' AS loai, m.MaYeuThich, t.MaTour AS id,
          t.TenTour AS ten, t.MoTaTour AS moTa, t.ImageTourMain AS anh  -- <-- ĐÃ SỬA Ở ĐÂY
   FROM MUCYEUTHICH m
   JOIN TOUR t ON m.MaTour = t.MaTour
   WHERE m.MaSoTK = ? AND m.Loai = 'TOUR'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $maSoTK, $maSoTK, $maSoTK, $maSoTK);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode([
    'loggedIn' => true,
    'items' => $items
]);



