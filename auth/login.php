<?php
session_start();

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user = $_POST["user"] ?? '';
  $pass = $_POST["pass"] ?? '';

  try {
    // Intento de conexión con credenciales MySQL
    $pdo = new PDO(
      "mysql:host=localhost;dbname=transporte_escolar;charset=utf8",
      $user,
      $pass,
      [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Login correcto
    $_SESSION['db_user'] = $user;
    $_SESSION['db_pass'] = $pass;
    $_SESSION['rol']     = ($user === 'trans_admin') ? 'admin' :
                           (($user === 'trans_app') ? 'app' : 'read');

    header("Location: ../index.php");
    exit;

  } catch (PDOException $e) {
    $error = "Usuario o contraseña incorrectos.";
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <h2>Login – Transporte Escolar</h2>

      <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST">
        <label>Usuario MySQL:</label>
        <input name="user" required>

        <label>Contraseña:</label>
        <input type="password" name="pass" required>

        <button type="submit">Ingresar</button>
      </form>

      <p class="info">
        Usuarios: <b>trans_admin</b>, <b>trans_app</b>, <b>trans_read</b>
      </p>
    </div>
  </div>
</body>
</html>
