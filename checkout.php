<?php
session_start();
require_once 'config/database.php';
require_once 'functions/helpers.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('login.php');
}

if (empty($_SESSION['cart'])) {
    redirect('cart.php');
}

$error = '';
$user = fetchOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    // Hitung total
    $total = 0;
    $cartItems = [];
    $ids = implode(',', array_keys($_SESSION['cart']));
    $products = fetchAll("SELECT * FROM products WHERE id IN ($ids)");

    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
        $cartItems[] = ['product' => $product, 'qty' => $qty];
    }

    // Simpan order
    $sql = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) VALUES (?, ?, ?, ?)";
    query($sql, [$_SESSION['user_id'], $total, $address, $payment_method]);
    $order_id = $pdo->lastInsertId();

    // Simpan order items & update stok
    foreach ($cartItems as $item) {
        query(
            "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)",
            [$order_id, $item['product']['id'], $item['qty'], $item['product']['price']]
        );
        query("UPDATE products SET stock = stock - ? WHERE id = ?", [$item['qty'], $item['product']['id']]);
    }

    // Kosongkan keranjang
    $_SESSION['cart'] = [];

    setFlash('success', 'Pesanan berhasil dibuat!');
    redirect('orders.php');
}

$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
        <h1>💳 Checkout</h1>

        <div class="card">
            <form method="POST">
                <div class="form-group">
                    <label>Alamat Pengiriman</label>
                    <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="payment_method" required>
                        <option value="transfer">Transfer Bank</option>
                        <option value="cod">COD (Bayar di Tempat)</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Konfirmasi Pesanan</button>
                <a href="cart.php" style="margin-left: 1rem;">Kembali ke Keranjang</a>
            </form>
        </div>
    </div>
</body>

</html>