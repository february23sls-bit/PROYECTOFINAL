<?php
require_once __DIR__ . '/../config.php'; // Ruta hacia el archivo config.php

// Obtener todos los pedidos
function obtenerTodosLosPedidos() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pedidos");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener motorizados disponibles (activos)
function obtenerMotorizadosDisponibles() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_motorizado, nombre_completo FROM motorizados WHERE activo = 1");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Asignar motorizado a un pedido
if (isset($_POST['asignar'])) {
    $pedido_id = $_POST['asignar']; // ID del pedido a asignar
    $motorizado_id = $_POST['motorizado_id_' . $pedido_id]; // ID del motorizado seleccionado

    if ($motorizado_id) {
        // Actualizar el pedido con el motorizado asignado
        $stmt = $pdo->prepare("UPDATE pedidos SET id_motorizado = ?, estado = 'asignado' WHERE id_pedido = ?");
        $stmt->execute([$motorizado_id, $pedido_id]);

        // Redirigir para mostrar el mensaje
        header("Location: pedidos.php?mensaje=Motorizado asignado correctamente");
        exit;
    }
}

// Eliminar un pedido
if (isset($_GET['eliminar'])) {
    $pedido_id = $_GET['eliminar']; // ID del pedido a eliminar
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
    $stmt->execute([$pedido_id]);

    // Redirigir para mostrar el mensaje
    header("Location: pedidos.php?mensaje=Pedido eliminado correctamente");
    exit;
}

// Obtener todos los pedidos y motorizados disponibles
$pedidos = obtenerTodosLosPedidos();
$motorizados = obtenerMotorizadosDisponibles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionar Pedidos | SDT Courier</title>
  <style>
    /* Estilos generales */
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
    }

    header h1 {
      margin: 0;
      font-size: 24px;
    }

    .container {
      width: 80%;
      margin: 20px auto;
    }

    /* Mensajes */
    .alert {
      padding: 10px;
      background-color: #f8d7da;
      color: #721c24;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }

    .alert--success {
      background-color: #d4edda;
      color: #155724;
    }

    .alert--error {
      background-color: #f8d7da;
      color: #721c24;
    }

    /* Tabla de pedidos */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #007bff;
      color: white;
    }

    td {
      background-color: #fff;
    }

    td select, td button {
      padding: 5px;
      font-size: 14px;
    }

    .btn-primary {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 8px 16px;
      cursor: pointer;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    .btn-sm {
      padding: 6px 12px;
      font-size: 12px;
      cursor: pointer;
      background-color: #007bff;
      color: white;
      border: none;
    }

    .btn-sm:hover {
      background-color: #0056b3;
    }

    .acciones {
      display: flex;
      gap: 10px;
    }

    select {
      width: 200px;
    }

    .actions button {
      padding: 6px 16px;
    }
  </style>
</head>
<body>

  <!-- Mostrar mensaje de éxito si está presente -->
  <?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert--success"><?= htmlspecialchars($_GET['mensaje']) ?></div>
  <?php endif; ?>

  <!-- Barra superior -->
  <header>
    <h1>Pedidos Registrados</h1>
  </header>

  <!-- Contenido principal -->
  <div class="container">
    <!-- Mostrar tabla con pedidos -->
    <table>
      <thead>
        <tr>
          <th>ID Pedido</th>
          <th>Destinatario</th>
          <th>Teléfono</th>
          <th>Distrito</th>
          <th>Dirección</th>
          <th>Detalle de Pedido</th>
          <th>Estado</th>
          <th>Motorizado Asignado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pedidos as $pedido): ?>
          <tr>
            <td><?= htmlspecialchars($pedido['id_pedido']) ?></td>
            <td><?= htmlspecialchars($pedido['destinatario']) ?></td>
            <td><?= htmlspecialchars($pedido['telefono']) ?></td>
            <td><?= htmlspecialchars($pedido['distrito']) ?></td>
            <td><?= htmlspecialchars($pedido['direccion']) ?></td>
            <td><?= htmlspecialchars($pedido['detalle_pedido']) ?></td>
            <td><?= htmlspecialchars($pedido['estado']) ?></td>
            <td>
              <?php
              // Verificar si ya está asignado un motorizado
              if ($pedido['id_motorizado']) {
                // Obtener el nombre del motorizado asignado
                $stmt = $pdo->prepare("SELECT nombre_completo FROM motorizados WHERE id_motorizado = ?");
                $stmt->execute([$pedido['id_motorizado']]);
                $motorizado = $stmt->fetch(PDO::FETCH_ASSOC);
                echo htmlspecialchars($motorizado['nombre_completo']);
              } else {
                echo "No asignado";
              }
              ?>
            </td>
    <td>
  <!-- Formulario para asignar un motorizado -->
  <form method="POST" action="pedidos.php">
    <input type="hidden" name="asignar" value="<?= $pedido['id_pedido'] ?>">
    <select name="motorizado_id_<?= $pedido['id_pedido'] ?>" required>
      <option value="">-- Seleccionar Motorizado --</option>
      <?php foreach ($motorizados as $motorizado): ?>
        <option value="<?= $motorizado['id_motorizado'] ?>"><?= htmlspecialchars($motorizado['nombre_completo']) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn-sm">Asignar</button>
  </form>
  <!-- Botón de eliminar pedido -->
  <a href="pedidos.php?eliminar=<?= $pedido['id_pedido'] ?>" onclick="return confirm('¿Estás seguro de eliminar este pedido?')" class="btn-sm">Eliminar</a>
  <!-- Enlace para editar pedido -->
  <a href="editar_pedido.php?id=<?= $pedido['id_pedido'] ?>" class="btn-sm">Editar</a>
</td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
