-- ==========================================
-- TẠO CƠ SỞ DỮ LIỆU DU LỊCH
-- ==========================================
CREATE DATABASE IF NOT EXISTS QuanLyDuLich
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE QuanLyDuLich;

-- ==========================================
-- BẢNG TÀI KHOẢN NGƯỜI DÙNG
-- ==========================================
CREATE TABLE TAIKHOAN (
    MaSoTK VARCHAR(10) PRIMARY KEY COMMENT 'Mã số tài khoản (VD: KH0001 hoặc AD0001)',
    MatKhau VARCHAR(100) NOT NULL COMMENT 'Mật khẩu đăng nhập',
    TenTaiKhoan VARCHAR(100) NOT NULL COMMENT 'Tên người dùng hoặc tài khoản',
    Email VARCHAR(100) NOT NULL COMMENT 'Địa chỉ email tài khoản',
    LoaiTaiKhoan ENUM('KH','AD') DEFAULT 'KH' COMMENT 'Loại tài khoản: KH = Khách hàng, AD = Quản trị viên',
    MaXacNhan VARCHAR(10) NULL COMMENT 'Mã xác nhận tạm thời cho quên mật khẩu',
    ThoiGianXacNhan DATETIME NULL COMMENT 'Thời điểm mã được tạo'

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Thông tin tài khoản người dùng';


-- ==========================================
-- BẢNG KHÁCH HÀNG
-- ==========================================
CREATE TABLE KHACHHANG (
	HoVaTen VARCHAR(100) COMMENT'Họ và tên của khách hàng',
    GioiTinh ENUM('Nam', 'Nữ', 'Khác') COMMENT 'Giới tính',
    NgaySinh DATE COMMENT 'Ngày sinh',
    DiaChi VARCHAR(255) NULL COMMENT 'Địa chỉ của khách hàng',
    SDT VARCHAR(20) COMMENT 'Số điện thoại liên hệ',
    MaSoTK VARCHAR(10) NOT NULL COMMENT 'Liên kết đến tài khoản người dùng',
    FOREIGN KEY (MaSoTK) REFERENCES TAIKHOAN(MaSoTK)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Thông tin khách hàng đã đăng ký tài khoản';

-- ==========================================
-- BẢNG THÔNG TIN TƯ VẤN
-- ==========================================
CREATE TABLE THONGTINTUVAN (
    MaTV INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã tư vấn',
    HoVaTenTuVan VARCHAR(100) NOT NULL COMMENT 'Họ và tên khách hàng tư vấn',
    EmailTuVan VARCHAR(100) NOT NULL COMMENT 'Địa chỉ email khách hàng tư vấn',
    SoDienThoaiTuVan VARCHAR(20) NOT NULL COMMENT 'Số điện thoại liên hệ khách hàng',
    ChuDeQuanTam VARCHAR(200) COMMENT 'Nội dung hoặc chủ đề khách hàng quan tâm',
    NoiDung TEXT COMMENT 'Nội dung chi tiết tư vấn',
    ThoiGianTao DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo yêu cầu tư vấn',
    MaSoTK VARCHAR(10) NULL COMMENT 'Tài khoản liên quan (nếu có)',
    FOREIGN KEY (MaSoTK) REFERENCES TAIKHOAN(MaSoTK)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Thông tin tư vấn khách hàng';

-- ==========================================
-- BẢNG MÓN ĂN
-- ==========================================
CREATE TABLE MONAN (
    MaMonAn INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã món ăn',
    TenMonAn VARCHAR(100) NOT NULL COMMENT 'Tên món ăn',
    DiaChiMonAn VARCHAR(100) COMMENT 'Khu vực hoặc nơi nổi tiếng món ăn',
    MapLinkMonAn varchar(600) COMMENT 'link lấy từ google map',
    LoaiMonAn varchar(100) COMMENT 'Phân loại món ăn',
    GiaMonAn VARCHAR(100) COMMENT 'Khoảng giá mon an (ví dụ: 30.000 – 40.000 VNĐ/người)',
    MoTaMonAn TEXT COMMENT 'Thông tin mô tả món ăn',
    ImageLinkMonAn varchar(100) COMMENT 'Đường dẫn lấy ảnh món ăn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Thông tin các món ăn đặc sản';

-- ==========================================
-- BẢNG KHU NGHỈ DƯỠNG
-- ==========================================
CREATE TABLE KHUNGHIDUONG (
    MaKND INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã khu nghỉ dưỡng',
    TenKND VARCHAR(100) NOT NULL COMMENT 'Tên khu nghỉ dưỡng',
    DiaChiKND VARCHAR(200) COMMENT 'Địa chỉ khu nghỉ dưỡng',
    MapLinkKND VARCHAR(600) COMMENT 'link lấy từ google map',
    LoaiKHD VARCHAR(100) COMMENT 'Loại khu nghỉ dưỡng',
    MoTaKND TEXT COMMENT 'Thông tin mô tả khu nghỉ dưỡng',
    ImageKND VARCHAR(100) COMMENT 'Link ảnh mô tả khu nghỉ dưỡng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Thông tin khu nghỉ dưỡng';

-- ==========================================
-- BẢNG ĐỊA DANH
-- ==========================================
CREATE TABLE DIADANH (
    MaDD INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã địa danh',
    TenDD VARCHAR(100) NOT NULL COMMENT 'Tên địa danh',
    DiaChiDD VARCHAR(200) COMMENT 'Vị trí địa lý',
    MapLinkDD VARCHAR(600) COMMENT 'link map lất từ google map',
    MoTaDD TEXT COMMENT 'Thông tin chi tiết về địa danh',
    ImageDD VARCHAR(100) COMMENT 'Link ảnh mô tả địa danh',
    LoaiDD VARCHAR(100) COMMENT 'Nhom dia danh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh sách các địa danh du lịch';

-- ==========================================
-- BẢNG TOUR DU LỊCH
-- ==========================================
CREATE TABLE TOUR (
    MaTour INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã tour du lịch',
    TenTour VARCHAR(200) NOT NULL COMMENT 'Tên tour du lịch',
    MoTaTour TEXT COMMENT 'Mô tả khái quát về tour',
    GiaTour DECIMAL(10,2 )COMMENT ' giá tour (ví dụ 4.000.000 VNĐ/người)',
    ThoiGianTour VARCHAR(100) COMMENT 'Thời gian tour (ví dụ: 3 ngày 2 đêm)',
    DoiTuong VARCHAR(200) COMMENT 'Đối tượng khách phù hợp (nhóm bạn, cặp đôi, v.v.)',
    KhachSan VARCHAR(200) COMMENT 'Thông tin khách sạn hoặc resort trong tour',
    LichTrinhTour TEXT COMMENT 'Lịch trình chi tiết theo ngày',
    ImageTourMain VARCHAR(150) COMMENT 'Đường dẫn ảnh minh họa tour chính',
    ImageTourSub VARCHAR(150) COMMENT 'Đường dẫn ảnh minh họa tour phụ',
    LaNoiBat BOOLEAN DEFAULT FALSE COMMENT 'Đánh dấu tour nổi bật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh sách các tour du lịch';

CREATE TABLE TOUR_DIADANH (
    MaTour INT NOT NULL COMMENT 'Khóa ngoại từ bảng TOUR',
    MaDiaDanh INT NOT NULL COMMENT 'Khóa ngoại từ bảng DIADANH (MaDD)',
    
    -- Khóa chính phức hợp, đảm bảo 1 tour không bị lặp 1 địa danh
    PRIMARY KEY (MaTour, MaDiaDanh), 
    
    -- Khóa ngoại liên kết đến bảng TOUR
    FOREIGN KEY (MaTour) REFERENCES TOUR(MaTour)
        ON DELETE CASCADE ON UPDATE CASCADE,
        
    -- Khóa ngoại liên kết đến bảng DIADANH
    -- Lưu ý: Cột MaDD trong bảng DIADANH của bạn
    FOREIGN KEY (MaDiaDanh) REFERENCES DIADANH(MaDD) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Liên kết Tour và Địa danh';

CREATE TABLE TOUR_MONAN (
    MaTour INT NOT NULL COMMENT 'Khóa ngoại từ bảng TOUR',
    MaMonAn INT NOT NULL COMMENT 'Khóa ngoại từ bảng MONAN',
    
    -- Khóa chính
    PRIMARY KEY (MaTour, MaMonAn),
    
    -- Khóa ngoại
    FOREIGN KEY (MaTour) REFERENCES TOUR(MaTour)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (MaMonAn) REFERENCES MONAN(MaMonAn)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Liên kết Tour và Món ăn';




CREATE TABLE TOUR_KND (
    MaTour INT NOT NULL COMMENT 'Khóa ngoại từ bảng TOUR',
    MaKND INT NOT NULL COMMENT 'Khóa ngoại từ bảng KHUNGHIDUONG',
    
    -- Khóa chính
    PRIMARY KEY (MaTour, MaKND),
    
    -- Khóa ngoại
    FOREIGN KEY (MaTour) REFERENCES TOUR(MaTour)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (MaKND) REFERENCES KHUNGHIDUONG(MaKND)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Liên kết Tour và Khu nghỉ dưỡng';



-- ==========================================
-- BẢNG LỊCH SỬ ĐẶT TOUR
-- ==========================================
CREATE TABLE LICHSU (
    MaDatTour INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã đặt tour',
    MaSoTK VARCHAR(10) NOT NULL COMMENT 'Tài khoản đặt tour',
    MaTour INT NOT NULL COMMENT 'Tour được đặt',
    HoVaTenT VARCHAR(100) COMMENT 'Họ và tên khách đặt tour',
	SDTT VARCHAR(20) COMMENT 'Số điện thoại đặt tour',
    EmailT VARCHAR(100) NOT NULL COMMENT 'Địa chỉ email đặt tour',
    DiaChiT VARCHAR(255) NULL COMMENT 'Địa chỉ của khách hàng đặt tour',
    SoLuongKhach TINYINT COMMENT 'Số lượng khách đi tour đó',
    TongTien DECIMAL(12,2) COMMENT 'Tổng số tiền tour',
    TrangThai ENUM('TC','CXN','DH','YCH') COMMENT 'Trang thái tour đặt thành công, chờ xác nhận, đã huỷ, yêu cầu huỷ',
    ThoiGian DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian đặt tour',
    FOREIGN KEY (MaSoTK) REFERENCES TAIKHOAN(MaSoTK)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (MaTour) REFERENCES TOUR(MaTour)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Lịch sử đặt tour của người dùng';

-- ==========================================
-- BẢNG MỤC ƯA THÍCH
-- ==========================================
CREATE TABLE MUCYEUTHICH (
   MaYeuThich INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã dòng yêu thích',
   MaSoTK VARCHAR(10) NOT NULL COMMENT 'Người dùng đánh dấu ưa thích',
   Loai ENUM('DIADANH','MONAN','KND','TOUR') NOT NULL COMMENT 'Loại mục',
   MaMonAn INT NULL,
   MaDiaDanh INT NULL,
   MaKND INT NULL,
   MaTour INT NULL,


   -- Không cho trùng 1 mục yêu thích 2 lần
   UNIQUE KEY uk_yeuthich (MaSoTK, Loai, MaMonAn, MaDiaDanh, MaKND, MaTour),


   FOREIGN KEY (MaSoTK) REFERENCES TAIKHOAN(MaSoTK)
       ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (MaMonAn) REFERENCES MONAN(MaMonAn)
       ON DELETE SET NULL ON UPDATE CASCADE,
   FOREIGN KEY (MaDiaDanh) REFERENCES DIADANH(MaDD)
       ON DELETE SET NULL ON UPDATE CASCADE,
   FOREIGN KEY (MaKND) REFERENCES KHUNGHIDUONG(MaKND)
       ON DELETE SET NULL ON UPDATE CASCADE,
   FOREIGN KEY (MaTour) REFERENCES TOUR(MaTour)
       ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh sách các mục người dùng yêu thích';


DROP DATABASE QuanLyDuLich;















