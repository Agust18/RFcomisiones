<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../funciones/funciones.php';
include_once __DIR__ . '/../funciones/utilidad.php';
//Recolección de datos
$id_usuario = $_SESSION['id_usuario'] ?? 0; // ID del cliente logueado
$calle = trim($_POST['calle'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$codigo_postal = trim($_POST['codigo_postal'] ?? '');
// CAMPOS NUEVOS 
$etiqueta = trim($_POST['etiqueta'] ?? '');
$referencias = trim($_POST['referencias'] ?? '');
$descripcion_general = trim($_POST['descripcion_general'] ?? '');
$dimension_elegida = $_POST['dimension_elegida'] ?? '';
$precio_estimado  = $_POST['precio_envio'] ?? 0; //  valor mínimo enviado

// Validación de campos obligatorios 
if ($id_usuario === 0 || empty($calle) || empty($ciudad)  || empty($descripcion_general) || empty($etiqueta)) {
    notificar('error', 'Error', 'Faltan datos obligatorios.');
    header("Location: ".BASE_URL."nuevo_pedido");
    exit;
}

//Guardar Dirección
$id_direccion = guardar_direccion($id_usuario, $calle, $numero, $ciudad, $codigo_postal, $etiqueta, $referencias);
if (!$id_direccion) {
    notificar('error', 'Error', 'No se pudo guardar la dirección de entrega.');;
    header("Location: ".BASE_URL."nuevo_pedido");
    exit;
}
$codigo_seguimiento_real = crear_pedido($id_usuario, $id_direccion, $descripcion_general, $dimension_elegida, $precio_estimado);

if ($codigo_seguimiento_real) {
    // Éxito: Usamos la variable que devuelve la función
    notificar('success', '¡Pedido creado!', 'Código de seguimiento: #' . $codigo_seguimiento_real);
    header("Location: " . BASE_URL . "cliente");
    exit;
} else {
    // Error
    notificar('error', 'Error', 'Hubo un problema al procesar tu pedido.');
    header("Location: " . BASE_URL . "nuevo_pedido");
    exit;
}

