<?php
$host = 'localhost';
$db   = 'eventease';
$user = 'root'; // if ur username is different then write it here
$pass = 'User/Chud6716'; //update with your own password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
     $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>