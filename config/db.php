<?php
require_once __DIR__ . "/auth.php";

if (!isset($_SESSION['db_user'], $_SESSION['db_pass'])) {
  header("Location: auth/login.php");
  exit;
}

try {
  $pdo = new PDO(
    "mysql:host=localhost;dbname=transporte_escolar;charset=utf8",
    $_SESSION['db_user'],
    $_SESSION['db_pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  die("Error de conexión.");
}
