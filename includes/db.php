<?php
declare(strict_types=1);

/**
 * Trả về kết nối PDO dùng chung (tạo 1 lần).
 * Biến môi trường DB_DSN chỉ dùng cho việc chạy test tự động.
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsnEnv = getenv('DB_DSN');
    if ($dsnEnv) {
        $pdo = new PDO($dsnEnv);
        if (str_starts_with($dsnEnv, 'sqlite')) {
            $pdo->exec('PRAGMA foreign_keys = ON');
        }
    } else {
        $c   = require __DIR__ . '/../config/config.php';
        $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['dbname']};charset={$c['charset']}";
        $pdo = new PDO($dsn, $c['user'], $c['pass']);
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
}
