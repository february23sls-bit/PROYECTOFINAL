<?php
// ===== DEBUG (quitar en producción) =====
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== Sesión =====
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ===== Conexión PDO =====
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'courier_db';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
try {
  $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (Exception $e) {
  die("Error de conexión a la BD: " . $e->getMessage());
}

// ===== Utilidades de URL (para redirecciones correctas según carpeta) =====
// Ejemplos si estás en /courier_sdt/admin/index.php:
//   $CURRENT_DIR => /courier_sdt/admin
//   $APP_BASE    => /courier_sdt
$CURRENT_DIR = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');     // dir de la página actual
$APP_BASE    = rtrim(dirname($CURRENT_DIR), '/');                // carpeta web del proyecto

// ===== Helpers de roles =====
function es_admin(): bool       { return !empty($_SESSION['admin_id']); }
function es_emprendedor(): bool { return !empty($_SESSION['emprendedor_id']); }
function es_motorizado(): bool  { return !empty($_SESSION['motorizado_id']); }

// ===== Guardas (protecciones de ruta) =====
function exigir_admin() {
  // redirige a .../admin/login.php
  $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // p.ej. /courier_sdt/admin
  if (!es_admin()) {
    header("Location: {$dir}/login.php");
    exit;
  }
}
function exigir_emprendedor() {
  // redirige a .../acceso.php en la raíz del proyecto
  $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // p.ej. /courier_sdt/admin
  $app = rtrim(dirname($dir), '/');                    // p.ej. /courier_sdt
  if (!es_emprendedor()) {
    header("Location: {$app}/acceso.php?tab=emprendedor");
    exit;
  }
}
function exigir_motorizado() {
  $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
  $app = rtrim(dirname($dir), '/');
  if (!es_motorizado()) {
    header("Location: {$app}/acceso.php?tab=motorizado");
    exit;
  }
}
