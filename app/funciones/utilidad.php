<?php
function verificar_acceso($rol_requerido) {
    // Usar la clave de sesión correcta ('rol') que establecimos en login_handler.php
    $rol_actual = $_SESSION['rol'] ?? 'invitado';
    $acceso_permitido = false;
    // Lógica para roles operativos (Comisionista y Admin)
    if ($rol_requerido === 'comisionista') {
        // Un administrador también puede hacer tareas de comisionista
        if ($rol_actual === 'comisionista' || $rol_actual === 'administrador') {
            $acceso_permitido = true;
        }
    }
    // Lógica para rol de administrador (estrictamente 'administrador')
    elseif ($rol_requerido === 'administrador') {
        if ($rol_actual === 'administrador') {
            $acceso_permitido = true;
        }
    }
    // Lógica para el rol 'cliente'
    elseif ($rol_requerido === 'cliente') {
        if ($rol_actual === 'cliente') {
            $acceso_permitido = true;
        }
    }
    // Nota: Otros roles (como 'invitado') 
    // también deben ser bloqueados por el default de !$acceso_permitido
    if (!$acceso_permitido) {
        // Usamos $_SESSION['error'] en lugar de un parámetro GET
        notificar('error', 'Acceso denegado', 'No tienes permisos para esta sección.');
       
        
        // Redirigir al login (limpiando la URL del error que viste)
        header("Location:".BASE_URL."login");
        exit;
    }
    // Si el acceso es permitido, la ejecución del script continúa.
}
function cerrarSession() {
    //forzamos inicio de sesión para poder destruirla
    if (session_status() === PHP_SESSION_NONE) { session_start(); }

    //limpieza total
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();

    // respuesta para el JS
    //si detectamos que es Fetch, mandamos el JSON y CORTAMOS
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['REQUEST_METHOD'] === 'POST') {
        ob_clean(); // Borramos cualquier basura HTML
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit(); // ESTO ES LO QUE "GUARDA" EL CIERRE
    }
    //si entran por URL manual
    header("Location:".BASE_URL."login");
    exit();
}



function notificar($tipo, $titulo, $mensaje, $tiempo = 3000) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    $_SESSION['notificacion'] = [
        'icon'   => $tipo,    // 'success' o 'error'
        'title'  => $titulo,  
        'text'   => $mensaje,
        'timer'  => $tiempo
    ];
}