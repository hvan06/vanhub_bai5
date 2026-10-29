<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/books.php';

$d = ['quantity' => '0', 'price' => '0'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$d, $errors] = book_validate($_POST);
    if (!$errors) {
        if (book_create($d) !== false) {
            flash('Đã thêm sách “' . $d['title'] . '”.');
            redirect('index.php');
        }
        $errors['code'] = 'Mã sách đã tồn tại.';
    }
}

$pageTitle   = 'Thêm sách';
$categories  = categories_all();
$action      = 'create.php';
$submitLabel = 'Lưu sách';
require __DIR__ . '/includes/header.php';
?>
<h1>Thêm sách</h1>
<?php require __DIR__ . '/includes/form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
