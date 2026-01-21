<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedidos_ids = $_POST['pedidos_a_actualizar'] ?? [];
    $nuevo_estado = $_POST['nuevo_estado_masivo'] ?? '';
    $id_comisionista = $_SESSION['id_usuario'] ?? null; 

    if (empty($pedidos_ids) || empty($nuevo_estado) || !$id_comisionista) {
        $_SESSION['error'] = "Error: No se seleccionaron pedidos, falta el estado o el ID de usuario.";
        header("Location:"  .BASE_URL."comisionista");
        exit;
    }

    // Llama a la función de DB (definida a continuación)
    $filas_afectadas = actualizar_estados_masivamente($pedidos_ids, $nuevo_estado, $id_comisionista);

    if ($filas_afectadas > 0) {
        $_SESSION['exito'] = "¡Éxito! Se actualizaron " . $filas_afectadas . " pedidos a: " . $nuevo_estado . "";
    } elseif ($filas_afectadas === 0) {
         $_SESSION['error'] = "Advertencia: Ningún pedido fue actualizado. Podrían ya estar en ese estado o no pertenecerte.";
    } else {
        $_SESSION['error'] = "Error al actualizar los estados. Consulta el log de errores.";
    }

    header("Location:"  .BASE_URL."comisionista");
    exit;
}
// Si se accede directamente, redirigir
header("Location:"  .BASE_URL."comisionista");
exit;
?>