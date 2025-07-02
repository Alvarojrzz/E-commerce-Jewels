<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$loggedIn = !empty($_SESSION['usuario']);
?>
<header class="header">
    <section id="logo-container" role="banner" class="cursor-pointer">
        <a href="/web/index.php">
            <canvas id="diamondCanvas" width="270" height="100"></canvas>
        </a>
    </section>

    <section class="search-bar">
       <form action="/web/products/search.php" method="get">
            <button type="submit"><i class="fas fa-search"></i></button>
            <input type="text" name="q" placeholder="Buscar joyas..." />
        </form>
    </section>

    <section class="access">
        <ul class="login-icons">
            <?php if ($loggedIn): ?>
                <li>
                    <a href="/web/products/carrito.php" title="Carrito">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </li>
                <li>
                    <a href="/web/users/perfil.php" title="Mi Perfil">
                        <i class="fas fa-user"></i>
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="/web/users/log.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                        class="login-link"
                        title="Iniciar sesión">
                        Iniciar sesión
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </section>

    <nav class="menu-nav">
        <ul>
            <li><a href="/web/products/tienda.php">Catálogo</a></li>
            <li><a href="/web/products/tienda.php">Novedades</a></li>
            <li><a href="/web/products/tienda.php">Ofertas</a></li>
            <li><a href="/web/products/tienda.php">Más</a></li>
        </ul>
    </nav>
</header>
