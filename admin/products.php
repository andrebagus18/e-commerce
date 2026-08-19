<?php
session_start();
require_once '../config/database.php';
require_once '../functions/helpers.php';

if (!isAdmin()) {
    redirect('../login.php');
}

// Tambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    query(
        "INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)",
        [$_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock']]
    );
    setFlash('success', 'Produk berhasil ditambahkan!');
    redirect('products.php');
}

// Edit produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    query(
        "UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?",
        [$_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['id']]
    );
    setFlash('success', 'Produk berhasil diupdate!');
    redirect('products.php');
}

// Hapus produk
if (isset($_GET['hapus'])) {
    query("DELETE FROM products WHERE id = ?", [$_GET['hapus']]);
    setFlash('success', 'Produk berhasil dihapus!');
    redirect('products.php');
}

$products = fetchAll("SELECT * FROM products ORDER BY id DESC");
$editProduct = isset($_GET['edit']) ? fetchOne("SELECT * FROM products WHERE id = ?", [$_GET['edit']]) : null;
$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">Admin<span>Panel</span></div>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="products.php">Produk</a>
            <a href="orders.php">Pesanan</a>
            <a href="../index.php">Home</a>
            <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Kelola Produk</h1>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>
                <?= $editProduct ? '% Edit Produk' : '+ Tambah Produk' ?>
            </h2>
            <form method="POST">
                <?php if ($editProduct): ?>
                    <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="name" value="<?= $editProduct['name'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description"><?= $editProduct['description'] ?? '' ?></textarea>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="price" value="<?= $editProduct['price'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" value="<?= $editProduct['stock'] ?? 0 ?>" required>
                </div>
                <button type="submit" name="<?= $editProduct ? 'edit' : 'tambah' ?>">
                    <?= $editProduct ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($editProduct): ?>
                    <a href="products.php" style="margin-left: 1rem;">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Produk</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?= $product['id'] ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($product['name']) ?>
                            </td>
                            <td>
                                <?= formatRupiah($product['price']) ?>
                            </td>
                            <td>
                                <?= $product['stock'] ?>
                            </td>
                            <td>
                                <a href="?edit=<?= $product['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                                <a href="?hapus=<?= $product['id'] ?>" class="btn btn-sm"
                                    onclick="return confirm('Yakin?')">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>