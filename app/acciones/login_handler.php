<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Verifica que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //con trim limpiamos espacios
    $email = trim($_POST['email'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');

    // Llamada a la función de verificación (Asumimos que está definida)
    //  asegurarse de que verificarCredenciales() retorne FALSE si falla.
    $datosUsuarios = verificarCredenciales($email, $contraseña);

    if ($datosUsuarios) {
        //  ASIGNACIÓN DE SESIÓN 
        $_SESSION['id_usuario'] = $datosUsuarios['id'];
        $_SESSION['nombre'] = $datosUsuarios['nombre'];
        $_SESSION['rol'] = strtolower($datosUsuarios['rol']); 
        $_SESSION['autenticado'] = true;

        $rol = $_SESSION['rol']; // Usamos el valor guardado
        
        //  definimos mensaje de éxito
        notificar('success', '¡Bienvenido, ' . htmlspecialchars($datosUsuarios['nombre']) . '!', 'Has iniciado sesión correctamente.');
       
        
        // Lógica de Redirección
        if ($rol === 'administrador' || $rol === 'comisionista') {
            $pagina_destino = 'comisionista'; 
        
        } elseif ($rol === 'cliente') {
            $pagina_destino = 'cliente';
        
        } else {
            $pagina_destino = 'inicio'; 
        }
        
        header("Location:" .BASE_URL . $pagina_destino);
        exit();
        
    } else {
        // Manejo de error de credenciales
        notificar('error', 'Error de inicio de sesión', 'Datos incorrectas o usuario no encontrado. Por favor, inténtalo de nuevo.');
        header("Location:" . BASE_URL . "login"); // Redirige de vuelta al formulario de login
        exit();
    }
} else {
    // Si acceden por GET, redirigir al login
    notificar('error', 'Acceso no autorizado', 'Por favor, utiliza el formulario de inicio de sesión para acceder.');
    header("Location:". BASE_URL."login");
    exit();
}