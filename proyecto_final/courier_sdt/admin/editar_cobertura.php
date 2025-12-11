<?php
require_once __DIR__ . '/../config.php'; // Ruta hacia el archivo config.php

// Verificar si la ID de la cobertura está en la URL
if (isset($_GET['id'])) {
    $id_cobertura = $_GET['id'];

    // Obtener la cobertura seleccionada para editar
    $stmt = $pdo->prepare("SELECT * FROM coberturas WHERE id_cobertura = ?");
    $stmt->execute([$id_cobertura]);
    $cobertura = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si la cobertura no existe, redirigir con un mensaje de error
    if (!$cobertura) {
        header("Location: rutas.php?mensaje=La cobertura no existe&error=1");
        exit;
    }
} else {
    // Si no se pasa la ID, redirigir con un mensaje de error
    header("Location: rutas.php?mensaje=No se ha especificado una cobertura&error=1");
    exit;
}

// Actualizar la cobertura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['distrito_edit'], $_POST['tarifa_edit'])) {
    $distrito_edit = $_POST['distrito_edit'];
    $tarifa_edit = $_POST['tarifa_edit'];

    // Actualizar la cobertura en la base de datos
    $stmt = $pdo->prepare("UPDATE coberturas SET distrito = ?, tarifa = ? WHERE id_cobertura = ?");
    $stmt->execute([$distrito_edit, $tarifa_edit, $id_cobertura]);

    // Redirigir con mensaje de éxito
    header("Location: rutas.php?mensaje=Cobertura actualizada correctamente");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Cobertura | SDT Courier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .form-container {
            margin-top: 30px;
        }
        .form-container input, .form-container select {
            padding: 10px;
            font-size: 14px;
            width: 100%;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            color: white;
            background-color: #28a745;
            text-align: center;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Editar Cobertura</h1>

        <!-- Mostrar mensaje de éxito si está presente -->
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="message <?= isset($_GET['error']) ? 'error' : '' ?>">
                <?= htmlspecialchars($_GET['mensaje']) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para editar cobertura -->
        <div class="form-container">
            <form method="POST" action="editar_cobertura.php?id=<?= $cobertura['id_cobertura'] ?>">
                <label for="distrito_edit">Distrito:</label>
                <input type="text" id="distrito_edit" name="distrito_edit" value="<?= htmlspecialchars($cobertura['distrito']) ?>" required>

                <label for="tarifa_edit">Tarifa (en soles):</label>
                <input type="text" id="tarifa_edit" name="tarifa_edit" value="<?= htmlspecialchars($cobertura['tarifa']) ?>" required>

                <button type="submit">Actualizar Cobertura</button>
            </form>
        </div>
    </div>

</body>
</html>
