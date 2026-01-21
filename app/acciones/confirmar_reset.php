<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/db.php';         
require_once __DIR__ . '/../funciones/funciones.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $pass1 = $_POST['password'] ?? '';
    $pass2 = $_POST['confirm_password'] ?? '';

    // Validaciones básicas
    if (empty($pass1) || strlen($pass1) < 8 || $pass1 !== $pass2) {
        notificar('error', 'Error', 'Las contraseñas no coinciden o son muy cortas.');
        header("Location:" . BASE_URL . "reset_password?token=" . $token);
        exit;
    }

    if (actualizarPasswordConToken($token, $pass1, $conexion)) {
        notificar('success', '¡Éxito!', '¡Contraseña actualizada! Ya puedes iniciar sesión.');
        header("Location: /RFcomisiones/login");
        
    } else {
        notificar('error', 'Error', 'El enlace ha expirado.');
        header("Location: /RFcomisiones/solicitar_reset");

    }
    exit;
}



  