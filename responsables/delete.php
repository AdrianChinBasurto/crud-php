<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

// 1) Validar si tiene alumnos
$chk = $pdo->prepare("SELECT COUNT(*) AS total FROM alumnos WHERE id_responsable = ?");
$chk->execute([$id]);
$total = (int)$chk->fetch()["total"];

if ($total > 0) {
  // Si tiene alumnos, NO se borra
  die("No se puede eliminar: este responsable tiene $total alumno(s) asignado(s).");
}

// 2) Si no tiene alumnos, sí se borra
$del = $pdo->prepare("DELETE FROM responsables WHERE id_responsable = ?");
$del->execute([$id]);

header("Location: index.php");
exit;
