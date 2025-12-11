<?php
$pagina_activa = 'cobertura';
include 'includes/header.php';
include 'config.php';

// Consulta para obtener todas las coberturas
$q = strtolower(trim($_GET['buscar'] ?? ''));

// Verificar si la consulta está vacía o si existe un valor para la búsqueda
if ($q === '') {
    $stmt = $pdo->query("SELECT * FROM coberturas ORDER BY distrito ASC"); // Cambié a coberturas
} else {
    $stmt = $pdo->prepare("SELECT * FROM coberturas WHERE LOWER(distrito) LIKE :search ORDER BY distrito ASC");
    $stmt->execute(['search' => "%$q%"]);
}

$coberturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zona de Cobertura | Courier Delivery</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Incluye tu archivo de estilos aquí -->
</head>
<body>

  

    <!-- Hero Section -->
    <section class="hero container">
        <h1 class="hero__title">Zona de Cobertura</h1>
        <p class="hero__subtitle">
            Consulta nuestros distritos disponibles y tarifas de envío.
            Si tu zona no figura, contáctanos para una cobertura personalizada.
        </p>

        <!-- Buscador -->
        <form class="search" method="get" action="cobertura.php" id="form-buscar">
            <input class="search__input" type="text" name="buscar" placeholder="Buscar distrito..." value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn--primary search__btn">Buscar</button>
        </form>
    </section>

    <!-- Cobertura Section -->
    <section class="container cobertura">
        <h2 class="section-title">Zonas y Tarifas</h2>

        <?php if (!$coberturas): ?>
            <p class="alert alert--error">No se encontraron resultados para tu búsqueda.</p>
        <?php else: ?>
            <div class="tarifario">
                <?php foreach ($coberturas as $r): ?>
                    <div class="tarifa-card">
                        <h3><?= htmlspecialchars($r['distrito']) ?></h3>
                        <span class="precio">S/ <?= number_format($r['tarifa'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Courier Delivery. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
