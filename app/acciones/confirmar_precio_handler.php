<?php
session_start();
// require_once '../funciones/funciones.php'; // Para tener la función confirmar_precio_pedido()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validamos que lleguen los datos
    $id_pedido = isset($_POST['id_pedido']) ? (int)$_POST['id_pedido'] : 0;
    $nuevo_precio = isset($_POST['nuevo_precio']) ? (float)$_POST['nuevo_precio'] : 0.0;

  if ($id_pedido > 0 && $nuevo_precio >= 0) {
        // Intentamos guardar en la base de datos
        if (confirmar_precio_pedido($id_pedido, $nuevo_precio)) {
            // ÉXITO
            
            header("Location: " . BASE_URL . "comisionista?res=ok");
            exit;
        } else {
            // ERROR EN LA CONSULTA SQL (ej. la base de datos rechazó el cambio)
            header("Location: " . BASE_URL . "comisionista?res=error_db");
            exit;
        }
    } else {
        // ERROR DE VALIDACIÓN (ej. el precio enviado es negativo o no hay ID)
        header("Location: " . BASE_URL . "comisionista?res=datos_invalidos");
        exit;
    }
} 