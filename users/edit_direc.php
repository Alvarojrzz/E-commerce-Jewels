<?php
session_start();
include '../includes/db.php';
include '../includes/auth.php';

require_login();

$user_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address_id = (int)$_POST['id'];
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

    // Verificar que la dirección pertenece al usuario
    $sql = "SELECT id_Direccion FROM Direcciones WHERE id_Direccion = ? AND id_Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $address_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = 'Dirección no encontrada o no tienes permiso para editarla.';
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

    // Actualizar la dirección
    $sql = "UPDATE Direcciones 
            SET calle = ?, numero = ?, piso = ?, cod_postal = ?, ciudad = ?, provincia = ?, pais = ?, direccion_principal = ?
            WHERE id_Direccion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssii', $calle, $numero, $piso, $cod_postal, $ciudad, $provincia, $pais, $direccion_principal, $address_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Dirección actualizada correctamente.';
    } else {
        $_SESSION['error'] = 'Error al actualizar la dirección.';
    }

    $stmt->close();
}

header('Location: perfil.php#addresses');
exit;
?>