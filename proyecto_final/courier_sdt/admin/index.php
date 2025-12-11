<?php
// admin/index.php
require_once __DIR__ . '/../config.php'; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Protección de acceso
if (empty($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

// Detectar base relativa (/admin)
$ADMIN_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/' );

// Funciones seguras
function safeCount(PDO $pdo, string $table): int {
  try {
    $res = $pdo->query("SELECT COUNT(*) FROM {$table}");
    return (int)$res->fetchColumn();
  } catch (Throwable $e) {
    return 0;
  }
}

function safeFetch(PDO $pdo, string $sql, array $params = []): array {
  try {
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  } catch (Throwable $e) {
    return [];
  }
}

// Consultas de métricas
$totEmprendedores = safeCount($pdo, 'emprendedores');
$totMotorizados   = safeCount($pdo, 'motorizados');
$totPedidos       = safeCount($pdo, 'pedidos');

// Consultar pedidos pendientes
$pedPendientes = safeFetch($pdo, "SELECT COUNT(*) AS c FROM pedidos WHERE estado = 'pendiente'");
$totPendientes = isset($pedPendientes[0]['c']) ? (int)$pedPendientes[0]['c'] : 0;

// Últimos registros
$ultEmprendedores = safeFetch($pdo, "
  SELECT id_emprendimiento AS id, nombre, telefono
  FROM emprendedores
  ORDER BY id_emprendimiento DESC
  LIMIT 8
");

$ultMotorizados = safeFetch($pdo, "
  SELECT id_motorizado AS id, nombre, telefono, placa_moto, activo
  FROM motorizados
  ORDER BY id_motorizado DESC
  LIMIT 8
");

$ultPedidos = safeFetch($pdo, "
  SELECT id_pedido AS id, codigo_seguimiento, estado, creado_en,
         id_emprendedor, id_motorizado, destino
  FROM pedidos
  ORDER BY id_pedido DESC
  LIMIT 10
");
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel Administrador | SDT Courier</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script defer src="../js/app.js"></script>
</head>
<body class="admin-page">

  <!-- Topbar -->
  <header class="admin-topbar">
    <div class="admin-topbar__inner">
      <div class="admin-brand">
        <i class="fa-solid fa-truck-fast"></i>
        <strong>SDT Courier</strong> – Panel Admin
      </div>
      <div class="admin-user">
        <i class="fa-regular fa-user"></i>
        <span><?= htmlspecialchars($_SESSION['admin_nombre'] ?? $_SESSION['admin_usuario'] ?? 'Administrador') ?></span>
        <a class="btn btn--light" href="<?= $ADMIN_BASE ?>/salir.php" title="Cerrar sesión">
          <i class="fa-solid fa-right-from-bracket"></i> Salir
        </a>
      </div>
    </div>
  </header>

  <!-- Layout general -->
  <div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <nav class="admin-nav">
        <a href="<?= $ADMIN_BASE ?>/index.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <div class="admin-nav__group">Gestión</div>
        <a href="<?= $ADMIN_BASE ?>/emprendedores.php"><i class="fa-solid fa-store"></i> Emprendimientos</a>
        <a href="<?= $ADMIN_BASE ?>/motorizados.php"><i class="fa-solid fa-motorcycle"></i> Motorizados</a>
        <a href="<?= $ADMIN_BASE ?>/pedidos.php"><i class="fa-solid fa-boxes-packing"></i> Pedidos</a>
        <a href="<?= $ADMIN_BASE ?>/rutas.php"><i class="fa-solid fa-map-location-dot"></i> Rutas y Cobertura</a>
        <div class="admin-nav__group">Acciones rápidas</div>
        <a href="<?= $ADMIN_BASE ?>/emprendedor_nuevo.php"><i class="fa-solid fa-user-plus"></i> Nuevo Emprendimiento</a>
        <a href="<?= $ADMIN_BASE ?>/motorizado_nuevo.php"><i class="fa-solid fa-id-card-clip"></i> Nuevo Motorizado</a>
        <a href="<?= $ADMIN_BASE ?>/pedido_nuevo.php"><i class="fa-solid fa-file-circle-plus"></i> Nuevo Pedido</a>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="admin-content">
      <section class="admin-hello">
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?></h1>
        <p class="muted">Desde aquí puedes controlar emprendimientos, motorizados, pedidos y rutas.</p>
      </section>

      <!-- Métricas -->
      <section class="admin-stats">
        <div class="stat-card">
          <div class="stat-icon bg-blue"><i class="fa-solid fa-store"></i></div>
          <div class="stat-info">
            <div class="stat-label">Emprendimientos</div>
            <div class="stat-value"><?= $totEmprendedores ?></div>
          </div>
          <a class="stat-link" href="<?= $ADMIN_BASE ?>/emprendedores.php">Ver todo</a>
        </div>

        <div class="stat-card">
          <div class="stat-icon bg-green"><i class="fa-solid fa-motorcycle"></i></div>
          <div class="stat-info">
            <div class="stat-label">Motorizados</div>
            <div class="stat-value"><?= $totMotorizados ?></div>
          </div>
          <a class="stat-link" href="<?= $ADMIN_BASE ?>/motorizados.php">Ver todo</a>
        </div>

        <div class="stat-card">
          <div class="stat-icon bg-orange"><i class="fa-solid fa-box"></i></div>
          <div class="stat-info">
            <div class="stat-label">Pedidos totales</div>
            <div class="stat-value"><?= $totPedidos ?></div>
          </div>
          <a class="stat-link" href="<?= $ADMIN_BASE ?>/pedidos.php">Ver todo</a>
        </div>

        <div class="stat-card">
          <div class="stat-icon bg-red"><i class="fa-solid fa-clock"></i></div>
          <div class="stat-info">
            <div class="stat-label">Pendientes</div>
            <div class="stat-value"><?= $totPendientes ?></div>
          </div>
          <a class="stat-link" href="<?= $ADMIN_BASE ?>/pedidos.php?estado=pendiente">Revisar</a>
        </div>
      </section>

      <!-- Acciones rápidas -->
      <section class="quick-actions">
        <a class="qa-btn" href="<?= $ADMIN_BASE ?>/pedido_nuevo.php"><i class="fa-solid fa-file-circle-plus"></i> Crear pedido</a>
        <a class="qa-btn" href="<?= $ADMIN_BASE ?>/asignaciones.php"><i class="fa-solid fa-route"></i> Asignar pedidos</a>
        <a class="qa-btn" href="<?= $ADMIN_BASE ?>/rutas.php"><i class="fa-solid fa-map"></i> Editar cobertura</a>
      </section>

      <!-- Listas recientes -->
      <section class="admin-grids">
        <!-- Emprendedores -->
        <div class="admin-card">
          <div class="admin-card__header">
            <h3>Últimos Emprendimientos</h3>
            <a href="<?= $ADMIN_BASE ?>/emprendedores.php" class="link">ver todo</a>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead><tr><th>ID</th><th>Nombre</th><th>Teléfono</th><th>Acciones</th></tr></thead>
              <tbody>
                <?php if (empty($ultEmprendedores)): ?>
                  <tr><td colspan="4" class="muted">No hay registros aún.</td></tr>
                <?php else: foreach ($ultEmprendedores as $e): ?>
                  <tr>
                    <td><?= intval($e['id']) ?></td>
                    <td><?= htmlspecialchars($e['nombre'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($e['telefono'] ?? '-') ?></td>
                    <td class="t-actions">
                      <a class="btn-sm" href="<?= $ADMIN_BASE ?>/emprendedor_editar.php?id=<?= intval($e['id']) ?>">Editar</a>
                      <a class="btn-sm btn--light" href="<?= $ADMIN_BASE ?>/pedido_nuevo.php?emprendedor_id=<?= intval($e['id']) ?>">Nuevo pedido</a>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Motorizados -->
        <div class="admin-card">
          <div class="admin-card__header">
            <h3>Últimos Motorizados</h3>
            <a href="<?= $ADMIN_BASE ?>/motorizados.php" class="link">ver todo</a>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead><tr><th>ID</th><th>Nombre</th><th>Teléfono</th><th>Placa</th><th>Activo</th><th>Acciones</th></tr></thead>
              <tbody>
                <?php if (empty($ultMotorizados)): ?>
                  <tr><td colspan="6" class="muted">No hay registros aún.</td></tr>
                <?php else: foreach ($ultMotorizados as $m): ?>
                  <tr>
                    <td><?= intval($m['id']) ?></td>
                    <td><?= htmlspecialchars($m['nombre_completo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($m['telefono'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($m['placa_moto'] ?? '-') ?></td>
                    <td><?= !empty($m['activo']) ? 'Sí' : 'No' ?></td>
                    <td class="t-actions">
                      <a class="btn-sm" href="<?= $ADMIN_BASE ?>/motorizado_editar.php?id=<?= intval($m['id']) ?>">Editar</a>
                      <a class="btn-sm btn--light" href="<?= $ADMIN_BASE ?>/asignaciones.php?motorizado_id=<?= intval($m['id']) ?>">Asignar pedidos</a>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Pedidos recientes -->
      <section class="admin-card">
        <div class="admin-card__header">
          <h3>Pedidos recientes</h3>
          <a href="<?= $ADMIN_BASE ?>/pedidos.php" class="link">ver todo</a>
        </div>
        <div class="table-wrap">
          <table class="table">
            <thead><tr><th>ID</th><th>Código</th><th>Estado</th><th>Emprendedor</th><th>Motorizado</th><th>Destino</th><th>Creado</th></tr></thead>
            <tbody>
              <?php if (empty($ultPedidos)): ?>
                <tr><td colspan="7" class="muted">No hay pedidos o la tabla no existe aún.</td></tr>
              <?php else: foreach ($ultPedidos as $p): ?>
                <tr>
                  <td><?= intval($p['id']) ?></td>
                  <td><?= htmlspecialchars($p['codigo_seguimiento'] ?? '-') ?></td>
                  <td><span class="badge"><?= htmlspecialchars($p['estado'] ?? '-') ?></span></td>
                  <td><?= intval($p['id_emprendedor'] ?? 0) ?></td>
                  <td><?= intval($p['id_motorizado'] ?? 0) ?></td>
                  <td><?= htmlspecialchars($p['destino'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($p['creado_en'] ?? '-') ?></td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <footer class="admin-footer">
        SDT Courier • Panel Administrador © <?= date('Y') ?>
      </footer>
    </main>
  </div>
</body>
</html>
