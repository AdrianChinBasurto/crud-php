<?php
require_once __DIR__ . "/../config/db.php";

// Para el combo: listar responsables
$responsables = $pdo->query("SELECT id_responsable, nombres, apellidos FROM responsables ORDER BY id_responsable DESC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_responsable   = (int)$_POST["id_responsable"];
  $nombres          = trim($_POST["nombres"]);
  $apellidos        = trim($_POST["apellidos"]);
  $fecha_nacimiento = $_POST["fecha_nacimiento"] ?: null;
  $curso            = trim($_POST["curso"]);
  $colegio          = trim($_POST["colegio"]);
  $estado           = $_POST["estado"];

  $stmt = $pdo->prepare(
    "INSERT INTO alumnos (id_responsable, nombres, apellidos, fecha_nacimiento, curso, colegio, estado)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
  );
  $stmt->execute([$id_responsable, $nombres, $apellidos, $fecha_nacimiento, $curso, $colegio, $estado]);

  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Alumno</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Nuevo Alumno</h2>

  <form method="POST">
    <label>Responsable:</label><br>
    <select name="id_responsable" required>
      <option value="">-- Seleccione --</option>
      <?php foreach ($responsables as $r): ?>
        <option value="<?= $r["id_responsable"] ?>">
          <?= htmlspecialchars($r["nombres"] . " " . $r["apellidos"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Nombres:</label><br>
    <input name="nombres" required><br><br>

    <label>Apellidos:</label><br>
    <input name="apellidos" required><br><br>

    <label>Fecha de nacimiento:</label><br>
    <input type="date" name="fecha_nacimiento"><br><br>

    <label>Curso:</label><br>
    <input name="curso"><br><br>

    <label>Colegio:</label><br>
    <input name="colegio"><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="ACTIVO">ACTIVO</option>
      <option value="INACTIVO">INACTIVO</option>
    </select><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
