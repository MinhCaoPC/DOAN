USE QuanLyDuLich;


-- Tạo tài khoản
DELIMITER $$

CREATE TRIGGER trg_TAIKHOAN_AutoID
BEFORE INSERT ON TAIKHOAN
FOR EACH ROW
BEGIN
    DECLARE v_max INT DEFAULT 0;

    -- Mặc định loại tài khoản
    IF NEW.LoaiTaiKhoan IS NULL OR NEW.LoaiTaiKhoan = '' THEN
        SET NEW.LoaiTaiKhoan = 'KH';
    END IF;

    -- Kiểm tra email trống
    IF NEW.Email IS NULL OR TRIM(NEW.Email) = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'APP:CODE=4;MSG=EMAIL_EMPTY';
    END IF;

    -- Kiểm tra username trống
    IF NEW.TenTaiKhoan IS NULL OR TRIM(NEW.TenTaiKhoan) = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'APP:CODE=5;MSG=USERNAME_EMPTY';
    END IF;

    -- Email đã tồn tại?
    IF EXISTS(SELECT 1 FROM TAIKHOAN WHERE Email = NEW.Email) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'APP:CODE=3;MSG=EMAIL_EXISTS';
    END IF;

    -- Tên tài khoản đã tồn tại?
    IF EXISTS(SELECT 1 FROM TAIKHOAN WHERE TenTaiKhoan = NEW.TenTaiKhoan) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'APP:CODE=2;MSG=USERNAME_EXISTS';
    END IF;

    -- Sinh MaSoTK tự động nếu là KH và chưa set
    IF NEW.LoaiTaiKhoan = 'KH' AND (NEW.MaSoTK IS NULL OR NEW.MaSoTK = '') THEN
        SELECT COALESCE(MAX(CAST(SUBSTRING(MaSoTK, 3) AS UNSIGNED)), 0)
          INTO v_max
        FROM TAIKHOAN
        WHERE MaSoTK LIKE 'KH%';

        SET NEW.MaSoTK = CONCAT('KH', LPAD(v_max + 1, 8, '0'));
    END IF;
END$$

DELIMITER ;


-- Lấy danh sách Tour
DELIMITER $$

CREATE PROCEDURE GetTourList()
BEGIN
    SELECT 
        MaTour, 
        TenTour, 
        MoTaTour, 
        GiaTour, 
        ThoiGianTour, 
        DoiTuong, 
        KhachSan, 
        LichTrinhTour, 
        ImageTourMain, 
        ImageTourSub 
    FROM TOUR 
    WHERE LaNoiBat = 0;
END$$

DELIMITER ;

-- Lấy các Địa danh qua tour
DELIMITER $$

CREATE PROCEDURE GetDiaDanhMap()
BEGIN
    SELECT MaDD, TenDD 
    FROM DIADANH 
    ORDER BY LENGTH(TenDD) DESC;
END$$

DELIMITER ;

-- Lấy các Món ăn qua tour
DELIMITER $$

CREATE PROCEDURE GetMonAnMap()
BEGIN
    SELECT MaMonAn, TenMonAn 
    FROM MONAN 
    ORDER BY LENGTH(TenMonAn) DESC;
END$$

DELIMITER ;

-- Lấy các Khu nghỉ dưỡng qua tour
DELIMITER $$

CREATE PROCEDURE GetKhuNghiDuongMap()
BEGIN
    SELECT MaKND, TenKND 
    FROM KHUNGHIDUONG 
    ORDER BY LENGTH(TenKND) DESC;
END$$

DELIMITER ;


-- Lấy tour nổi bật 
DELIMITER $$

CREATE PROCEDURE GetFeaturedTours()
BEGIN
    SELECT 
        MaTour, 
        TenTour, 
        ImageTourMain, 
        GiaTour, 
        ThoiGianTour 
    FROM TOUR 
    WHERE LaNoiBat = 1;
END$$

DELIMITER ;




