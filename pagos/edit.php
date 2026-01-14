<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) die("ID inválido.");

$stmt = $pdo->prepare("SELECT * FROM pagos WHERE id_pago = ?");
$stmt->execute([$id]);
$pago = $stmt->fetch();
if (!$pago) die("Pago no encontrado.");

$alumnos = $pdo->query("SELECT id_alumno, nombres, apellidos FROM alumnos ORDER BY id_alumno DESC")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_alumno  = (int)$_POST["id_alumno"];
  $anio       = (int)$_POST["anio"];
  $mes        = (int)$_POST["mes"];
  $monto      = (float)$_POST["monto"];
  $fecha_pago = $_POST["fecha_pago"];
  $metodo     = $_POST["metodo"];
  $estado     = $_POST["estado"];
  $obs        = trim($_POST["observacion"]);

  try {
    $up = $pdo->prepare(
      "UPDATE pagos
       SET id_alumno=?, anio=?, mes=?, monto=?, fecha_pago=?, metodo=?, estado=?, observacion=?
       WHERE id_pago=?"
    );
    $up->execute([$id_alumno, $anio, $mes, $monto, $fecha_pago, $metodo, $estado, $obs ?: null, $id]);

    header("Location: index.php");
    exit;
  } catch (PDOException $e) {
    die("Error al actualizar (¿duplicado mes/año para el alumno?): " . $e->getMessage());
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Pago</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Editar Pago (ID: <?= $pago["id_pago"] ?>)</h2>

  <form method="POST">
    <label>Alumno:</label><br>
    <select name="id_alumno" required>
      <?php foreach ($alumnos as $a): ?>
        <option value="<?= $a["id_alumno"] ?>" <?= ($a["id_alumno"] == $pago["id_alumno"] ? "selected" : "") ?>>
          <?= htmlspecialchars($a["nombres"]." ".$a["apellidos"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Año:</label><br>
    <input type="number" name="anio" value="<?= (int)$pago["anio"] ?>" required><br><br>

    <label>Mes (1-12):</label><br>
    <input type="number" name="mes" min="1" max="12" value="<?= (int)$pago["mes"] ?>" required><br><br>

    <label>Monto:</label><br>
    <input type="number" step="0.01" name="monto" value="<?= htmlspecialchars($pago["monto"]) ?>" required><br><br>

    <label>Fecha de pago:</label><br>
    <input type="date" name="fecha_pago" value="<?= htmlspecialchars($pago["fecha_pago"]) ?>" required><br><br>

    <label>Método:</label><br>
    <select name="metodo">
      <option value="EFECTIVO" <?= ($pago["metodo"] === "EFECTIVO" ? "selected" : "") ?>>EFECTIVO</option>
      <option value="TRANSFERENCIA" <?= ($pago["metodo"] === "TRANSFERENCIA" ? "selected" : "") ?>>TRANSFERENCIA</option>
      <option value="TARJETA" <?= ($pago["metodo"] === "TARJETA" ? "selected" : "") ?>>TARJETA</option>
    </select><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="PAGADO" <?= ($pago["estado"] === "PAGADO" ? "selected" : "") ?>>PAGADO</option>
      <option value="PENDIENTE" <?= ($pago["estado"] === "PENDIENTE" ? "selected" : "") ?>>PENDIENTE</option>
    </select><br><br>

    <label>Observación:</label><br>
    <input name="observacion" value="<?= htmlspecialchars($pago["observacion"] ?? "") ?>"><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
