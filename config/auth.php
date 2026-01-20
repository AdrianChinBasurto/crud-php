<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function getRolFromUser($user) {
  if ($user === 'trans_admin') return 'admin';
  if ($user === 'trans_app')   return 'app';
  if ($user === 'trans_read')  return 'read';
  return 'unknown';
}

function isLogged() {
  return isset($_SESSION['db_user']);
}

function requireLogin() {
  if (!isLogged()) {
    header("Location: auth/login.php");
    exit;
  }
}
