<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pdo = Database::getInstance()->getConnection();
$search = trim((string) ($_GET['q'] ?? ''));
$requestedPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$page = is_int($requestedPage) && $requestedPage > 0 ? $requestedPage : 1;
$perPage = 8;
$searchPattern = '%' . $search . '%';

$countStatement = $pdo->prepare(
    'SELECT COUNT(*)
     FROM products p
     INNER JOIN categories c ON c.id = p.category_id
     INNER JOIN suppliers s ON s.id = p.supplier_id
     WHERE p.name LIKE :name OR c.name LIKE :category OR s.name LIKE :supplier'
);
$countStatement->execute([
    'name' => $searchPattern,
    'category' => $searchPattern,
    'supplier' => $searchPattern,
]);
$totalProducts = (int) $countStatement->fetchColumn();
$totalPages = max(1, (int) ceil($totalProducts / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$statement = $pdo->prepare(
    'SELECT p.id, p.name, p.sku, p.price, p.stock, c.name AS category_name, s.name AS supplier_name
     FROM products p
     INNER JOIN categories c ON c.id = p.category_id
     INNER JOIN suppliers s ON s.id = p.supplier_id
     WHERE p.name LIKE :name OR c.name LIKE :category OR s.name LIKE :supplier
     ORDER BY p.id DESC
     LIMIT :limit OFFSET :offset'
);
$statement->bindValue(':name', $searchPattern, PDO::PARAM_STR);
$statement->bindValue(':category', $searchPattern, PDO::PARAM_STR);
$statement->bindValue(':supplier', $searchPattern, PDO::PARAM_STR);
$statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
$statement->execute();
$products = $statement->fetchAll();

$pageTitle = 'Daftar Produk';
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">TUGAS RUTIN 8</p>
        <h1>CRUD Inventaris</h1>
        <p class="subtitle">Kelola data produk, kategori, dan supplier dalam satu tempat.</p>
    </div>
    <a class="button" href="create.php">+ Tambah Produk</a>
</section>

<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>Daftar Produk</h2>
            <p class="muted"><?= $totalProducts ?> produk ditemukan</p>
        </div>
        <form class="search-form" method="get" action="index.php">
            <label class="visually-hidden" for="q">Cari produk, kategori, atau supplier</label>
            <input id="q" type="search" name="q" value="<?= e($search) ?>" placeholder="Cari produk, kategori, supplier...">
            <button class="button button-secondary" type="submit">Cari</button>
            <?php if ($search !== ''): ?>
                <a class="button button-quiet" href="index.php">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products === []): ?>
                    <tr><td class="empty-state" colspan="7">Belum ada produk yang cocok. Tambahkan produk baru atau ubah kata pencarian.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="product-name"><?= e($product['name']) ?></td>
                            <td><?= e($product['sku']) ?></td>
                            <td><span class="pill"><?= e($product['category_name']) ?></span></td>
                            <td><?= e($product['supplier_name']) ?></td>
                            <td>Rp <?= number_format((float) $product['price'], 0, ',', '.') ?></td>
                            <td><?= (int) $product['stock'] ?></td>
                            <td>
                                <div class="actions">
                                    <a class="text-link" href="edit.php?id=<?= (int) $product['id'] ?>">Edit</a>
                                    <form method="post" action="delete.php" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                        <button class="text-link text-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination" aria-label="Navigasi halaman">
            <?php if ($page > 1): ?>
                <a href="?q=<?= urlencode($search) ?>&amp;page=<?= $page - 1 ?>">← Sebelumnya</a>
            <?php else: ?>
                <span class="disabled">← Sebelumnya</span>
            <?php endif; ?>
            <span>Halaman <?= $page ?> dari <?= $totalPages ?></span>
            <?php if ($page < $totalPages): ?>
                <a href="?q=<?= urlencode($search) ?>&amp;page=<?= $page + 1 ?>">Berikutnya →</a>
            <?php else: ?>
                <span class="disabled">Berikutnya →</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
