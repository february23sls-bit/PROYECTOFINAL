<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar que el administrador esté logueado
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener los motorizados registrados
$motorizados = obtenerMotorizados();

// Eliminar un motorizado
if (isset($_GET['eliminar_id'])) {
    $id_motorizado = (int)$_GET['eliminar_id'];
    eliminarMotorizado($id_motorizado);
    header("Location: motorizados.php");
    exit;
}

// Obtener los motorizados
function obtenerMotorizados() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM motorizados ORDER BY id_motorizado DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Eliminar un motorizado
function eliminarMotorizado($id_motorizado) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM motorizados WHERE id_motorizado = ?");
    $stmt->execute([$id_motorizado]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Motorizados | SDT Courier</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-page">

    <!-- Barra superior -->
    <header class="admin-topbar">
        <div class="admin-topbar__inner">
            <div class="admin-brand"><i class="fa-solid fa-motorcycle"></i> Gestión de Motorizados</div>
            <div class="admin-user">
                <i class="fa-regular fa-user"></i>
                <span><?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?></span>
                <a class="btn btn--light" href="index.php">Volver</a>
            </div>
        </div>
    </header>

    <div class="admin-layout" style="grid-template-columns: 1fr;">
        <main class="admin-content">
            <section class="admin-hello">
                <h1>Motorizados Registrados</h1>
                <p class="muted">Aquí puedes ver, editar, eliminar y asignar pedidos a los motorizados.</p>
                <a href="motorizado_nuevo.php" class="btn btn--primary">Nuevo Motorizado</a>
            </section>

            <section class="admin-card">
                <div class="admin-card__header">
                    <h3>Motorizados Registrados</h3>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Distrito Asignado</th>
                                <th>Placa Moto</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$motorizados): ?>
                                <tr><td colspan="6" class="muted">No hay motorizados registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($motorizados as $m): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($m['nombre_completo']) ?></td>
                                        <td><?= htmlspecialchars($m['telefono']) ?></td>
                                        <td><?= htmlspecialchars($m['distrito_asignado']) ?></td>
                                        <td><?= htmlspecialchars($m['placa_moto']) ?></td>
                                        <td><?= $m['activo'] ? 'Sí' : 'No' ?></td>
                                        <td>
                                            <a href="editar_motorizado.php?id=<?= $m['id_motorizado'] ?>" class="btn-sm">Editar</a>
                                            <a href="?eliminar_id=<?= $m['id_motorizado'] ?>" class="btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este motorizado?')">Eliminar</a>
                                            <a href="asignar_pedido.php?id=<?= $m['id_motorizado'] ?>" class="btn-sm">Asignar Pedido</a>
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
