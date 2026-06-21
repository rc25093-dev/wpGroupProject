<?php
$host = 'localhost';
$db   = 'eventease';
$user = 'root';
$pass = 'User/Chud6716';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('MySQL connection failed: ' . $conn->connect_error);
}

$conn->set_charset($charset);
?>