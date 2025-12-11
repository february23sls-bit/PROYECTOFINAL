<?php
// motorizado_nuevo.php
require_once __DIR__ . '/../config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) session_start();

// Protección de acceso: verificar que el admin esté logueado
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Variable para los mensajes
$mensaje = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $placa_moto = trim($_POST['placa_moto'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $distrito_asignado = trim($_POST['distrito_asignado'] ?? '');
    $activo = $_POST['activo'] ?? '0';

    // Validar que todos los campos estén llenos
    if ($nombre_completo && $telefono && $placa_moto && $contrasena && $distrito_asignado) {
        try {
            // Hash de la contraseña
            $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            // Preparar la consulta para insertar el motorizado
            $st = $pdo->prepare("INSERT INTO motorizados (nombre_completo, telefono, placa_moto, contrasena, distrito_asignado, activo, creado_en) 
                                 VALUES (?, ?, ?, ?, ?, ?, NOW())");

            // Ejecutar la consulta
            $st->execute([$nombre_completo, $telefono, $placa_moto, $hash_contrasena, $distrito_asignado, $activo]);

            // Mensaje de éxito
            $mensaje = "✅ Motorizado registrado correctamente.";
        } catch (Throwable $e) {
            // Mensaje de error
            $mensaje = "❌ Error al registrar el motorizado: " . $e->getMessage();
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
    <title>Nuevo Motorizado | SDT Courier</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-page">

    <!-- Barra superior -->
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand"><i class="fa-solid fa-motorcycle"></i> SDT Courier — Nuevo Motorizado</div>
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
                <div class="admin-card__header"><h3>Registrar nuevo motorizado</h3></div>

                <!-- Mostrar mensaje si existe -->
                <?php if ($mensaje): ?>
                    <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
                <?php endif; ?>

                <!-- Formulario para registrar un nuevo motorizado -->
                <form method="POST" style="padding:20px; display:flex; flex-direction:column; gap:15px;">
                    <!-- Campos del formulario -->
                    <input type="text" name="nombre_completo" placeholder="Nombre completo" required>
                    <input type="text" name="telefono" placeholder="Teléfono (usuario)" required>
                    <input type="text" name="placa_moto" placeholder="Placa de moto" required>
                    <input type="password" name="contrasena" placeholder="Contraseña" required>
                    <input type="text" name="distrito_asignado" placeholder="Distrito asignado" required>
                    <select name="activo" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>

                    <!-- Botón para registrar el motorizado -->
                    <button type="submit" class="btn btn--primary">Guardar</button>
                </form>
            </section>
        </main>
    </div>

</body>
</html>
