<?php
require_once __DIR__ . "/../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombres   = trim($_POST['nombres']);
  $apellidos = trim($_POST['apellidos']);
  $licencia  = trim($_POST['licencia']);
  $telefono  = trim($_POST['telefono']);
  $estado    = $_POST['estado'];

  $stmt = $pdo->prepare(
    "INSERT INTO conductores (nombres, apellidos, licencia, telefono, estado)
     VALUES (?, ?, ?, ?, ?)"
  );
  $stmt->execute([$nombres, $apellidos, $licencia, $telefono, $estado]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Conductor</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Nuevo Conductor</h2>

  <form method="POST">
    <label>Nombres:</label><br>
    <input name="nombres" required><br><br>

    <label>Apellidos:</label><br>
    <input name="apellidos" required><br><br>

    <label>Licencia:</label><br>
    <input name="licencia" required><br><br>

    <label>Teléfono:</label><br>
    <input name="telefono"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVO">ACTIVO</option>
      <option value="INACTIVO">INACTIVO</option>
    </select><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
