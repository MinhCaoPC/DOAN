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




DELIMITER $$

CREATE PROCEDURE GetFavoriteItems(IN p_MaSoTK VARCHAR(10))
BEGIN
    -- Định nghĩa một bộ ký tự chung để so sánh (ví dụ: utf8mb4_unicode_ci)
    -- Giả sử MaSoTK đang gây ra lỗi collation
    DECLARE common_collation VARCHAR(50) DEFAULT 'utf8mb4_unicode_ci'; 

    SELECT
        'DIADANH' AS loai,
        m.MaYeuThich,
        d.MaDD AS id,
        d.TenDD AS ten,
        d.MoTaDD AS moTa,
        d.ImageDD AS anh
    FROM MUCYEUTHICH m
    JOIN DIADANH d ON m.MaDiaDanh = d.MaDD
    -- Áp dụng COLLATE cho MaSoTK để thống nhất bộ ký tự so sánh
    WHERE m.MaSoTK COLLATE utf8mb4_unicode_ci = p_MaSoTK COLLATE utf8mb4_unicode_ci AND m.Loai = 'DIADANH'

    UNION ALL

    SELECT
        'MONAN' AS loai,
        m.MaYeuThich,
        a.MaMonAn AS id,
        a.TenMonAn AS ten,
        a.MoTaMonAn AS moTa,
        a.ImageLinkMonAn AS anh
    FROM MUCYEUTHICH m
    JOIN MONAN a ON m.MaMonAn = a.MaMonAn
    WHERE m.MaSoTK COLLATE utf8mb4_unicode_ci = p_MaSoTK COLLATE utf8mb4_unicode_ci AND m.Loai = 'MONAN'

    UNION ALL

    SELECT
        'KND' AS loai,
        m.MaYeuThich,
        k.MaKND AS id,
        k.TenKND AS ten,
        k.MoTaKND AS moTa,
        k.ImageKND AS anh
    FROM MUCYEUTHICH m
    JOIN KHUNGHIDUONG k ON m.MaKND = k.MaKND
    WHERE m.MaSoTK COLLATE utf8mb4_unicode_ci = p_MaSoTK COLLATE utf8mb4_unicode_ci AND m.Loai = 'KND'

    UNION ALL

    SELECT
        'TOUR' AS loai,
        m.MaYeuThich,
        t.MaTour AS id,
        t.TenTour AS ten,
        t.MoTaTour AS moTa,
        t.ImageTourMain AS anh
    FROM MUCYEUTHICH m
    JOIN TOUR t ON m.MaTour = t.MaTour
    WHERE m.MaSoTK COLLATE utf8mb4_unicode_ci = p_MaSoTK COLLATE utf8mb4_unicode_ci AND m.Loai = 'TOUR'

    ORDER BY MaYeuThich DESC;
END$$

DELIMITER ;


DELIMITER $$

-- =============================================
-- 1. PROCEDURE THÊM YÊU THÍCH (Phiên bản ép kiểu Collation)
-- =============================================
DROP PROCEDURE IF EXISTS AddFavoriteItem$$

CREATE PROCEDURE AddFavoriteItem(
    IN p_MaSoTK VARCHAR(10),
    IN p_Loai VARCHAR(20),
    IN p_ID INT
)
BEGIN
    DECLARE v_Count INT;
    DECLARE v_Exists INT;
    DECLARE v_MaMonAn INT DEFAULT NULL;
    DECLARE v_MaDiaDanh INT DEFAULT NULL;
    DECLARE v_MaKND INT DEFAULT NULL;
    DECLARE v_MaTour INT DEFAULT NULL;

    -- 1. Đếm số lượng (Ép kiểu MaSoTK)
    SELECT COUNT(*) INTO v_Count 
    FROM MUCYEUTHICH 
    WHERE MaSoTK = p_MaSoTK COLLATE utf8mb4_unicode_ci;

    IF v_Count >= 99 THEN
        SELECT 'LIMIT' AS result;
    ELSE
        -- 2. Map ID
        CASE p_Loai
            WHEN 'DIADANH' THEN SET v_MaDiaDanh = p_ID;
            WHEN 'MONAN'   THEN SET v_MaMonAn = p_ID;
            WHEN 'KND'     THEN SET v_MaKND = p_ID;
            WHEN 'TOUR'    THEN SET v_MaTour = p_ID;
            ELSE SET p_ID = 0;
        END CASE;

        IF p_ID = 0 THEN
             SELECT 'INVALID_TYPE' AS result;
        ELSE
            -- 3. Check tồn tại (Ép kiểu cả MaSoTK và Loai)
            SELECT COUNT(*) INTO v_Exists
            FROM MUCYEUTHICH
            WHERE MaSoTK = p_MaSoTK COLLATE utf8mb4_unicode_ci
              AND Loai   = p_Loai   COLLATE utf8mb4_unicode_ci
              AND (
                  (p_Loai = 'DIADANH' AND MaDiaDanh = v_MaDiaDanh) OR
                  (p_Loai = 'MONAN'   AND MaMonAn = v_MaMonAn) OR
                  (p_Loai = 'KND'     AND MaKND = v_MaKND) OR
                  (p_Loai = 'TOUR'    AND MaTour = v_MaTour)
              );

            IF v_Exists > 0 THEN
                SELECT 'EXISTS' AS result;
            ELSE
                -- 4. Insert (Insert không cần ép kiểu, chỉ cần giá trị)
                INSERT INTO MUCYEUTHICH (MaSoTK, Loai, MaMonAn, MaDiaDanh, MaKND, MaTour)
                VALUES (p_MaSoTK, p_Loai, v_MaMonAn, v_MaDiaDanh, v_MaKND, v_MaTour);
                
                SELECT 'SUCCESS' AS result;
            END IF;
        END IF;
    END IF;
