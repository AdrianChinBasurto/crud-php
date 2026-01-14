<?php
require_once __DIR__ . "/../config/db.php";

$sql = "SELECT p.*, a.nombres AS a_nombres, a.apellidos AS a_apellidos
        FROM pagos p
        INNER JOIN alumnos a ON a.id_alumno = p.id_alumno
        ORDER BY p.id_pago DESC";
$pagos = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Pagos</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Pagos (READ)</h2>

  <p>
    <a href="../index.php">← Menú</a> |
    <a href="create.php">+ Nuevo pago</a>
  </p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Alumno</th>
      <th>Año</th>
      <th>Mes</th>
      <th>Monto</th>
      <th>Fecha pago</th>
      <th>Método</th>
      <th>Estado</th>
      <th>Obs.</th>
      <th>Acciones</th>
    </tr>

    <?php foreach ($pagos as $p): ?>
      <tr>
        <td><?= $p["id_pago"] ?></td>
        <td><?= htmlspecialchars($p["a_nombres"]." ".$p["a_apellidos"]) ?></td>
        <td><?= (int)$p["anio"] ?></td>
        <td><?= (int)$p["mes"] ?></td>
        <td><?= number_format((float)$p["monto"], 2) ?></td>
        <td><?= htmlspecialchars($p["fecha_pago"]) ?></td>
        <td><?= $p["metodo"] ?></td>
        <td><?= $p["estado"] ?></td>
        <td><?= htmlspecialchars($p["observacion"] ?? "") ?></td>
        <td>
          <a href="edit.php?id=<?= $p["id_pago"] ?>">Editar</a> |
          <a href="delete.php?id=<?= $p["id_pago"] ?>" onclick="return confirm('¿Eliminar pago?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
