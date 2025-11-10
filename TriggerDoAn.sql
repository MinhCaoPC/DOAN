USE QuanLyDuLich;



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





INSERT INTO TAIKHOAN (MaSoTK, MatKhau, TenTaiKhoan, Email, LoaiTaiKhoan)
VALUES ('AD00000001', 'admin123', 'Quản trị viên', 'minhcao26042005@gmail.com', 'AD');


DROP TRIGGER IF EXISTS trg_TAIKHOAN_AutoID;



DELETE FROM TAIKHOAN
WHERE MaSoTK='KH00000001';

SELECT * FROM TAIKHOAN