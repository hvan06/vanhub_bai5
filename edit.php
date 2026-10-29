<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/books.php';

$id   = (int) ($_GET['id'] ?? 0);
$book = $id > 0 ? book_find($id) : null;
if (!$book) {
    http_response_code(404);
    flash('Không tìm thấy sách cần sửa.', 'bad');
    redirect('index.php');
}

$d = $book;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$d, $errors] = book_validate($_POST, $id);
    if (!$errors) {
        if (book_update($id, $d)) {
            flash('Đã cập nhật sách “' . $d['title'] . '”.');
            redirect('index.php');
        }
        $errors['code'] = 'Mã sách đã tồn tại.';
    }
}

$pageTitle   = 'Sửa sách';
$categories  = categories_all();
$action      = 'edit.php?id=' . $id;
$submitLabel = 'Cập nhật';
require __DIR__ . '/includes/header.php';
?>
<h1>Sửa sách <code><?= e($book['code']) ?></code></h1>
<?php require __DIR__ . '/includes/form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
