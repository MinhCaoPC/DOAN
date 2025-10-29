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
    LoaiTaiKhoan ENUM('KH','AD') DEFAULT 'KH' COMMENT 'Loại tài khoản: KH = Khách hàng, AD = Quản trị viên'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Thông tin tài khoản người dùng';


-- ==========================================
-- BẢNG KHÁCH HÀNG
-- ==========================================
CREATE TABLE KHACHHANG (
    MaKH INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã khách hàng',
    HoTen VARCHAR(100) NOT NULL COMMENT 'Họ tên khách hàng',
    NgaySinh DATE COMMENT 'Ngày sinh',
    SDT VARCHAR(20) COMMENT 'Số điện thoại liên hệ',
    Email VARCHAR(100) COMMENT 'Địa chỉ email liên hệ',
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
    ThongTinLienLac VARCHAR(200) COMMENT 'Cách thức liên lạc (email, sđt, ...)',
    MucQuanTam VARCHAR(200) COMMENT 'Nội dung hoặc chủ đề khách hàng quan tâm',
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
    GiaMonAn DECIMAL(12,2) COMMENT 'Giá món ăn',
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
    GiaKND DECIMAL(12,2) COMMENT 'Giá trung bình dịch vụ',
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
    ImageDD VARCHAR(100) COMMENT 'Link ảnh mô tả địa danh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh sách các địa danh du lịch';

-- ==========================================
-- BẢNG TOUR DU LỊCH
-- ==========================================
CREATE TABLE TOUR (
    MaTour INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã tour du lịch',
    TenTour VARCHAR(150) NOT NULL COMMENT 'Tên tour du lịch',
    GiaTour DECIMAL(12,2) COMMENT 'Giá tour',
    ThoiGianTour VARCHAR(100) COMMENT 'Khoảng thời gian tour',
    MoTaTour TEXT COMMENT 'Thông tin lịch tour',
    LichTrinhTour TEXT COMMENT 'Lịch trình tour tính theo ngày',
    MaDiaDanh INT COMMENT 'Địa danh liên quan',
    MaMonAn INT COMMENT 'Món ăn đặc trưng trong tour',
    MaKND INT COMMENT 'Khu nghỉ dưỡng trong tour',
    FOREIGN KEY (MaDiaDanh) REFERENCES DIADANH(MaDD)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (MaMonAn) REFERENCES MONAN(MaMonAn)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (MaKND) REFERENCES KHUNGHIDUONG(MaKND)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh sách các tour du lịch';

-- ==========================================
-- BẢNG LỊCH SỬ ĐẶT TOUR
-- ==========================================
CREATE TABLE LICHSU (
    MaDatTour INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Mã đặt tour',
    MaSoTK VARCHAR(10) NOT NULL COMMENT 'Tài khoản đặt tour',
    MaTour INT NOT NULL COMMENT 'Tour được đặt',
	SDTT VARCHAR(20) COMMENT 'Số điện thoại đặt tour',
    EmailT VARCHAR(100) NOT NULL COMMENT 'Địa chỉ email đặt tour',
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
    MaSoTK VARCHAR(10) NOT NULL COMMENT 'Người dùng đánh dấu ưa thích',
    Loai VARCHAR(50) COMMENT 'Loại mục (món ăn, tour, địa danh...)',
    MaMonAn INT NULL,
    MaDiaDanh INT NULL,
    MaKND INT NULL,
    MaTour INT NULL,
    PRIMARY KEY (MaSoTK, Loai),
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
