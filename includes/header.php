<?php
require_once __DIR__ . '/helpers.php';
$pageTitle = $pageTitle ?? 'Quản lý sách';
$flash = flash();
?><!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> · Sách của Vân</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="top">
    <a class="brand" href="index.php">📚 Sách của Vân</a>
    <a class="btn" href="create.php">+ Thêm sách</a>
</header>
<main>
<?php if ($flash): ?>
    <div class="flash <?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>
