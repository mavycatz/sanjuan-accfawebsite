<?php
$host = "sql113.infinityfree.com";
$username = "if0_38107253";
$password = "yZcPxgWO74P";
$database = "if0_38107253_accfa_tracker";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
