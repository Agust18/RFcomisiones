<?php
require_once __DIR__ . '/../funciones/funciones.php';
//Limpieza absoluta: Evita que el HTML del index se mezcle con el JSON
while (ob_get_level()) { ob_end_clean(); }
ob_start();
header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// $_POST que es lo que envía FormData
$id_pedido = $_POST['id_pedido'] ?? null;
$nuevo_estado = $_POST['nuevo_estado'] ?? 'Asignado';
$id_comisionista = $_SESSION['id_usuario'] ?? $_POST['id_comisionista'] ?? null;
$response = ['success' => false, 'message' => 'Error desconocido'];
//Verificación de seguridad
if ($id_pedido && $id_comisionista) {
    // Asegúrate de que la función de DB esté disponible
    if (function_exists('actualizar_estado_pedido')) {
        $resultado = actualizar_estado_pedido($id_pedido, $nuevo_estado, $id_comisionista);
        
        if ($resultado) {
            $response = ['success' => true, 'message' => 'Pedido asignado correctamente.'];
        } else {
            $response['message'] = 'No se pudo actualizar en la base de datos (posiblemente ya asignado).';
        }
    } else {
        $response['message'] = 'Error interno: Función de actualización no cargada.';
    }
} else {
    // Debug ultra-claro para saber qué falló
    $response['message'] = "Datos incompletos. ID: $id_pedido, Estado: $nuevo_estado, User: $id_comisionista";
}
//Limpiamos cualquier "Warning" accidental y enviamos
ob_clean();
echo json_encode($response);
exit;