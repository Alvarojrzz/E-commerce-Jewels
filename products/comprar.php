<?php
include '../includes/db.php';
include '../includes/auth.php';
require_login();

$total = 0.0;

include 'pedidos_func.php';

$userId = $_SESSION['usuario_id'];
$cart   = $_SESSION['cart'] ?? [];

$items = obtener_items_carrito($conn, $cart, $total);

$error = $_SESSION['checkout_error'] ?? '';
unset($_SESSION['checkout_error']);
$old   = $_SESSION['checkout_old']   ?? [];
unset($_SESSION['checkout_old']);

function old($key) {
    global $old;
    return htmlspecialchars($old[$key] ?? '');
}
function checked($key, $value) {
    global $old;
    return (isset($old[$key]) && $old[$key] === $value) ? 'checked' : '';
}

$direcciones = obtener_direcciones_usuario($conn, $userId);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tramitar pedido · JoyasJewels</title>
  <link rel="shortcut icon" href="/web/assets/img/logo/favicon.ico" />
  <link rel="stylesheet" href="/web/assets/css/comprar.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <main class="checkout-container">
    <h1>Finalizar pedido</h1>

    <?php if ($error !== ''): ?>
      <section class="message error"><?= htmlspecialchars($error) ?></section>
    <?php endif; ?>

    <section class="checkout-grid">
      <section class="order-summary">
        <h2>Resumen de tu pedido</h2>
        <?php if (empty($items)): ?>
          <p>Tu carrito está vacío. <a href="/web/products/tienda.php">Ir a la tienda</a>.</p>
        <?php else: ?>
          <table>
            <thead>
              <tr>
                <th style="width:50%;">Producto</th>
                <th style="width:15%;">Precio</th>
                <th style="width:10%;">Cantidad</th>
                <th style="width:15%;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item): ?>
                <tr>
                  <td>
                    <img
                      src="/web/<?= htmlspecialchars($item['imagen']) ?>"
                      alt="<?= htmlspecialchars($item['nombre']) ?>"
                      style="width:50px; height:50px; object-fit:cover; border-radius:4px;
                             margin-right:8px; vertical-align:middle;">
                    <?= htmlspecialchars($item['nombre']) ?>
                  </td>
                  <td><?= number_format($item['precio'], 2, ',', '.') ?> €</td>
                  <td><?= $item['cantidad'] ?></td>
                  <td><?= number_format($item['subtotal'], 2, ',', '.') ?> €</td>
                </tr>
              <?php endforeach; ?>
              <tr class="total-row">
                <td colspan="3" style="text-align:right;">Total:</td>
                <td style="color: var(--color-detalle); font-weight:600;">
                  <?= number_format($total, 2, ',', '.') ?> €
                </td>
              </tr>
            </tbody>
          </table>
        <?php endif; ?>
      </section>

      <section class="delivery-form">
        <div class="section-card fade-in">
          <h2 class="section-title">Datos de envío / facturación</h2>

          <form action="procesar_pedido.php" method="post">
            <?php if (count($direcciones) > 0): ?>
              <fieldset>
                <legend style="margin-bottom: 0.5rem; font-weight: 600; color: #444;">Elige una dirección guardada o crea una nueva:</legend>
                <div class="direcciones-container">
                  <?php foreach ($direcciones as $dir): 
                        $textoDir = 
                            htmlspecialchars($dir['calle']) . ", " . 
                            htmlspecialchars($dir['numero']) .
                            ($dir['piso'] !== '' ? " (piso ".htmlspecialchars($dir['piso']).")" : "") . " - " .
                            htmlspecialchars($dir['cod_postal']) . ", " .
                            htmlspecialchars($dir['ciudad']) . " (" .
                            htmlspecialchars($dir['provincia']) . "), " .
                            htmlspecialchars($dir['pais']);
                        if (isset($old['usar_direccion_id'])) {
                          $selected = ($old['usar_direccion_id'] == $dir['id_Direccion']);
                        } else {
                          $selected = $dir['direccion_principal'];
                        }
                  ?>
                    <label class="direccion-card <?= $selected ? 'selected' : '' ?>">
                      <input type="radio" 
                            name="usar_direccion_id" 
                            value="<?= $dir['id_Direccion'] ?>" 
                            <?= $selected ? 'checked' : '' ?> 
                            style="display: none;">
                      <h3><?= htmlspecialchars($dir['nombre_direccion'] ?? 'Mi dirección') ?></h3>
                      <p><?= $textoDir ?></p>
                      <?php if ($dir['direccion_principal']): ?>
                        <span class="principal-badge">Principal</span>
                      <?php endif; ?>
                    </label>
                  <?php endforeach; ?>

                  <label class="direccion-card <?= (isset($old['usar_direccion_id']) && $old['usar_direccion_id'] === 'nueva' ? 'selected' : '')?>">
                    <input type="radio" 
                          name="usar_direccion_id" 
                          value="nueva"
                          <?= (isset($old['usar_direccion_id']) && $old['usar_direccion_id'] === 'nueva') ? 'checked' : '' ?>
                          style="display: none;">
                    <h3><i class="fas fa-plus-circle"></i> Nueva dirección</h3>
                    <p>Crear una nueva dirección de envío</p>
                  </label>
                </div>
              </fieldset>
            <?php endif; ?>

            <div id="nueva-direccion-campos"
                style="<?php 
                  if (count($direcciones) > 0 && 
                      (!isset($old['usar_direccion_id']) || $old['usar_direccion_id'] !== 'nueva')) {
                    echo 'display:none;';
                  }
                ?>"
                class="fade-in">
              
              <h3 style="margin-top: 1.5rem; color: var(--color-primario);">Detalles de la nueva dirección</h3>
              
              <div class="form-grid">
                <div class="form-group">
                  <label for="nombre">Nombre completo *</label>
                  <input type="text" name="nombre" id="nombre" data-requerido="true" required value="<?= old('nombre') ?>">
                </div>
                
                <div class="form-group">
                  <label for="calle">Calle *</label>
                  <input type="text" name="calle" id="calle" data-requerido="true" required value="<?= old('calle') ?>">
                </div>
                
                <div class="form-group">
                  <label for="numero">Número *</label>
                  <input type="text" name="numero" id="numero" data-requerido="true" required value="<?= old('numero') ?>">
                </div>
                
                <div class="form-group">
                  <label for="piso">Piso / Escalera (opcional)</label>
                  <input type="text" name="piso" id="piso" value="<?= old('piso') ?>">
                </div>
                
                <div class="form-group">
                  <label for="cod_postal">Código Postal *</label>
                  <input type="text" name="cod_postal" id="cod_postal" data-requerido="true" required value="<?= old('cod_postal') ?>">
                </div>
                
                <div class="form-group">
                  <label for="ciudad">Ciudad *</label>
                  <input type="text" name="ciudad" id="ciudad" data-requerido="true" required value="<?= old('ciudad') ?>">
                </div>
                
                <div class="form-group">
                  <label for="provincia">Provincia *</label>
                  <input type="text" name="provincia" id="provincia" data-requerido="true" required value="<?= old('provincia') ?>">
                </div>
                
                <div class="form-group">
                  <label for="pais">País *</label>
                  <select name="pais" id="pais" data-requerido="true" required>
                    <option value="">--Selecciona--</option>
                    <?php
                      $paises = [
                        'España','Francia','Italia','Alemania','Reino Unido',
                        'Portugal','Grecia','Australia','Nueva Zelanda','Japón',
                        'Corea del Sur','China','India','Brasil','Argentina',
                        'Colombia','Perú','Chile','Otro'
                      ];
                      foreach ($paises as $p) {
                        $sel = (isset($old['pais']) && $old['pais'] === $p) ? 'selected' : '';
                        echo "<option value=\"$p\" $sel>$p</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="section-card fade-in" style="margin-top: 1.5rem;">
              <h2 class="section-title">Datos de facturación</h2>
              
              <div class="form-grid">
                <div class="form-group">
                  <label for="nombre_factura">Nombre *</label>
                  <input type="text" name="nombre" id="nombre_factura" required value="<?= old('nombre') ?>">
                </div>
                
                <div class="form-group">
                  <label for="telefono">Teléfono *</label>
                  <input type="tel" name="telefono" id="telefono" required value="<?= old('telefono') ?>">
                </div>
                
                <div class="form-group">
                  <label for="email">Email *</label>
                  <input type="email" name="email" id="email" required value="<?= old('email') ?>">
                </div>
              </div>
            </div>

            <div class="section-card fade-in">
              <h2 class="section-title">Método de pago</h2>
              
              <div class="metodos-container">
                <label class="metodo-card <?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'tarjeta') || !isset($old['metodo_pago']) ? 'selected' : '' ?>">
                  <input type="radio" name="metodo_pago" value="tarjeta" 
                         <?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'tarjeta') || !isset($old['metodo_pago']) ? 'checked' : '' ?> 
                         style="display: none;">
                  <span class="icon"><i class="fas fa-credit-card"></i></span>
                  <span class="card-label">Tarjeta de crédito/débito</span>
                </label>
                
                <label class="metodo-card <?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'paypal') ? 'selected' : '' ?>">
                  <input type="radio" name="metodo_pago" value="paypal" 
                         <?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'paypal') ? 'checked' : '' ?> 
                         style="display: none;">
                  <span class="icon"><i class="fab fa-paypal"></i></span>
                  <span class="card-label">PayPal</span>
                </label>
                
                <label class="metodo-card <?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'transferencia') ? 'selected' : '' ?>">
                  <input type="radio" name="metodo_pago" value="transferencia" 
                         <?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'transferencia') ? 'checked' : '' ?> 
                         style="display: none;">
                  <span class="icon"><i class="fas fa-university"></i></span>
                  <span class="card-label">Transferencia bancaria</span>
                </label>
              </div>
              
              <div id="datos-tarjeta" class="datos-extra fade-in" 
                   style="<?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'tarjeta') || !isset($old['metodo_pago']) ? '' : 'display:none;' ?>">
                <h3>Datos de tarjeta</h3>
                
                <div class="form-grid">
                  <div class="form-group">
                    <label for="card_number">Número de tarjeta *</label>
                    <input type="text" name="card_number" id="card_number" maxlength="19" value="<?= old('card_number') ?>">
                  </div>
                  
                  <div class="form-group">
                    <label for="card_expiry">Fecha de expiración (MM/AA) *</label>
                    <input type="text" name="card_expiry" id="card_expiry" maxlength="5" placeholder="MM/AA" value="<?= old('card_expiry') ?>">
                  </div>
                  
                  <div class="form-group">
                    <label for="card_cvv">CVV *</label>
                    <input type="text" name="card_cvv" id="card_cvv" maxlength="4" value="<?= old('card_cvv') ?>">
                  </div>
                </div>
              </div>
              
              <div id="datos-paypal" class="datos-extra fade-in" 
                   style="<?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'paypal') ? '' : 'display:none;' ?>">
                <h3>Datos de PayPal</h3>
                
                <div class="form-group">
                  <label for="paypal_email">Correo de PayPal *</label>
                  <input type="email" name="paypal_email" id="paypal_email" value="<?= old('paypal_email') ?>">
                </div>
              </div>
              
              <div id="datos-transferencia" class="datos-extra fade-in" 
                   style="<?= (isset($old['metodo_pago']) && $old['metodo_pago'] === 'transferencia') ? '' : 'display:none;' ?>">
                <h3>Datos de transferencia bancaria</h3>
                
                <div class="form-grid">
                  <div class="form-group">
                    <label for="iban">IBAN *</label>
                    <input type="text" name="iban" id="iban" maxlength="34" value="<?= old('iban') ?>">
                  </div>
                  
                  <div class="form-group">
                    <label for="titular_cuenta">Titular de la cuenta *</label>
                    <input type="text" name="titular_cuenta" id="titular_cuenta" value="<?= old('titular_cuenta') ?>">
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" name="finalizar_pedido" 
                    style="margin-top: 2rem; padding: 1rem 2rem; background: var(--color-primario); 
                           color: white; border: none; border-radius: 6px; cursor: pointer; 
                           font-size: 1.1rem; font-weight: 600; width: 100%;">
              Confirmar Pedido
            </button>
          </form>
        </div>
      </section>
    </section>
  </main>

  <?php include '../includes/footer.php'; ?>

  <script type="module" src="/web/assets/js/logo.js"></script>
  <script type="module" src="/web/assets/js/comprar.js"></script>  
</body>
</html>