<?php
include '../includes/db.php';
include '../includes/auth.php';
include 'funciones.php';

session_start();
$loggedIn = !empty($_SESSION['usuario_id']);
$favIds = [];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JoyasJewels · Joyería de Excelencia</title>
    <base href="<?= BASE_URL ?>">
    <link rel="shortcut icon" href="assets/img/logo/favicon.ico">
    <link rel="stylesheet" href="assets/css/tienda.css">
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
    <?php include '../includes/header.php'; ?>
    <main class="parent">
        <aside class="sidebar">
            <h3>Categorías</h3>
            <ul>
                <?php
                $categorias = get_categorias();
                foreach ($categorias as $cat) {
                    echo '<li><a href="products/tienda.php?categoria=' . $cat['id'] . '">' . htmlspecialchars($cat['nombre']) . '</a></li>';
                }
                ?>
            </ul>
        </aside>
        <section class="products-container">
            <?php
            $productos = isset($_GET['categoria']) ? get_productos_por_categoria((int)$_GET['categoria']): get_productos();
            ?>
            <?php foreach ($productos as $prod): ?>
                <article class="product-item">
                    <a href="products/producto.php?id=<?= $prod['id'] ?>" class="product-card">
                        <section class="product-image">
                            <img src="<?= htmlspecialchars($prod['imagen']) ?>"
                                 alt="<?= htmlspecialchars($prod['nombre']) ?>"
                                 loading="lazy">
                            <?php if ($loggedIn): ?>
                                <button class="btn-fav <?= in_array($prod['id'], $favIds) ? 'active' : '' ?>"
                                        data-id="<?= $prod['id'] ?>"
                                        title="Favoritos">
                                    <i class="fas fa-heart"></i>
                                </button>
                            <?php endif; ?>
                        </section>
                        <section class="product-info">
                            <h4><?= htmlspecialchars($prod['nombre']) ?></h4>
                            <p class="price"><?= number_format($prod['precio'], 2, ',', '.') ?> €</p>
                        </section>
                    </a>
                    <section class="product-actions">
                        <a href="products/carrito.php?add=<?= $prod['id'] ?>" class="btn-cart" title="Añadir al carrito">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                        <a href="products/producto.php?id=<?= $prod['id'] ?>" class="btn-view" title="Ver producto">
                            Ver producto
                        </a>
                    </section>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="assets/js/tienda.js"></script>
    <script src="assets/js/logo.js"></script>
</body>
</html>
