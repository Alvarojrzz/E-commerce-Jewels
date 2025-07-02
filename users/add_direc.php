<?php
session_start();
include '../includes/db.php';
include '../includes/auth.php';

require_login();

$user_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $calle = trim($_POST['calle']);
    $numero = trim($_POST['numero']);
    $piso = trim($_POST['piso'] ?? '');
    $cod_postal = trim($_POST['cod_postal']);
    $ciudad = trim($_POST['ciudad']);
    $provincia = trim($_POST['provincia']);
    $pais = trim($_POST['pais']);
    $direccion_principal = isset($_POST['direccion_principal']) ? 1 : 0;

    // Validar datos
    if (empty($calle) || empty($numero) || empty($cod_postal) || empty($ciudad) || empty($provincia) || empty($pais)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos obligatorios.';
        header('Location: perfil.php#addresses');
        exit;
    }

    // Si se marca como principal, quitar la principal actual
    if ($direccion_principal) {
        $sql = "UPDATE Direcciones SET direccion_principal = 0 WHERE id_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Insertar nueva dirección
    $sql = "INSERT INTO Direcciones (calle, numero, piso, cod_postal, id_Usuario, ciudad, provincia, pais, direccion_principal) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssisssi', $calle, $numero, $piso, $cod_postal, $user_id, $ciudad, $provincia, $pais, $direccion_principal);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Dirección añadida correctamente.';
    } else {
        $_SESSION['error'] = 'Error al añadir la dirección.';
    }

    $stmt->close();
}

header('Location: perfil.php#addresses');
exit;
?>