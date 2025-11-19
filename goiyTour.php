<?php
// goiyTour.php
header('Content-Type: application/json');

// Yêu cầu file cấu hình kết nối CSDL (sẽ khởi tạo biến $conn)
require 'config.php'; 

// Khai báo biến $conn là global để sử dụng
global $conn;

// Lấy tham số action để xác định yêu cầu
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $data['action'] : '');

// ==========================================
// CHỨC NĂNG 1: TẠO DANH SÁCH LỰA CHỌN ĐỘNG (action=get_features)
// ==========================================
if ($action === 'get_features') {
    $features = [];
    $tables = [
        'diadanh' => ['table' => 'DIADANH', 'id_col' => 'MaDD', 'name_col' => 'TenDD', 'title' => 'Địa Danh Quan Tâm', 'emoji' => '🌍'],
        'knd' => ['table' => 'KHUNGHIDUONG', 'id_col' => 'MaKND', 'name_col' => 'TenKND', 'title' => 'Khu Nghỉ Dưỡng', 'emoji' => '🏖️'],
        'monan' => ['table' => 'MONAN', 'id_col' => 'MaMonAn', 'name_col' => 'TenMonAn', 'title' => 'Ẩm Thực', 'emoji' => '🍜'],
    ];

    foreach ($tables as $key => $conf) {
        $items = [];
        try {
            // Dùng $conn->query() cho truy vấn đơn giản này
            $result = $conn->query("SELECT {$conf['id_col']} as id, {$conf['name_col']} as name FROM {$conf['table']} ORDER BY {$conf['name_col']} ASC");
            
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $items[] = $row;
                }
                $result->free();
            }
            
            $features[$key] = [
                'title' => $conf['title'],
                'emoji' => $conf['emoji'],
                'items' => $items
            ];
        } catch (Exception $e) {
            $features[$key] = [
                'error' => 'Lỗi truy vấn ' . $conf['title'] . ': ' . $e->getMessage()
            ];
        }
    }
    
    echo json_encode(['success' => true, 'features' => $features]);
    exit();
}


// ==========================================
// CHỨC NĂNG 2: TÍNH TOÁN GỢI Ý (AJAX POST)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // 1. Lọc và chuẩn hóa dữ liệu đầu vào
    $selected_diadanh = isset($data['diadanh']) ? (is_array($data['diadanh']) ? $data['diadanh'] : []) : [];
    $selected_knd = isset($data['knd']) ? (is_array($data['knd']) ? $data['knd'] : []) : [];
    $selected_monan = isset($data['monan']) ? (is_array($data['monan']) ? $data['monan'] : []) : [];

    $total_selected_features = count($selected_diadanh) + count($selected_knd) + count($selected_monan);

    if ($total_selected_features === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Vui lòng chọn ít nhất một tiêu chí để nhận gợi ý.']);
        exit();
    }

    // 2. Chuẩn bị chuỗi ID cho truy vấn SQL
    $diadanh_in = !empty($selected_diadanh) ? implode(',', array_map('intval', $selected_diadanh)) : '0';
    $knd_in = !empty($selected_knd) ? implode(',', array_map('intval', $selected_knd)) : '0';
    $monan_in = !empty($selected_monan) ? implode(',', array_map('intval', $selected_monan)) : '0';

    // 3. TRUY VẤN SQL TÍNH ĐỘ CHỒNG LẤP (OVERLAP SCORE)
    $sql = "
        SELECT 
            T.MaTour, 
            T.TenTour,
            COUNT(T.MaTour) AS DiemTuongDong
        FROM 
            TOUR T
        JOIN 
            (
                SELECT MaTour FROM TOUR_DIADANH WHERE MaDiaDanh IN ($diadanh_in)
                UNION ALL
                SELECT MaTour FROM TOUR_KND WHERE MaKND IN ($knd_in)
                UNION ALL
                SELECT MaTour FROM TOUR_MONAN WHERE MaMonAn IN ($monan_in)
            ) AS MatchedFeatures ON T.MaTour = MatchedFeatures.MaTour
        GROUP BY 
            T.MaTour, T.TenTour
        ORDER BY 
            DiemTuongDong DESC
        LIMIT 10 
    ";

    $recommended_tours = [];
    try {
        // Sử dụng $conn->query() cho truy vấn phức tạp nhưng đã được sanitize
        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $overlap_score = (int)$row['DiemTuongDong'];
                $ratio = ($overlap_score / $total_selected_features) * 100;
                
                $row['TyLeTuongDong'] = number_format($ratio, 2); 
                $recommended_tours[] = $row;
            }
            $result->free();
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Lỗi truy vấn gợi ý: ' . $e->getMessage()]);
        exit();
    }

    echo json_encode($recommended_tours);
    exit();
}

// Trường hợp request không hợp lệ
http_response_code(400);
echo json_encode(['error' => 'Yêu cầu không hợp lệ hoặc thiếu action.']);
?>