<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

// Traer ruta
$stmt = $pdo->prepare("SELECT * FROM rutas WHERE id_ruta = ?");
$stmt->execute([$id]);
$ruta = $stmt->fetch();
if (!$ruta) die("Ruta no encontrada.");

// Combos (incluye activos; si el asignado está inactivo, igual lo mostramos)
$conductores = $pdo->query("SELECT id_conductor, nombres, apellidos, estado FROM conductores ORDER BY id_conductor DESC")->fetchAll();
$vehiculos   = $pdo->query("SELECT id_vehiculo, placa, marca, modelo, estado FROM vehiculos ORDER BY id_vehiculo DESC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_conductor  = (int)$_POST["id_conductor"];
  $id_vehiculo   = (int)$_POST["id_vehiculo"];
  $nombre        = trim($_POST["nombre"]);
  $horario       = trim($_POST["horario"]);
  $tarifa        = (float)$_POST["tarifa_mensual"];
  $estado        = $_POST["estado"];

  $up = $pdo->prepare(
    "UPDATE rutas
     SET id_conductor=?, id_vehiculo=?, nombre=?, horario=?, tarifa_mensual=?, estado=?
     WHERE id_ruta=?"
  );
  $up->execute([$id_conductor, $id_vehiculo, $nombre, $horario, $tarifa, $estado, $id]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Ruta</title>
</head>
<body>
  <h2>Editar Ruta (ID: <?= $ruta["id_ruta"] ?>)</h2>

  <form method="POST">
    <label>Conductor:</label><br>
    <select name="id_conductor" required>
      <?php foreach ($conductores as $c): ?>
        <option value="<?= $c["id_conductor"] ?>" <?= ($c["id_conductor"] == $ruta["id_conductor"] ? "selected" : "") ?>>
          <?= htmlspecialchars($c["nombres"]." ".$c["apellidos"]." (".$c["estado"].")") ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Vehículo:</label><br>
    <select name="id_vehiculo" required>
      <?php foreach ($vehiculos as $v): ?>
        <option value="<?= $v["id_vehiculo"] ?>" <?= ($v["id_vehiculo"] == $ruta["id_vehiculo"] ? "selected" : "") ?>>
          <?= htmlspecialchars($v["placa"]." - ".$v["marca"]." ".$v["modelo"]." (".$v["estado"].")") ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Nombre:</label><br>
    <input name="nombre" value="<?= htmlspecialchars($ruta["nombre"]) ?>" required><br><br>

    <label>Horario:</label><br>
    <input name="horario" value="<?= htmlspecialchars($ruta["horario"] ?? "") ?>"><br><br>

    <label>Tarifa mensual:</label><br>
    <input type="number" step="0.01" name="tarifa_mensual" value="<?= htmlspecialchars($ruta["tarifa_mensual"]) ?>" required><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVA" <?= ($ruta["estado"] === "ACTIVA" ? "selected" : "") ?>>ACTIVA</option>
      <option value="INACTIVA" <?= ($ruta["estado"] === "INACTIVA" ? "selected" : "") ?>>INACTIVA</option>
    </select><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
