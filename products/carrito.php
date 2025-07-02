<?php
include '../includes/db.php';
include '../includes/auth.php';
include 'pedidos_func.php';

require_login();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $id       = (int) $_GET['add'];
    $qty      = isset($_GET['quantity']) ? max(1, intval($_GET['quantity'])) : 1;
    if ($id > 0) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $qty;
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    if (isset($_GET['count_only'])) {
        $totalItems = array_sum($_SESSION['cart']);
        header('Content-Type: application/json');
        echo json_encode(['total_items' => $totalItems]);
        exit;
    }
    header('Location: carrito.php');
    exit;
}


if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header('Location: carrito.php');
    exit;
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header('Location: carrito.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $id = (int) $id;
        $qty = max(1, (int) $qty);
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    header('Location: carrito.php');
    exit;
}

$cart = $_SESSION['cart'];
$items = [];
$total = 0.0;

if (!empty($cart)) {
    $items = obtener_items_carrito($conn, $cart, $total);
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JoyasJewels · Joyería de Excelencia</title>
    <link rel="shortcut icon" href="/web/assets/img/logo/favicon.ico">
    <link rel="stylesheet" href="/web/assets/css/carrito.css">
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
    <h1>Tu Carrito de Compra</h1>

    <?php if (empty($items)): ?>
        <section class="empty-cart">
            <i class="fas fa-shopping-cart empty-icon"></i>
            <p class="empty-text">
            Tu carrito está vacío.
            <br>
            <a href="/web/products/tienda.php" class="empty-link">Ver productos</a>
            </p>
        </section>
    <?php else: ?>
        <form action="carrito.php" method="post">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                    <td class="prod-info">
                            <a href="producto.php?id=<?= $item['id_Producto'] ?>" class="prod-link">
                                <section class="prod-thumb">
                                    <img 
                                        src="/web/<?= htmlspecialchars($item['imagen']) ?>" 
                                        alt="<?= htmlspecialchars($item['nombre']) ?>" 
                                    >
                                </section>
                                <section class="prod-name">
                                    <?= htmlspecialchars($item['nombre']) ?>
                                </section>
                            </a>
                        </td>
                        <td><?= number_format($item['precio'], 2, ',', '.') ?> €</td>
                        <td>
                            <input 
                              type="number" 
                              name="quantities[<?= $item['id_Producto'] ?>]" 
                              value="<?= $item['cantidad'] ?>" 
                              min="1"
                            >
                        </td>
                        <td><?= number_format($item['subtotal'], 2, ',', '.') ?> €</td>
                        <td>
                            <a href="carrito.php?remove=<?= $item['id_Producto'] ?>" class="btn-remove">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <section class="cart-actions">
                <button type="submit" class="btn-update">Actualizar carrito</button>
                <a href="carrito.php?clear=1" class="btn-clear">Vaciar carrito</a>
                <a href="tienda.php" class="btn-buy">Seguir comprando</a>
            </section>
        </form>
        <section class="cart-total">
            <span>Total:</span>
            <strong><?= number_format($total, 2, ',', '.') ?> €</strong>
        </section>
        <section class="checkout">
            <a href="/web/products/comprar.php" class="btn-checkout">Proceder al pago</a>
        </section>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
<script type="module" src="/web/assets/js/logo.js"></script>
</body>
</html>
