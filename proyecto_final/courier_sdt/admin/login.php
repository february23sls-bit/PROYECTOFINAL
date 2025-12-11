<?php
session_start();

// Asegúrate de que la ruta de config.php sea correcta
require_once __DIR__ . '/../config.php';  // Ruta ajustada si es necesario

$mensaje = ''; // Variable para mostrar el mensaje de error

// Comprobar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Validar si se han ingresado ambos campos
    if ($usuario === '' || $contrasena === '') {
        $mensaje = 'Por favor, ingresa tu usuario y contraseña.';
    } else {
        // Consultar la base de datos
        $st = $pdo->prepare("SELECT * FROM administradores WHERE usuario = ? LIMIT 1");
        $st->execute([$usuario]);
        $row = $st->fetch();

        // Si encontramos un usuario con ese nombre, validamos la contraseña
        if ($row && $row['contrasena'] === $contrasena) {
            // Si la contraseña es correcta, iniciamos sesión
            $_SESSION['admin_id'] = $row['id_administrador'];
            $_SESSION['admin_usuario'] = $row['usuario'];
            $_SESSION['admin_nombre'] = $row['nombre_completo'];

            // Redirigir al dashboard
            header("Location: index.php");
            exit;
        } else {
            $mensaje = 'Usuario o contraseña incorrectos.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-logo">
        <img src="../images/logo.png" alt="SDT Courier">
    </div>

    <div class="login-box">
        <h2>Administrador</h2>

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($mensaje): ?>
            <div class="alert--error"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST">
            <div class="input-group">
                <input type="text" name="usuario" placeholder="Usuario" required
                       value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>">
                <i class="fa-regular fa-user"></i>
            </div>
            <div class="input-group">
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <button type="submit" class="btn btn--primary">Entrar</button>
        </form>

        <p style="margin-top:12px;font-size:14px;color:#cbd5e1">
            
            ¿Ya estás logueado y quieres reautenticarte?
            <a href="login.php?force=1" style="color:#fff;text-decoration:underline">Forzar login</a>
        </p>
    </div>
</body>
</html>
