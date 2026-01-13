<?php
$host = "localhost";
$db   = "transporte_escolar";
$user = "root";      // o trans_app si lo creaste
$pass = "";          // en XAMPP suele ser vacío, o tu clave
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}