END$$


-- =============================================
-- 2. PROCEDURE XÓA YÊU THÍCH (Phiên bản ép kiểu Collation)
-- =============================================
DROP PROCEDURE IF EXISTS RemoveFavoriteItem$$

CREATE PROCEDURE RemoveFavoriteItem(
    IN p_MaSoTK VARCHAR(10),
    IN p_Loai VARCHAR(20),
    IN p_ID INT
)
BEGIN
    DELETE FROM MUCYEUTHICH 
    WHERE MaSoTK = p_MaSoTK COLLATE utf8mb4_unicode_ci
      AND Loai   = p_Loai   COLLATE utf8mb4_unicode_ci
      AND (
          (p_Loai = 'DIADANH' AND MaDiaDanh = p_ID) OR
          (p_Loai = 'MONAN'   AND MaMonAn = p_ID) OR
          (p_Loai = 'KND'     AND MaKND = p_ID) OR
          (p_Loai = 'TOUR'    AND MaTour = p_ID)
      );
      
    SELECT ROW_COUNT() AS affected_rows;
END$$

DELIMITER ;

-- sủa hồ sơ tài khoản ( thông tin khách hàng)
DELIMITER $$

DROP PROCEDURE IF EXISTS UpdateUserProfile$$

CREATE PROCEDURE UpdateUserProfile(
iKhoan VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_Email VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_HoTen VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_GioiTinh VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_NgaySinh DATE,
    IN p_DiaChi VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN p_SDT VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
)
BEGIN
    DECLARE v_Count INT;

    -- 1. Kiểm tra trùng Tên Tài Khoản (Ép kiểu so sánh)
    SELECT COUNT(*) INTO v_Count
    FROM TAIKHOAN
    WHERE TenTaiKhoan COLLATE utf8mb4_unicode_ci = p_TenTaiKhoan 
      AND MaSoTK COLLATE utf8mb4_unicode_ci != p_MaSoTK;

    IF v_Count > 0 THEN
        SELECT 'DUPLICATE_USERNAME' AS result;
    ELSE
        -- 2. Kiểm tra trùng Email (Ép kiểu so sánh)
        SELECT COUNT(*) INTO v_Count
        FROM TAIKHOAN
        WHERE Email COLLATE utf8mb4_unicode_ci = p_Email 
          AND MaSoTK COLLATE utf8mb4_unicode_ci != p_MaSoTK;

        IF v_Count > 0 THEN
            SELECT 'DUPLICATE_EMAIL' AS result;
        ELSE
            -- 3. Bắt đầu cập nhật
            START TRANSACTION;

            -- Cập nhật bảng TAIKHOAN
            -- Lưu ý: WHERE cũng cần ép kiểu để tìm đúng dòng
            UPDATE TAIKHOAN
            SET TenTaiKhoan = p_TenTaiKhoan, 
                Email = p_Email
            WHERE MaSoTK COLLATE utf8mb4_unicode_ci = p_MaSoTK;

            -- Cập nhật bảng KHACHHANG
            UPDATE KHACHHANG
            SET HoVaTen = p_HoTen, 
                GioiTinh = p_GioiTinh, 
                NgaySinh = p_NgaySinh, 
                DiaChi = p_DiaChi, 
                SDT = p_SDT
            WHERE MaSoTK COLLATE utf8mb4_unicode_ci = p_MaSoTK;

            COMMIT;
            
            SELECT 'SUCCESS' AS result;
        END IF;
    END IF;
END$$

DELIMITER ;