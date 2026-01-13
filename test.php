<?php
require_once __DIR__ . "/config/db.php";

$stmt = $pdo->query("SELECT NOW() AS ahora");
$row = $stmt->fetch();

echo "✅ Conexión OK. Hora del servidor: " . $row["ahora"];
