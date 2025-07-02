<?php
include '../includes/db.php';
include '../includes/auth.php';

require_login();

$user_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM Usuarios WHERE id_Usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql = "SELECT * FROM Direcciones WHERE id_Usuario = ? ORDER BY direccion_principal DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT p.*, f.numero_factura
        FROM Pedidos p 
        LEFT JOIN Facturas f ON p.id_Pedido = f.id_Pedido 
        WHERE p.id_Usuario = ? 
        ORDER BY p.fecha_pedido DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil · JoyasJewels</title>
    <link rel="shortcut icon" href="../assets/img/logo/favicon.ico">
    <link rel="stylesheet" href="../assets/css/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>
    
    <main class="parent">
        <aside class="profile-sidebar">
            <section class="user-summary">
                <article class="user-avatar">
                    <?= strtoupper(substr($user['nombre'], 0, 1)) ?>
                </article>
                <h2 class="user-name"><?= htmlspecialchars($user['nombre']) ?></h2>
                <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
            </section>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#profile" class="nav-link active">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#addresses" class="nav-link">
                        <i class="fas fa-map-marker-alt"></i> Mis Direcciones
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#orders" class="nav-link">
                        <i class="fas fa-shopping-bag"></i> Mis Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#invoices" class="nav-link">
                        <i class="fas fa-file-invoice"></i> Mis Facturas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#security" class="nav-link">
                        <i class="fas fa-lock"></i> Seguridad
                    </a>
                </li>
            </ul>
            <a href="/web/users/logout.php" class="btn">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </aside>
        
        <section class="profile-content">
            <section id="profile">
                <h2 class="section-title">Mi Perfil</h2>
                
                <form action="edit_perfil.php" method="POST">
                    <section class="form-row">
                        <section class="form-col">
                            <section class="form-group">
                                <label for="nombre">Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" 
                                       value="<?= htmlspecialchars($user['nombre']) ?>" required>
                            </section>
                        </section>
                        <section class="form-col">
                            <section class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email']) ?>" required>
                            </section>
                        </section>
                    </section>
                    
                    <section class="form-group">
                        <label for="fecha_registro">Fecha de registro</label>
                        <input type="text" id="fecha_registro" 
                               value="<?= date('d/m/Y', strtotime($user['fecha_registro'])) ?>" readonly>
                    </section>
                    
                    <button type="submit" class="btn">Actualizar Información</button>
                </form>
            </section>
            
            <section id="addresses" style="display: none;">
                <h2 class="section-title">Mis Direcciones</h2>
                
                <h3 class="section-subtitle">Direcciones guardadas</h3>
                
                <?php if (count($addresses) > 0): ?>
                    <section class="address-grid">
                        <?php foreach ($addresses as $address): ?>
                            <section class="address-card <?= $address['direccion_principal'] ? 'primary' : '' ?>">
                                <section class="address-title">
                                    <span><?= htmlspecialchars($address['calle']) ?> <?= htmlspecialchars($address['numero']) ?></span>
                                    <?php if ($address['direccion_principal']): ?>
                                        <span class="primary-badge">Principal</span>
                                    <?php endif; ?>
                                </section>
                                
                                <section class="address-details">
                                    <p><?= htmlspecialchars($address['piso']) ?></p>
                                    <p><?= htmlspecialchars($address['cod_postal']) ?>, <?= htmlspecialchars($address['ciudad']) ?></p>
                                    <p><?= htmlspecialchars($address['provincia']) ?>, <?= htmlspecialchars($address['pais']) ?></p>
                              </section>
                                
                                <section class="address-actions">
                                    <a href="#" class="btn btn-sm btn-secondary edit-address" 
                                    data-id="<?= $address['id_Direccion'] ?>"
                                    data-calle="<?= htmlspecialchars($address['calle']) ?>"
                                    data-numero="<?= htmlspecialchars($address['numero']) ?>"
                                    data-piso="<?= htmlspecialchars($address['piso']) ?>"
                                    data-cod_postal="<?= htmlspecialchars($address['cod_postal']) ?>"
                                    data-ciudad="<?= htmlspecialchars($address['ciudad']) ?>"
                                    data-provincia="<?= htmlspecialchars($address['provincia']) ?>"
                                    data-pais="<?= htmlspecialchars($address['pais']) ?>"
                                    data-principal="<?= $address['direccion_principal'] ? '1' : '0' ?>"
                                    title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="eliminar_direc.php?id=<?= $address['id_Direccion'] ?>" class="btn btn-sm btn-danger" title="Eliminar"
                                    onclick="return confirm('¿Estás seguro de eliminar esta dirección?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </section>
                            </section>
                        <?php endforeach; ?>
                    </section>
                <?php else: ?>
                    <div class="message info">
                        <p>Aún no tienes direcciones guardadas. Agrega tu primera dirección a continuación.</p>
                    </div>
                <?php endif; ?>
                
                <h3 class="section-subtitle" id="form-title">Añadir nueva dirección</h3>
                
                <form class="address-form" action="add_direc.php" method="POST" id="address-form">
                    <input type="hidden" name="id" id="address-id">
                    
                    <section class="form-row">
                        <section class="form-col">
                            <section class="form-group">
                                <label for="calle">Calle</label>
                                <input type="text" id="calle" name="calle" required>
                            </section>
                        </section>
                        <section class="form-col">
                            <section class="form-group">
                                <label for="numero">Número</label>
                                <input type="text" id="numero" name="numero" required>
                            </section>
                        </section>
                    </section>
                    
                    <section class="form-row">
                        <section class="form-col">
                            <section class="form-group">
                                <label for="piso">Piso y Puerta</label>
                                <input type="text" id="piso" name="piso">
                            </section>
                        </section>
                        <section class="form-col">
                            <section class="form-group">
                                <label for="cod_postal">Código Postal</label>
                                <input type="text" id="cod_postal" name="cod_postal" required>
                            </section>
                        </section>
                    </section>
                    
                    <section class="form-row">
                        <section class="form-col">
                            <section class="form-group">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" id="ciudad" name="ciudad" required>
                            </section>
                        </section>
                        <section class="form-col">
                            <section class="form-group">
                                <label for="provincia">Provincia</label>
                                <input type="text" id="provincia" name="provincia" required>
                            </section>
                        </section>
                    </section>
                    
                    <section class="form-group">
                        <label for="pais">País</label>
                        <select id="pais" name="pais" required>
                            <option value="">Seleccionar país</option>
                            <option value="España">España</option>
                            <option value="Francia">Francia</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Italia">Italia</option>
                            <option value="Alemania">Alemania</option>
                            <option value="Reino Unido">Reino Unido</option>
                        </select>
                    </section>
                    
                    <section class="form-group">
                        <label>
                            <input type="checkbox" name="direccion_principal" id="direccion_principal"> 
                            Establecer como dirección principal
                        </label>
                    </section>
                    
                    <section class="form-actions">
                        <button type="submit" class="btn" id="save-btn">Guardar Dirección</button>
                        <button type="reset" class="btn btn-secondary" id="cancel-btn">Cancelar</button>
                    </section>
                </form>
            </section>
            
            <!-- Sección de pedidos -->
            <section id="orders" style="display: none;">
                <h2 class="section-title">Mis Pedidos</h2>
                
                <?php if (count($orders) > 0): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Nº Pedido</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Método de Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <?php
                                $status_class = 'status-' . $order['estado'];
                                $status_text = ucfirst($order['estado']);
                                ?>
                                <tr>
                                    <td>#<?= $order['id_Pedido'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($order['fecha_pedido'])) ?></td>
                                    <td><?= number_format($order['precio_total'], 2, ',', '.') ?> €</td>
                                    <td>
                                        <span class="status-badge <?= $status_class ?>">
                                            <?= $status_text ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $payment_methods = [
                                            'tarjeta' => 'Tarjeta',
                                            'paypal' => 'PayPal',
                                            'transferencia' => 'Transferencia'
                                        ];
                                        echo $payment_methods[$order['metodo_pago']] ?? 'Otro';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm">Ver Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <section class="message info">
                        <p>Aún no has realizado ningún pedido. <a href="/web/products/tienda.php">Explora nuestra colección</a> para encontrar joyas que te encantarán.</p>
                    </section>
                <?php endif; ?>
            </section>
            
            <!-- Sección de facturas -->
            <section id="invoices" style="display: none;">
                <h2 class="section-title">Mis Facturas</h2>
                
                <?php if (count($orders) > 0): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Nº Factura</th>
                                <th>Fecha</th>
                                <th>Importe</th>
                                <th>IVA</th>
                                <th>Pedido</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <?php if ($order['numero_factura']): ?>
                                    <tr>
                                        <td><?= $order['numero_factura'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($order['fecha_pedido'])) ?></td>
                                        <td><?= number_format($order['precio_total'], 2, ',', '.') ?> €</td>
                                        <td>21%</td>
                                        <td>#<?= $order['id_Pedido'] ?></td>
                                        <td>
                                        <?php if (!empty($order['numero_factura'])): ?>
                                            <a href="../products/generar_factura.php?order_id=<?= $order['id_Pedido'] ?>"
                                               class="action-btn secondary-btn">
                                                <i class="fas fa-file-pdf"></i> Descargar Factura
                                            </a>
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <section class="message info">
                        <p>No tienes facturas disponibles. Las facturas se generan automáticamente al completar un pedido.</p>
                    </section>
                <?php endif; ?>
            </section>
            
            <section id="security" style="display: none;">
                <h2 class="section-title">Seguridad de la Cuenta</h2>
                
                <form action="cambiar_password.php" method="POST">
                    <h3 class="section-subtitle">Cambiar contraseña</h3>
                    
                    <section class="form-row">
                        <section class="form-col">
                            <section class="form-group">
                                <label for="current_password">Contraseña actual</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </section>
                        </section>
                    </section>
                    
                    <section class="form-row">
                        <section class="form-col">
                            <section class="form-group">
                                <label for="new_password">Nueva contraseña</label>
                                <input type="password" id="new_password" name="new_password" required>
                            </section>
                        </section>
                        <section class="form-col">
                            <section class="form-group">
                                <label for="confirm_password">Confirmar nueva contraseña</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </section>
                        </section>
                    </section>
                    
                    <button type="submit" class="btn">Actualizar Contraseña</button>
                </form>
                
                <section class="message info" style="margin-top: 40px;">
                    <h3 class="section-subtitle">Sesiones activas</h3>
                    <p>Actualmente estás conectado desde este dispositivo.</p>
                    <p>Último acceso: <?= date('d/m/Y H:i') ?></p>
                    <button class="btn btn-secondary" style="margin-top: 15px;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar todas las sesiones
                    </button>
                </section>
            </section>
        </section>
    </main>
    
    <?php include '../includes/footer.php';?>
    
    <script type="module" src="../assets/js/logo.js"></script>
    <script type="module" src="../assets/js/perfil.js"></script>
</body>
</html>