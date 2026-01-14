<?php
require_once __DIR__ . "/../config/db.php";

// READ: listar conductores
$conductores = $pdo->query("SELECT * FROM conductores ORDER BY id_conductor DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Conductores</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Conductores (READ)</h2>

  <p>
    <a href="../index.php">← Menú</a> |
    <a href="create.php">+ Nuevo conductor</a>
  </p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>Licencia</th>
      <th>Teléfono</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>

    <?php foreach ($conductores as $c): ?>
      <tr>
        <td><?= $c["id_conductor"] ?></td>
        <td><?= htmlspecialchars($c["nombres"]) ?></td>
        <td><?= htmlspecialchars($c["apellidos"]) ?></td>
        <td><?= htmlspecialchars($c["licencia"]) ?></td>
        <td><?= htmlspecialchars($c["telefono"]) ?></td>
        <td><?= $c["estado"] ?></td>
        <td>
          <a href="edit.php?id=<?= $c["id_conductor"] ?>">Editar</a> |
          <a href="delete.php?id=<?= $c["id_conductor"] ?>" onclick="return confirm('¿Desactivar conductor?')">Desactivar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
