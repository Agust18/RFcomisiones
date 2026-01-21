<?php
// Recoger los datos del formulario (GET)
$busqueda = isset($_GET['q']) ? mysqli_real_escape_string($conexion, $_GET['q']) : '';
$estado_filtro = isset($_GET['estado']) ? mysqli_real_escape_string($conexion, $_GET['estado']) : 'Todos';
$pagina_actual = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$pedidos_per_page = 6;
$offset = ($pagina_actual - 1) * $pedidos_per_page;

// Construir el filtro dinámico
$filtro_sql = "";
if ($busqueda !== '') {
    // PRIORIDAD: Si el usuario escribió algo, buscamos en todos los estados
    $filtro_sql .= " AND (p.codigo_seguimiento LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%') ";
    
    // OPCIONAL: Si quieres que al buscar se limpie el selector visual de estados, 
    // podrías forzar $estado_filtro = 'Todos'; aquí.
} else {
    // Si NO hay búsqueda, entonces sí aplicamos el filtro de estado por botones
    if ($estado_filtro !== 'Todos') {
        $filtro_sql .= " AND p.estado = '$estado_filtro' ";
    }
}


// 1. Contar TOTAL de registros con filtros (para los números de las páginas)
$sql_count = "SELECT COUNT(*) as total FROM pedidos p 
              LEFT JOIN usuarios u ON p.id_comisionista = u.id 
              WHERE p.id_usuario = '$id_cliente' AND p.activo = 0 $filtro_sql";
$res_count = mysqli_query($conexion, $sql_count);
$total_registros = mysqli_fetch_assoc($res_count)['total'];
$total_paginas = ceil($total_registros / $pedidos_per_page);

// 2. Obtener los pedidos de la página actual con los filtros
$sql_pedidos = "SELECT p.*, u.nombre AS nombre_comisionista, u.telefono AS telefono_comisionista 
                FROM pedidos p 
                LEFT JOIN usuarios u ON p.id_comisionista = u.id 
                WHERE p.id_usuario = '$id_cliente' AND p.activo = 0 $filtro_sql 
                ORDER BY p.fecha_creacion DESC 
                LIMIT $pedidos_per_page OFFSET $offset";

$res_pedidos = mysqli_query($conexion, $sql_pedidos);
$pedidos = mysqli_fetch_all($res_pedidos, MYSQLI_ASSOC);