<?php
require_once __DIR__ . "/../config/db.php";

// READ: listar alumnos + responsable (JOIN)
$sql = "SELECT a.*, r.nombres AS resp_nombres, r.apellidos AS resp_apellidos
        FROM alumnos a
        INNER JOIN responsables r ON r.id_responsable = a.id_responsable
        ORDER BY a.id_alumno DESC";
$alumnos = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Alumnos</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Alumnos (READ)</h2>

  <p>
    <a href="../index.php">← Menú</a> |
    <a href="create.php">+ Nuevo alumno</a>
  </p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Alumno</th>
      <th>Responsable</th>
      <th>Fecha Nac.</th>
      <th>Curso</th>
      <th>Colegio</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>

    <?php foreach ($alumnos as $a): ?>
      <tr>
        <td><?= $a["id_alumno"] ?></td>
        <td><?= htmlspecialchars($a["nombres"] . " " . $a["apellidos"]) ?></td>
        <td><?= htmlspecialchars($a["resp_nombres"] . " " . $a["resp_apellidos"]) ?></td>
        <td><?= htmlspecialchars($a["fecha_nacimiento"] ?? "") ?></td>
        <td><?= htmlspecialchars($a["curso"] ?? "") ?></td>
        <td><?= htmlspecialchars($a["colegio"] ?? "") ?></td>
        <td><?= $a["estado"] ?></td>
        <td>
          <a href="edit.php?id=<?= $a["id_alumno"] ?>">Editar</a> |
          <a href="delete.php?id=<?= $a["id_alumno"] ?>" onclick="return confirm('¿Desactivar alumno?')">Desactivar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

</body>
</html>
