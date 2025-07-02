<?php 
include '../includes/db.php';

$mensaje_enviado = isset($_GET['enviado']) && $_GET['enviado'] == 1;
$error_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);

    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $error_mensaje = 'Por favor, complete todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_mensaje = 'Por favor, introduzca un correo electrónico válido.';
    } else {
        $stmt = $conn->prepare("INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $mensaje);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF'] . "?enviado=1");
            exit();
        } else {
            $error_mensaje = 'Hubo un error al enviar su mensaje. Inténtelo de nuevo.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JoyasJewels · Joyería de Excelencia</title>
    <link rel="shortcut icon" href="../assets/img/logo/favicon.ico">
    <link rel="stylesheet" href="../assets/css/contacto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- SEO -->
    <meta name="description" content="JoyasJewels, artesanía de joyería desde 1980. Descubre anillos, collares y más piezas únicas de oro y diamantes.">
    <meta property="og:title" content="JoyasJewels · Joyería de Excelencia">
    <meta property="og:description" content="Descubre nuestra colección de joyas artesanales.">
    <meta property="og:image" content="https://joyasjewels.com/assets/img/anillodediamante.webp">
    <meta property="og:url" content="https://joyasjewels.com">
    <meta property="og:type" content="website">
</head>

<body>

    <?php include '../includes/header.php'; ?>
    <main class="contact-container">
        <section class="contact-info">
            <h1>Contacta con Soporte</h1>
            <p>Estamos aquí para ayudarte. Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos.</p>
            
            <section class="contact-details">
                <article class="contact-detail">
                    <i class="fas fa-phone"></i>
                    <span>Teléfono: +34 123 456 789</span>
                </article>
                <article class="contact-detail">
                    <i class="fas fa-envelope"></i>
                    <span>Email: contacto@joyasjewels.com</span>
                </article>
                <article class="contact-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Dirección: Av. de los Jeronimos, 135, 30107 Guadalupe, Murcia</span>
                </article>
            </section>
        </section>

        <section class="contact-form">
            <?php if ($mensaje_enviado): ?>
                <section class="alert success">
                    <p>¡Gracias por su mensaje! Nos pondremos en contacto con usted en la mayor brevedad posible.</p>
                </section>
            <?php endif; ?>

            <?php if (!empty($error_mensaje)): ?>
                <section class="alert error">
                    <p><?php echo htmlspecialchars($error_mensaje); ?></p>
                </section>
            <?php endif; ?>

            <form method="POST" action="">
                <section class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required 
                    value="<?php echo (!$mensaje_enviado && isset($_POST['nombre'])) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                </section>

                <section class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required 
                    value="<?php echo (!$mensaje_enviado && isset($_POST['email'])) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </section>

                <section class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="5" required><?php 
                    echo (!$mensaje_enviado && isset($_POST['mensaje'])) ? htmlspecialchars($_POST['mensaje']) : ''; 
                ?></textarea>
                </section>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Enviar Mensaje
                </button>
            </form>
        </section>
    </main>
    <?php include '../includes/footer.php';?>
    
    <script type="module" src="/web/assets/js/logo.js"></script>
</body>

</html>