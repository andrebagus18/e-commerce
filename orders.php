<?php
session_start();
require_once 'config/database.php';
require_once 'functions/helpers.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (isAdmin()) {
    $orders = fetchAll("
        SELECT o.*, u.username, u.full_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.order_date DESC
    ");
} else {
    $orders = fetchAll("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC", [$_SESSION['user_id']]);
}

$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">Toko<span>Online</span></div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="cart.php">Keranjang</a>
            <a href="orders.php">Pesanan</a>
            <?php if (isAdmin()): ?>
                <a href="admin/index.php">Admin</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Riwayat Pesanan</h1>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <?php if (empty($orders)): ?>
                <p>Belum ada pesanan.</p>
                <a href="index.php" class="btn">Belanja Sekarang</a>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <?= $order['id'] ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?>
                                </td>
                                <td>
                                    <?= formatRupiah($order['total_amount']) ?>
                                </td>
                                <td>
                                    <?= ucfirst($order['status']) ?>
                                </td>
                                <td><a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-sm">Detail</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>