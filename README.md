# Sách của Vân – Bài tập về nhà 5 (cá nhân)

Ứng dụng PHP + PDO + MySQL quản lý sách, có đủ **CRUD**, **tìm kiếm**, **phân trang** và 5 test case.

## Chức năng

| Chức năng | Trang | Ghi chú |
|-----------|-------|---------|
| Xem danh sách (Read) | `index.php` | JOIN `books` với `categories`, 5 sách/trang |
| Tìm kiếm | `index.php?q=...` | Theo mã, tên sách hoặc tác giả |
| Phân trang | `index.php?page=2` | `LIMIT ... OFFSET ...`, giữ từ khóa khi chuyển trang |
| Thêm (Create) | `create.php` | Kiểm tra dữ liệu, báo lỗi từng ô |
| Sửa (Update) | `edit.php?id=1` | Cho phép giữ nguyên mã của chính nó |
| Xóa (Delete) | nút Xóa trên danh sách | Chỉ nhận POST, có xác nhận |

## Cơ sở dữ liệu

2 bảng, quan hệ 1–N: một thể loại (`categories`) có nhiều sách (`books`), khóa ngoại `books.category_id`.

```
categories (id PK, name UNIQUE, created_at)
books      (id PK, category_id FK, code UNIQUE, title, author, price, quantity, published_year, created_at, updated_at)
```

## Bảo mật đã làm

- Prepared statement cho mọi truy vấn có dữ liệu từ người dùng (chống SQL Injection).
- `htmlspecialchars` khi in dữ liệu ra HTML (chống XSS).
- CSRF token cho form thêm, sửa, xóa.
- Xóa chỉ bằng POST; từ khóa tìm kiếm được thoát ký tự `%` và `_`.

## Cách chạy (XAMPP, macOS)

1. Bật **MySQL** trong XAMPP.
2. Import CSDL và dữ liệu mẫu (chạy trong thư mục project):
   ```
   /Applications/XAMPP/xamppfiles/bin/mysql -u root < database/schema.sql
   /Applications/XAMPP/xamppfiles/bin/mysql -u root < database/seed.sql
   ```
3. Nếu MySQL có mật khẩu hoặc khác cổng, sửa `config/config.php`.
4. Chạy web:
   ```
   /Applications/XAMPP/xamppfiles/bin/php -S localhost:8000
   ```
5. Mở http://localhost:8000/index.php

## Cấu trúc thư mục

```
├── index.php  create.php  edit.php  delete.php
├── includes/   db.php  helpers.php  books.php  header.php  footer.php  form.php
├── config/config.php
├── database/   schema.sql  seed.sql
├── assets/css/style.css
└── docs/test-cases.md
```

## Test case

Xem [docs/test-cases.md](docs/test-cases.md): 5 test case nhập đúng/sai và các kiểm thử an toàn.
