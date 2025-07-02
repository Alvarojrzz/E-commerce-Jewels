<?php
session_start();
include '../includes/db.php';
include '../includes/auth.php';

require_login();

$user_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);

    if (empty($nombre) || empty($email)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos.';
        header('Location: perfil.php');
        exit;
    }

    $sql = "SELECT id_Usuario FROM Usuarios WHERE email = ? AND id_Usuario != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'El correo electrónico ya está en uso por otro usuario.';
        header('Location: perfil.php');
        exit;
    }

    $sql = "UPDATE Usuarios SET nombre = ?, email = ? WHERE id_Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $nombre, $email, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Perfil actualizado correctamente.';
    } else {
        $_SESSION['error'] = 'Error al actualizar el perfil.';
    }

    $stmt->close();
}

header('Location: perfil.php');
exit;
?>