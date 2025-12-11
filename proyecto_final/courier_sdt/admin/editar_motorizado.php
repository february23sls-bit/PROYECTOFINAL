<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar que el administrador esté logueado
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$mensaje = '';

// Obtener los detalles del motorizado a editar
if (isset($_GET['id'])) {
    $id_motorizado = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM motorizados WHERE id_motorizado = ?");
    $stmt->execute([$id_motorizado]);
    $motorizado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$motorizado) {
        $mensaje = 'El motorizado no existe.';
    }
} else {
    $mensaje = 'No se especificó un ID válido.';
}

// Actualizar los datos del motorizado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $placa = trim($_POST['placa']);
    $distrito = trim($_POST['distrito']);
    $activo = isset($_POST['activo']) ? 1 : 0;

    if ($nombre && $telefono && $placa && $distrito) {
        $stmt = $pdo->prepare("UPDATE motorizados SET nombre_completo = ?, telefono = ?, placa_moto = ?, distrito_asignado = ?, activo = ? WHERE id_motorizado = ?");
        $stmt->execute([$nombre, $telefono, $placa, $distrito, $activo, $id_motorizado]);

        header("Location: motorizados.php");
        exit;
    } else {
        $mensaje = 'Todos los campos son requeridos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Motorizado | SDT Courier</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .admin-page {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Barra superior */
        .admin-topbar {
            background-color: #007bff;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-topbar__inner {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .admin-brand {
            font-size: 24px;
            font-weight: bold;
        }
        .admin-user {
            font-size: 16px;
        }

        /* Contenido principal */
        .admin-layout {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .admin-content {
            background-color: white;
            padding: 20px;
            width: 70%;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Formulario de edición */
        .form-edit-moto {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .input-group label {
            font-weight: bold;
            color: #333;
        }
        .input-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .input-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }
        .btn--primary {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn--primary:hover {
            background-color: #0056b3;
        }

        .alert {
            color: red;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="admin-page">

    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand">Editar Motorizado</div>
            <div class="admin-user">
                <a class="btn btn--light" href="motorizados.php">Volver</a>
            </div>
        </div>
    </header>

    <div class="admin-layout">
        <main class="admin-content">
            <section class="admin-card">
                <div class="admin-card__header">
                    <h3>Editar Motorizado</h3>
                </div>

                <?php if ($mensaje): ?>
                    <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
                <?php endif; ?>

                <form method="POST" class="form-edit-moto">
                    <div class="input-group">
                        <label for="nombre">Nombre del Motorizado:</label>
                        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($motorizado['nombre_completo']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($motorizado['telefono']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="placa">Placa Moto:</label>
                        <input type="text" name="placa" id="placa" value="<?= htmlspecialchars($motorizado['placa_moto']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="distrito">Distrito Asignado:</label>
                        <input type="text" name="distrito" id="distrito" value="<?= htmlspecialchars($motorizado['distrito_asignado']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="activo">Activo:</label>
                        <input type="checkbox" name="activo" id="activo" <?= $motorizado['activo'] ? 'checked' : '' ?>>
                    </div>

                    <button type="submit" class="btn--primary">Actualizar Motorizado</button>
                </form>
            </section>
        </main>
    </div>

</body>
</html>
