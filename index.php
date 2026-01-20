<?php
require_once __DIR__ . "/config/auth.php";
requireLogin();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Menú – Transporte Escolar</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="top-bar">
  <div>
    Usuario: <b><?= $_SESSION['db_user'] ?></b> |
    Rol: <b><?= $_SESSION['rol'] ?></b>
  </div>
  <a href="auth/logout.php" class="logout">Cerrar sesión</a>
</div>

<h2>Sistema de Transporte Escolar</h2>

<div class="menu-container">
  <a href="alumnos/index.php">Alumnos</a>
  <a href="rutas/index.php">Rutas</a>
  <a href="pagos/index.php">Pagos</a>

  <?php if ($_SESSION['rol'] !== 'read'): ?>
    <a href="responsables/index.php">Responsables</a>
    <a href="conductores/index.php">Conductores</a>
    <a href="vehiculos/index.php">Vehículos</a>
    <a href="inscripciones/index.php">Inscripciones</a>
  <?php endif; ?>

  <?php if ($_SESSION['rol'] === 'admin'): ?>
    <a href="#">Administración BD</a>
  <?php endif; ?>
</div>

<p class="legend">
  Lectura: solo consultas · App: CRUD operativo · Admin: control total
</p>

</body>
</html>
