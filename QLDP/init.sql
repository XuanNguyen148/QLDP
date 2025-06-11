CREATE DATABASE IF NOT EXISTS ql_datphonghoc;
USE ql_datphonghoc;

CREATE TABLE NGUOIDUNG (
    MAND CHAR(5) PRIMARY KEY CHECK (MAND LIKE 'ND%'),
    HOTEN VARCHAR(40) NOT NULL,
    EMAIL VARCHAR(255) UNIQUE NOT NULL,
    VAITRO VARCHAR(50) CHECK (VAITRO IN ('Quản trị viên', 'Người dùng')),
    MATKHAU VARCHAR(255) NOT NULL,
    CONSTRAINT CK_EMAIL_GMAIL CHECK (EMAIL LIKE '_%@gmail.com' AND EMAIL NOT LIKE '% %')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PHONG (
    MAPHONG CHAR(5) PRIMARY KEY,
    TENPHONG VARCHAR(100) NOT NULL,
    SUCCHUA INT CHECK (SUCCHUA > 0),
    TRANGTHAI VARCHAR(50) CHECK (TRANGTHAI IN ('Trống', 'Đã đặt'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PHIEUDATPHONG (
    MAPHIEU CHAR(10) PRIMARY KEY CHECK (MAPHIEU LIKE 'MP%'),
    MAND CHAR(5) NOT NULL,
    MAPHONG CHAR(5) NOT NULL,
    TGBD DATETIME NOT NULL,
    TGKT DATETIME NOT NULL,
    MUCDICH VARCHAR(100) NOT NULL CHECK (MUCDICH IN ('Dạy học', 'Hội thảo', 'Khác')),
    TRANGTHAI VARCHAR(50) CHECK (TRANGTHAI IN ('Đã đặt', 'Hoàn thành', 'Hủy')),
    FOREIGN KEY (MAND) REFERENCES NGUOIDUNG(MAND),
    FOREIGN KEY (MAPHONG) REFERENCES PHONG(MAPHONG),
    CONSTRAINT CK_TGBD_TGKT CHECK (TGBD < TGKT),
    CONSTRAINT CK_PHUT_HOP_LE CHECK (
        MINUTE(TGBD) IN (0, 30) AND MINUTE(TGKT) IN (0, 30)
    ),
    CONSTRAINT CK_GIO_HOP_LE CHECK (
        (HOUR(TGBD) * 60 + MINUTE(TGBD)) >= (7 * 60 + 30) AND
        (HOUR(TGKT) * 60 + MINUTE(TGKT)) <= (16 * 60)
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE LICHSU (
    MALS INT PRIMARY KEY AUTO_INCREMENT,
    MAPHIEU CHAR(10) NOT NULL,
    THOIGIAN VARCHAR(50),
    GHICHU VARCHAR(500),
    FOREIGN KEY (MAPHIEU) REFERENCES PHIEUDATPHONG(MAPHIEU)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO NGUOIDUNG (MAND, HOTEN, EMAIL, VAITRO, MATKHAU) VALUES
('ND009', 'Trịnh Văn Quyết', 'trinhquyetad@gmail.com', 'Quản trị viên', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND019', 'Cao Văn Sĩ', 'sivancao@gmail.com', 'Quản trị viên', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND001', 'Nguyễn Văn Sơn', 'vanson@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND002', 'Trần Thị Lý', 'lytranthi@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND003', 'Lê Văn Lương', 'leluong23@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND004', 'Phạm Lê Anh Thư', 'anhthu98@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND005', 'Hoàng Thị Vân', 'hgvan_ktct@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND006', 'Vũ Thị Thùy', 'thuyvu@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND007', 'Đỗ Hùng Dũng', 'hgdung78@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND008', 'Nguyễn Thị Hương Ly', 'hly78tb@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND010', 'Phan Thị Mai Hoa', 'hoamaithi@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND011', 'Lương Thành Đạt', 'thanhdattmu@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND012', 'Ngô Thị Liên Hương', 'lienhuong12@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND013', 'Tạ Đình Thi', 'dinhthi76@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e12g3h4i5j'),
('ND014', 'Tống Thị Ni Na', 'ninatong@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND015', 'Bùi Văn Thành', 'thanhvan25@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND016', 'Đào Thị Phúc Vinh', 'phucvinhdao@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND017', 'Dương Văn Minh', 'vanminh30475@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND018', 'Hà Thị Thao', 'hathaotb@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j'),
('ND020', 'Mai Thị Trang Anh', 'tranganh98@gmail.com', 'Người dùng', '$2y$10$9z5b1X6a3Y7k9m2p8n0q.u8t1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j');

INSERT INTO PHONG (MAPHONG, TENPHONG, SUCCHUA, TRANGTHAI) VALUES
('V204', 'Phòng họp giảng viên 1', 30, 'Trống'),
('V304', 'Phòng học lý thuyết 1', 100, 'Đã đặt'),
('V404', 'Phòng họp giảng viên 2', 30, 'Trống'),
('V504', 'Phòng học lý thuyết 2', 80, 'Trống'),
('V604', 'Phòng thực hành', 50, 'Trống'),
('G404', 'Hội trường 1', 200, 'Trống'),
('G504', 'Phòng máy 1', 50, 'Trống'),
('P202', 'Phòng thư viện tự học 1', 10, 'Trống'),
('P203', 'Phòng bảo vệ luận văn 1', 30, 'Trống'),
('P304', 'Phòng bảo vệ luận văn 2', 30, 'Trống'),
('P305', 'Phòng thư viện tự học 2', 10, 'Trống'),
('P403', 'Phòng thực hành', 40, 'Trống'),
('P405', 'Phòng máy 3', 50, 'Trống'),
('P504', 'Phòng họp giảng viên 3', 40, 'Trống'),
('P505', 'Phòng khách', 20, 'Trống'),
('G403', 'Phòng máy 2', 50, 'Đã đặt'),
('G402', 'Hội trường 2', 200, 'Trống'),
('V201', 'Phòng học lý thuyết 3', 100, 'Trống'),
('V301', 'Phòng hoạt động câu lạc bộ', 50, 'Trống'),
('P602', 'Phòng thu Studio', 20, 'Trống');

INSERT INTO PHIEUDATPHONG (MAPHIEU, MAND, MAPHONG, TGBD, TGKT, MUCDICH, TRANGTHAI) VALUES
('MP001', 'ND001', 'V204', '2025-06-01 08:00:00', '2025-06-01 09:30:00', 'Khác', 'Hoàn thành'),
('MP002', 'ND002', 'V304', '2025-06-15 09:30:00', '2025-06-15 12:00:00', 'Dạy học', 'Đã đặt'),
('MP003', 'ND003', 'P202', '2025-06-02 07:30:00', '2025-06-02 10:30:00', 'Khác', 'Hoàn thành'),
('MP004', 'ND004', 'G404', '2025-06-04 13:30:00', '2025-06-04 16:00:00', 'Hội thảo', 'Hoàn thành'),
('MP005', 'ND005', 'V404', '2025-06-05 07:30:00', '2025-06-05 08:30:00', 'Khác', 'Hủy'),
('MP006', 'ND006', 'V504', '2025-06-06 13:30:00', '2025-06-06 15:00:00', 'Khác', 'Hoàn thành'),
('MP007', 'ND007', 'G403', '2025-06-16 08:30:00', '2025-06-16 10:30:00', 'Dạy học', 'Đã đặt'),
('MP008', 'ND008', 'P602', '2025-06-08 14:00:00', '2025-06-08 15:30:00', 'Khác', 'Hoàn thành'),
('MP009', 'ND009', 'P505', '2025-06-09 08:30:00', '2025-06-09 10:00:00', 'Khác', 'Hoàn thành'),
('MP010', 'ND010', 'P203', '2025-06-10 07:30:00', '2025-06-10 12:00:00', 'Khác', 'Hủy'),
('MP015', 'ND007', 'G404', '2025-06-02 09:30:00', '2025-06-02 12:00:00', 'Hội thảo', 'Hoàn thành'),
('MP011', 'ND003', 'G404', '2025-06-04 09:30:00', '2025-06-04 12:00:00', 'Hội thảo', 'Hoàn thành'),
('MP012', 'ND002', 'V404', '2025-06-05 13:00:00', '2025-06-05 15:30:00', 'Khác', 'Hủy'),
('MP013', 'ND009', 'P505', '2025-06-01 08:30:00', '2025-06-01 10:00:00', 'Khác', 'Hoàn thành');

INSERT INTO LICHSU (MAPHIEU, THOIGIAN, GHICHU) VALUES
('MP001', '2025-06-01 08:00:00 - 09:30:00', 'Hoàn thành'),
('MP002', '2025-06-15 09:30:00 - 12:00:00', 'Đã đặt'),
('MP003', '2025-06-02 07:30:00 - 10:30:00', 'Hoàn thành'),
('MP004', '2025-06-04 13:30:00 - 16:00:00', 'Hoàn thành'),
('MP005', '2025-06-05 07:30:00 - 08:30:00', 'Hủy'),
('MP006', '2025-06-06 13:30:00 - 15:00:00', 'Hoàn thành'),
('MP007', '2025-06-16 08:30:00 - 10:30:00', 'Đã đặt'),
('MP008', '2025-06-08 14:00:00 - 15:30:00', 'Hoàn thành'),
('MP009', '2025-06-09 08:30:00 - 10:00:00', 'Hoàn thành'),
('MP010', '2025-06-10 07:30:00 - 12:00:00', 'Hủy'),
('MP015', '2025-06-02 09:30:00 - 12:00:00', 'Hoàn thành'),
('MP011', '2025-06-04 09:30:00 - 12:00:00', 'Hoàn thành'),
('MP012', '2025-06-05 13:00:00 - 15:30:00', 'Hủy'),
('MP013', '2025-06-01 08:30:00 - 10:00:00', 'Hoàn thành');