<?php
require_once __DIR__ . "/../config/db.php";

$sql = "SELECT i.*, 
               a.nombres AS a_nombres, a.apellidos AS a_apellidos,
               r.nombre AS ruta_nombre
        FROM inscripciones i
        INNER JOIN alumnos a ON a.id_alumno = i.id_alumno
        INNER JOIN rutas r   ON r.id_ruta   = i.id_ruta
        ORDER BY i.id_inscripcion DESC";
$inscripciones = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Inscripciones</title>
</head>
<body>
  <h2>Inscripciones (READ)</h2>

  <p>
    <a href="../index.php">← Menú</a> |
    <a href="create.php">+ Nueva inscripción</a>
  </p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Alumno</th>
      <th>Ruta</th>
      <th>Inicio</th>
      <th>Fin</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>

    <?php foreach ($inscripciones as $i): ?>
      <tr>
        <td><?= $i["id_inscripcion"] ?></td>
        <td><?= htmlspecialchars($i["a_nombres"]." ".$i["a_apellidos"]) ?></td>
        <td><?= htmlspecialchars($i["ruta_nombre"]) ?></td>
        <td><?= htmlspecialchars($i["fecha_inicio"]) ?></td>
        <td><?= htmlspecialchars($i["fecha_fin"] ?? "") ?></td>
        <td><?= $i["estado"] ?></td>
        <td>
          <a href="edit.php?id=<?= $i["id_inscripcion"] ?>">Editar</a> |
          <a href="delete.php?id=<?= $i["id_inscripcion"] ?>" onclick="return confirm('¿Eliminar inscripción?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p><small>Nota: aquí el eliminar es REAL (DELETE físico).</small></p>
</body>
</html>
