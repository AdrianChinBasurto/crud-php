<?php
require_once __DIR__ . "/../config/db.php";

$responsables = $pdo->query("SELECT * FROM responsables ORDER BY id_responsable DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Responsables</title>
</head>
<body>
  <h2>Responsables (READ)</h2>

  <p>
    <a href="../index.php">← Menú</a> |
    <a href="create.php">+ Nuevo responsable</a>
  </p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>CI</th>
      <th>Teléfono</th>
      <th>Email</th>
      <th>Dirección</th>
      <th>Acciones</th>
    </tr>

    <?php foreach ($responsables as $r): ?>
      <tr>
        <td><?= $r["id_responsable"] ?></td>
        <td><?= htmlspecialchars($r["nombres"]) ?></td>
        <td><?= htmlspecialchars($r["apellidos"]) ?></td>
        <td><?= htmlspecialchars($r["ci"]) ?></td>
        <td><?= htmlspecialchars($r["telefono"]) ?></td>
        <td><?= htmlspecialchars($r["email"]) ?></td>
        <td><?= htmlspecialchars($r["direccion"]) ?></td>
        <td>
          <a href="edit.php?id=<?= $r["id_responsable"] ?>">Editar</a> |
          <a href="delete.php?id=<?= $r["id_responsable"] ?>" onclick="return confirm('¿Eliminar responsable?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p><small>Nota: si un responsable ya tiene alumnos, no se podrá eliminar por la FK.</small></p>
</body>
</html>
