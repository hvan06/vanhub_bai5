<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/books.php';

// READ: danh sách + tìm kiếm + phân trang
$kw      = trim((string) ($_GET['q'] ?? ''));
$total   = books_count($kw);
$pages   = max(1, (int) ceil($total / BOOKS_PER_PAGE));
$page    = min(max(1, (int) ($_GET['page'] ?? 1)), $pages);   // kẹp trong 1..$pages
$books   = books_search($kw, $page);

$link = fn(int $p) => 'index.php?' . http_build_query(array_filter(['q' => $kw, 'page' => $p > 1 ? $p : null]));

$pageTitle = 'Danh sách sách';
require __DIR__ . '/includes/header.php';
?>
<h1>Danh sách sách</h1>

<form method="get" class="search">
    <input type="search" name="q" value="<?= e($kw) ?>" placeholder="Tìm theo mã, tên sách hoặc tác giả…">
    <button class="btn" type="submit">Tìm</button>
    <?php if ($kw !== ''): ?><a class="btn ghost" href="index.php">Xóa lọc</a><?php endif; ?>
</form>

<p class="muted">
    <?= $total ?> kết quả<?= $kw !== '' ? ' cho “' . e($kw) . '”' : '' ?> · Trang <?= $page ?>/<?= $pages ?>
</p>

<?php if (!$books): ?>
    <div class="card empty">Không tìm thấy sách nào.</div>
<?php else: ?>
<div class="card table-wrap">
<table>
    <thead>
        <tr><th>Mã</th><th>Tên sách</th><th>Tác giả</th><th>Thể loại</th><th class="r">Giá</th><th class="r">SL</th><th>Năm</th><th></th></tr>
    </thead>
    <tbody>
    <?php foreach ($books as $b): ?>
        <tr>
            <td><code><?= e($b['code']) ?></code></td>
            <td><?= e($b['title']) ?></td>
            <td><?= e($b['author']) ?></td>
            <td><span class="tag"><?= e($b['category_name']) ?></span></td>
            <td class="r"><?= e(money($b['price'])) ?></td>
            <td class="r"><?= (int) $b['quantity'] ?></td>
            <td><?= e((string) $b['published_year']) ?></td>
            <td class="nowrap">
                <a href="edit.php?id=<?= (int) $b['id'] ?>">Sửa</a>
                <form method="post" action="delete.php" class="inline"
                      onsubmit="return confirm('Xóa sách «<?= e(addslashes($b['title'])) ?>»?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                    <button type="submit" class="link danger">Xóa</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>

<?php if ($pages > 1): ?>
<nav class="pager" aria-label="Phân trang">
    <?php if ($page > 1): ?><a href="<?= e($link($page - 1)) ?>">‹ Trước</a><?php endif; ?>
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="<?= e($link($i)) ?>" class="<?= $i === $page ? 'cur' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $pages): ?><a href="<?= e($link($page + 1)) ?>">Sau ›</a><?php endif; ?>
</nav>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
