<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) {
  die("ID inválido.");
}

// Traer datos (READ por id)
$stmt = $pdo->prepare("SELECT * FROM conductores WHERE id_conductor = ?");
$stmt->execute([$id]);
$conductor = $stmt->fetch();

if (!$conductor) {
  die("Conductor no encontrado.");
}

// Si envían el formulario: UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombres   = trim($_POST["nombres"]);
  $apellidos = trim($_POST["apellidos"]);
  $licencia  = trim($_POST["licencia"]);
  $telefono  = trim($_POST["telefono"]);
  $estado    = $_POST["estado"];

  $up = $pdo->prepare(
    "UPDATE conductores
     SET nombres = ?, apellidos = ?, licencia = ?, telefono = ?, estado = ?
     WHERE id_conductor = ?"
  );
  $up->execute([$nombres, $apellidos, $licencia, $telefono, $estado, $id]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Conductor</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Editar Conductor (ID: <?= $conductor["id_conductor"] ?>)</h2>

  <form method="POST">
    <label>Nombres:</label><br>
    <input name="nombres" value="<?= htmlspecialchars($conductor["nombres"]) ?>" required><br><br>

    <label>Apellidos:</label><br>
    <input name="apellidos" value="<?= htmlspecialchars($conductor["apellidos"]) ?>" required><br><br>

    <label>Licencia:</label><br>
    <input name="licencia" value="<?= htmlspecialchars($conductor["licencia"]) ?>" required><br><br>

    <label>Teléfono:</label><br>
    <input name="telefono" value="<?= htmlspecialchars($conductor["telefono"]) ?>"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVO"   <?= ($conductor["estado"] === "ACTIVO" ? "selected" : "") ?>>ACTIVO</option>
      <option value="INACTIVO" <?= ($conductor["estado"] === "INACTIVO" ? "selected" : "") ?>>INACTIVO</option>
    </select><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
