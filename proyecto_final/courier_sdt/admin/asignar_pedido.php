<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar que el administrador esté logueado
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$mensaje = '';

// Obtener todos los pedidos disponibles
$pedidos = obtenerPedidosDisponibles();

// Asignar pedido a motorizado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['motorizado_id'])) {
    $pedido_id = (int)$_POST['pedido_id'];
    $motorizado_id = (int)$_POST['motorizado_id'];

    if (asignarMotorizadoAPedido($pedido_id, $motorizado_id)) {
        $mensaje = "✅ Pedido asignado correctamente.";
    } else {
        $mensaje = "❌ Error al asignar el pedido.";
    }
}

/**
 * Obtener todos los pedidos disponibles para asignar
 */
function obtenerPedidosDisponibles() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_pedido, codigo_seguimiento, destinatario, telefono, direccion, detalle_pedido
                           FROM pedidos
                           WHERE id_motorizado IS NULL"); // Solo los pedidos sin motorizado asignado
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Asignar un motorizado a un pedido
 */
function asignarMotorizadoAPedido($pedido_id, $motorizado_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE pedidos SET id_motorizado = ? WHERE id_pedido = ?");
    return $stmt->execute([$motorizado_id, $pedido_id]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asignar Pedido | SDT Courier</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-page">

    <!-- Barra superior -->
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand"><i class="fa-solid fa-truck"></i> Asignar Pedido</div>
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
                <div class="admin-card__header">
                    <h3>Pedidos Disponibles</h3>
                </div>

                <!-- Mostrar mensaje si existe -->
                <?php if ($mensaje): ?>
                    <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
                <?php endif; ?>

                <!-- Tabla de pedidos disponibles -->
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código de Seguimiento</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Detalle Pedido</th>
                                <th>Asignar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pedidos)): ?>
                                <tr><td colspan="6" class="muted">No hay pedidos disponibles para asignar.</td></tr>
                            <?php else: ?>
                                <?php foreach ($pedidos as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['codigo_seguimiento']) ?></td>
                                        <td><?= htmlspecialchars($p['destinatario']) ?></td>
                                        <td><?= htmlspecialchars($p['telefono']) ?></td>
                                        <td><?= htmlspecialchars($p['direccion']) ?></td>
                                        <td><?= htmlspecialchars($p['detalle_pedido']) ?></td>
                                        <td>
                                            <form method="POST" style="display:flex;gap:6px;">
                                                <input type="hidden" name="pedido_id" value="<?= (int)$p['id_pedido'] ?>">
                                                <select name="motorizado_id" required>
                                                    <!-- Opciones de motorizados -->
                                                    <?php
                                                    $stmt = $pdo->prepare("SELECT id_motorizado, nombre FROM motorizados WHERE activo = 1");
                                                    $stmt->execute();
                                                    $motorizados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($motorizados as $m) {
                                                        echo "<option value=\"{$m['id_motorizado']}\">{$m['nombre']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <button type="submit" class="btn-sm">Asignar</button>
                                            </form>
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
