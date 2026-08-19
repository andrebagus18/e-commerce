<?php
session_start();
require_once '../config/database.php';
require_once '../functions/helpers.php';

if (!isAdmin()) {
    redirect('../login.php');
}

$totalProducts = fetchOne("SELECT COUNT(*) as total FROM products")['total'];
$totalUsers = fetchOne("SELECT COUNT(*) as total FROM users WHERE role = 'user'")['total'];
$totalOrders = fetchOne("SELECT COUNT(*) as total FROM orders")['total'];
$totalRevenue = fetchOne("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'")['total'];

$recentOrders = fetchAll("
    SELECT o.*, u.username FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <h1>Admin Dashboard</h1>

        <div class="stats-grid"
            style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
            <div class="card" style="text-align: center;">
                <h3>
                    <?= $totalProducts ?>
                </h3>
                <p>Total Produk</p>
            </div>
            <div class="card" style="text-align: center;">
                <h3>
                    <?= $totalUsers ?>
                </h3>
                <p>Total User</p>
            </div>
            <div class="card" style="text-align: center;">
                <h3>
                    <?= $totalOrders ?>
                </h3>
                <p>Total Pesanan</p>
            </div>
            <div class="card" style="text-align: center;">
                <h3>
                    <?= formatRupiah($totalRevenue ?? 0) ?>
                </h3>
                <p>Pendapatan</p>
            </div>
        </div>

        <div class="card">
            <h2>Pesanan Terbaru</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>
                                <?= $order['id'] ?>
                            </td>
                            <td>
                                <?= $order['username'] ?>
                            </td>
                            <td>
                                <?= formatRupiah($order['total_amount']) ?>
                            </td>
                            <td>
                                <?= ucfirst($order['status']) ?>
                            </td>
                            <td><a href="orders.php" class="btn btn-sm">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>