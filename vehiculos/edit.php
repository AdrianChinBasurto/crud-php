<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

$stmt = $pdo->prepare("SELECT * FROM vehiculos WHERE id_vehiculo = ?");
$stmt->execute([$id]);
$vehiculo = $stmt->fetch();

if (!$vehiculo) die("Vehículo no encontrado.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $placa     = strtoupper(trim($_POST["placa"]));
  $marca     = trim($_POST["marca"]);
  $modelo    = trim($_POST["modelo"]);
  $capacidad = (int)$_POST["capacidad"];
  $estado    = $_POST["estado"];

  $up = $pdo->prepare(
    "UPDATE vehiculos
     SET placa = ?, marca = ?, modelo = ?, capacidad = ?, estado = ?
     WHERE id_vehiculo = ?"
  );
  $up->execute([$placa, $marca, $modelo, $capacidad, $estado, $id]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Vehículo</title>
</head>
<body>
  <h2>Editar Vehículo (ID: <?= $vehiculo["id_vehiculo"] ?>)</h2>

  <form method="POST">
    <label>Placa:</label><br>
    <input name="placa" value="<?= htmlspecialchars($vehiculo["placa"]) ?>" required><br><br>

    <label>Marca:</label><br>
    <input name="marca" value="<?= htmlspecialchars($vehiculo["marca"]) ?>"><br><br>

    <label>Modelo:</label><br>
    <input name="modelo" value="<?= htmlspecialchars($vehiculo["modelo"]) ?>"><br><br>

    <label>Capacidad:</label><br>
    <input type="number" name="capacidad" min="1" value="<?= (int)$vehiculo["capacidad"] ?>" required><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVO" <?= ($vehiculo["estado"] === "ACTIVO" ? "selected" : "") ?>>ACTIVO</option>
      <option value="MANTENIMIENTO" <?= ($vehiculo["estado"] === "MANTENIMIENTO" ? "selected" : "") ?>>MANTENIMIENTO</option>
      <option value="INACTIVO" <?= ($vehiculo["estado"] === "INACTIVO" ? "selected" : "") ?>>INACTIVO</option>
    </select><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
