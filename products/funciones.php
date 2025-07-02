<?php

function get_productos() {
    global $conn;
    $sql = "SELECT p.id_Producto as id, p.nombre, p.descripcion, p.precio, p.stock, p.quilates, p.material, p.peso_gramos, p.dimensiones, p.sku, p.disponible, 
                   p.imagen_url, c.nombre as categoria, c.slug as slug_categoria
            FROM Productos p 
            INNER JOIN Categorias c ON p.id_Categoria = c.id_Categoria
            WHERE p.stock > 0";
    $result = mysqli_query($conn, $sql);
    $productos = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['imagen'] = $row['imagen_url'] ?? '/web/assets/img/productos/default.jpg';
            unset($row['imagen_url']);
            $productos[] = $row;
        }
    } 
    return $productos;
}

function get_categorias() {
    global $conn;
    $sql = "SELECT id_Categoria as id, nombre, slug, imagen as imagen_db FROM Categorias";
    $result = mysqli_query($conn, $sql);
    $categorias = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['imagen'] = $row['imagen_db'] ?? '/web/assets/img/productos/default.jpg';
            unset($row['imagen_db']); 
            $categorias[] = $row;
        }
    } 
    return $categorias;
}


function get_productos_por_categoria($categoria_id) {
    global $conn;
    $categoria_id_escaped = mysqli_real_escape_string($conn, $categoria_id);
    $sql = "SELECT p.id_Producto as id, p.nombre, p.descripcion, p.precio, p.stock, p.quilates, p.material, p.peso_gramos, p.dimensiones, p.sku, p.disponible, 
                   p.imagen_url, c.nombre as categoria, c.slug as slug_categoria
            FROM Productos p 
            INNER JOIN Categorias c ON p.id_Categoria = c.id_Categoria 
            WHERE p.stock > 0 AND p.id_Categoria = '$categoria_id_escaped'";
    $result = mysqli_query($conn, $sql);
    $productos = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['imagen'] = $row['imagen_url'] ?? '/web/assets/img/productos/default.jpg';
            unset($row['imagen_url']);
            $productos[] = $row;
        }
    }
    return $productos;
}


function get_producto_por_id($producto_id) {
    global $conn;
    $producto_id_escaped = mysqli_real_escape_string($conn, $producto_id);
    $sql = "SELECT p.id_Producto as id, p.nombre, p.descripcion, p.precio, p.stock, p.quilates, p.material, p.peso_gramos, p.dimensiones, p.sku, p.disponible, 
                   p.imagen_url, c.nombre as categoria, c.slug as slug_categoria
            FROM Productos p 
            INNER JOIN Categorias c ON p.id_Categoria = c.id_Categoria 
            WHERE p.stock > 0 AND p.id_Producto = '$producto_id_escaped'";
    $sql = "SELECT p.id_Producto as id, p.nombre, p.descripcion, p.precio, p.stock, p.quilates, p.material, p.peso_gramos, p.dimensiones, p.sku, p.disponible, 
                   p.imagen_url, c.nombre as categoria, c.slug as slug_categoria
            FROM Productos p 
            INNER JOIN Categorias c ON p.id_Categoria = c.id_Categoria 
            WHERE p.stock > 0 AND p.id_Producto = '$producto_id_escaped'";
    $result = mysqli_query($conn, $sql);
    $producto = null;
    if ($result && mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
        $producto['imagen'] = $producto['imagen_url'] ?? '/web/assets/img/productos/default.jpg';
        unset($producto['imagen_url']);
    }   
    return $producto;
}


function get_productos_relacionados($categoria_nombre, $producto_id_excluir = null, $limite = 4) {
    global $conn;
    $categoria_nombre_escaped = mysqli_real_escape_string($conn, $categoria_nombre);
    $filtro_producto = "";
    if ($producto_id_excluir !== null) {
        $producto_id_excluir_escaped = mysqli_real_escape_string($conn, $producto_id_excluir);
        $filtro_producto = "AND p.id_Producto != '$producto_id_excluir_escaped'";
    }

    $limite_escaped = (int)$limite;
    $sql = "SELECT p.id_Producto as id, p.nombre, p.descripcion, p.precio, p.imagen_url
            FROM Productos p
            INNER JOIN Categorias c ON p.id_Categoria = c.id_Categoria
            WHERE p.stock > 0 AND c.nombre = '$categoria_nombre_escaped' $filtro_producto
            LIMIT $limite_escaped";
    
    $result = mysqli_query($conn, $sql);
    $productos = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['imagen'] = $row['imagen_url'] ?? '/web/assets/img/productos/default.jpg';
            unset($row['imagen_url']);
            $productos[] = $row;
        }
    }
    return $productos;
}

?>