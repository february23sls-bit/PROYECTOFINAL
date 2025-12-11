<?php
// admin/salir.php

// Iniciar sesión si aún no existe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpiar todas las variables de sesión
$_SESSION = [];

// Destruir la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión completamente
session_destroy();

// Redirigir al login del administrador
$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
header("Location: {$BASE}/login.php");
exit;
