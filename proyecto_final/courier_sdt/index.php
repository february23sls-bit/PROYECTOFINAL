<?php
// Página principal — buscador de seguimiento
include 'includes/header.php';
include 'config.php';

$busqueda = trim($_GET['seguimiento'] ?? '');
$resultado = null;
$mensaje   = null;

if ($busqueda !== '') {
  if (!ctype_digit($busqueda)) {
    $mensaje = "El número de seguimiento debe ser numérico (ID del pedido).";
  } else {
    $st = $pdo->prepare("SELECT p.*, e.nombre AS nombre_emprendedor,
                                (SELECT nombre FROM motorizados m
                                  JOIN asignaciones a ON a.id_motorizado=m.id_motorizado
                                  WHERE a.id_pedido=p.id_pedido
                                  ORDER BY a.id_asignacion DESC LIMIT 1) AS motorizado_asignado
                         FROM pedidos p
                         JOIN emprendedores e ON e.id_emprendedor = p.id_emprendedor
                         WHERE p.id_pedido = ?");
    $st->execute([$busqueda]);
    $resultado = $st->fetch(PDO::FETCH_ASSOC);
    if (!$resultado) $mensaje = "No encontramos un pedido con ese número. Verifica e intenta nuevamente.";
  }
}
?>

<!-- Hero con buscador -->
<!-- Hero con imagen de fondo -->
<section class="hero-banner" style="
  background: url('images/sdt1.png') center center / cover no-repeat;
  height: 100vh; /* ocupa toda la altura de la pantalla */
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  text-align: center;
  color: #fff;
  position: relative;
">
  <!-- Sombra oscura encima de la imagen -->
  <div style="
    position:absolute;
    inset:0;
    background: rgba(0,0,0,0.55);
    z-index:1;
  "></div>

  <!-- Contenido encima de la imagen -->
  <div style="position:relative; z-index:2; max-width: 800px; padding:20px;">
    <h1 style="font-size:3rem; font-weight:700; margin-bottom:1rem;">
      Envía con nuestro servicio Courier
    </h1>
    <p style="font-size:1.3rem; margin-bottom:2rem;">
      Consulta el estado de tu paquete ingresando tu <b>número de seguimiento</b>.
    </p>

    <form class="search" method="get" action="/index.php" role="search" id="form-seguimiento" style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
      <input class="search__input" type="text" name="seguimiento" placeholder="Número de seguimiento de tu pedido"
             value="<?php echo htmlspecialchars($busqueda); ?>"
             style="padding:12px; width:300px; border-radius:6px; border:none; outline:none;">
      <button class="btn btn--primary search__btn" style="padding:12px 20px; border:none; border-radius:6px; background:#ff0000; color:#fff; font-weight:600;">
        Buscar
      </button>
    </form>
  </div>
</section>



<!-- Resultado del seguimiento -->
<?php if ($busqueda !== ''): ?>
  <section class="container resultados">
    <div class="card">
      <h2 class="section-title">Resultado de seguimiento</h2>
      <?php if ($mensaje): ?>
        <p class="alert alert--error"><?php echo htmlspecialchars($mensaje); ?></p>
      <?php else: ?>
        <div class="grid grid--2">
          <div>
            <div class="pill">Pedido #<?php echo (int)$resultado['id_pedido']; ?></div>
            <p><b>Descripción:</b> <?php echo htmlspecialchars($resultado['descripcion']); ?></p>
            <p><b>Estado:</b> <span class="badge"><?php echo htmlspecialchars($resultado['estado']); ?></span></p>
            <p><b>Emprendedor:</b> <?php echo htmlspecialchars($resultado['nombre_emprendedor']); ?></p>
          </div>
          <div>
            <p><b>Destino:</b> <?php echo htmlspecialchars($resultado['destino_distrito']); ?> — <?php echo htmlspecialchars($resultado['destino_direccion']); ?></p>
            <p><b>Motorizado asignado:</b> <?php echo htmlspecialchars($resultado['motorizado_asignado'] ?: 'Pendiente'); ?></p>
            <p><b>Fecha de creación:</b> <?php echo htmlspecialchars($resultado['fecha_creacion']); ?></p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>

<!-- Tarjetas informativas -->
<section class="container features">
  <div class="feature-card">
    <h3 class="feature-card__title">Tu aliado logístico</h3>
    <p>Envíos rápidos, confiables y económicos dentro de Lima Metropolitana.</p>
  </div>
  <div class="feature-card">
    <h3 class="feature-card__title">Formalidad y respaldo</h3>
    <p>Contamos con permisos y protocolos para garantizar la seguridad de tus envíos.</p>
  </div>
  <div class="feature-card">
    <h3 class="feature-card__title">Cobertura expandida</h3>
    <p>Revisa nuestros distritos disponibles o solicita una ruta a medida para tu negocio.</p>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
