<?php
//Configuración de seguridad de sesión
$opciones_sesion = [
    'cookie_lifetime' => 86400,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
];
// Solo activa Secure si usas HTTPS (MAMP suele ser HTTP a menos que lo configures)
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $opciones_sesion['cookie_secure'] = true;
}
if (session_status() === PHP_SESSION_NONE) {
    session_start($opciones_sesion);
}
require_once __DIR__ . '/app/config/db.php';
require_once __DIR__ . '/app/funciones/funciones.php';
require_once __DIR__ . '/app/funciones/utilidad.php';
require_once __DIR__ . '/app/funciones/alertas.php';

?>
<?php
$vista = $_GET['pagina'] ?? 'inicio'; // Por defecto, muestra 'index'
$ruta_vista = ''; // Inicializamos la variable que contendrá la ruta del archivo
?>
<?php include 'app/includes/head.php'; ?>

<body>
    <?php

    $paginas_modal = ['detalle_pedido', 'form_editar_pedido', 'ver_detalle_cliente'];
    $paginas_sin_navbar = ['inicio', 'login', 'registro', 'politicas-de-privacidad', 'terminos-y-condiciones', 'como-funciona', "ver_detalle_pedido", ""];
    // CORRECCIÓN CLAVE: Usamos $vista que contiene el valor de $_GET['pagina'].
    if (in_array($vista, $paginas_sin_navbar)) {
        // Usamos la ruta absoluta para el navbar para evitar errores.
        include "app/includes/header.php";
    }
    ?>

    <?php
    //vistas publicas
    switch ($vista) {
        // VISTA POR DEFECTO: index ( PÁGINA DE INICIO)
        case 'inicio':
            $ruta_vista = 'app/vistas/publicas/inicio.php';
            break;
        case 'login':
            $ruta_vista = 'app/vistas/publicas/login.php';
            break;

        case 'registro':
            $ruta_vista = 'app/vistas/publicas/registro.php';
            break;

        case 'politicas-de-privacidad':
            $ruta_vista = 'app/vistas/publicas/politica_privacidad.php';
            break;

        case 'terminos-y-condiciones':
            $ruta_vista = 'app/vistas/publicas/terminos_condiciones.php';
            break;

        case 'como-funciona':
            $ruta_vista = 'app/vistas/publicas/como_funciona.php';
            break;

        // HANDLERS DE ACCIÓN PÚBLICA (Se ejecutan y terminan el script)
    
        case 'login_action':
            $ruta_vista = 'app/acciones/login_handler.php';
            break;

        case 'registro_action':
            $ruta_vista = 'app/acciones/registro_handler.php';
            break;

        case 'solicitar_reset':
            $ruta_vista = 'app/vistas/publicas/solicitar_reset.php';
            break;

        case 'reset_password':
            $ruta_vista = 'app/vistas/publicas/reset_password.php';
            break;



        // VISTAS PRIVADAS
        case 'comisionista':
            verificar_acceso('comisionista');
            $ruta_vista = 'app/vistas/privadas/dashboard_comisionista.php';
            break;

        case 'cliente':
            verificar_acceso('cliente');
            $ruta_vista = 'app/vistas/privadas/dashboard_cliente.php';
            break;

        case 'administracion':
            verificar_acceso('administrador');
            $ruta_vista = 'app/vistas/dashboard/main_dashboard.php';
            break;

        case 'nuevo_pedido':
            verificar_acceso('cliente');
            $ruta_vista = "app/vistas/privadas/nuevo_pedido.php";
            break;

        case 'detalle_pedido':
            $ruta_vista = 'app/vistas/privadas/ver_detalle_pedido.php';
            break;

        case 'form_editar_pedido':
            verificar_acceso('administrador'); // O el rol que corresponda
            $ruta_vista = 'app/vistas/privadas/form_editar_pedido.php';
            break;

        case 'ver_detalle_cliente': // <--- ESTE NOMBRE debe ser igual al del fetch
            $ruta_vista = 'app/vistas/privadas/ver_detalle_pedido_cliente.php';
            break;





        // HANDLERS DE ACCIÓN PRIVADA (Se ejecutan y terminan el script)
        case 'crear_pedido_action':
            require __DIR__ . "/app/acciones/crear_pedido_handler.php";
            exit;

        case 'actualizar_estado':
            // Este handler maneja Entregado y Completado
            $ruta_vista = 'app/acciones/actualizar_estado_handler.php';
            break;

        case 'procesar_edicion_handler':
            // El handler mismo ya tiene el header JSON y el exit
            require_once __DIR__ . '/app/acciones/procesar_edicion_handler.php';
            exit;

        case 'asignar_pedido':
            // Este handler debería ser el mismo que actualizar_estado (más simple)
            $ruta_vista = 'app/acciones/actualizar_estado_handler.php';
            break;

        case 'eliminar_pedido_logico':
            verificar_acceso('administrador'); // Seguridad
            require __DIR__ . '/app/acciones/borrar_pedido_handler.php';
            break;

        case 'procesar_edicion_action':
            require __DIR__ . '/app/acciones/procesar_edicion_handler.php';
            exit;

        case 'actualizar_precio_accion':
            require_once __DIR__ . '/app/acciones/actualizar_precio_handler.php';
            exit;

        case 'confirmar_reset':
            // Esta ruta debe ser la ubicación REAL del archivo en tu disco
            require_once __DIR__ . '/app/acciones/confirmar_reset.php';
            exit;
            break;

        case 'procesar_recuperacion':
            require_once __DIR__ . '/app/acciones/procesar_recuperacion.php';
            exit;


        // case 'actualizar_estado_masivo':
        //     require __DIR__ . '/app/acciones/actualizar_estado_masivo_handler.php';
        //     break;
    
        // ACCIÓN DE LOGOUT
    
        case 'logout':
    // Importante: Si la función cerrarSession() está en otro archivo, hacé el require antes
    cerrarSession(); 
    exit; // El exit dentro de la función o aquí es vital para que el JS reciba JSON limpio
    break;

        default:
            header("HTTP/1.0 404 Not Found");
            $ruta_vista = 'app/vistas/publicas/404.php';
            break;
    }

    // 3. LÓGICA DE RENDERIZADO
    if (in_array($vista, $paginas_modal)) {
        // --- MODO MODAL: Si es una página de modal, incluimos SOLO el archivo PHP ---
        if (file_exists($ruta_vista)) {
            include $ruta_vista;
        }
    } else {
        // --- MODO COMPLETO: Si es una página normal, cargamos toda la estructura HTML ---
        include 'app/includes/head.php';
        echo '<body>';

        // $paginas_sin_navbar = ['inicio', 'login', 'registro', 'politicas-de-privacidad', 'terminos-y-condiciones', 'como-funciona'];
        // if (in_array($vista, $paginas_sin_navbar)) {
        //     include "app/includes/header.php";
        // }
    
        echo '<main class="container mt-4 bg-white shadow-sm rounded-4 overflow-hidden">';
        if (file_exists($ruta_vista)) {
            include $ruta_vista;
        }
        echo '</main>';

        // Footer dinámico
        if (in_array($vista, $paginas_sin_navbar)) {
            include "app/includes/footer.php";
        } else {
            include "app/includes/footer_admin.php";
        }
        echo '</body></html>';
    }

    ?>