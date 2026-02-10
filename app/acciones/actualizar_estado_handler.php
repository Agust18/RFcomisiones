<?php
// Limpieza total para asegurar un JSON válido
while (ob_get_level()) { ob_end_clean(); }
ob_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../funciones/funciones.php'; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Capturamos todo desde $_REQUEST (que lee GET y POST por igual)
$id_pedido    = $_REQUEST['id_pedido'] ?? null;
$nuevo_estado = $_REQUEST['nuevo_estado'] ?? null;
$id_usuario   = $_SESSION['id_usuario'] ?? null;

if ($id_pedido && $nuevo_estado && $id_usuario) {
    // Ejecutamos la actualización
    if (actualizar_estado_pedido($id_pedido, $nuevo_estado, $id_usuario)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar en Base de Datos']);
    }
} else {
    // Diagnóstico final en caso de error
    $faltantes = [];
    if (!$id_pedido) $faltantes[] = "ID";
    if (!$nuevo_estado) $faltantes[] = "Estado";
    if (!$id_usuario) $faltantes[] = "Sesión";
    
    echo json_encode([
        'success' => false, 
        'message' => "Faltan: " . implode(", ", $faltantes)
    ]);
}
exit;