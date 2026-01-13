<?php
require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $placa     = strtoupper(trim($_POST["placa"]));
  $marca     = trim($_POST["marca"]);
  $modelo    = trim($_POST["modelo"]);
  $capacidad = (int)$_POST["capacidad"];
  $estado    = $_POST["estado"];

  $stmt = $pdo->prepare(
    "INSERT INTO vehiculos (placa, marca, modelo, capacidad, estado)
     VALUES (?, ?, ?, ?, ?)"
  );
  $stmt->execute([$placa, $marca, $modelo, $capacidad, $estado]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Vehículo</title>
</head>
<body>
  <h2>Nuevo Vehículo</h2>

  <form method="POST">
    <label>Placa:</label><br>
    <input name="placa" required><br><br>

    <label>Marca:</label><br>
    <input name="marca"><br><br>

    <label>Modelo:</label><br>
    <input name="modelo"><br><br>

    <label>Capacidad:</label><br>
    <input type="number" name="capacidad" min="1" required><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVO">ACTIVO</option>
      <option value="MANTENIMIENTO">MANTENIMIENTO</option>
      <option value="INACTIVO">INACTIVO</option>
    </select><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
