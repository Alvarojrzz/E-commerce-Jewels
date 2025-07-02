<?php
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JoyasJewels · Joyería de Excelencia</title>
    <link rel="shortcut icon" href="assets/img/logo/favicon.ico">
    <link rel="stylesheet" href="assets/css/index.css">
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

   <?php include 'includes/header.php'; ?>

    <main class="parent">
        <section class="hero">
            <video src="assets/img/video/Video.mp4" autoplay loop muted class="hero-video"></video>
            <article class="hero-content">
                <h2 class="hero-title">Artesanía Joyería Tradicional</h2>
                <p class="hero-subtitle">Desde 1980 creando piezas únicas</p>
                <a href="products/tienda.php?categoria=1" class="hero-cta"><span>Descubrir categoría</span></a>
            </article>
        </section>

        <section class="collection">
            <section class="anillo">
                <a href="/web/products/producto.php?id=1">
                    <img src="assets/img/hero/anillodediamante.webp" loading="lazy" alt="Anillo de diamantes">
                </a>
            </section>
            <section class="cta-grid">
                <a href="/web/products/tienda.php">
                    <article class="cta-content">
                        <h2>ALTA JOYERÍA</h2>
                        <p>Descubra las joyas más exclusivas de nuestra colección. Diseños únicos elaborados a
                            partir de una selección excepcional de piedras preciosas.</p>
                        <p>Descubrir</p>
                    </article>
                </a>
            </section>
            <section class="collar">
                <a href="/web/products/tienda.php?categoria=2">
                    <img src="assets/img/hero/collar_diamantes_estelar.webp" loading="lazy" alt="Collar de diamantes">
                    <p class="text">Collares especiales</p>
                    <p class="subtext">Descubrir</p>
                </a>
            </section>
            <section class="pendiente">
                <a href="/web/products/tienda.php?categoria=3">
                    <img src="assets/img/hero/pendientes_diamantes_luz.webp" loading="lazy" alt="Pendientes de diamantes">
                    <p class="text">Pendientes exclusivos</p>
                    <p class="subtext">Descubrir</p>
                </a>
            </section>
            <section class="pulsera">
                <a href="/web/products/tienda.php?categoria=4">
                    <img src="assets/img/hero/pulsera_diamantes.webp" loading="lazy" alt="Pulsera de diamantes">
                    <p class="text">Pulseras lujosas</p>
                    <p class="subtext">Descubrir</p>
                </a>
            </section>
        </section>


        <section class="event-calendar">
            <h2 class="section-title">Próximos Eventos</h2>
            <p class="section-subtitle">No te pierdas nuestros eventos especiales</p>
            <div class="calendar-wrapper">
                <article id="calendar"></article>
                <aside id="event-list" class="event-list"></aside>
            </div>
        </section>        

        <!-- Modal eventos -->
        <section id="eventModal" class="modal">
            <section class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 id="eventTitle" class="modal-title"></h2>
                <article class="modal-info">
                    <p><i class="fas fa-calendar"></i> <span id="eventDate"></span></p>
                    <p><i class="fas fa-clock"></i> <span id="eventTime"></span></p>
                    <p><i class="fas fa-align-left"></i> <span id="eventDescription"></span></p>
                </article>
            </section>
        </section>

        <section class="geo">
            <section class="geo-header">
                <h2 class="section-title">Nuestro Taller</h2>
                <p class="section-subtitle">Donde la magia sucede</p>
            </section>
            <section class="geo-content">
                <article id="map" class="map-container"></article>
                <address class="tienda-info">
                    <article class="info-item">
                        <h4 class="info-title">Ubicación</h4><br>
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Av. de los Jeronimos, 135<br>30107 Guadalupe, Murcia</p>
                    </article>
                    <article class="info-item">
                        <h4 class="info-title">Horario</h4><br>
                        <i class="fas fa-clock"></i>
                        <p>L-V: 10:00 - 20:00<br>Sáb: 10:00 - 13:00</p>
                    </article>
                </address>
            </section>
        </section>
    </main>
    <?php include 'includes/footer.php';?>
    <script type="module" src="assets/js/mapa.js"></script>
    <script type="module" src="assets/js/logo.js"></script>
    <script type="module" src="assets/js/calendar.js"></script>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js'></script>
</body>

</html>