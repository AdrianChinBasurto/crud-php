<?php
require_once __DIR__ . "/../config/db.php";

// Solo activos para asignar
$conductores = $pdo->query("SELECT id_conductor, nombres, apellidos FROM conductores WHERE estado='ACTIVO' ORDER BY id_conductor DESC")->fetchAll();
$vehiculos   = $pdo->query("SELECT id_vehiculo, placa, marca, modelo FROM vehiculos WHERE estado='ACTIVO' ORDER BY id_vehiculo DESC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_conductor  = (int)$_POST["id_conductor"];
  $id_vehiculo   = (int)$_POST["id_vehiculo"];
  $nombre        = trim($_POST["nombre"]);
  $horario       = trim($_POST["horario"]);
  $tarifa        = (float)$_POST["tarifa_mensual"];
  $estado        = $_POST["estado"];

  $stmt = $pdo->prepare(
    "INSERT INTO rutas (id_conductor, id_vehiculo, nombre, horario, tarifa_mensual, estado)
     VALUES (?, ?, ?, ?, ?, ?)"
  );
  $stmt->execute([$id_conductor, $id_vehiculo, $nombre, $horario, $tarifa, $estado]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nueva Ruta</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Nueva Ruta</h2>

  <form method="POST">
    <label>Conductor:</label><br>
    <select name="id_conductor" required>
      <option value="">-- Seleccione --</option>
      <?php foreach ($conductores as $c): ?>
        <option value="<?= $c["id_conductor"] ?>">
          <?= htmlspecialchars($c["nombres"]." ".$c["apellidos"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Vehículo:</label><br>
    <select name="id_vehiculo" required>
      <option value="">-- Seleccione --</option>
      <?php foreach ($vehiculos as $v): ?>
        <option value="<?= $v["id_vehiculo"] ?>">
          <?= htmlspecialchars($v["placa"]." - ".$v["marca"]." ".$v["modelo"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Nombre de la ruta:</label><br>
    <input name="nombre" required><br><br>

    <label>Horario:</label><br>
    <input name="horario" placeholder="06:30-07:30"><br><br>

    <label>Tarifa mensual:</label><br>
    <input type="number" step="0.01" name="tarifa_mensual" required><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVA">ACTIVA</option>
      <option value="INACTIVA">INACTIVA</option>
    </select><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
