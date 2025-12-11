<?php
// admin/emprendedor_nuevo.php
require_once __DIR__ . '/../config.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protección de acceso: verificar que el admin esté logueado
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Inicialización de la variable mensaje
$mensaje = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre_tienda = trim($_POST['nombre_tienda'] ?? '');
    $nombre_cliente = trim($_POST['nombre_cliente'] ?? '');
    $rubro = trim($_POST['rubro'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Validar que todos los campos estén llenos
    if ($nombre_tienda && $nombre_cliente && $rubro && $direccion && $telefono && $contrasena) {
        try {
            // Generar el hash de la contraseña
            $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            // Preparar la consulta para insertar al nuevo emprendedor
            $st = $pdo->prepare("
                INSERT INTO emprendedores (nombre_tienda, nombre_cliente, rubro, direccion, telefono, contrasena, creado_en) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $st->execute([$nombre_tienda, $nombre_cliente, $rubro, $direccion, $telefono, $hash_contrasena]);

            // Mensaje de éxito
            $mensaje = "✅ Emprendimiento registrado correctamente.";
        } catch (Throwable $e) {
            // Mensaje de error
            $mensaje = "❌ Error al registrar el emprendimiento: " . $e->getMessage();
        }
    } else {
        // Mensaje si faltan campos
        $mensaje = "⚠️ Todos los campos son requeridos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Emprendimiento | SDT Courier</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-page">

    <!-- Barra superior -->
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand"><i class="fa-solid fa-store"></i> SDT Courier — Nuevo Emprendimiento</div>
            <div class="admin-user">
                <i class="fa-regular fa-user"></i>
                <span><?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?></span>
                <a class="btn btn--light" href="index.php">Volver</a>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="admin-layout" style="grid-template-columns: 1fr;">
        <main class="admin-content">
            <section class="admin-card">
                <div class="admin-card__header"><h3>Registrar nuevo emprendimiento</h3></div>

                <!-- Formulario para crear un nuevo emprendedor -->
                <form method="POST" style="padding:20px; display:flex; flex-direction:column; gap:15px;">
                    <!-- Mostrar mensaje si existe -->
                    <?php if ($mensaje): ?>
                        <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
                    <?php endif; ?>
                    
                    <!-- Campos del formulario -->
                    <input type="text" name="nombre_tienda" placeholder="Nombre del emprendimiento" required>
                    <input type="text" name="nombre_cliente" placeholder="Nombre del cliente" required>
                    <input type="text" name="rubro" placeholder="Rubro" required>
                    <input type="text" name="direccion" placeholder="Dirección" required>
                    <input type="text" name="telefono" placeholder="Número de teléfono (usuario)" required>
                    <input type="password" name="contrasena" placeholder="Contraseña de acceso" required>

                    <!-- Botón de guardar -->
                    <button type="submit" class="btn btn--primary">Guardar</button>
                </form>
            </section>
        </main>
    </div>

</body>
</html>
