<?php
/**
 * ESTE ARCHIVO ES EL "PROCESADOR". 
 * Su única misión es recibir datos, mandarlos a la base de datos y avisar si salió bien.
 */

// Limpia cualquier espacio en blanco o error previo para que no rompa el JSON
if (ob_get_level()) ob_end_clean();

// Le avisa al navegador que lo que viene a continuación es un objeto JSON y no una página HTML
header('Content-Type: application/json');

/**
 * 1. EL FILTRO DE SEGURIDAD (MÉTODO)
 * Verificamos que los datos vengan por POST (el método seguro para enviar formularios).
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: Se esperaba POST y llegó ' . $_SERVER['REQUEST_METHOD'],
        'debug_info' => 'Esto pasa si el .htaccess hace una redirección 301'
    ]);
    exit;
}

// Importamos las funciones donde vive "actualizar_pedido_y_direccion"
require_once __DIR__ . '/../funciones/funciones.php';

/**
 * 2. RECOLECCIÓN DE DATOS
 * El $_POST es como una caja que trae todo lo que escribiste en el formulario.
 */
$id_pedido = $_POST['id_pedido'] ?? null;

if ($id_pedido) {
    
    // Armamos el "Paquete A" (Datos del Pedido)
    // Buscamos 'descripcion_pedido' que es el NAME que pusimos en el <textarea>
    $datos_p = [
        'descripcion' => $_POST['descripcion_pedido'] ?? ''
    ];

    // Armamos el "Paquete B" (Datos de la Dirección)
    // Agrupamos todo lo que va a la tabla de direcciones
    $datos_d = [
        'calle'         => $_POST['calle'] ?? '',
        'numero'        => $_POST['numero'] ?? '',
        'ciudad'        => $_POST['ciudad'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'referencias'   => $_POST['referencias'] ?? ''
    ];

    /**
     * 3. EL MOMENTO DE LA ACCIÓN
     * Le pasamos los paquetes a la función que creamos.
     * $resultado guardará lo que la función devuelva (true o false + mensaje).
     */
    $resultado = actualizar_pedido_y_direccion($id_pedido, $datos_p, $datos_d);

    /**
     * 4. LA RESPUESTA AL JAVASCRIPT
     * Convertimos el resultado en un texto JSON para que SweetAlert pueda leerlo.
     * Ejemplo: {"success": true, "message": "Cambios guardados"}
     */
    echo json_encode($resultado);

} else {
    // Si por alguna razón el formulario no envió el ID escondido (hidden input)
    echo json_encode([
        'success' => false, 
        'message' => 'Error crítico: No se encontró el ID del pedido en el envío.'
    ]);
}

// Cortamos la ejecución aquí para asegurar que no se envíe nada extra
exit;