<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/books.php';

// Xóa chỉ nhận POST (không xóa qua link GET) + kiểm tra CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Phương thức không được phép.');
}
csrf_check();

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0 && book_delete($id) === 1) {
    flash('Đã xóa sách.');
} else {
    flash('Không tìm thấy sách để xóa.', 'bad');
}
redirect('index.php');
