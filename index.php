<?php
// 1. CONFIGURACIÓN DE SEGURIDAD Y SESIÓN
$opciones_sesion = [
    'cookie_lifetime' => 86400,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
];
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $opciones_sesion['cookie_secure'] = true;
}
if (session_status() === PHP_SESSION_NONE) {
    session_start($opciones_sesion);
}

// 2. CARGA DE DEPENDENCIAS
require_once __DIR__ . '/app/config/db.php'; // Aquí asumo que ya tienes BASE_URL definido
require_once __DIR__ . '/app/funciones/funciones.php';
require_once __DIR__ . '/app/funciones/utilidad.php';
require_once __DIR__ . '/app/funciones/alertas.php';

// --- 3. EL MOTOR DE RUTAS (ROUTING) ---
// Capturamos 'pagina' que viene del .htaccess (ej: "cliente/Asignado")
$url_input = $_GET['pagina'] ?? 'inicio';
$url_input = rtrim($url_input, '/');
$partes = explode('/', $url_input);

// Definimos la vista y los parámetros
$vista = $partes[0]; 

// Si la URL es "cliente/Asignado", $partes[1] es "Asignado"
// Lo inyectamos en $_GET para no romper tus archivos actuales
if (isset($partes[1])) {
    $_GET['estado'] = $partes[1];
}
// ---------------------------------------

$ruta_vista = '';
$paginas_modal = ['detalle_pedido', 'form_editar_pedido', 'ver_detalle_cliente'];
$paginas_sin_navbar = ['inicio', 'login', 'registro', 'politicas-de-privacidad', 'terminos-y-condiciones', 'como-funciona', ''];

// 4. EL SELECTOR DE CONTENIDO (SWITCH)
switch ($vista) {
    case 'inicio':      $ruta_vista = 'app/vistas/publicas/inicio.php'; break;
    case 'login':       $ruta_vista = 'app/vistas/publicas/login.php'; break;
    case 'registro':    $ruta_vista = 'app/vistas/publicas/registro.php'; break;
    case 'como-funciona': $ruta_vista = 'app/vistas/publicas/como_funciona.php'; break;
    case 'politicas-de-privacidad': $ruta_vista = 'app/vistas/publicas/politicas_privacidad.php'; break;
    case 'terminos-y-condiciones': $ruta_vista = 'app/vistas/publicas/terminos_condiciones.php'; break;
    case 'solicitar_reset':         $ruta_vista = 'app/vistas/publicas/solicitar_reset.php'; break;
    case 'reset_password':          $ruta_vista = 'app/vistas/publicas/reset_password.php'; break;
    


    // VISTAS PRIVADAS
    case 'comisionista':
        verificar_acceso('comisionista');
        $ruta_vista = 'app/vistas/privadas/dashboard_comisionista.php';
        break;
    case 'cliente':
        verificar_acceso('cliente');
        $ruta_vista = 'app/vistas/privadas/dashboard_cliente.php';
        break;
    
    // MODALES (Solo devuelven el fragmento de HTML)
    case 'ver_detalle_cliente':
        $ruta_vista = 'app/vistas/privadas/ver_detalle_pedido_cliente.php';
        break;

    // ACCIONES PÚBLICAS (Handlers que procesan formularios)
    case 'login_action':
        require_once __DIR__ . '/app/acciones/login_handler.php';
        exit; // Importante: estas acciones terminan el script
    case 'registro_action':
        require_once __DIR__ . '/app/acciones/registro_handler.php';
        exit;
    case 'confirmar_reset':
        require_once __DIR__ . '/app/acciones/confirmar_reset.php';
        exit;
    case 'procesar_recuperacion':
        require_once __DIR__ . '/app/acciones/procesar_recuperacion.php';
        exit;

    // ACCIONES (Handlers)
    case 'logout':
        cerrarSession();
        exit;

    case 'administracion':
        verificar_acceso('administrador');
        $ruta_vista = 'app/vistas/dashboard/main_dashboard.php';
        break;
    case 'nuevo_pedido':
        verificar_acceso('cliente');
        $ruta_vista = "app/vistas/privadas/nuevo_pedido.php";
        break;

    // --- MODALES / VISTAS AJAX ---
    case 'detalle_pedido':
        $ruta_vista = 'app/vistas/privadas/ver_detalle_pedido.php';
        break;
    case 'form_editar_pedido':
        verificar_acceso('administrador');
        $ruta_vista = 'app/vistas/privadas/form_editar_pedido.php';
        break;
 

    // --- ACCIONES PRIVADAS ---
    case 'crear_pedido_action':
        require_once __DIR__ . "/app/acciones/crear_pedido_handler.php";
        exit;
    case 'actualizar_estado':
    case 'asignar_pedido':
        require_once __DIR__ . '/app/acciones/actualizar_estado_handler.php';
        exit;
    case 'procesar_edicion_handler':
    case 'procesar_edicion_action':
        require_once __DIR__ . '/app/acciones/procesar_edicion_handler.php';
        exit;
    case 'eliminar_pedido_logico':
        verificar_acceso('administrador');
        require_once __DIR__ . '/app/acciones/borrar_pedido_handler.php';
        exit;
    // case 'actualizar_precio_accion':
    //     require_once __DIR__ . '/app/acciones/actualizar_precio_handler.php';
    //     exit;
    case 'confirmar_precio':
        require_once __DIR__ . '/app/acciones/confirmar_precio_handler.php';

        if (file_exists($archivo)) {
        require_once $archivo;
    } else {
        die("El archivo no existe en: " . $archivo); // Esto te dirá la verdad
    }
    
        exit;
  

    default:
        header("HTTP/1.0 404 Not Found");
        $ruta_vista = 'app/vistas/publicas/404.php';
        break;
}
    
        


// 5. LÓGICA DE RENDERIZADO (DIBUJAR LA PÁGINA)
if (in_array($vista, $paginas_modal)) {
    // Modo fragmento para AJAX/Modales
    if (file_exists($ruta_vista)) include $ruta_vista;
} else {
    // Modo página completa
    include 'app/includes/head.php';
    
    if (in_array($vista, $paginas_sin_navbar)) {
        include "app/includes/header.php";
    }

    echo '<main class="container mt-4 bg-white shadow-sm rounded-4 overflow-hidden">';
    if (file_exists($ruta_vista)) {
        include $ruta_vista;
    } else {
        echo "Error: La vista no existe ($ruta_vista)";
    }
    echo '</main>';

    if (in_array($vista, $paginas_sin_navbar)) {
        include "app/includes/footer.php";
    } else {
        include "app/includes/footer_admin.php";
    }
    echo '</body></html>';
}