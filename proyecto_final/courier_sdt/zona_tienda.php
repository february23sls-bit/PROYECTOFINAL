<?php
// zona_tienda.php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Protección de acceso: verificar que el cliente esté logueado
if (empty($_SESSION['emprendedor_id'])) {
    header("Location: acceso.php?tab=emprendedor");
    exit;
}

$mensaje = '';

// Obtener los distritos y tarifas desde la base de datos
$distritos = [];
try {
    $stmt = $pdo->query("SELECT distrito, tarifa FROM coberturas");
    $distritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $mensaje = '❌ Error al obtener distritos: ' . $e->getMessage();
}

// Consultar los pedidos del cliente logueado
$pedidos = [];
try {
    $stmt = $pdo->prepare("SELECT id_pedido, destinatario, estado, creado_en FROM pedidos WHERE id_emprendedor = ?");
    $stmt->execute([$_SESSION['emprendedor_id']]);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $mensaje = '❌ Error al obtener los pedidos: ' . $e->getMessage();
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $destinatario = trim($_POST['destinatario'] ?? '');
    $tipo_pedido = trim($_POST['tipo_pedido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $distrito = trim($_POST['distrito'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $detalle_pedido = trim($_POST['detalle_pedido'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');

    // Validar que todos los campos estén llenos
    if ($destinatario && $tipo_pedido && $telefono && $distrito && $direccion && $detalle_pedido) {
        try {
            // Preparar la consulta para insertar el nuevo pedido
            $st = $pdo->prepare("
                INSERT INTO pedidos (id_emprendedor, destinatario, tipo_pedido, telefono, distrito, direccion, detalle_pedido, observaciones)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            // Ejecutar la consulta
            $st->execute([
                $_SESSION['emprendedor_id'],
                $destinatario,
                $tipo_pedido,
                $telefono,
                $distrito,
                $direccion,
                $detalle_pedido,
                $observaciones
            ]);

            // Mensaje de éxito
            $mensaje = "✅ Pedido registrado correctamente.";
        } catch (Throwable $e) {
            // Mensaje de error
            $mensaje = "❌ Error al registrar el pedido: " . $e->getMessage();
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
    <title>Registrar Pedido | SDT Courier</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-page">

    <!-- Barra superior -->
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand"><i class="fa-solid fa-truck"></i> SDT Courier — Zona Tienda</div>
            <div class="admin-user">
                <i class="fa-regular fa-user"></i>
                <span><?= htmlspecialchars($_SESSION['emprendedor_nombre'] ?? 'Emprendedor') ?></span>
                <a class="btn btn--light" href="index.php">Volver</a>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="admin-layout" style="grid-template-columns: 1fr;">
        <main class="admin-content">
            <section class="admin-card">
                <div class="admin-card__header"><h3>Registrar Pedido</h3></div>

                <!-- Mostrar mensaje si existe -->
                <?php if ($mensaje): ?>
                    <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
                <?php endif; ?>

                <!-- Formulario para registrar un nuevo pedido -->
                <form method="POST" style="padding:20px; display:flex; flex-direction:column; gap:15px;">
                    <!-- Campos del formulario -->
                    <input type="text" name="destinatario" placeholder="Destinatario" required>
                    <select name="tipo_pedido" required>
                        <option value="CONTRAENTREGA">CONTRAENTREGA</option>
                        <option value="PAGO ANTICIPADO">PAGO ANTICIPADO</option>
                    </select>
                    <input type="text" name="telefono" placeholder="Teléfono" required>
                    
                    <!-- Campo Distrito con tarifa -->
                    <select name="distrito" required>
                        <option value="">Seleccionar Distrito</option>
                        <?php foreach ($distritos as $distrito): ?>
                            <option value="<?= htmlspecialchars($distrito['distrito']) ?>">
                                <?= htmlspecialchars($distrito['distrito']) ?> - S/<?= number_format((float)$distrito['tarifa'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" name="direccion" placeholder="Dirección" required>
                    <textarea name="detalle_pedido" placeholder="Detalle de pedido" required></textarea>
                    <textarea name="observaciones" placeholder="Observaciones (Opcional)"></textarea>

                    <!-- Botón para registrar el pedido -->
                    <button type="submit" class="btn btn--primary">Registrar Pedido</button>
                </form>
            </section>

            <!-- Mostrar Mis Pedidos -->
            <section class="admin-card">
                <div class="admin-card__header"><h3>Mis Pedidos</h3></div>

                <?php if (empty($pedidos)): ?>
                    <div class="alert alert-danger">No tienes pedidos registrados.</div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Destinatario</th>
                                <th>Estado</th>
                                <th>Fecha de Creación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pedido['id_pedido']) ?></td>
                                    <td><?= htmlspecialchars($pedido['destinatario']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= ($pedido['estado'] == 'Entregado') ? 'success' : 'warning' ?>">
                                            <?= htmlspecialchars($pedido['estado']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($pedido['creado_en']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </main>
    </div>

</body>
</html>
