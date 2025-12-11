<?php
require_once __DIR__ . '/../config.php'; // Ruta hacia la raíz
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar que el administrador esté logueado
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$mensaje = '';

// Eliminar un emprendedor
if (isset($_GET['eliminar_id'])) {
    $id_emprendedor = (int)$_GET['eliminar_id'];
    
    // Verificar que el emprendedor existe y no es el administrador
    $stmt = $pdo->prepare("SELECT id_emprendedor FROM emprendedores WHERE id_emprendedor = ?");
    $stmt->execute([$id_emprendedor]);
    $emprendedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($emprendedor) {
        // Eliminar
        $del = $pdo->prepare("DELETE FROM emprendedores WHERE id_emprendedor = ?");
        $del->execute([$id_emprendedor]);
        $mensaje = "✅ Emprendedor eliminado correctamente.";
    } else {
        $mensaje = "❌ El emprendedor no existe.";
    }
}

// Obtener todos los emprendedores
$stmt = $pdo->prepare("SELECT id_emprendedor, nombre_cliente, telefono, direccion, rubro FROM emprendedores");
$stmt->execute();
$emprendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionar Emprendedores | SDT Courier</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-page">
    <!-- Barra superior -->
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand"><i class="fa-solid fa-store"></i> Gestión de Emprendedores</div>
            <div class="admin-user">
                <i class="fa-regular fa-user"></i>
                <span><?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?></span>
                <a class="btn btn--light" href="index.php">Volver</a>
            </div>
        </div>
    </header>

    <div class="admin-layout" style="grid-template-columns: 1fr;">
        <main class="admin-content">
            <!-- Mensaje de éxito o error -->
            <?php if ($mensaje): ?>
                <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <section class="admin-card">
                <div class="admin-card__header">
                    <h3>Emprendedores Registrados</h3>
                    <a href="emprendedor_nuevo.php" class="btn btn--primary">Nuevo Emprendedor</a>
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre Cliente</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Rubro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($emprendedores)): ?>
                                <tr><td colspan="5" class="muted">No hay emprendedores registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($emprendedores as $emprendedor): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($emprendedor['nombre_cliente']) ?></td>
                                        <td><?= htmlspecialchars($emprendedor['telefono']) ?></td>
                                        <td><?= htmlspecialchars($emprendedor['direccion']) ?></td>
                                        <td><?= htmlspecialchars($emprendedor['rubro']) ?></td>
                                        <td>
                                            <a href="editar_emprendedor.php?id=<?= $emprendedor['id_emprendedor'] ?>" class="btn-sm btn--secondary">Editar</a>
                                            <a href="?eliminar_id=<?= $emprendedor['id_emprendedor'] ?>" class="btn-sm btn--danger" onclick="return confirm('¿Estás seguro de eliminar este emprendedor?')">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
