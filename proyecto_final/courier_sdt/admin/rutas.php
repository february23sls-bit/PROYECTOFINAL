<?php
require_once __DIR__ . '/../config.php'; // Ruta hacia el archivo config.php

// Función para obtener todas las coberturas
function obtenerCoberturas() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM coberturas");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para agregar una nueva cobertura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['distrito'], $_POST['tarifa'])) {
    $distrito = $_POST['distrito'];
    $tarifa = $_POST['tarifa'];

    $stmt = $pdo->prepare("INSERT INTO coberturas (distrito, tarifa) VALUES (?, ?)");
    $stmt->execute([$distrito, $tarifa]);

    header("Location: rutas.php?mensaje=Tarifa agregada correctamente");
    exit;
}

// Función para editar una cobertura existente
if (isset($_GET['editar']) && isset($_GET['id'])) {
    $id_cobertura = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM coberturas WHERE id_cobertura = ?");
    $stmt->execute([$id_cobertura]);
    $cobertura = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cobertura_edit'], $_POST['distrito_edit'], $_POST['tarifa_edit'])) {
    $id_cobertura = $_POST['id_cobertura_edit'];
    $distrito_edit = $_POST['distrito_edit'];
    $tarifa_edit = $_POST['tarifa_edit'];

    $stmt = $pdo->prepare("UPDATE coberturas SET distrito = ?, tarifa = ? WHERE id_cobertura = ?");
    $stmt->execute([$distrito_edit, $tarifa_edit, $id_cobertura]);

    header("Location: rutas.php?mensaje=Tarifa actualizada correctamente");
    exit;
}

// Función para eliminar una cobertura
if (isset($_GET['eliminar']) && isset($_GET['id'])) {
    $id_cobertura = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM coberturas WHERE id_cobertura = ?");
    $stmt->execute([$id_cobertura]);

    header("Location: rutas.php?mensaje=Tarifa eliminada correctamente");
    exit;
}

// Obtener todas las coberturas
$coberturas = obtenerCoberturas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionar Coberturas | SDT Courier</title>
    <style>
        /* CSS de la página */
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
        .form-container, .table-container {
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
        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table-container th, .table-container td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .table-container th {
            background-color: #007bff;
            color: white;
        }
        .table-container td {
            background-color: #f9f9f9;
        }
        .table-container a {
            text-decoration: none;
            color: #007bff;
            margin: 0 5px;
        }
        .table-container a:hover {
            color: #0056b3;
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
        <h1>Gestionar Coberturas</h1>

        <!-- Mostrar mensaje de éxito si está presente -->
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="message <?= isset($_GET['error']) ? 'error' : '' ?>">
                <?= htmlspecialchars($_GET['mensaje']) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para agregar nueva cobertura -->
        <div class="form-container">
            <h3>Agregar Nueva Cobertura</h3>
            <form action="rutas.php" method="POST">
                <input type="text" name="distrito" placeholder="Distrito" required>
                <input type="number" step="0.01" name="tarifa" placeholder="Tarifa (en soles)" required>
                <button type="submit">Agregar Cobertura</button>
            </form>
        </div>

        <!-- Tabla de coberturas -->
        <div class="table-container">
            <h3>Listado de Coberturas</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Cobertura</th>
                        <th>Distrito</th>
                        <th>Tarifa</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coberturas as $cobertura): ?>
                        <tr>
                            <td><?= htmlspecialchars($cobertura['id_cobertura']) ?></td>
                            <td><?= htmlspecialchars($cobertura['distrito']) ?></td>
                            <td>S/ <?= number_format($cobertura['tarifa'], 2) ?></td>
                            <td>
                                <a href="editar_cobertura.php?id=1">Editar</a>
                                <a href="rutas.php?eliminar=1&id=<?= $cobertura['id_cobertura'] ?>" onclick="return confirm('¿Estás seguro de eliminar esta cobertura?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
