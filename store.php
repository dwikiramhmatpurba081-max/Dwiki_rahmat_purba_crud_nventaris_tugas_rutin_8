<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('create.php');
}

verifyCsrfToken();

$name = trim((string) ($_POST['name'] ?? ''));
$sku = trim((string) ($_POST['sku'] ?? ''));
$price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
$stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
$categoryId = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
$supplierId = filter_var($_POST['supplier_id'] ?? null, FILTER_VALIDATE_INT);

if (
    $name === '' || strlen($name) > 150 ||
    $sku === '' || strlen($sku) > 50 ||
    $price === false || $price < 0 ||
    $stock === false || $stock < 0 ||
    $categoryId === false || $categoryId < 1 ||
    $supplierId === false || $supplierId < 1
) {
    setFlash('error', 'Data produk belum valid. Periksa kembali semua kolom.');
    redirect('create.php');
}

try {
    $statement = Database::getInstance()->getConnection()->prepare(
        'INSERT INTO products (name, sku, price, stock, category_id, supplier_id)
         VALUES (:name, :sku, :price, :stock, :category_id, :supplier_id)'
    );
    $statement->execute([
        'name' => $name,
        'sku' => $sku,
        'price' => $price,
        'stock' => $stock,
        'category_id' => $categoryId,
        'supplier_id' => $supplierId,
    ]);
    setFlash('success', 'Produk berhasil ditambahkan.');
} catch (PDOException $exception) {
    setFlash('error', $exception->getCode() === '23000'
        ? 'SKU sudah digunakan atau kategori/supplier tidak valid.'
        : 'Produk gagal disimpan. Periksa koneksi database dan coba lagi.');
    redirect('create.php');
}

redirect('index.php');
