<?php
include '../includes/db.php';
include '../includes/auth.php';
include 'pedidos_func.php';
require_login();

if (!isset($_GET['order_id']) || !ctype_digit($_GET['order_id'])) {
    header('Location: comprar.php');
    exit;
}
$order_id = intval($_GET['order_id']);
$userId   = $_SESSION['usuario_id'];

$sqlPedido = "
    SELECT 
      p.id_Pedido,
      p.fecha_pedido,
      p.precio_total,
      p.metodo_pago,
      f.numero_factura,
      f.iva,
      f.nombre_cliente,
      f.email_cliente,
      f.telefono_cliente,
      d.calle,
      d.numero,
      d.piso,
      d.cod_postal,
      d.ciudad,
      d.provincia,
      d.pais,
      u.nombre,
      u.email
    FROM Pedidos p
    JOIN Direcciones d ON p.id_Direccion = d.id_Direccion
    JOIN Facturas f   ON f.id_Pedido  = p.id_Pedido
    JOIN Usuarios u   ON u.id_Usuario = p.id_Usuario
    WHERE p.id_Pedido = ? AND p.id_Usuario = ?
";
$stmt = $conn->prepare($sqlPedido);
$stmt->bind_param("ii", $order_id, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header('Location: comprar.php');
    exit;
}

$pedido = $result->fetch_assoc();
$stmt->close();

$stmtItems = $conn->prepare("
    SELECT id_Producto, cantidad, precio_unitario
    FROM Pedido_Productos
    WHERE id_Pedido = ?
");
$stmtItems->bind_param("i", $order_id);
$stmtItems->execute();
$resItems = $stmtItems->get_result();

$cartTemp   = [];
$precios    = [];
while ($row = $resItems->fetch_assoc()) {
    $pid            = (int)$row['id_Producto'];
    $cartTemp[$pid] = (int)$row['cantidad'];
    $precios[$pid]  = floatval($row['precio_unitario']);
}
$stmtItems->close();

$out_total = 0.0;
$items     = obtener_items_carrito($conn, $cartTemp, $out_total);

$metodos = [
    'tarjeta'       => 'Tarjeta de crédito/débito',
    'paypal'        => 'PayPal',
    'transferencia' => 'Transferencia bancaria'
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pedido #<?= htmlspecialchars($pedido['numero_factura']) ?> · JoyasJewels</title>
  <link rel="shortcut icon" href="/web/assets/img/logo/favicon.ico" />
  <link rel="stylesheet" href="/web/assets/css/comprar.css" />
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

  <?php include '../includes/header.php'; ?>

  <main class="checkout-container">
    <section class="confirmation-container">
      <section class="confirmation-header">
        <i class="fas fa-check-circle confirmation-icon"></i>
        <h1 class="confirmation-title">¡Pedido Completado con Éxito!</h1>
        <article class="order-number-badge">
          <?= htmlspecialchars($pedido['numero_factura']) ?>
        </article>
      </section>

      <p class="confirmation-message">
        Gracias por tu compra en JoyasJewels. Tu pedido se registró el 
        <?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?> 
        y está siendo procesado.
      </p>

      <article class="email-notice">
        <i class="fas fa-envelope"></i>
        <section>
          <strong>Te hemos enviado un email con los detalles a:</strong>
          <p><strong><?= htmlspecialchars($pedido['email_cliente']) ?></strong></p>
        </section>
      </article>

      <section class="order-details-card">
        <h3 class="summary-title">Resumen del pedido</h3>
        <section class="detail-grid">
          <section>
            <article class="detail-item">
              <article class="detail-label">Método de pago</article>
              <article class="detail-value">
                <?= htmlspecialchars($metodos[$pedido['metodo_pago']] ?? $pedido['metodo_pago']) ?>
              </article>
            </article>
            <article class="detail-item">
              <article class="detail-label">IVA (21%)</article>
              <article class="detail-value">
                <?= number_format($pedido['iva'], 2, ',', '.') ?> €
              </article>
            </article>
            <article class="detail-item">
              <article class="detail-label">Total</article>
              <article class="detail-value" style="color: var(--color-detalle); font-weight:600;">
                <?= number_format($pedido['precio_total'], 2, ',', '.') ?> €
              </article>
            </article>
            <article class="detail-item">
              <article class="detail-label">Factura</article>
              <article class="detail-value">
                <?= htmlspecialchars($pedido['numero_factura']) ?>
              </article>
            </article>
          </section>

          <section>
            <article class="detail-item">
              <article class="detail-label">Dirección de envío</article>
              <article class="detail-value">
                <?= nl2br(htmlspecialchars($pedido['calle'])) ?><br>
                <?= htmlspecialchars($pedido['numero']) ?>, 
                <?= htmlspecialchars($pedido['piso']) ?><br>
                <?= htmlspecialchars($pedido['cod_postal']) ?>, 
                <?= htmlspecialchars($pedido['ciudad']) ?><br>
                <?= htmlspecialchars($pedido['provincia']) ?>, 
                <?= htmlspecialchars($pedido['pais']) ?>
              </article>
            </article>
          </section>
        </section>

        <h3 class="summary-title">Productos adquiridos</h3>
        <?php foreach ($items as $item): ?>
          <article class="product-summary">
            <img src="/web/<?= htmlspecialchars($item['imagen']) ?>"
                alt="<?= htmlspecialchars($item['nombre']) ?>">
            <article class="product-info">
              <article class="product-name"><?= htmlspecialchars($item['nombre']) ?></article>
              <article class="product-details">
                <?= $item['cantidad'] ?>&nbsp;×&nbsp;<?= number_format($item['precio'], 2, ',', '.') ?> €
                <span style="margin: 0 8px">•</span>
                Subtotal: <?= number_format($item['subtotal'], 2, ',', '.') ?> €
              </article>
            </article>
          </article>
        <?php endforeach; ?>
      </section>

      <section class="confirmation-actions">
        <a href="/web/products/tienda.php" class="action-btn primary-btn">
          <i class="fas fa-store"></i> Seguir Comprando
        </a>
        <a href="/web/users/perfil.php#orders" class="action-btn secondary-btn">
          <i class="fas fa-clipboard-list"></i> Ver Mis Pedidos
        </a>
        <?php if (!empty($pedido['numero_factura'])): ?>
          <a href="generar_factura.php?order_id=<?= $order_id ?>"
             class="action-btn secondary-btn">
            <i class="fas fa-file-pdf"></i> Descargar Factura
          </a>
        <?php endif; ?>
      </section>
    </section>
  </main>

  <?php include '../includes/footer.php'; ?>
  <script type="module" src="/web/assets/js/logo.js"></script>
</body>
</html>
