<?php
require_once __DIR__ . "/../config/db.php";

$alumnos = $pdo->query("SELECT id_alumno, nombres, apellidos FROM alumnos WHERE estado='ACTIVO' ORDER BY id_alumno DESC")->fetchAll();
$rutas   = $pdo->query("SELECT id_ruta, nombre FROM rutas WHERE estado='ACTIVA' ORDER BY id_ruta DESC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_alumno    = (int)$_POST["id_alumno"];
  $id_ruta      = (int)$_POST["id_ruta"];
  $fecha_inicio = $_POST["fecha_inicio"];
  $fecha_fin    = $_POST["fecha_fin"] ?: null;
  $estado       = $_POST["estado"];

  $stmt = $pdo->prepare(
    "INSERT INTO inscripciones (id_alumno, id_ruta, fecha_inicio, fecha_fin, estado)
     VALUES (?, ?, ?, ?, ?)"
  );
  $stmt->execute([$id_alumno, $id_ruta, $fecha_inicio, $fecha_fin, $estado]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nueva Inscripción</title>
</head>
<body>
  <h2>Nueva Inscripción</h2>

  <form method="POST">
    <label>Alumno:</label><br>
    <select name="id_alumno" required>
      <option value="">-- Seleccione --</option>
      <?php foreach ($alumnos as $a): ?>
        <option value="<?= $a["id_alumno"] ?>">
          <?= htmlspecialchars($a["nombres"]." ".$a["apellidos"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Ruta:</label><br>
    <select name="id_ruta" required>
      <option value="">-- Seleccione --</option>
      <?php foreach ($rutas as $r): ?>
        <option value="<?= $r["id_ruta"] ?>">
          <?= htmlspecialchars($r["nombre"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Fecha inicio:</label><br>
    <input type="date" name="fecha_inicio" required><br><br>

    <label>Fecha fin (opcional):</label><br>
    <input type="date" name="fecha_fin"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVA">ACTIVA</option>
      <option value="FINALIZADA">FINALIZADA</option>
    </select><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
