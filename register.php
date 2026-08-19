<?php
session_start();
require_once 'config/database.php';
require_once 'functions/helpers.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Cek username/email sudah ada
    $check = fetchOne("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);

    if ($check) {
        $error = 'Username atau email sudah terdaftar!';
    } else {
        query(
            "INSERT INTO users (username, email, password, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?)",
            [$username, $email, $password, $full_name, $phone, $address]
        );
        $success = 'Pendaftaran berhasil! Silakan login.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Commerce</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container" style="max-width: 500px;">
        <div class="card">
            <h2>Register</h2>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="address"></textarea>
                </div>
                <button type="submit">Daftar</button>
                <p style="margin-top: 1rem;">
                    Sudah punya akun? <a href="login.php">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>