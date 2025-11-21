<?php
// goiyTour.php - Phiên bản KNN
header('Content-Type: application/json');
require 'config.php'; 
global $conn;

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $data['action'] : '');

// --- PHẦN 1: GIỮ NGUYÊN LOGIC LẤY DANH SÁCH (GET_FEATURES) ---
if ($action === 'get_features') {
    // (Giữ nguyên code cũ của phần này, không thay đổi gì)
    $features = [];
    $tables = [
        'diadanh' => ['table' => 'DIADANH', 'id_col' => 'MaDD', 'name_col' => 'TenDD', 'title' => 'Địa Danh Quan Tâm', 'emoji' => '🌍'],
        'knd' => ['table' => 'KHUNGHIDUONG', 'id_col' => 'MaKND', 'name_col' => 'TenKND', 'title' => 'Khu Nghỉ Dưỡng', 'emoji' => '🏖️'],
        'monan' => ['table' => 'MONAN', 'id_col' => 'MaMonAn', 'name_col' => 'TenMonAn', 'title' => 'Ẩm Thực', 'emoji' => '🍜'],
    ];

    foreach ($tables as $key => $conf) {
        $items = [];
        try {
            $result = $conn->query("SELECT {$conf['id_col']} as id, {$conf['name_col']} as name FROM {$conf['table']} ORDER BY {$conf['name_col']} ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) $items[] = $row;
                $result->free();
            }
            $features[$key] = ['title' => $conf['title'], 'emoji' => $conf['emoji'], 'items' => $items];
        } catch (Exception $e) {
            $features[$key] = ['error' => 'Lỗi: ' . $e->getMessage()];
        }
    }
    echo json_encode(['success' => true, 'features' => $features]);
    exit();
}

// --- PHẦN 2: THUẬT TOÁN KNN (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // 1. Vector Người dùng (User Profile Vector)
    // Gom tất cả ID người dùng chọn vào một mảng duy nhất
    $user_vector = [];
    if(isset($data['diadanh'])) foreach($data['diadanh'] as $id) $user_vector[] = "DD_".$id;
    if(isset($data['knd']))     foreach($data['knd'] as $id)     $user_vector[] = "KND_".$id;
    if(isset($data['monan']))   foreach($data['monan'] as $id)   $user_vector[] = "MA_".$id;

    // Nếu không chọn gì
    if (empty($user_vector)) {
        http_response_code(400);
        echo json_encode(['error' => 'Vui lòng chọn ít nhất một tiêu chí.']);
        exit();
    }

    // 2. Lấy dữ liệu toàn bộ Tour (Training Data)
    // Thay vì SQL lọc, ta lấy hết về để tính toán
    $tours = [];
    
    // Lấy thông tin cơ bản Tour
    $sqlBase = "SELECT MaTour, TenTour FROM TOUR";
    $resultBase = $conn->query($sqlBase);
    while($row = $resultBase->fetch_assoc()) {
        $tours[$row['MaTour']] = [
            'info' => $row,
            'features' => [] // Vector đặc trưng của Tour
        ];
    }

    // Lấy đặc trưng (Features) cho từng Tour và gán vào Vector
    // Dùng prefix DD_, KND_, MA_ để phân biệt các loại ID trùng nhau
    $sqlFeatures = "
        SELECT MaTour, CONCAT('DD_', MaDiaDanh) as FeatureID FROM TOUR_DIADANH
        UNION ALL
        SELECT MaTour, CONCAT('KND_', MaKND) as FeatureID FROM TOUR_KND
        UNION ALL
        SELECT MaTour, CONCAT('MA_', MaMonAn) as FeatureID FROM TOUR_MONAN
    ";
    
    $resultFeat = $conn->query($sqlFeatures);
    while($row = $resultFeat->fetch_assoc()) {
        if(isset($tours[$row['MaTour']])) {
            $tours[$row['MaTour']]['features'][] = $row['FeatureID'];
        }
    }

    // 3. Tính khoảng cách/độ tương đồng (KNN Logic)
    $scored_tours = [];

    foreach ($tours as $maTour => $tourData) {
        $tour_vector = $tourData['features'];
        
        // Bỏ qua tour không có đặc điểm nào (dữ liệu rác)
        if (empty($tour_vector)) continue;

        // --- TÍNH COSINE SIMILARITY ---
        // Công thức: (A giao B) / (sqrt(A) * sqrt(B))
        
        // A giao B: Số lượng đặc điểm trùng nhau
        $intersection = count(array_intersect($user_vector, $tour_vector));
        
        // Nếu không có điểm chung nào, bỏ qua ngay để tối ưu
        if ($intersection == 0) continue;

        // Độ dài vector (số lượng phần tử)
        $len_user = count($user_vector);
        $len_tour = count($tour_vector);

        // Tính điểm (Score)
        // Tránh chia cho 0
        if ($len_user * $len_tour > 0) {
            $similarity = $intersection / (sqrt($len_user) * sqrt($len_tour));
        } else {
            $similarity = 0;
        }

        // Lưu kết quả
        $scored_tours[] = [
            'MaTour' => $tourData['info']['MaTour'],
            'TenTour' => $tourData['info']['TenTour'],
            'Similarity' => $similarity,
            'DiemTuongDong' => $intersection, // Giữ lại field này để hiển thị số lượng trùng
            'TotalFeatures' => $len_tour
        ];
    }

    // 4. Sắp xếp (Ranking) - Tìm K láng giềng gần nhất
    // Sắp xếp giảm dần theo Similarity
    usort($scored_tours, function ($a, $b) {
        return $b['Similarity'] <=> $a['Similarity'];
    });

    // 5. Lấy Top K (K=3)
    $k_neighbors = array_slice($scored_tours, 0, 3);

    // 6. Format lại dữ liệu trả về cho đúng ý Frontend cũ
    $output = [];
    foreach ($k_neighbors as $tour) {
        // Tính lại tỷ lệ % để hiển thị cho đẹp (Frontend mong đợi TyLeTuongDong)
        // Ở đây mình dùng chính điểm Similarity * 100
        $output[] = [
            'MaTour' => $tour['MaTour'],
            'TenTour' => $tour['TenTour'],
            'DiemTuongDong' => $tour['DiemTuongDong'], // Số lượng mục trùng khớp
            'TyLeTuongDong' => number_format($tour['Similarity'] * 100, 2) // Chuyển cosine 0-1 thành 0-100%
        ];
    }

    echo json_encode($output);
    exit();
}
?>