<?php
session_start();
require_once 'config/database.php';
require_once 'functions/helpers.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $user = fetchOne("SELECT * FROM users WHERE username = ? AND password = ?", [$username, $password]);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            redirect('admin/index.php');
        } else {
            redirect('index.php');
        }
    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Commerce</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container" style="max-width: 500px;">
        <div class="card">
            <h2>🔐 Login</h2>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Login</button>
                <p style="margin-top: 1rem;">
                    Belum punya akun? <a href="register.php">Register</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>