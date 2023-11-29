-- a
SELECT * FROM baiviet bv, theloai tl WHERE tl.ten_tloai = 'Nhạc trữ tình' AND bv.ma_tloai = tl.ma_tloai;

-- b
SELECT * FROM baiviet bv, tacgia tg WHERE tg.ten_tgia = 'Nhacvietplus' AND bv.ma_tgia = tg.ma_tgia;

-- c
SELECT ten_tloai FROM theloai WHERE ma_tloai NOT IN (SELECT ma_tloai FROM baiviet);

-- d
SELECT ma_bviet, tieude, ten_bhat, ten_tgia, ten_tloai, ngayviet FROM baiviet INNER JOIN tacgia ON baiviet.ma_tgia = tacgia.ma_tgia INNER JOIN theloai ON baiviet.ma_tloai = theloai.ma_tloai;

-- e
SELECT theloai.ten_tloai, COUNT(baiviet.ma_bviet) AS so_baiviet
FROM theloai
JOIN baiviet ON theloai.ma_tloai = baiviet.ma_tloai
GROUP BY theloai.ma_tloai
ORDER BY so_baiviet DESC
LIMIT 1;


-- f
SELECT tacgia.ten_tgia, COUNT(baiviet.ma_bviet) AS so_baiviet
FROM tacgia
JOIN baiviet ON tacgia.ma_tgia = baiviet.ma_tgia
GROUP BY tacgia.ma_tgia
ORDER BY so_baiviet DESC
LIMIT 2;

-- g
SELECT * FROM baiviet WHERE ten_bhat LIKE '%yêu%' OR ten_bhat LIKE '%thương%' OR ten_bhat LIKE '%anh%' OR ten_bhat LIKE '%em%';

-- h
SELECT * FROM baiviet WHERE (tieude LIKE '%yêu%' OR ten_bhat LIKE '%yêu%') OR (tieude LIKE '%thương%' OR ten_bhat LIKE '%thương%') OR (tieude LIKE '%anh%' OR ten_bhat LIKE '%anh%') OR (tieude LIKE '%em%' OR ten_bhat LIKE '%em%');

-- i
CREATE VIEW vw_Music AS 
SELECT baiviet.*, theloai.ten_tloai, tacgia.ten_tgia
FROM baiviet 
INNER JOIN theloai ON baiviet.ma_tloai = theloai.ma_tloai 
INNER JOIN tacgia ON baiviet.ma_tgia = tacgia.ma_tgia;

-- j
DELIMITER //

CREATE PROCEDURE sp_DSBaiViet(IN ten_tloai VARCHAR(50))
BEGIN
    -- Kiểm tra xem thể loại có tồn tại không
    IF NOT EXISTS(SELECT 1 FROM theloai tl WHERE tl.ten_tloai = ten_tloai) THEN
        SELECT 'Không tìm thấy thể loại' AS message;
    END IF;
    
    -- Nếu tồn tại thì truy vấn danh sách bài viết của thể loại đó
    SELECT bv.*, tl.ten_tloai
    FROM baiviet bv
    JOIN theloai tl ON bv.ma_tloai = tl.ma_tloai
    WHERE tl.ten_tloai = ten_tloai;
END //

DELIMITER ;

-- k
-- thêm cột SLBaiViet
ALTER TABLE theloai ADD SLBaiViet INT NOT NULL DEFAULT 0;

-- tạo trigger
DELIMITER $$
CREATE TRIGGER `tg_SuaBaiViet` AFTER UPDATE ON `baiviet` FOR EACH ROW BEGIN
		UPDATE theloai
    	SET SLBaiViet = (SELECT COUNT(*) FROM baiviet WHERE ma_tloai = NEW.ma_tloai)
    	WHERE ma_tloai = NEW.ma_tloai;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_ThemHBaiViet` AFTER INSERT ON `baiviet` FOR EACH ROW BEGIN
		UPDATE theloai
    	SET SLBaiViet = (SELECT COUNT(*) FROM baiviet WHERE ma_tloai = NEW.ma_tloai)
    	WHERE ma_tloai = NEW.ma_tloai;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_XoaBaiViet` AFTER DELETE ON `baiviet` FOR EACH ROW BEGIN
		UPDATE theloai
        SET SLBaiViet = (SELECT COUNT(*) FROM baiviet WHERE ma_tloai = OLD.ma_tloai)
        WHERE ma_tloai = OLD.ma_tloai;
END
$$
DELIMITER ;