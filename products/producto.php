<?php
include '../includes/db.php';
include 'funciones.php';
session_start();

$producto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$producto = get_producto_por_id($producto_id);
if (!$producto) {
    die("Producto no encontrado");
}

$productos_relacionados = get_productos_relacionados($producto['categoria'], $producto_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_reseña'])) {
    if (empty($_SESSION['usuario_id'])) {
        die("Debes iniciar sesión para enviar una reseña.");
    }

    $userId  = $_SESSION['usuario_id'];
    $prodId  = $producto_id;
    $stars   = (int) $_POST['puntuacion'];
    $comment = trim($_POST['comentario']);

    $stmt = $conn->prepare(
        "INSERT INTO Reseñas 
            (id_Usuario, id_Producto, puntuacion, comentario) 
         VALUES (?,?,?,?)"
    );
    $stmt->bind_param("iiis", $userId, $prodId, $stars, $comment);

    if ($stmt->execute()) {
        header("Location: producto.php?id=$prodId#reseñas");
        exit;
    } else {
        $error_reseña = "No se pudo guardar tu reseña. Intenta más tarde.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($producto['nombre']); ?> · JoyasJewels</title>
  <base href="<?= BASE_URL ?>">
  <link rel="shortcut icon" href="assets/img/logo/favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/producto.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <main class="product-container">
    <section class="product-content-wrapper">

      <section class="product-gallery">
        <?php 
        $urlImagen = $producto['imagen']; 
        echo '<img id="mainImage" src="/web/' . htmlspecialchars($urlImagen) . '" alt="' . htmlspecialchars($producto['nombre']) . '" class="product-main-image">';
        ?>
      </section>

      <section class="product-details">
        <section class="product-header">
          <h1 class="product-title">
            <?php echo htmlspecialchars($producto['nombre']); ?>
          </h1>
          <p class="product-price">
            <?php echo number_format($producto['precio'], 2, ',', '.'); ?> €
          </p>
        </section>

        <section class="product-description">
          <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
          <ul>
            <li><span>Material:</span> <?php echo htmlspecialchars($producto['material']); ?></li>
            <li><span>Quilates:</span> <?php echo htmlspecialchars($producto['quilates']); ?></li>
            <li><span>Peso:</span> <?php echo htmlspecialchars($producto['peso_gramos']); ?> g</li>
            <li><span>Dimensiones:</span> <?php echo htmlspecialchars($producto['dimensiones']); ?></li>
            <li><span>SKU:</span> <?php echo htmlspecialchars($producto['sku']); ?></li>
            <li><span>Disponibilidad:</span> 
              <?php echo $producto['disponible'] ? 'En stock' : 'Agotado'; ?>
            </li>
          </ul>
        </section>

        <section class="product-actions">
            <section class="quantity-control">
                <button type="button" onclick="decreaseQuantity()">–</button>
                <input type="number" id="quantity" value="1" min="1" max="10">
                <button type="button" onclick="increaseQuantity()">+</button>
            </section>
            <a href="#" id="addBtn" class="btn btn-cart" title="Añadir al carrito"
                onclick="addToCart(<?= $producto_id ?>, event)">
                <i class="fas fa-shopping-cart"></i>
            </a>
            <button class="btn btn-primary" onclick="goToCart()">
                Comprar Ahora
            </button>
        </section>

      </section>

    </section>

    <?php if (!empty($productos_relacionados)): ?>
    <section class="related-products">
      <h2>Productos Relacionados</h2>
      <section class="related-products-grid">
        <?php foreach ($productos_relacionados as $prodRel): 
            $urlRel = $prodRel['imagen'];
        ?>
          <article class="related-product">
            <img src="<?= htmlspecialchars($urlRel) ?>" 
                 alt="<?= htmlspecialchars($prodRel['nombre']) ?>">
            <section class="related-product-info">
              <h3><?= htmlspecialchars($prodRel['nombre']) ?></h3>
              <p><?= number_format($prodRel['precio'], 2, ',', '.') ?> €</p>
              <a href="/web/products/producto.php?id=<?= $prodRel['id'] ?>" class="btn btn-secondary">
                Ver Producto
              </a>
            </section>
          </article>
        <?php endforeach; ?>
      </section>
    </section>
    <?php endif; ?>

    <section class="product-reviews" id="reseñas">
      <h2>Reseñas de usuarios</h2>
      <section class="reviews-list">
        <?php
        $stmt = $conn->prepare("SELECT r.puntuacion, r.comentario, r.fecha, u.nombre FROM Reseñas r JOIN Usuarios u ON r.id_Usuario = u.id_Usuario WHERE r.id_Producto = ? ORDER BY r.fecha DESC");
        $stmt->bind_param("i", $producto['id']);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0): ?>
          <p class="no-reviews">Aún no hay reseñas. ¡Sé el primero en opinar!</p>
        <?php else: 
          while ($rev = $res->fetch_assoc()):
        ?>
          <section class="review-item">
            <section class="review-header">
              <strong><?= htmlspecialchars($rev['nombre']) ?></strong>
              <span class="stars"><?= str_repeat('★', $rev['puntuacion']) ?></span>
              <small><?= date('d/m/Y H:i', strtotime($rev['fecha'])) ?></small>
            </section>
            <p><?= nl2br(htmlspecialchars($rev['comentario'])) ?></p>
          </section>
        <?php 
          endwhile;
        endif;
        ?>
      </section>

      <section class="reviews-form">
        <?php if (!empty($_SESSION['usuario_id'])): ?>
          <h3>Deja tu reseña</h3>
          <?php if (!empty($error_reseña)): ?>
            <section class="alert error"><?= htmlspecialchars($error_reseña) ?></section>
          <?php endif; ?>

          <form action="/web/products/producto.php?id=<?= $producto['id'] ?>#reseñas" method="post">
            <label for="puntuacion">Puntuación:</label>
            <select name="puntuacion" id="puntuacion" required>
              <option value="">--Elige--</option>
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>"
                  <?= (isset($_POST['puntuacion']) && $_POST['puntuacion'] == $i) 
                        ? 'selected' : '' ?>>
                  <?= str_repeat('★', $i) ?>
                </option>
              <?php endfor; ?>
            </select>

            <label for="comentario">Comentario:</label>
            <textarea name="comentario" id="comentario" rows="4" required><?= 
                isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario']) : '' 
            ?></textarea>

            <button type="submit" name="enviar_reseña" class="btn btn-primary">
              Enviar reseña
            </button>
          </form>
        <?php else: ?>
          <section class="login-prompt">
            <a class="btn-login" href="/web/users/log.php">Inicia sesión</a> para dejar una reseña.
          </section>
        <?php endif; ?>
      </section>
    </section>
  </main>

  <script src="/web/assets/js/producto.js"></script>
  <?php include '../includes/footer.php'; ?>
  <script type="module" src="/web/assets/js/logo.js"></script>
</body>
</html>
