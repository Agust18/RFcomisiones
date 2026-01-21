<?php
header('Content-Type: application/json');
// Obtenemos los datos del FormData
$id_pedido = $_POST['id_pedido'] ?? null;
$id_comisionista = $_POST['id_comisionista'] ?? null;
$nuevo_estado = $_POST['nuevo_estado'] ?? null;

if ($id_pedido && $id_comisionista && $nuevo_estado) {
    // Llamamos a la función que revisamos antes
    $resultado = actualizar_estado_pedido($id_pedido, $nuevo_estado, $id_comisionista);

    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'El pedido ahora es tuyo.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar en la base de datos o ya tiene dueño.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
}
exit;