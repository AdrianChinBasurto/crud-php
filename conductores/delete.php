<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) {
  die("ID inválido.");
}

$stmt = $pdo->prepare("UPDATE conductores SET estado='INACTIVO' WHERE id_conductor = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
