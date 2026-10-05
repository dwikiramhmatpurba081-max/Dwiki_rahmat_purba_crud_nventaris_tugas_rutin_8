<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$id = productIdFromRequest();
$statement = Database::getInstance()->getConnection()->prepare('SELECT * FROM products WHERE id = :id');
$statement->execute(['id' => $id]);
$product = $statement->fetch();

if ($product === false) {
    setFlash('error', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$pdo = Database::getInstance()->getConnection();
$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$suppliers = $pdo->query('SELECT id, name FROM suppliers ORDER BY name')->fetchAll();
$pageTitle = 'Edit Produk';
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">DATA INVENTARIS</p>
        <h1>Edit Produk</h1>
        <p class="subtitle">Perbarui informasi produk di bawah ini.</p>
    </div>
</section>
<?php require __DIR__ . '/includes/product-form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
