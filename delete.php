<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

verifyCsrfToken();

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id < 1) {
    setFlash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

$pdo = Database::getInstance()->getConnection();
try {
    $pdo->beginTransaction();
    $statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    $deleted = $statement->rowCount() > 0;
    $pdo->commit();
    setFlash($deleted ? 'success' : 'error', $deleted ? 'Produk berhasil dihapus.' : 'Produk tidak ditemukan.');
} catch (PDOException $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    setFlash('error', 'Produk gagal dihapus. Coba lagi.');
}

redirect('index.php');
