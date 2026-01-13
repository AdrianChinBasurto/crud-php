<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

$del = $pdo->prepare("DELETE FROM inscripciones WHERE id_inscripcion = ?");
$del->execute([$id]);

header("Location: index.php");
exit;
