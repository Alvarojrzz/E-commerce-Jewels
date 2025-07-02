<?php

function redirect_after_login() {
    if (!empty($_SESSION['redirect_after_login'])) {
        $dest = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
    } else {
        $dest = '../index.php';
    }
    header("Location: $dest");
    exit;
}

function registrar_usuario($conn, $nombre, $email, $password) {
    $stmt = $conn->prepare("SELECT id_Usuario FROM Usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return "El correo ya está registrado.";
    }
    $stmt->close();

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO Usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $hash);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: /web/users/log.php"); 
        exit;
    } else {
        $stmt->close();
        return "Error al registrar usuario.";
    }
}


function iniciar_sesion($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT id_Usuario, nombre, password FROM Usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['usuario'] = $user['nombre'];
            $_SESSION['usuario_id'] = $user['id_Usuario'];
            redirect_after_login();
        } else {
            return "Contraseña incorrecta.";
        }
    } else {
        return "Usuario no encontrado.";
    }
}
