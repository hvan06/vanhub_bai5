USE hvanhub_books;
SET NAMES utf8mb4;

INSERT INTO categories (id, name) VALUES
  (1, 'Văn học'),
  (2, 'Lập trình'),
  (3, 'Kinh tế'),
  (4, 'Kỹ năng sống'),
  (5, 'Thiếu nhi');

INSERT INTO books (category_id, code, title, author, price, quantity, published_year) VALUES
  (1, 'SACH001', 'Dế Mèn phiêu lưu ký', 'Tô Hoài', 45000, 20, 1941),
  (1, 'SACH002', 'Tắt đèn', 'Ngô Tất Tố', 38000, 12, 1939),
  (1, 'SACH003', 'Số đỏ', 'Vũ Trọng Phụng', 52000, 8, 1936),
  (1, 'SACH004', 'Truyện Kiều', 'Nguyễn Du', 60000, 15, 1820),
  (2, 'SACH005', 'Lập trình PHP căn bản', 'Nguyễn Văn An', 120000, 10, 2022),
  (2, 'SACH006', 'MySQL từ cơ bản đến nâng cao', 'Trần Minh Khoa', 135000, 7, 2021),
  (2, 'SACH007', 'Cấu trúc dữ liệu và giải thuật', 'Lê Thị Hoa', 150000, 5, 2020),
  (3, 'SACH008', 'Kinh tế học đại cương', 'Phạm Quốc Bảo', 98000, 14, 2019),
  (3, 'SACH009', 'Nguyên lý marketing', 'Đỗ Thu Trang', 110000, 9, 2023),
  (4, 'SACH010', 'Đắc nhân tâm', 'Dale Carnegie', 86000, 30, 1936),
  (4, 'SACH011', 'Tư duy nhanh và chậm', 'Daniel Kahneman', 145000, 6, 2011),
  (5, 'SACH012', 'Chú mèo dạy hải âu bay', 'Luis Sepúlveda', 55000, 18, 1996);
