<?php
require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombres   = trim($_POST["nombres"]);
  $apellidos = trim($_POST["apellidos"]);
  $ci        = trim($_POST["ci"]);
  $telefono  = trim($_POST["telefono"]);
  $email     = trim($_POST["email"]);
  $direccion = trim($_POST["direccion"]);

  $stmt = $pdo->prepare(
    "INSERT INTO responsables (nombres, apellidos, ci, telefono, email, direccion)
     VALUES (?, ?, ?, ?, ?, ?)"
  );
  $stmt->execute([$nombres, $apellidos, $ci, $telefono, $email, $direccion]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Responsable</title>
</head>
<body>
  <h2>Nuevo Responsable</h2>

  <form method="POST">
    <label>Nombres:</label><br>
    <input name="nombres" required><br><br>

    <label>Apellidos:</label><br>
    <input name="apellidos" required><br><br>

    <label>CI:</label><br>
    <input name="ci"><br><br>

    <label>Teléfono:</label><br>
    <input name="telefono"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email"><br><br>

    <label>Dirección:</label><br>
    <input name="direccion"><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
