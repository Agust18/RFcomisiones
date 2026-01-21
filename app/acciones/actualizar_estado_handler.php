<?php
//Limpiamos absolutamente todo lo que index.php haya escrito antes (Navbar, espacios, etc)
while (ob_get_level()) {
    ob_end_clean();
}
//Iniciar buffer nuevo para controlar la salida
ob_start();
//Cabecera JSON
header('Content-Type: application/json');
$res = ['success' => false, 'message' => 'Error desconocido'];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Asegúrate de que la sesión esté disponible
    if (session_status() === PHP_SESSION_NONE) { session_start(); }

    $id_pedido = $_POST['id_pedido'] ?? null;
    $nuevo_estado = $_POST['nuevo_estado'] ?? null;
    $id_comisionista = $_SESSION['id_usuario'] ?? null;

    if ($id_pedido && $nuevo_estado && $id_comisionista) {
        // Llamada a tu función (asegúrate que el archivo de funciones esté incluido antes)
        if (actualizar_estado_pedido($id_pedido, $nuevo_estado, $id_comisionista)) {
            $res = [
                'success' => true, 
                'message' => "¡Estado actualizado a $nuevo_estado!"
            ];
        } else {
            $res['message'] = "Error al actualizar en la base de datos.";
        }
    } else {
        $res['message'] = "Faltan datos (ID: $id_pedido, Estado: $nuevo_estado, User: $id_comisionista)";
    }
}
//Limpiar cualquier basura que se haya colado durante la ejecución (warnings silenciosos)
ob_clean();
echo json_encode($res);

// Detener la ejecución para que index.php no escriba nada más
exit;