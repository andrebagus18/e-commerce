<?php
function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}