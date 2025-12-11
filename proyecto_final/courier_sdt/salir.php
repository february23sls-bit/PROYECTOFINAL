<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = [];

// Si hay cookies de sesión, eliminarlas
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al inicio o al login principal
header("Location: acceso.php");
exit;
?>
