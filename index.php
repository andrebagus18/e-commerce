<?php
session_start();
require_once 'config/database.php';
require_once 'functions/helpers.php';

$products = fetchAll("SELECT * FROM products WHERE stock > 0 ORDER BY id DESC");
$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">Toko<span>Online</span></div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if (isLoggedIn()): ?>
                <a href="cart.php">🛒 Keranjang</a>
                <a href="orders.php">Pesanan</a>
                <a href="logout.php">Logout (<?= $_SESSION['username'] ?>)</a>
                <?php if (isAdmin()): ?>
                    <a href="admin/index.php">👑 Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h1>🛍️ Selamat Datang di Toko Online</h1>
        <p>Belanja kebutuhan Anda dengan mudah dan murah!</p>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
        <?php endif; ?>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        📦
                    </div>
                    <div class="product-info">
                        <div class="product-title"><?= htmlspecialchars($product['name']) ?></div>
                        <div class="product-price"><?= formatRupiah($product['price']) ?></div>
                        <div class="product-stock">Stok: <?= $product['stock'] ?></div>
                        <?php if (isLoggedIn() && !isAdmin()): ?>
                            <a href="cart.php?add=<?= $product['id'] ?>" class="btn btn-primary btn-sm">🛒 Beli</a>
                        <?php elseif (!isLoggedIn()): ?>
                            <a href="login.php" class="btn btn-primary btn-sm">Login untuk Beli</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>