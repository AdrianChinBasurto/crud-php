<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

$stmt = $pdo->prepare("SELECT * FROM responsables WHERE id_responsable = ?");
$stmt->execute([$id]);
$responsable = $stmt->fetch();

if (!$responsable) die("Responsable no encontrado.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombres   = trim($_POST["nombres"]);
  $apellidos = trim($_POST["apellidos"]);
  $ci        = trim($_POST["ci"]);
  $telefono  = trim($_POST["telefono"]);
  $email     = trim($_POST["email"]);
  $direccion = trim($_POST["direccion"]);

  $up = $pdo->prepare(
    "UPDATE responsables
     SET nombres = ?, apellidos = ?, ci = ?, telefono = ?, email = ?, direccion = ?
     WHERE id_responsable = ?"
  );
  $up->execute([$nombres, $apellidos, $ci, $telefono, $email, $direccion, $id]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Responsable</title>
</head>
<body>
  <h2>Editar Responsable (ID: <?= $responsable["id_responsable"] ?>)</h2>

  <form method="POST">
    <label>Nombres:</label><br>
    <input name="nombres" value="<?= htmlspecialchars($responsable["nombres"]) ?>" required><br><br>

    <label>Apellidos:</label><br>
    <input name="apellidos" value="<?= htmlspecialchars($responsable["apellidos"]) ?>" required><br><br>

    <label>CI:</label><br>
    <input name="ci" value="<?= htmlspecialchars($responsable["ci"]) ?>"><br><br>

    <label>Teléfono:</label><br>
    <input name="telefono" value="<?= htmlspecialchars($responsable["telefono"]) ?>"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($responsable["email"]) ?>"><br><br>

    <label>Dirección:</label><br>
    <input name="direccion" value="<?= htmlspecialchars($responsable["direccion"]) ?>"><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
