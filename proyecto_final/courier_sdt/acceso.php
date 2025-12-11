<?php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$mensaje = '';
$tab_activa = $_GET['tab'] ?? 'emprendedor';

/**
 * Verifica la contraseña del usuario.
 * Acepta bcrypt y texto plano.
 */
function verificarPasswordFila(array $fila, string $clave): bool {
  $hash = $fila['contrasena'] ?? '';

  if (!$hash) return false;

  // Verificar si la contraseña es bcrypt
  if (substr($hash, 0, 4) === '$2y$') {
    return password_verify($clave, $hash);
  }

  // Texto plano simple (contraseña almacenada tal cual)
  return trim($hash) === trim($clave);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tipo       = $_POST['tipo_usuario'] ?? '';
  $telefono   = trim($_POST['telefono'] ?? '');
  $clave      = trim($_POST['password'] ?? '');
  $tab_activa = $tipo === 'motorizado' ? 'motorizado' : 'emprendedor';

  if ($telefono === '' || $clave === '') {
    $mensaje = 'Completa todos los campos.';
  } else {
    if ($tipo === 'emprendedor') {
      // LOGIN EMPRENDEDOR (TIENDA)
      $st = $pdo->prepare("SELECT id_emprendedor, nombre_cliente, telefono, contrasena
                           FROM emprendedores
                           WHERE telefono = ?
                           LIMIT 1");
      $st->execute([$telefono]);
      $fila = $st->fetch(PDO::FETCH_ASSOC);

      if ($fila && verificarPasswordFila($fila, $clave)) {
        $_SESSION['emprendedor_id'] = (int)$fila['id_emprendedor'];
        $_SESSION['emprendedor_nombre'] = $fila['nombre_cliente'] ?? 'Emprendedor';
        header('Location: zona_tienda.php');
        exit;
      } else {
        $mensaje = 'Datos incorrectos para Emprendimiento.';
      }

    } elseif ($tipo === 'motorizado') {
      // LOGIN MOTORIZADO
   $st = $pdo->prepare("SELECT id_motorizado, nombre_completo, telefono, contrasena
                     FROM motorizados
                     WHERE telefono = ?
                     LIMIT 1");
      $st->execute([$telefono]);
      $fila = $st->fetch(PDO::FETCH_ASSOC);

      if ($fila && verificarPasswordFila($fila, $clave)) {
        $_SESSION['motorizado_id'] = (int)$fila['id_motorizado'];
        $_SESSION['motorizado_nombre'] = $fila['nombre'] ?? 'Motorizado';
        header('Location: zona_motorizado.php');
        exit;
      } else {
        $mensaje = 'Datos incorrectos para Motorizado.';
      }
    } else {
      $mensaje = 'Selecciona un tipo de usuario válido.';
    }
  }
}
?>


<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acceso | Emprendimiento / Motorizado</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="login-page">
  <div class="login-logo">
    <img src="images/logo.png" alt="Logo">
  </div>

  <div class="login-box">
    <div class="login-tabs">
      <button class="tab-btn <?= $tab_activa === 'emprendedor' ? 'active' : '' ?>" data-tab="emprendedor">Emprendimiento</button>
      <button class="tab-btn <?= $tab_activa === 'motorizado' ? 'active' : '' ?>" data-tab="motorizado">Motorizado</button>
    </div>

    <?php if ($mensaje): ?>
      <div class="alert--error"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="post" id="form-login">
      <input type="hidden" name="tipo_usuario" id="tipo_usuario" value="<?= $tab_activa ?>">

      <div class="input-group">
        <input type="text" name="telefono" placeholder="Teléfono" required
               value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
        <i class="fa-solid fa-phone"></i>
      </div>

      <div class="input-group">
        <input type="password" name="password" placeholder="Contraseña" required>
        <i class="fa-solid fa-lock"></i>
      </div>

      <button type="submit" class="btn btn--primary">Entrar</button>
      <button 
        type="button" 
        class="btn btn--secondary"
        onclick="window.open('https://wa.me/51944718388?text=Hola,%20quisiera%20solicitar%20mi%20usuario%20para%20el%20servicio%20Courier%20Delivery.', '_blank')">
        Solicita tu usuario ¡Aquí!
      </button>
    </form>
  </div>

  <script>
    // Cambio visual de pestañas
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const tipo = btn.dataset.tab;
        document.getElementById('tipo_usuario').value = tipo;
        const url = new URL(window.location);
        url.searchParams.set('tab', tipo);
        window.history.replaceState({}, '', url);
      });
    });
  </script>
</body>
</html>
