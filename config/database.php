<?php
$host = 'localhost';
$port = '5432';
$dbname = 'ecommerce_db';
$user = 'postgres';
$password = 'admin123';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

function query($sql, $params = [])
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function fetchAll($sql, $params = [])
{
    return query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

function fetchOne($sql, $params = [])
{
    return query($sql, $params)->fetch(PDO::FETCH_ASSOC);
}