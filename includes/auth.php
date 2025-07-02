<?php

function require_login() {
    session_start();
    if (empty($_SESSION['usuario'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . 'users/log.php');
        exit;
    }
}
?>
