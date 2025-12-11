<?php
require_once __DIR__ . '/../config.php'; // Ruta hacia el archivo config.php

// Obtener los detalles del pedido
if (isset($_GET['id'])) {
    $pedido_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = ?");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        // Si no se encuentra el pedido
        header("Location: pedidos.php?mensaje=El pedido no existe.");
        exit;
    }
}

// Actualizar el pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinatario = $_POST['destinatario'];
    $telefono = $_POST['telefono'];
    $distrito = $_POST['distrito'];
    $direccion = $_POST['direccion'];
    $detalle_pedido = $_POST['detalle_pedido'];
    $estado = $_POST['estado'];

    // Actualizar los datos del pedido
    $stmt = $pdo->prepare("UPDATE pedidos SET destinatario = ?, telefono = ?, distrito = ?, direccion = ?, detalle_pedido = ?, estado = ? WHERE id_pedido = ?");
    $stmt->execute([$destinatario, $telefono, $distrito, $direccion, $detalle_pedido, $estado, $pedido_id]);

    // Redirigir con mensaje de éxito
    header("Location: pedidos.php?mensaje=Pedido actualizado correctamente");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Pedido | SDT Courier</title>
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

    form {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-weight: bold;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .alert--success {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>
<body>

  <header>
    <h1>Editar Pedido</h1>
  </header>

  <div class="container">
    <!-- Mostrar mensaje de éxito si está presente -->
    <?php if (isset($_GET['mensaje'])): ?>
      <div class="alert alert--success"><?= htmlspecialchars($_GET['mensaje']) ?></div>
    <?php endif; ?>

    <!-- Formulario de edición de pedido -->
    <form method="POST" action="editar_pedido.php?id=<?= $pedido['id_pedido'] ?>">
      <div class="form-group">
        <label for="destinatario">Destinatario</label>
        <input type="text" name="destinatario" id="destinatario" value="<?= htmlspecialchars($pedido['destinatario']) ?>" required>
      </div>
      <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($pedido['telefono']) ?>" required>
      </div>
      <div class="form-group">
        <label for="distrito">Distrito</label>
        <input type="text" name="distrito" id="distrito" value="<?= htmlspecialchars($pedido['distrito']) ?>" required>
      </div>
      <div class="form-group">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($pedido['direccion']) ?>" required>
      </div>
      <div class="form-group">
        <label for="detalle_pedido">Detalle de Pedido</label>
        <input type="text" name="detalle_pedido" id="detalle_pedido" value="<?= htmlspecialchars($pedido['detalle_pedido']) ?>" required>
      </div>
      <div class="form-group">
        <label for="estado">Estado</label>
        <select name="estado" id="estado">
          <option value="asignado" <?= $pedido['estado'] === 'asignado' ? 'selected' : '' ?>>Asignado</option>
          <option value="en ruta" <?= $pedido['estado'] === 'en ruta' ? 'selected' : '' ?>>En Ruta</option>
          <option value="entregado" <?= $pedido['estado'] === 'entregado' ? 'selected' : '' ?>>Entregado</option>
          <option value="anulado" <?= $pedido['estado'] === 'anulado' ? 'selected' : '' ?>>Anulado</option>
        </select>
      </div>

      <button type="submit" class="btn-primary">Actualizar Pedido</button>
    </form>
  </div>

</body>
</html>
