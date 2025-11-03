USE QuanLyDuLich;



DELIMITER $$

CREATE TRIGGER trg_TAIKHOAN_AutoID
BEFORE INSERT ON TAIKHOAN
FOR EACH ROW
BEGIN
    DECLARE max_id INT DEFAULT 0;
    DECLARE new_id VARCHAR(10);
    DECLARE email_count INT DEFAULT 0;
    DECLARE name_count INT DEFAULT 0;

    -- Nếu LoaiTaiKhoan không được truyền, mặc định là 'KH'
    IF NEW.LoaiTaiKhoan IS NULL OR NEW.LoaiTaiKhoan = '' THEN
        SET NEW.LoaiTaiKhoan = 'KH';
    END IF;

    -- Kiểm tra email đã tồn tại chưa
    SELECT COUNT(*) INTO email_count
    FROM TAIKHOAN
    WHERE Email = NEW.Email;

    IF email_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Email da ton tai, khong the them!';
    END IF;

    -- Kiểm tra TênTaiKhoan đã tồn tại chưa
    SELECT COUNT(*) INTO name_count
    FROM TAIKHOAN
    WHERE TenTaiKhoan = NEW.TenTaiKhoan;

    IF name_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Ten tai khoan da ton tai, khong the them!';
    END IF;

    -- Chỉ tạo mã tự động nếu là tài khoản khách hàng (KH)
    IF NEW.LoaiTaiKhoan = 'KH' THEN
        -- Lấy số lớn nhất hiện có từ mã KH
        SELECT COALESCE(MAX(CAST(SUBSTRING(MaSoTK, 3) AS UNSIGNED)), 0)
        INTO max_id
        FROM TAIKHOAN
        WHERE MaSoTK LIKE 'KH%';

        -- Tạo mã mới: KH + số tăng dần (8 chữ số)
        SET new_id = CONCAT('KH', LPAD(max_id + 1, 8, '0'));

        -- Gán lại cho bản ghi mới
        SET NEW.MaSoTK = new_id;
    END IF;

END$$

DELIMITER ;



INSERT INTO TAIKHOAN (MaSoTK, MatKhau, TenTaiKhoan, Email, LoaiTaiKhoan)
VALUES ('AD00000001', 'admin123', 'Quản trị viên', 'minhcao26042005@gmail.com', 'AD');


DROP TRIGGER IF EXISTS trg_TAIKHOAN_AutoID;



DELETE FROM TAIKHOAN
WHERE MaSoTK='KH00000001';

SELECT * FROM TAIKHOAN