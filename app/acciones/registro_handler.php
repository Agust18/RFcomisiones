<?php
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location:" .BASE_URL."registro"); 
    exit;
}

//Recolección y saneamiento de datos
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$contraseña = $_POST['contraseña'] ?? ''; // Dejamos la contraseña como string
$contraseña_conf = $_POST['contraseña_conf'] ?? '';
$telefono = trim($_POST['telefono'] ?? '');

$error = false;

//Validaciones
if (empty($nombre) || empty($email) || empty($contraseña) || empty($contraseña_conf)) {
    notificar("error", "Error de registro", "Todos los campos obligatorios deben ser llenados.");
    $error = true;
}

if (!$error && $contraseña !== $contraseña_conf) {
    notificar("error", "Error de registro", "Las contraseñas no coinciden.");
    $error = true;
}

if (!$error && existe_email($email)) {
    notificar("error", "Error de registro", "El correo electrónico ya está registrado.");
    $error = true;
}

//Proceso de Registro
if (!$error) {
    if (registrar_Usuarios($nombre, $email, $contraseña, $telefono)) {
        // ÉXITO: Usamos notificar para que el componente lo vea
        notificar("success", "¡Registro exitoso!", "Ya puedes iniciar sesión con tu cuenta.");
        header("Location: " . BASE_URL . "login");
        exit;
    } else {
        // FALLO DB: También usamos notificar
        notificar("error", "Error de sistema", "No se pudo conectar con la base de datos.");
        $error = true; 
    }
}

//Redirección de error (si $error es true)
if ($error) {
    header("Location: " . BASE_URL . "registro");
    notificar("error", "Error de registro", "Por favor, corrige los errores e intenta nuevamente.");
    exit;
}
?>