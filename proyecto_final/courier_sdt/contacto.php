<?php 
include 'includes/header.php'; // Incluir el header
include 'config.php'; // Conexión a la base de datos

// Definir el mensaje de éxito o error
$mensaje_envio = "";

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $mensaje = $_POST['mensaje'];

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($email) && !empty($mensaje)) {
        // Preparar la consulta para insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO mensajes_contacto (nombre, email, telefono, mensaje) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $telefono, $mensaje]);

        // Mensaje de éxito
        $mensaje_envio = "Tu mensaje se ha enviado correctamente. Nos pondremos en contacto contigo pronto.";
    } else {
        // Mensaje de error si hay campos vacíos
        $mensaje_envio = "Por favor, completa todos los campos.";
    }
}
?>

<!-- Sección principal -->
<section class="hero container">
  <h1 class="hero__title">Contáctanos</h1>
  <p class="hero__subtitle">
    ¿Tienes dudas, consultas o deseas solicitar un servicio personalizado? Contáctanos y nuestro equipo responderá a la brevedad.
  </p>
</section>

<!-- Mostrar mensaje de éxito o error -->
<?php if (!empty($mensaje_envio)): ?>
  <div class="alert <?= strpos($mensaje_envio, 'correctamente') !== false ? 'alert--success' : 'alert--error' ?>">
    <?= htmlspecialchars($mensaje_envio) ?>
  </div>
<?php endif; ?>

<!-- Sección de información -->
<section class="container contacto">
  <div class="contacto-grid">
    <div class="contacto-info">
      <h2>Información de Contacto</h2>
      <ul>
        <li><i class="fa-solid fa-location-dot"></i> Av. Brasil 1360, Pueblo Libre 15084</li>
        <li><i class="fa-solid fa-phone"></i> 944 718 388</li>
        <li><i class="fa-solid fa-envelope"></i> contactanos@sdtcourier.com</li>
      </ul>

      <div class="contacto-redes">
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
        <a href="#"><i class="fa-brands fa-tiktok"></i></a>
      </div>
    </div>

    <div class="contacto-form">
      <h2>Envíanos un mensaje</h2>
      <form method="POST" action="contacto.php">
        <input type="text" name="nombre" placeholder="Tu nombre" required>
        <input type="email" name="email" placeholder="Tu correo electrónico" required>
        <input type="text" name="telefono" placeholder="Tu número de teléfono" required>
        <textarea name="mensaje" placeholder="Escribe tu mensaje..." rows="5" required></textarea>
        <button type="submit" class="btn btn--primary">Enviar mensaje</button>
      </form>
    </div>
  </div>
</section>

<!-- Mapa -->
<section class="mapa-cobertura container">
  <h2 class="section-title">Ubicación</h2>
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3901.304691692617!2d-77.05520872588577!3d-12.08136554244594!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8e1e642b5b1%3A0x725f2da9da9a9b3b!2sAv.%20Brasil%201360%2C%20Pueblo%20Libre%2015084!5e0!3m2!1ses-419!2spe!4v1730599458995!5m2!1ses-419!2spe" 
    width="100%" 
    height="400" 
    style="border:0; border-radius:14px; box-shadow: var(--sombra);" 
    allowfullscreen="" 
    loading="lazy">
  </iframe>
</section>

<?php include 'includes/footer.php'; ?>
