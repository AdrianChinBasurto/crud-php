<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

// Combo responsables
$responsables = $pdo->query("SELECT id_responsable, nombres, apellidos FROM responsables ORDER BY id_responsable DESC")->fetchAll();

// Traer alumno
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id_alumno = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch();

if (!$alumno) die("Alumno no encontrado.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_responsable   = (int)$_POST["id_responsable"];
  $nombres          = trim($_POST["nombres"]);
  $apellidos        = trim($_POST["apellidos"]);
  $fecha_nacimiento = $_POST["fecha_nacimiento"] ?: null;
  $curso            = trim($_POST["curso"]);
  $colegio          = trim($_POST["colegio"]);
  $estado           = $_POST["estado"];

  $up = $pdo->prepare(
    "UPDATE alumnos
     SET id_responsable = ?, nombres = ?, apellidos = ?, fecha_nacimiento = ?, curso = ?, colegio = ?, estado = ?
     WHERE id_alumno = ?"
  );
  $up->execute([$id_responsable, $nombres, $apellidos, $fecha_nacimiento, $curso, $colegio, $estado, $id]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Alumno</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Editar Alumno (ID: <?= $alumno["id_alumno"] ?>)</h2>

  <form method="POST">
    <label>Responsable:</label><br>
    <select name="id_responsable" required>
      <?php foreach ($responsables as $r): ?>
        <option value="<?= $r["id_responsable"] ?>"
          <?= ($r["id_responsable"] == $alumno["id_responsable"] ? "selected" : "") ?>>
          <?= htmlspecialchars($r["nombres"] . " " . $r["apellidos"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Nombres:</label><br>
    <input name="nombres" value="<?= htmlspecialchars($alumno["nombres"]) ?>" required><br><br>

    <label>Apellidos:</label><br>
    <input name="apellidos" value="<?= htmlspecialchars($alumno["apellidos"]) ?>" required><br><br>

    <label>Fecha de nacimiento:</label><br>
    <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($alumno["fecha_nacimiento"] ?? "") ?>"><br><br>

    <label>Curso:</label><br>
    <input name="curso" value="<?= htmlspecialchars($alumno["curso"] ?? "") ?>"><br><br>

    <label>Colegio:</label><br>
    <input name="colegio" value="<?= htmlspecialchars($alumno["colegio"] ?? "") ?>"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVO" <?= ($alumno["estado"] === "ACTIVO" ? "selected" : "") ?>>ACTIVO</option>
      <option value="INACTIVO" <?= ($alumno["estado"] === "INACTIVO" ? "selected" : "") ?>>INACTIVO</option>
    </select><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
