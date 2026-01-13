<?php
require_once __DIR__ . "/../config/db.php";

$sql = "SELECT rt.*, 
               c.nombres AS c_nombres, c.apellidos AS c_apellidos,
               v.placa AS v_placa, v.marca AS v_marca, v.modelo AS v_modelo
        FROM rutas rt
        INNER JOIN conductores c ON c.id_conductor = rt.id_conductor
        INNER JOIN vehiculos v   ON v.id_vehiculo  = rt.id_vehiculo
        ORDER BY rt.id_ruta DESC";
$rutas = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Rutas</title>
</head>
<body>
  <h2>Rutas (READ)</h2>

  <p>
    <a href="../index.php">← Menú</a> |
    <a href="create.php">+ Nueva ruta</a>
  </p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Horario</th>
      <th>Tarifa mensual</th>
      <th>Conductor</th>
      <th>Vehículo</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>

    <?php foreach ($rutas as $r): ?>
      <tr>
        <td><?= $r["id_ruta"] ?></td>
        <td><?= htmlspecialchars($r["nombre"]) ?></td>
        <td><?= htmlspecialchars($r["horario"] ?? "") ?></td>
        <td><?= number_format((float)$r["tarifa_mensual"], 2) ?></td>
        <td><?= htmlspecialchars($r["c_nombres"]." ".$r["c_apellidos"]) ?></td>
        <td><?= htmlspecialchars($r["v_placa"]." - ".$r["v_marca"]." ".$r["v_modelo"]) ?></td>
        <td><?= $r["estado"] ?></td>
        <td>
          <a href="edit.php?id=<?= $r["id_ruta"] ?>">Editar</a> |
          <a href="delete.php?id=<?= $r["id_ruta"] ?>" onclick="return confirm('¿Desactivar ruta?')">Desactivar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p><small>Nota: Desactivar cambia el estado a INACTIVA (no borra) para no afectar inscripciones.</small></p>
</body>
</html>
