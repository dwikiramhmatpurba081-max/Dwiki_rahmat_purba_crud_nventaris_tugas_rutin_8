<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pdo = Database::getInstance()->getConnection();
$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$suppliers = $pdo->query('SELECT id, name FROM suppliers ORDER BY name')->fetchAll();
$pageTitle = 'Tambah Produk';
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">DATA INVENTARIS</p>
        <h1>Tambah Produk</h1>
        <p class="subtitle">Isi informasi produk lalu simpan ke database.</p>
    </div>
</section>
<?php require __DIR__ . '/includes/product-form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
