<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

const BOOKS_PER_PAGE = 5;

/** Danh sách thể loại cho ô chọn. */
function categories_all(): array
{
    return db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
}

/**
 * Thoát ký tự đặc biệt của LIKE (% _ !) để người dùng gõ "50%" vẫn tìm đúng.
 * Dùng ký tự thoát '!' (ESCAPE '!').
 */
function like_pattern(string $kw): string
{
    return '%' . strtr($kw, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
}

const BOOKS_WHERE =
    "(b.code LIKE :kw1 ESCAPE '!' OR b.title LIKE :kw2 ESCAPE '!' OR b.author LIKE :kw3 ESCAPE '!')";

/** Đếm số sách khớp từ khóa (để tính số trang). */
function books_count(string $kw = ''): int
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM books b WHERE ' . BOOKS_WHERE);
    $p = like_pattern($kw);
    $stmt->execute(['kw1' => $p, 'kw2' => $p, 'kw3' => $p]);
    return (int) $stmt->fetchColumn();
}

/** Lấy 1 trang sách khớp từ khóa, kèm tên thể loại (JOIN). */
function books_search(string $kw = '', int $page = 1, int $perPage = BOOKS_PER_PAGE): array
{
    $sql = 'SELECT b.id, b.code, b.title, b.author, b.price, b.quantity,
                   b.published_year, c.name AS category_name
            FROM books b
            JOIN categories c ON c.id = b.category_id
            WHERE ' . BOOKS_WHERE . '
            ORDER BY b.code
            LIMIT :lim OFFSET :off';
    $stmt = db()->prepare($sql);
    $p = like_pattern($kw);
    $stmt->bindValue('kw1', $p);
    $stmt->bindValue('kw2', $p);
    $stmt->bindValue('kw3', $p);
    $stmt->bindValue('lim', $perPage, PDO::PARAM_INT);
    $stmt->bindValue('off', max(0, ($page - 1) * $perPage), PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function book_find(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM books WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Kiểm tra dữ liệu form. Trả về [dữ liệu sạch, mảng lỗi theo tên trường].
 * $ignoreId: id sách đang sửa (để mã không bị coi là trùng với chính nó).
 */
function book_validate(array $in, ?int $ignoreId = null): array
{
    $err = [];
    $d = [
        'code'           => trim((string) ($in['code'] ?? '')),
        'title'          => trim((string) ($in['title'] ?? '')),
        'author'         => trim((string) ($in['author'] ?? '')),
        'category_id'    => trim((string) ($in['category_id'] ?? '')),
        'price'          => trim((string) ($in['price'] ?? '')),
        'quantity'       => trim((string) ($in['quantity'] ?? '')),
        'published_year' => trim((string) ($in['published_year'] ?? '')),
    ];

    // Mã sách
    if ($d['code'] === '') {
        $err['code'] = 'Mã sách không được để trống.';
    } elseif (!preg_match('/^[A-Za-z0-9_-]{3,20}$/', $d['code'])) {
        $err['code'] = 'Mã sách gồm 3–20 ký tự: chữ, số, gạch ngang hoặc gạch dưới.';
    } else {
        $stmt = db()->prepare('SELECT id FROM books WHERE code = :code AND id <> :id');
        $stmt->execute(['code' => $d['code'], 'id' => $ignoreId ?? 0]);
        if ($stmt->fetch()) {
            $err['code'] = 'Mã sách đã tồn tại.';
        }
    }

    // Tên sách, tác giả
    $len = mb_strlen($d['title']);
    if ($len < 2 || $len > 150) {
        $err['title'] = 'Tên sách phải từ 2 đến 150 ký tự.';
    }
    $len = mb_strlen($d['author']);
    if ($len < 2 || $len > 100) {
        $err['author'] = 'Tác giả phải từ 2 đến 100 ký tự.';
    }

    // Thể loại phải tồn tại
    if (!ctype_digit($d['category_id'])) {
        $err['category_id'] = 'Hãy chọn thể loại.';
    } else {
        $stmt = db()->prepare('SELECT 1 FROM categories WHERE id = :id');
        $stmt->execute(['id' => (int) $d['category_id']]);
        if (!$stmt->fetch()) {
            $err['category_id'] = 'Thể loại không tồn tại.';
        }
    }

    // Giá: số không âm, tối đa 100 triệu
    if ($d['price'] === '' || !preg_match('/^\d+(\.\d{1,2})?$/', $d['price'])) {
        $err['price'] = 'Giá phải là số không âm (ví dụ 45000).';
    } elseif ((float) $d['price'] > 100000000) {
        $err['price'] = 'Giá tối đa 100.000.000.';
    }

    // Số lượng: số nguyên 0..100000
    if (!ctype_digit($d['quantity']) || (int) $d['quantity'] > 100000) {
        $err['quantity'] = 'Số lượng phải là số nguyên từ 0 đến 100000.';
    }

    // Năm xuất bản: không bắt buộc, nhưng nếu nhập thì hợp lệ
    if ($d['published_year'] !== '') {
        $maxYear = (int) date('Y');
        if (!ctype_digit($d['published_year'])
            || (int) $d['published_year'] < 1000
            || (int) $d['published_year'] > $maxYear) {
            $err['published_year'] = "Năm xuất bản phải từ 1000 đến $maxYear.";
        }
    }

    return [$d, $err];
}

function book_params(array $d): array
{
    return [
        'category_id'    => (int) $d['category_id'],
        'code'           => $d['code'],
        'title'          => $d['title'],
        'author'         => $d['author'],
        'price'          => $d['price'],
        'quantity'       => (int) $d['quantity'],
        'published_year' => $d['published_year'] === '' ? null : (int) $d['published_year'],
    ];
}

/** CREATE. Trả về id mới, hoặc false nếu mã bị trùng (race condition). */
function book_create(array $d): int|false
{
    $sql = 'INSERT INTO books (category_id, code, title, author, price, quantity, published_year)
            VALUES (:category_id, :code, :title, :author, :price, :quantity, :published_year)';
    try {
        db()->prepare($sql)->execute(book_params($d));
        return (int) db()->lastInsertId();
    } catch (PDOException $ex) {
        if ($ex->getCode() === '23000') {
            return false;
        }
        throw $ex;
    }
}

/** UPDATE. Trả về false nếu mã trùng. */
function book_update(int $id, array $d): bool
{
    $sql = 'UPDATE books
            SET category_id = :category_id, code = :code, title = :title, author = :author,
                price = :price, quantity = :quantity, published_year = :published_year
            WHERE id = :id';
    try {
        db()->prepare($sql)->execute(book_params($d) + ['id' => $id]);
        return true;
    } catch (PDOException $ex) {
        if ($ex->getCode() === '23000') {
            return false;
        }
        throw $ex;
    }
}

/** DELETE. Có điều kiện WHERE id rõ ràng, trả về số dòng đã xóa. */
function book_delete(int $id): int
{
    $stmt = db()->prepare('DELETE FROM books WHERE id = :id');
    $stmt->execute(['id' => $id]);
    return $stmt->rowCount();
}
