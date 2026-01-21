<?php
ob_clean(); // Limpia cualquier salida accidental
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;

    if ($id_pedido) {
        $resultado = borrar_pedido_logico($id_pedido);
        echo json_encode($resultado);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
exit;