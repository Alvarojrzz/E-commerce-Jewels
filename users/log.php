<?php
session_start();
require_once '../includes/db.php';
require_once 'funciones.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modo = $_POST['modo'] ?? '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($modo === 'registro') {
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

        if (empty($nombre) || empty($email) || empty($password)) {
            $mensaje = "Todos los campos son obligatorios.";
        } else {
            $mensaje = registrar_usuario($conn, $nombre, $email, $password);
        }
    }

    if ($modo === 'login') {
        if (empty($email) || empty($password)) {
            $mensaje = "Debe ingresar correo y contraseña.";
        } else {
            $mensaje = iniciar_sesion($conn, $email, $password);
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
    <link rel="stylesheet" href="../assets/css/log.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- SEO -->
    <meta name="description" content="JoyasJewels, artesanía de joyería desde 1980. Descubre anillos, collares y más piezas únicas de oro y diamantes.">
    <meta property="og:title" content="JoyasJewels · Joyería de Excelencia">
    <meta property="og:description" content="Descubre nuestra colección de joyas artesanales.">
    <meta property="og:image" content="https://joyasjewels.com/assets/img/anillodediamante.webp">
    <meta property="og:url" content="https://joyasjewels.com">
    <meta property="og:type" content="website">
</head>
<body>
<?php if (!empty($mensaje)): ?>
    <section class="alert error"><?= htmlspecialchars($mensaje) ?></section>
<?php endif; ?>

<main class="container" id="container">
	<section class="form-container sign-up-container">
	<form action="log.php" method="POST">
    <input type="hidden" name="modo" value="registro">
    <h1>Crear Cuenta</h1>
    <section class="social-container">
        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
    </section>
    <span>o introduce tu información personal para registrarte</span>
    <input type="text" name="nombre" placeholder="Nombre" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Registrarse</button>
</form>

	</section>
	<section class="form-container sign-in-container">
		<form action="log.php" method="POST">
			<input type="hidden" name="modo" value="login">
			<h1>Entrar</h1>
			<section class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</section>
			<span>o usa tu cuenta</span>
			<input type="email" name="email" placeholder="Email" required />
			<input type="password" name="password" placeholder="Password" required />
			<a href="#">¿Olvidaste tu contraseña?</a>
			<button>Entrar</button>
		</form>
	</section>
	<section class="overlay-container">
		<section class="overlay">
			<section class="overlay-panel overlay-left">
				<h1>¡Bienvenido de nuevo!</h1>
				<p>Para mantenernos conectados con usted, por favor, inicie sesión con su información personal</p>
				<button class="ghost" id="signIn">Entrar</button>
			</section>
			<section class="overlay-panel overlay-right">
				<h1>¡Bienvenido, amigo!</h1>
				<p>Ingresa tus datos personales y comienza tu viaje con nosotros</p>
				<button class="ghost" id="signUp">Registrarse</button>
			</section>
		</section>
	</section>
</main>

<script src="../assets/js/log.js"></script>
</body>
</html>