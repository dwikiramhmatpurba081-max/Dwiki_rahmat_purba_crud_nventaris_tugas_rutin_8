<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

verifyCsrfToken();

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$name = trim((string) ($_POST['name'] ?? ''));
$sku = trim((string) ($_POST['sku'] ?? ''));
$price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
$stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
$categoryId = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
$supplierId = filter_var($_POST['supplier_id'] ?? null, FILTER_VALIDATE_INT);

if (
    $id === false || $id < 1 ||
    $name === '' || strlen($name) > 150 ||
    $sku === '' || strlen($sku) > 50 ||
    $price === false || $price < 0 ||
    $stock === false || $stock < 0 ||
    $categoryId === false || $categoryId < 1 ||
    $supplierId === false || $supplierId < 1
) {
    setFlash('error', 'Data produk belum valid. Periksa kembali semua kolom.');
    redirect(is_int($id) && $id > 0 ? 'edit.php?id=' . $id : 'index.php');
}

try {
    $statement = Database::getInstance()->getConnection()->prepare(
        'UPDATE products
         SET name = :name, sku = :sku, price = :price, stock = :stock,
             category_id = :category_id, supplier_id = :supplier_id
         WHERE id = :id'
    );
    $statement->execute([
        'name' => $name,
        'sku' => $sku,
        'price' => $price,
        'stock' => $stock,
        'category_id' => $categoryId,
        'supplier_id' => $supplierId,
        'id' => $id,
    ]);
    setFlash('success', 'Perubahan produk berhasil disimpan.');
} catch (PDOException $exception) {
    setFlash('error', $exception->getCode() === '23000'
        ? 'SKU sudah digunakan atau kategori/supplier tidak valid.'
        : 'Produk gagal diperbarui. Periksa koneksi database dan coba lagi.');
    redirect('edit.php?id=' . $id);
}

redirect('index.php');
