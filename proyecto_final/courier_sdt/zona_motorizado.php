<?php
// zona_motorizado.php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protección de acceso: solo motorizado logueado
if (empty($_SESSION['motorizado_id'])) {
    header("Location: acceso.php?tab=motorizado");
    exit;
}

$idMotorizado = (int) $_SESSION['motorizado_id'];
$mensaje = '';

/**
 * Obtener todos los pedidos asignados a un motorizado
 */
function obtenerPedidosAsignados(int $idMotorizado): array {
    global $pdo;

    $sql = "
        SELECT
            id_pedido,
            -- Si no tiene código, generamos uno simple P-00001
            IFNULL(codigo_seguimiento, CONCAT('P-', LPAD(id_pedido,5,'0'))) AS codigo_seguimiento,
            destinatario,
            distrito,
            direccion,
            estado,
            fecha_creacion
        FROM pedidos
        WHERE id_motorizado = ?
        ORDER BY fecha_creacion DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idMotorizado]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Actualizar el estado de un pedido
 */
function actualizarEstadoPedido(int $pedido_id, string $nuevo_estado, int $idMotorizado): bool {
    global $pdo;

    $permitidos = ['asignado', 'en ruta', 'entregado', 'anulado', 'reprogramado'];

    if (!in_array($nuevo_estado, $permitidos, true)) {
        return false;
    }

    $sql = "UPDATE pedidos
            SET estado = ?
            WHERE id_pedido = ? AND id_motorizado = ?";

    $up = $pdo->prepare($sql);
    return $up->execute([$nuevo_estado, $pedido_id, $idMotorizado]);
}

// Si viene un POST para cambiar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['nuevo_estado'])) {
    $pedido_id    = (int) ($_POST['pedido_id'] ?? 0);
    $nuevo_estado = trim($_POST['nuevo_estado'] ?? '');

    if ($pedido_id > 0 && $nuevo_estado !== '') {
        if (actualizarEstadoPedido($pedido_id, $nuevo_estado, $idMotorizado)) {
            // Recargar para evitar reenvío del formulario
            header("Location: zona_motorizado.php");
            exit;
        } else {
            $mensaje = "❌ Error al actualizar el estado del pedido.";
        }
    } else {
        $mensaje = "⚠️ Datos incompletos para actualizar el estado.";
    }
}

// Obtener pedidos después de posibles cambios
$pedidos = obtenerPedidosAsignados($idMotorizado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Zona Motorizado | SDT Courier</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-page">

  <!-- Barra superior -->
  <header class="admin-topbar">
    <div class="admin-topbar__inner">
      <div class="admin-brand">
        <i class="fa-solid fa-motorcycle"></i> Zona Motorizado
      </div>
      <div class="admin-user">
        <i class="fa-regular fa-user"></i>
        <span><?= htmlspecialchars($_SESSION['motorizado_nombre'] ?? 'Motorizado') ?></span>
        <a class="btn btn--light" href="salir.php">Salir</a>
      </div>
    </div>
  </header>

  <div class="admin-layout" style="grid-template-columns: 1fr;">
    <main class="admin-content">
      <section class="admin-hello">
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['motorizado_nombre'] ?? '') ?></h1>
        <p class="muted">Aquí puedes ver tus pedidos asignados y actualizar su estado.</p>
      </section>

      <section class="admin-card">
        <div class="admin-card__header">
          <h3>Pedidos asignados</h3>
        </div>

        <?php if ($mensaje): ?>
          <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="table-wrap">
          <table class="table">
            <thead>
              <tr>
                <th>Código</th>
                <th>Destinatario</th>
                <th>Distrito</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Actualizar</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($pedidos)): ?>
                <tr><td colspan="6" class="muted">No tienes pedidos asignados.</td></tr>
              <?php else: ?>
                <?php foreach ($pedidos as $p): ?>
                  <tr>
                    <td><?= htmlspecialchars($p['codigo_seguimiento']) ?></td>
                    <td><?= htmlspecialchars($p['destinatario']) ?></td>
                    <td><?= htmlspecialchars($p['distrito']) ?></td>
                    <td><?= htmlspecialchars($p['direccion']) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($p['estado']) ?></span></td>
                    <td>
                      <form method="post" style="display:flex;gap:6px;">
                        <input type="hidden" name="pedido_id" value="<?= (int)$p['id_pedido'] ?>">
                        <select name="nuevo_estado" required>
                          <?php
                          $estados = ['asignado', 'en ruta', 'entregado', 'anulado', 'reprogramado'];
                          foreach ($estados as $e) {
                              $sel = ($p['estado'] === $e) ? 'selected' : '';
                              echo "<option value=\"$e\" $sel>$e</option>";
                          }
                          ?>
                        </select>
                        <button type="submit" class="btn-sm">Guardar</button>
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
