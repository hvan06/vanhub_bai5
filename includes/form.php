<?php
/** Form dùng chung cho thêm / sửa. Cần: $d (giá trị), $errors, $categories, $action, $submitLabel */
$field = fn(string $k) => $d[$k] ?? '';
$err = fn(string $k) => isset($errors[$k]) ? '<span class="err">' . e($errors[$k]) . '</span>' : '';
?>
<form method="post" action="<?= e($action) ?>" class="card form" novalidate>
    <?= csrf_field() ?>

    <label>Mã sách *
        <input name="code" value="<?= e($field('code')) ?>" maxlength="20" placeholder="SACH013">
        <?= $err('code') ?>
    </label>

    <label>Tên sách *
        <input name="title" value="<?= e($field('title')) ?>" maxlength="150">
        <?= $err('title') ?>
    </label>

    <label>Tác giả *
        <input name="author" value="<?= e($field('author')) ?>" maxlength="100">
        <?= $err('author') ?>
    </label>

    <label>Thể loại *
        <select name="category_id">
            <option value="">-- Chọn thể loại --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= (string) $c['id'] === (string) $field('category_id') ? 'selected' : '' ?>>
                    <?= e($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?= $err('category_id') ?>
    </label>

    <div class="row">
        <label>Giá (₫) *
            <input name="price" value="<?= e($field('price')) ?>" inputmode="decimal" placeholder="45000">
            <?= $err('price') ?>
        </label>
        <label>Số lượng *
            <input name="quantity" value="<?= e($field('quantity')) ?>" inputmode="numeric" placeholder="10">
            <?= $err('quantity') ?>
        </label>
        <label>Năm xuất bản
            <input name="published_year" value="<?= e($field('published_year')) ?>" inputmode="numeric" placeholder="2024">
            <?= $err('published_year') ?>
        </label>
    </div>

    <div class="actions">
        <button class="btn" type="submit"><?= e($submitLabel) ?></button>
        <a class="btn ghost" href="index.php">Hủy</a>
    </div>
</form>
