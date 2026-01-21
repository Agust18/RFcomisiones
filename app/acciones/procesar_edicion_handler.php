<?php
ob_clean(); // Borra cualquier texto o espacio que se haya colado antes
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;

    $datos_pedido = [
        'descripcion' => $_POST['descripcion_pedido'] ?? ''
    ];

    $datos_direccion = [
        'calle'         => $_POST['calle'] ?? '',
        'numero'        => $_POST['numero'] ?? '',
        'ciudad'        => $_POST['ciudad'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'referencias'   => $_POST['referencias'] ?? ''
    ];

    if ($id_pedido) {
        // Verificamos si la función existe antes de llamarla para que no explote el script
        if (function_exists('actualizar_pedido_y_direccion')) {
            $resultado = actualizar_pedido_y_direccion($id_pedido, $datos_pedido, $datos_direccion);
            echo json_encode($resultado);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error técnico: La función de actualización no fue cargada.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de pedido faltante']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
exit;