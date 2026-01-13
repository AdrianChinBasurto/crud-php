<?php
require_once __DIR__ . "/../config/db.php";

$vehiculos = $pdo->query("SELECT * FROM vehiculos ORDER BY id_vehiculo DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Vehículos</title>
</head>
<body>
  <h2>Vehículos (READ)</h2>

  <p>
    <a href="../index.php">← Menú</a> |
    <a href="create.php">+ Nuevo vehículo</a>
  </p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Placa</th>
      <th>Marca</th>
      <th>Modelo</th>
      <th>Capacidad</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>

    <?php foreach ($vehiculos as $v): ?>
      <tr>
        <td><?= $v["id_vehiculo"] ?></td>
        <td><?= htmlspecialchars($v["placa"]) ?></td>
        <td><?= htmlspecialchars($v["marca"]) ?></td>
        <td><?= htmlspecialchars($v["modelo"]) ?></td>
        <td><?= (int)$v["capacidad"] ?></td>
        <td><?= $v["estado"] ?></td>
        <td>
          <a href="edit.php?id=<?= $v["id_vehiculo"] ?>">Editar</a> |
          <a href="delete.php?id=<?= $v["id_vehiculo"] ?>" onclick="return confirm('¿Desactivar vehículo?')">Desactivar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p><small>Nota: “Desactivar” cambia el estado a INACTIVO (no borra) para evitar errores por rutas.</small></p>
</body>
</html>
