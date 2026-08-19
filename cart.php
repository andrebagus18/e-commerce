<?php
session_start();
require_once 'config/database.php';
require_once 'functions/helpers.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('login.php');
}

// Inisialisasi keranjang
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah ke keranjang
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    setFlash('success', 'Produk ditambahkan ke keranjang!');
    redirect('cart.php');
}

// Hapus dari keranjang
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    setFlash('success', 'Produk dihapus dari keranjang!');
    redirect('cart.php');
}

// Update jumlah
if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    redirect('cart.php');
}

// Ambil detail produk
$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $products = fetchAll("SELECT * FROM products WHERE id IN ($ids)");

    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $subtotal = $product['price'] * $qty;
        $cartItems[] = [
            'product' => $product,
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
        $total += $subtotal;
    }
}

$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Toko<span>Online</span></div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="cart.php">🛒 Keranjang</a>
            <a href="orders.php">Pesanan</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>🛒 Keranjang Belanja</h1>
        
        <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
        <?php endif; ?>
        
        <?php if (empty($cartItems)): ?>
                <div class="card">
                    <p>Keranjang belanja kosong.</p>
                    <a href="index.php" class="btn">Belanja Sekarang</a>
                </div>
        <?php else: ?>
                <form method="POST">
                    <div class="card">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item">
                                <div style="flex: 2;">
                                    <strong><?= htmlspecialchars($item['product']['name']) ?></strong><br>
                                    <?= formatRupiah($item['product']['price']) ?>
                                </div>
                                <div>
                                    <input type="number" name="qty[<?= $item['product']['id'] ?>]" 
                                           value="<?= $item['qty'] ?>" min="0" style="width: 70px;">
                                </div>
                                <div style="width: 100px;">
                                    <?= formatRupiah($item['subtotal']) ?>
                                </div>
                                <div>
                                    <a href="?remove=<?= $item['product']['id'] ?>" class="btn btn-sm btn-warning">Hapus</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    
                        <div class="cart-total">
                            Total: <?= formatRupiah($total) ?>
                        </div>
                    
                        <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                            <button type="submit" name="update" class="btn">Update Keranjang</button>
                            <a href="checkout.php" class="btn btn-primary">Checkout</a>
                        </div>
                    </div>
                </form>
        <?php endif; ?>
    </div>
</body>
</html>