<?php
include '../includes/db.php';
include '../includes/auth.php';
include 'funciones.php';

session_start();
$loggedIn = !empty($_SESSION['usuario_id']);
$favIds = [];

$search_query = isset($_GET['q']) ? $_GET['q'] : '';
$search_query = htmlspecialchars($search_query);
$results = [];

if (!empty($search_query)) {
    $sql = "SELECT * FROM Productos WHERE nombre LIKE ? OR descripcion LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $search_term = "%" . $search_query . "%"; 
        mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term); 
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $results[] = $row;
            }
        } else {
            error_log("Database error: " . mysqli_error($conn)); 
            $error_message = "Error retrieving search results.";
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Prepare statement error: " . mysqli_error($conn));
        $error_message = "Error preparing search query.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JoyasJewels · Joyería de Excelencia</title>
    <link rel="shortcut icon" href="../assets/img/logo/favicon.ico">
    <link rel="stylesheet" href="../assets/css/search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

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
        <article class="container">
            <h1>Buscando resultados para "<?php echo htmlspecialchars($search_query); ?>"</h1>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <?php if (empty($results)): ?>
                <p>No se encontraron productos para su búsqueda.</p>
            <?php else: ?>
                <section class="product-list">
                    <?php foreach ($results as $prod): ?>
                        <article class="product-item">
                            <a href="/web/products/producto.php?id=<?= $prod['id'] ?>" class="product-card">
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
                                    <section class="quantity-wrapper">
                                        <button type="button" class="qty-btn" data-action="decrease">−</button>
                                        <input 
                                        type="number" 
                                        id="quantity" 
                                        value="1" 
                                        min="1" 
                                        max="10"
                                        class="qty-input"
                                        >
                                        <button type="button" class="qty-btn" data-action="increase">＋</button>
                                    </section>

                                    <button 
                                        type="button" 
                                        class="btn btn-primary btn-add-cart" 
                                        onclick="addToCart(<?= $producto_id ?>)"
                                    >
                                        <i class="fas fa-shopping-cart"></i>
                                        Añadir al carrito
                                    </button>

                                    <button 
                                        type="button" 
                                        class="btn btn-secondary btn-buy-now" 
                                        onclick="buyNow(<?= $prod['id'] ?>)"
                                    >
                                        Comprar ahora
                                    </button>
                            </section>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </article>
    </main>
    <?php include '../includes/footer.php';?>

    <script type="module" src="../assets/js/logo.js"></script>
    <script type="module" src="../assets/js/cookies.js"></script>

</body>
</html>
