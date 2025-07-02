<?php
if (!function_exists('obtener_items_carrito')) {
    function obtener_items_carrito(mysqli $conn, array $cart, float &$out_total): array {
        $out_total = 0.0;
        if (empty($cart)) {
            return [];
        }
        $ids = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "
            SELECT 
                p.id_Producto,
                p.nombre,
                p.precio,
                p.imagen_url AS imagen
            FROM Productos p
            WHERE p.id_Producto IN ($placeholders)
        ";
        $stmt = $conn->prepare($sql);
        $types = str_repeat('i', count($ids));
        $stmt->bind_param($types, ...$ids);
        $stmt->execute();
        $res = $stmt->get_result();

        $items = [];
        while ($row = $res->fetch_assoc()) {
            $pid = (int)$row['id_Producto'];
            $cantidad = (int)$cart[$pid];
            $row['cantidad'] = $cantidad;
            $row['subtotal'] = $cantidad * floatval($row['precio']);
            if (empty($row['imagen'])) {
                $row['imagen'] = '/web/assets/img/productos/default.jpg';
            }
            $out_total += $row['subtotal'];
            $items[] = $row;
        }
        $stmt->close();
        return $items;
    }
}

if (!function_exists('validar_campos_direccion')) {
    function validar_campos_direccion(array $post): array {
        $calle      = trim($post['calle']      ?? '');
        $numero     = trim($post['numero']     ?? '');
        $cod_postal = trim($post['cod_postal'] ?? '');
        $ciudad     = trim($post['ciudad']     ?? '');
        $provincia  = trim($post['provincia']  ?? '');
        $pais       = trim($post['pais']       ?? '');

        if ($calle === '' || $numero === '' || $cod_postal === '' ||
            $ciudad === '' || $provincia === '' || $pais === '') {
            return [false, 'Debes completar todos los campos de la nueva dirección.'];
        }
        return [true, ''];
    }
}

if (!function_exists('guardar_nueva_direccion')) {
    function guardar_nueva_direccion(mysqli $conn, int $userId, array $post): int {
        $calle      = trim($post['calle']      ?? '');
        $numero     = trim($post['numero']     ?? '');
        $piso       = trim($post['piso']       ?? '');
        $cod_postal = trim($post['cod_postal'] ?? '');
        $ciudad     = trim($post['ciudad']     ?? '');
        $provincia  = trim($post['provincia']  ?? '');
        $pais       = trim($post['pais']       ?? '');

        $sql = "
            INSERT INTO Direcciones 
                (calle, numero, piso, cod_postal, id_Usuario, ciudad, provincia, pais) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssissss",
            $calle,
            $numero,
            $piso,
            $cod_postal,
            $userId,
            $ciudad,
            $provincia,
            $pais
        );
        $stmt->execute();
        $newId = $conn->insert_id;
        $stmt->close();
        return $newId;
    }
}

if (!function_exists('verificar_direccion_propietario')) {

    function verificar_direccion_propietario(mysqli $conn, int $idDir, int $userId): bool {
        $sql = "SELECT COUNT(*) FROM Direcciones WHERE id_Direccion = ? AND id_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idDir, $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return ($count > 0);
    }
}

if (!function_exists(function: 'insertar_pedido')) {
    function insertar_pedido(mysqli $conn, int $userId, float $total, int $idDireccion, string $metodoPago): int {
        $sql = "
            INSERT INTO Pedidos 
              (id_Usuario, precio_total, id_Direccion, metodo_pago) 
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idis", $userId, $total, $idDireccion, $metodoPago);
        $stmt->execute();
        $newId = $conn->insert_id;
        $stmt->close();
        return $newId;
    }
}

if (!function_exists('insertar_items_y_reducir_stock')) {

    function insertar_items_y_reducir_stock(mysqli $conn, int $idPedido, array $cart, array $precios): void {
        $sqlItem = "
            INSERT INTO Pedido_Productos 
              (id_Pedido, id_Producto, cantidad, precio_unitario)
            VALUES (?, ?, ?, ?)
        ";
        $stmtItem = $conn->prepare($sqlItem);

        $sqlStock = "
            UPDATE Productos
            SET stock = stock - ?
            WHERE id_Producto = ?
        ";
        $stmtStock = $conn->prepare($sqlStock);

        foreach ($cart as $pid => $cant) {
            $precioUnit = floatval($precios[$pid] ?? 0);
            $stmtItem->bind_param("iiii", $idPedido, $pid, $cant, $precioUnit);
            $stmtItem->execute();
            $stmtStock->bind_param("ii", $cant, $pid);
            $stmtStock->execute();
        }
        $stmtItem->close();
        $stmtStock->close();
    }
}

if (!function_exists('insertar_factura')) {
    function insertar_factura(
        mysqli $conn,
        int   $idPedido,
        float $total,
        string $nombreCliente,
        string $telefonoCliente,
        string $emailCliente
    ): int {
        $iva        = round($total * 0.21, 2);
        $numeroFact = 'F-' . str_pad($idPedido, 6, '0', STR_PAD_LEFT);

        $sql = "
            INSERT INTO Facturas
              (id_Pedido, numero_factura, iva, total,
               nombre_cliente, telefono_cliente, email_cliente)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isddsss",
            $idPedido,
            $numeroFact,
            $iva,
            $total,
            $nombreCliente,
            $telefonoCliente,
            $emailCliente
        );
        $stmt->execute();
        $newId = $conn->insert_id;
        $stmt->close();
        return $newId;
    }
}


if (!function_exists('obtener_direcciones_usuario')) {

    function obtener_direcciones_usuario(mysqli $conn, int $userId): array {
        $sql = "
            SELECT 
              id_Direccion,
              calle,
              numero,
              piso,
              cod_postal,
              ciudad,
              provincia,
              pais,
              direccion_principal
            FROM Direcciones
            WHERE id_Usuario = ?
            ORDER BY direccion_principal DESC, id_Direccion ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $dirs = [];
        while ($row = $res->fetch_assoc()) {
            $dirs[] = $row;
        }
        $stmt->close();
        return $dirs;
    }
}
