<?php
// 1. Identificación del usuario
$id_comisionista_logeado = $_SESSION['id_usuario'] ?? 0;

// 2. Parámetros de la URL (Filtros)
// Asegúrate de que $conexion esté incluido antes de esto
$busqueda = isset($_GET['q']) ? mysqli_real_escape_string($conexion, $_GET['q']) : '';
$estado_filtro = isset($_GET['estado']) ? mysqli_real_escape_string($conexion, $_GET['estado']) : 'Todos';
$pagina_actual = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

$pedidos_per_page = 6;
$offset = ($pagina_actual - 1) * $pedidos_per_page;

// 3. Construcción del filtro SQL dinámico
$filtro_sql = "";

if ($busqueda !== '') {
    $filtro_sql .= " AND (p.codigo_seguimiento LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%') ";
}

// CORRECCIÓN AQUÍ: Si es "Todos", definimos qué estados queremos ver 
// para que no intente filtrar por la palabra "Todos" en la base de datos
if ($estado_filtro !== 'Todos') {
    $filtro_sql .= " AND p.estado = '$estado_filtro' ";
} else {
    $filtro_sql .= " AND p.estado IN ('Asignado', 'Recogido', 'EnCamino', 'Entregado') ";
}

// 4. Conteo total
$sql_count = "SELECT COUNT(*) as total FROM pedidos p 
              LEFT JOIN usuarios u ON p.id_usuario = u.id 
              WHERE p.id_comisionista = '$id_comisionista_logeado' AND p.activo = 0 $filtro_sql";

$res_count = mysqli_query($conexion, $sql_count);

if (!$res_count) {
    die("Error en conteo: " . mysqli_error($conexion)); // Esto te dirá el error exacto
}

$dato_count = mysqli_fetch_assoc($res_count);
$total_registros = $dato_count['total'] ?? 0;
$total_paginas = ceil($total_registros / $pedidos_per_page);

// 5. Consulta final de pedidos (CON TODAS LAS COLUMNAS)
// $sql_pedidos = "SELECT 
//                     p.*, 
//                     u.nombre AS nombre_cliente, 
//                     u.telefono AS telefono_cliente 
//                 FROM pedidos p 
//                 LEFT JOIN usuarios u ON p.id_usuario = u.id 
//                 WHERE p.id_comisionista = '$id_comisionista_logeado' 
//                 AND p.activo = 0 
//                 $filtro_sql 
//                 ORDER BY p.fecha_creacion DESC 
//                 LIMIT $pedidos_per_page OFFSET $offset";

$sql_pedidos = "SELECT 
                    p.*, 
                    u.nombre AS nombre_cliente, 
                    u.telefono AS telefono_cliente,
                    /* UNIMOS LOS CAMPOS DE LA DIRECCIÓN */
                    CONCAT(IFNULL(d.calle,''), ' ', IFNULL(d.numero,''), ', ', IFNULL(d.ciudad,'')) AS direccion_entrega
                FROM pedidos p 
                LEFT JOIN usuarios u ON p.id_usuario = u.id 
                /* AGREGAMOS EL JOIN A DIRECCIONES */
                LEFT JOIN direcciones d ON p.id_direccion = d.id 
                WHERE p.id_comisionista = '$id_comisionista_logeado' 
                AND p.activo = 0 
                $filtro_sql 
                ORDER BY p.fecha_creacion DESC 
                LIMIT $pedidos_per_page OFFSET $offset";

$res_pedidos = mysqli_query($conexion, $sql_pedidos);

if (!$res_pedidos) {
    die("Error en pedidos: " . mysqli_error($conexion)); // Esto te dirá si falta una columna
}

$pedidos_en_proceso = mysqli_fetch_all($res_pedidos, MYSQLI_ASSOC) ?: [];
?>