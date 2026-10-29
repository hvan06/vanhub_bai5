# Test case nhập dữ liệu (Thêm sách – `create.php`)

Dữ liệu mẫu hợp lệ dùng làm nền: mã `SACH013`, tên "Clean Code", tác giả "Robert Martin",
thể loại "Lập trình", giá `210000`, số lượng `4`, năm `2008`.

| # | Loại | Dữ liệu nhập | Kết quả mong đợi | Kết quả thực tế |
|---|------|--------------|------------------|-----------------|
| 1 | Đúng | Dữ liệu mẫu hợp lệ ở trên | Lưu thành công, quay về danh sách, hiện "Đã thêm sách “Clean Code”.", tổng số sách tăng 12 → 13 | Đạt |
| 2 | Sai | Mã sách `SACH001` (đã có trong DB), các trường khác hợp lệ | Không lưu, báo "Mã sách đã tồn tại." dưới ô mã, các ô khác giữ nguyên giá trị đã nhập | Đạt |
| 3 | Sai | Tên sách để trống | Không lưu, báo "Tên sách phải từ 2 đến 150 ký tự." | Đạt |
| 4 | Sai | Giá = `-5` | Không lưu, báo "Giá phải là số không âm (ví dụ 45000)." | Đạt |
| 5 | Sai | Nhiều lỗi cùng lúc: giá `abc`, số lượng `x`, năm xuất bản `3000`, thể loại không tồn tại (`99`) | Không lưu, mỗi trường lỗi hiện thông báo riêng | Đạt |

## Quy tắc kiểm tra dữ liệu

| Trường | Quy tắc |
|--------|---------|
| Mã sách | Bắt buộc, 3–20 ký tự (chữ, số, `-`, `_`), không trùng |
| Tên sách | Bắt buộc, 2–150 ký tự |
| Tác giả | Bắt buộc, 2–100 ký tự |
| Thể loại | Bắt buộc, phải tồn tại trong bảng `categories` |
| Giá | Số không âm, tối đa 100.000.000, tối đa 2 chữ số thập phân |
| Số lượng | Số nguyên 0–100000 |
| Năm xuất bản | Không bắt buộc; nếu nhập thì từ 1000 đến năm hiện tại |

## Kiểm thử thêm (an toàn)

| Tình huống | Kết quả |
|------------|---------|
| Tìm `' OR 1=1 --` | 0 kết quả, không lỗi (prepared statement) |
| Tìm `%` | 0 kết quả (ký tự `%` được thoát, không khớp mọi dòng) |
| Tìm `<script>alert(1)</script>` | Hiển thị dạng chữ, không chạy script (`htmlspecialchars`) |
| Gửi form thêm / xóa không có CSRF token | Bị chặn (HTTP 419) |
| Xóa bằng link GET `delete.php?id=...` | Bị chặn (HTTP 405), chỉ xóa bằng POST |
| `?page=99`, `?page=abc` | Tự đưa về trang cuối / trang 1 |
