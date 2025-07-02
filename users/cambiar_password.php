<?php
session_start();
include '../includes/db.php';
include '../includes/auth.php';

require_login();

$user_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar que las contraseñas coincidan
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = 'Las nuevas contraseñas no coinciden.';
        header('Location: perfil.php#security');
        exit;
    }

    // Obtener la contraseña actual del usuario
    $sql = "SELECT password FROM Usuarios WHERE id_Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verificar la contraseña actual
    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['error'] = 'La contraseña actual es incorrecta.';
        header('Location: perfil.php#security');
        exit;
    }

    // Actualizar la contraseña
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE Usuarios SET password = ? WHERE id_Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $hashed_password, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Contraseña actualizada correctamente.';
    } else {
        $_SESSION['error'] = 'Error al actualizar la contraseña.';
    }

    $stmt->close();
}

header('Location: perfil.php#security');
exit;
?>