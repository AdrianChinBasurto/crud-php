<?php
require_once __DIR__ . "/../config/db.php";

$alumnos = $pdo->query("SELECT id_alumno, nombres, apellidos FROM alumnos WHERE estado='ACTIVO' ORDER BY id_alumno DESC")->fetchAll();

$info = null;
$error = null;

// Si viene alumno por GET, buscamos su ruta activa y tarifa (para mostrar/llenar)
$id_alumno_get = (int)($_GET["id_alumno"] ?? 0);
if ($id_alumno_get > 0) {
  $q = $pdo->prepare(
    "SELECT i.id_inscripcion, r.id_ruta, r.nombre AS ruta_nombre, r.tarifa_mensual
     FROM inscripciones i
     INNER JOIN rutas r ON r.id_ruta = i.id_ruta
     WHERE i.id_alumno = ? AND i.estado='ACTIVA' AND r.estado='ACTIVA'
     ORDER BY i.fecha_inicio DESC
     LIMIT 1"
  );
  $q->execute([$id_alumno_get]);
  $info = $q->fetch();

  if (!$info) {
    $error = "Este alumno no tiene una inscripción ACTIVA en una ruta ACTIVA. No se puede autocalcular el monto.";
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_alumno  = (int)$_POST["id_alumno"];
  $anio       = (int)$_POST["anio"];
  $mes        = (int)$_POST["mes"];
  $monto      = (float)$_POST["monto"];
  $fecha_pago = $_POST["fecha_pago"];
  $metodo     = $_POST["metodo"];
  $estado     = $_POST["estado"];
  $obs        = trim($_POST["observacion"]);

  // Validar inscripción activa (obligatorio para registrar pago)
  $q = $pdo->prepare(
    "SELECT r.tarifa_mensual
     FROM inscripciones i
     INNER JOIN rutas r ON r.id_ruta = i.id_ruta
     WHERE i.id_alumno = ? AND i.estado='ACTIVA' AND r.estado='ACTIVA'
     ORDER BY i.fecha_inicio DESC
     LIMIT 1"
  );
  $q->execute([$id_alumno]);
  $rInfo = $q->fetch();

  if (!$rInfo) {
    die("No se puede registrar pago: el alumno no tiene una inscripción ACTIVA en una ruta ACTIVA.");
  }

  // Si el usuario dejó monto vacío o 0, usamos tarifa mensual
  if ($monto <= 0) {
    $monto = (float)$rInfo["tarifa_mensual"];
  }

  try {
    $stmt = $pdo->prepare(
      "INSERT INTO pagos (id_alumno, anio, mes, monto, fecha_pago, metodo, estado, observacion)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$id_alumno, $anio, $mes, $monto, $fecha_pago, $metodo, $estado, $obs ?: null]);

    header("Location: index.php");
    exit;
  } catch (PDOException $e) {
    // Por UNIQUE(id_alumno, anio, mes)
    die("Error al guardar pago (¿ya existe pago para ese mes/año?): " . $e->getMessage());
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Pago</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <h2>Nuevo Pago</h2>

  <p><a href="index.php">← Volver</a></p>

  <!-- Selector rápido: al elegir alumno recarga para mostrar tarifa -->
  <form method="GET" style="margin-bottom:12px;">
    <label>Seleccionar alumno para autollenar tarifa:</label><br>
    <select name="id_alumno" onchange="this.form.submit()">
      <option value="">-- Seleccione --</option>
      <?php foreach ($alumnos as $a): ?>
        <option value="<?= $a["id_alumno"] ?>" <?= ($id_alumno_get === (int)$a["id_alumno"] ? "selected" : "") ?>>
          <?= htmlspecialchars($a["nombres"]." ".$a["apellidos"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <noscript><button type="submit">Cargar</button></noscript>
  </form>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if ($info): ?>
    <p>
      Ruta activa: <b><?= htmlspecialchars($info["ruta_nombre"]) ?></b><br>
      Tarifa mensual sugerida: <b><?= number_format((float)$info["tarifa_mensual"], 2) ?></b>
    </p>
  <?php endif; ?>

  <form method="POST">
    <label>Alumno:</label><br>
    <select name="id_alumno" required>
      <option value="">-- Seleccione --</option>
      <?php foreach ($alumnos as $a): ?>
        <option value="<?= $a["id_alumno"] ?>" <?= ($id_alumno_get === (int)$a["id_alumno"] ? "selected" : "") ?>>
          <?= htmlspecialchars($a["nombres"]." ".$a["apellidos"]) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <br><br>

    <label>Año:</label><br>
    <input type="number" name="anio" value="<?= date("Y") ?>" required><br><br>

    <label>Mes (1-12):</label><br>
    <input type="number" name="mes" min="1" max="12" value="<?= date("n") ?>" required><br><br>

    <label>Monto (si lo dejas en 0 se usará la tarifa mensual):</label><br>
    <input type="number" step="0.01" name="monto" value="<?= $info ? htmlspecialchars($info["tarifa_mensual"]) : "0" ?>" required><br><br>

    <label>Fecha de pago:</label><br>
    <input type="date" name="fecha_pago" value="<?= date("Y-m-d") ?>" required><br><br>

    <label>Método:</label><br>
    <select name="metodo">
      <option value="EFECTIVO">EFECTIVO</option>
      <option value="TRANSFERENCIA">TRANSFERENCIA</option>
      <option value="TARJETA">TARJETA</option>
    </select><br><br>

    <label>Estado:</label><br>
    <select name="estado">
      <option value="PAGADO">PAGADO</option>
      <option value="PENDIENTE">PENDIENTE</option>
    </select><br><br>

    <label>Observación:</label><br>
    <input name="observacion"><br><br>

    <button type="submit">Guardar Pago</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
