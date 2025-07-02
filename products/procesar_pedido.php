<?php
include '../includes/db.php';
include '../includes/auth.php';
include 'pedidos_func.php';
require_login();

$userId = $_SESSION['usuario_id'];
$cart   = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    $_SESSION['checkout_error'] = 'Tu carrito está vacío.';
    header('Location: comprar.php');
    exit;
}

$ids          = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$sqlPrec      = "SELECT id_Producto, precio FROM Productos WHERE id_Producto IN ($placeholders)";
$stmtPrec     = $conn->prepare($sqlPrec);
$stmtPrec->bind_param(str_repeat('i', count($ids)), ...$ids);
$stmtPrec->execute();
$resPrec = $stmtPrec->get_result();

$precios = [];
while ($row = $resPrec->fetch_assoc()) {
    $precios[$row['id_Producto']] = floatval($row['precio']);
}
$stmtPrec->close();

$usar_dir = $_POST['usar_direccion_id'] ?? '';
if ($usar_dir === '') {
    $_SESSION['checkout_error'] = 'Debes seleccionar o escribir una dirección.';
    $_SESSION['checkout_old']   = $_POST;
    header('Location: comprar.php');
    exit;
}

$idDireccion = null;
if ($usar_dir === 'nueva') {
    [$ok, $msg] = validar_campos_direccion($_POST);
    if (!$ok) {
        $_SESSION['checkout_error'] = $msg;
        $_SESSION['checkout_old']   = $_POST;
        header('Location: comprar.php');
        exit;
    }
    $idDireccion = guardar_nueva_direccion($conn, $userId, $_POST);
} else {
    if (!verificar_direccion_propietario($conn, intval($usar_dir), $userId)) {
        $_SESSION['checkout_error'] = 'La dirección seleccionada no es válida.';
        $_SESSION['checkout_old']   = $_POST;
        header('Location: comprar.php');
        exit;
    }
    $idDireccion = intval($usar_dir);
}

$nombre  = trim($_POST['nombre']  ?? '');
$telefono= trim($_POST['telefono'] ?? '');
$email   = trim($_POST['email']    ?? '');
$metodo_pago = $_POST['metodo_pago'] ?? '';
if ($telefono === '' || $email === '' || $metodo_pago === '') {
    $_SESSION['checkout_error'] = 'Debes completar teléfono, email y método de pago.';
    $_SESSION['checkout_old']   = $_POST;
    header('Location: comprar.php');
    exit;
}

$total = 0.0;
foreach ($cart as $pid => $cant) {
    $total += $cant * ($precios[$pid] ?? 0.0);
}

$idPedido = insertar_pedido($conn, $userId, $total, $idDireccion, $metodo_pago);
insertar_items_y_reducir_stock($conn, $idPedido, $cart, $precios);

$idFactura = insertar_factura($conn, $idPedido, $total, $nombre, $telefono, $email);

unset($_SESSION['cart']);
header("Location: confirmacion.php?order_id=$idPedido");
exit;
