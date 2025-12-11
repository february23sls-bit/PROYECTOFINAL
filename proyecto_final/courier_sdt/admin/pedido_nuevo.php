<?php
require_once __DIR__ . '/../config.php'; // Ruta correcta para acceder al config
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Verificar que el administrador esté logueado
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
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

// Procesar el formulario de registro de pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinatario = trim($_POST['destinatario']);
    $tipo_pedido = trim($_POST['tipo_pedido']);
    $telefono = trim($_POST['telefono']);
    $distrito = trim($_POST['distrito']);
    $direccion = trim($_POST['direccion']);
    $detalle_pedido = trim($_POST['detalle_pedido']);
    $observaciones = trim($_POST['observaciones']);

    if ($destinatario && $tipo_pedido && $telefono && $distrito && $direccion && $detalle_pedido) {
        try {
            $stmt = $pdo->prepare("INSERT INTO pedidos (destinatario, tipo_pedido, telefono, distrito, direccion, detalle_pedido, observaciones, fecha_creacion) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$destinatario, $tipo_pedido, $telefono, $distrito, $direccion, $detalle_pedido, $observaciones]);
            $mensaje = '✅ Pedido registrado correctamente.';
        } catch (Throwable $e) {
            $mensaje = '❌ Error al registrar el pedido: ' . $e->getMessage();
        }
    } else {
        $mensaje = '⚠️ Todos los campos son obligatorios.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Pedido | SDT Courier</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Asegúrate que la ruta sea correcta -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        .admin-topbar {
            background-color: #007bff;
            color: white;
            padding: 15px;
        }

        .admin-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group input, .input-group select, .input-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .input-group textarea {
            resize: vertical;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body class="admin-page">
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand"><i class="fa-solid fa-box"></i> Registrar Pedido</div>
            <div class="admin-user">
                <i class="fa-regular fa-user"></i>
                <span><?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?></span>
                <a class="btn btn--light" href="index.php">Volver</a>
            </div>
        </div>
    </header>

    <div class="admin-layout" style="grid-template-columns: 1fr;">
        <main class="admin-content">
            <section class="admin-card">
                <div class="admin-card__header"><h3>Registrar Pedido</h3></div>

                <!-- Mostrar mensaje de éxito o error -->
                <?php if ($mensaje): ?>
                    <div class="alert <?= (strpos($mensaje, '✅') !== false) ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($mensaje) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="input-group">
                        <input type="text" name="destinatario" placeholder="Destinatario" required>
                    </div>
                    <div class="input-group">
                        <select name="tipo_pedido" required>
                            <option value="CONTRAENTREGA">CONTRAENTREGA</option>
                            <option value="PAGO ONLINE">PAGO ONLINE</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="telefono" placeholder="Teléfono" required>
                    </div>
                    <div class="input-group">
                        <select name="distrito" required>
                            <option value="">Seleccionar Distrito</option>
                            <?php foreach ($distritos as $distrito): ?>
                                <option value="<?= htmlspecialchars($distrito['distrito']) ?>"><?= htmlspecialchars($distrito['distrito']) ?> - S/<?= number_format((float)$distrito['tarifa'], 2) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="direccion" placeholder="Dirección" required>
                    </div>
                    <div class="input-group">
                        <textarea name="detalle_pedido" placeholder="Detalle de pedido" required></textarea>
                    </div>
                    <div class="input-group">
                        <textarea name="observaciones" placeholder="Observaciones (Opcional)"></textarea>
                    </div>

                    <button type="submit" class="btn">Registrar Pedido</button>
                </form>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
