<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

$stmt = $pdo->prepare("SELECT * FROM inscripciones WHERE id_inscripcion = ?");
$stmt->execute([$id]);
$ins = $stmt->fetch();
if (!$ins) die("Inscripción no encontrada.");

$alumnos = $pdo->query("SELECT id_alumno, nombres, apellidos, estado FROM alumnos ORDER BY id_alumno DESC")->fetchAll();
$rutas   = $pdo->query("SELECT id_ruta, nombre, estado FROM rutas ORDER BY id_ruta DESC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_alumno    = (int)$_POST["id_alumno"];
  $id_ruta      = (int)$_POST["id_ruta"];
  $fecha_inicio = $_POST["fecha_inicio"];
  $fecha_fin    = $_POST["fecha_fin"] ?: null;
  $estado       = $_POST["estado"];

  $up = $pdo->prepare(
    "UPDATE inscripciones
     SET id_alumno=?, id_ruta=?, fecha_inicio=?, fecha_fin=?, estado=?
     WHERE id_inscripcion=?"
  );
  $up->execute([$id_alumno, $id_ruta, $fecha_inicio, $fecha_fin, $estado, $id]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Inscripción</title>
</head>
<body>
  <h2>Editar Inscripción (ID: <?= $ins["id_inscripcion"] ?>)</h2>

  <form method="POST">
    <label>Alumno:</label><br>
    <select name="id_alumno" required>
      <?php foreach ($alumnos as $a): ?>
        <option value="<?= $a["id_alumno"] ?>" <?= ($a["id_alumno"] == $ins["id_alumno"] ? "selected" : "") ?>>
          <?= htmlspecialchars($a["nombres"]." ".$a["apellidos"]." (".$a["estado"].")") ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Ruta:</label><br>
    <select name="id_ruta" required>
      <?php foreach ($rutas as $r): ?>
        <option value="<?= $r["id_ruta"] ?>" <?= ($r["id_ruta"] == $ins["id_ruta"] ? "selected" : "") ?>>
          <?= htmlspecialchars($r["nombre"]." (".$r["estado"].")") ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Fecha inicio:</label><br>
    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($ins["fecha_inicio"]) ?>" required><br><br>

    <label>Fecha fin (opcional):</label><br>
    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($ins["fecha_fin"] ?? "") ?>"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVA" <?= ($ins["estado"] === "ACTIVA" ? "selected" : "") ?>>ACTIVA</option>
      <option value="FINALIZADA" <?= ($ins["estado"] === "FINALIZADA" ? "selected" : "") ?>>FINALIZADA</option>
    </select><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
