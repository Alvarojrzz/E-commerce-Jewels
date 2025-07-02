<?php
include '../includes/db.php';
include '../includes/auth.php';

require_login();

$user_id = $_SESSION['usuario_id'];

if (isset($_GET['id'])) {
    $address_id = (int)$_GET['id'];

    $sql = "SELECT id_Direccion FROM Direcciones WHERE id_Direccion = ? AND id_Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $address_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = 'Dirección no encontrada o no tienes permiso para eliminarla.';
        header('Location: perfil.php#addresses');
        exit;
    }

    $sql = "DELETE FROM Direcciones WHERE id_Direccion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $address_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Dirección eliminada correctamente.';
    } else {
        $_SESSION['error'] = 'Error al eliminar la dirección.';
    }

    $stmt->close();
}

header('Location: perfil.php#addresses');
exit;
?>