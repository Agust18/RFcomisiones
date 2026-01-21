<?php
// Incluimos el archivo de conexión. Esto hace que $conexion esté disponible globalmente.
require_once dirname(__DIR__) . '/config/db.php';

function registrar_Usuarios($nombre, $email, $contraseña, $telefono)
{
    global $conexion;
    $contraseña_hashed = password_hash($contraseña, PASSWORD_DEFAULT);
    $rol = 'cliente'; // Asignar rol por defecto

    $sql = "INSERT INTO usuarios (nombre,email,password,rol,telefono) VALUES (?,?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        // En un entorno de producción, aquí registrarías el error
        // die("Error en la preparación: " . $conexion->error); 
        return false;
    }


    $tipos = 'sssss';
    $stmt->bind_param($tipos, $nombre, $email, $contraseña_hashed, $rol, $telefono);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }



}

function obtener_todos_los_usuarios() {
    global $conexion;
    
    // Asumimos que la tabla 'usuarios' tiene id, nombre, email, y rol
    $sql = "SELECT id, nombre, email, rol, telefono FROM usuarios ORDER BY nombre ASC";
            
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt->execute()) {
        error_log("Error en la ejecución de obtener_todos_los_usuarios: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $resultado = $stmt->get_result();
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
    
    $stmt->close();
    
    return $usuarios;
}

function obtener_usuario_por_id($id_usuario) {
    global $conexion;
    $sql = "SELECT nombre, telefono, email FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


function existe_email($email) {
    global $conexion;
    
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt === false) {
        error_log("Error en la preparación de existe_email: " . $conexion->error);
        return true; // Asumir que existe para evitar un ataque de enumeración
    }
    
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result(); // Necesario para usar num_rows
    
    $existe = $stmt->num_rows > 0;
    
    $stmt->close();
    
    return $existe;
}

function verificarCredenciales($email, $contraseña)
{
    global $conexion;
    
    $sql = "SELECT id,nombre,email,password,rol,telefono from usuarios where email=?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        // En un entorno de producción, aquí registrarías el error
        // die("Error en la preparación: " . $conexion->error); 
        return false;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultado = $stmt->get_result(); // Obtiene el resultado de la consulta
    //preguntamos si encontro resultados y los guardamos en una variable resultado
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($contraseña, $usuario['password'])) {
            // Credenciales válidas
            // Compara la contraseña plana ingresada con el hash guardado en la columna 'password'
            $stmt->close();
            return $usuario; // Retorna los datos del usuario es decir id rol nombre
        } else {
            // Contraseña incorrecta
            $stmt->close();
            return false; //email no encontrado o contraseña incorrecta
        }
    }
    if ($stmt) { // Solo cerramos si se logró preparar la consulta
        $stmt->close();
    }
    return false; // Devuelve FALSE si el email no se encontró (num_rows != 1)
}

function guardar_direccion($id_usuario, $calle, $numero, $ciudad, $codigo_postal, $etiqueta, $referencias){
    global $conexion; 
    $sql = "INSERT INTO direcciones (id_usuario,calle, numero, ciudad, codigo_postal, etiqueta, referencias) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false ){
        error_log("Error en la preparación de guardar_direccion: " . $conexion->error);
        return false;
    }
    $tipos =   'issssss';
    
    $stmt->bind_param($tipos, $id_usuario, $calle, $numero, $ciudad, $codigo_postal, $etiqueta, $referencias);
    if ($stmt->execute()){
        // 🚨 CAMBIO CLAVE: Obtener y devolver el ID del registro insertado
        $nuevo_id = $stmt->insert_id;
        $stmt->close();
        return $nuevo_id; // Devolvemos el ID
    

    }else {
        error_log("Error en la ejecución de guardar_direccion: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

function obtener_direcciones($id_usuario){
    global $conexion;
    $sql= "SELECT * FROM direcciones  WHERE id_usuario = ? ORDER BY id DESC"; // Ordenamos por la más reciente";
    
    $stmt = $conexion->prepare($sql);
    
    if ($stmt === false ){
        error_log("Error en la preparación de obtener_direcciones: " . $conexion->error);
        return false;
    }
    $stmt->bind_param('i', $id_usuario);
    if (!$stmt->execute()){
        error_log("Error en la ejecución de obtener_direcciones: " . $stmt->error);
        $stmt->close();
        return false;
    }
    $resultado = $stmt->get_result();
    $direcciones = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    //si fue exitosa devuelve el array
    return $direcciones;

}

function obtener_codigo (){
    global $conexion;
    $sql = "SELECT MAX(codigo_seguimiento) AS max_codigo FROM pedidos ";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false ){
        error_log("Error en la preparación de obtener_codigo: " . $conexion->error);
        return false;
    }
    if (!$stmt->execute()){
        error_log("Error en la ejecución de obtener_codigo: " . $stmt->error);
        $stmt->close();
        return false;
    }
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    $stmt->close();
    return $fila['max_codigo'] ?? 0;


}

// function crear_pedido($id_usuario, $id_direccion,$descripcion_general) {
//     global $conexion;
//     $fecha_creacion_actual = date('Y-m-d H:i:s'); 
//     $estado_inicial = 'Pendiente'; 
//     $id_comisionista_temp = NULL; 
//     $codigo_seguimiento = obtener_codigo()+1;
//     $sql = "INSERT INTO pedidos (id_usuario, id_direccion, id_comisionista, descripcion_pedido, fecha_creacion,estado, codigo_seguimiento) 
//          VALUES (?, ?, ?, ?, ?, ?,?)";
    
//     $stmt = $conexion->prepare($sql);
    
//     if ($stmt === false) {
//         error_log("Error en la preparación de crear_pedido: " . $conexion->error);
//         return false;
//     }

//     // Tipos: 3 Enteros (id_usuario, id_direccion), 1 string (estado)
//     $tipos = 'iiisssi'; 
    
//     $stmt->bind_param($tipos,
//      $id_usuario,
//      $id_direccion, 
//         $id_comisionista_temp,
//         $descripcion_general,
//         $fecha_creacion_actual,
       
//       $estado_inicial,
//       $codigo_seguimiento);
    
//     if ($stmt->execute()){
//         // CRÍTICO: Obtenemos el ID del registro recién insertado
//         $nuevo_id_pedido = $stmt->insert_id; 
//         $stmt->close();
//         return $nuevo_id_pedido;
//     } else {
//         error_log("Error al ejecutar crear_pedido: " . $stmt->error);
//         $stmt->close();
//         return false;
//     }
// }


function crear_pedido($id_usuario, $id_direccion, $descripcion_general, $dimension_elegida, $precio_estimado) {
    global $conexion;
    
    $fecha_creacion_actual = date('Y-m-d H:i:s'); 
    $estado_inicial = 'Pendiente'; 
    $estado_precio = 'Estimado'; // Flag para el comisionista
    $id_comisionista_temp = NULL; 
    
    // Generamos el código de seguimiento
    $codigo_seguimiento = obtener_codigo() + 1;

    // Agregamos las 3 columnas nuevas a la consulta
    $sql = "INSERT INTO pedidos (
                id_usuario, id_direccion, id_comisionista, 
                descripcion_pedido, fecha_creacion, estado, 
                codigo_seguimiento, dimension_elegida, precio_final, estado_precio
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    if ($stmt === false) {
        error_log("Error en la preparación de crear_pedido: " . $conexion->error);
        return false;
    }

    /**
     * Definición de tipos para bind_param:
     * i = entero, s = string, d = doble/decimal
     * Orden: id_u(i), id_d(i), id_c(i), desc(s), fecha(s), est(s), cod_seg(i), dim(s), precio(d), est_pre(s)
     */
    $tipos = 'iiisssisds'; 
    
    $stmt->bind_param($tipos,
        $id_usuario,
        $id_direccion, 
        $id_comisionista_temp,
        $descripcion_general,
        $fecha_creacion_actual,
        $estado_inicial,
        $codigo_seguimiento,
        $dimension_elegida,
        $precio_estimado,
        $estado_precio
    );
    
    if ($stmt->execute()){
        // Retornamos el código de seguimiento en lugar del ID interno
        // para que el mensaje de éxito sea más profesional.
        $stmt->close();
        return $codigo_seguimiento; 
    } else {
        error_log("Error al ejecutar crear_pedido: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

function guarda_detalle_pedido($id_pedido, $items_de_comision){ // Usamos $items_de_comision para claridad
    global $conexion;
    
    // Asumiendo que el nombre de la columna 'pedido_id' es correcto ahora
   $sql = "INSERT INTO detalle_pedido (id_pedido, descripcion_item, cantidad) 
            VALUES (?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    if ($stmt === false ){
        error_log("Error en la preparación de guarda_detalle_pedido: " . $conexion->error);
        return false;
    }
    
    // Tipos: i (int), s (string), d (double), i (int), d (double)
    $tipos = 'isi'; 

    // Inicializamos las variables (ya están bien)
    $descripcion_item = '';
  
    $cantidad = 0;
    

    // 1. Vinculamos las variables a la sentencia preparada
    // $id_pedido es el único que no cambiará en el bucle
    $stmt->bind_param($tipos, $id_pedido, $descripcion_item,$cantidad);
    
    // 2. Dentro del bucle, actualizamos las variables y ejecutamos.
    foreach ($items_de_comision as $item) { // Asegúrate de usar el nombre correcto del array ($items)
        
       
        $descripcion_item = $item['descripcion_item'];
      
        $cantidad = intval($item['cantidad'] ?? 1); // Usamos 1 por defecto si no existe
      
        
        // El valor actualizado de las variables es el que se usa en execute()
        if (!$stmt->execute()){
            error_log("Error en la ejecución de guarda_detalle_pedido: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
    
    $stmt->close();
    return true;
}
function obtener_detalle_pedido($id_pedido) {
    
    global $conexion;
    $detalle_completo = ['pedido' => null, 'items' => []];

    // 1. Obtener Datos Principales del Pedido, Cliente, Comisionista y Dirección
    // Esta consulta es similar a la que usamos antes, ajustada a tu DB.
    $sql_pedido = "
        SELECT 
            p.*, 
            u.nombre AS nombre_cliente, u.email AS email_cliente, 
            c.nombre AS nombre_comisionista, c.telefono AS telefono_comisionista,
            d.calle, d.numero, d.ciudad, d.codigo_postal
        FROM pedidos p
        JOIN usuarios u ON p.id_usuario = u.id
        JOIN direcciones d ON p.id_direccion = d.id
        LEFT JOIN usuarios c ON p.id_comisionista = c.id
        WHERE p.id = ?";
        
    $stmt_pedido = $conexion->prepare($sql_pedido);
    if (!$stmt_pedido) { 
        error_log("Error en la preparación SQL del pedido: " . $conexion->error);
        return false; 
    }
    
    $stmt_pedido->bind_param('i', $id_pedido);
    $stmt_pedido->execute();
    $resultado_pedido = $stmt_pedido->get_result();
    $detalle_completo['pedido'] = $resultado_pedido->fetch_assoc();
    $stmt_pedido->close();

    // Si el pedido no existe, salimos
    if (!$detalle_completo['pedido']) {
        return false;
    }

    // 2. Obtener Ítems del Pedido desde tu tabla 'detalle_pedido'
    $sql_items = "
        SELECT 
            descripcion_item, cantidad
        FROM detalle_pedido
        WHERE id_pedido = ?";
        
    $stmt_items = $conexion->prepare($sql_items);
    if (!$stmt_items) { 
        error_log("Error en la preparación SQL de items: " . $conexion->error);
        // Si fallan los items, devolvemos lo que tenemos del pedido principal
        return $detalle_completo; 
    } 
    
    $stmt_items->bind_param('i', $id_pedido);
    $stmt_items->execute();
    $resultado_items = $stmt_items->get_result();
    $detalle_completo['items'] = $resultado_items->fetch_all(MYSQLI_ASSOC);
    $stmt_items->close();
    
    return $detalle_completo;
}


function obtener_pedidos_cliente($id_usuario_cliente) {
    global $conexion;
    
    // Consulta JOIN para obtener detalles del cliente y del comisionista
    $sql = "SELECT p.id, u.nombre AS nombre_cliente, p.fecha_creacion, p.estado, 
                  c.nombre AS nombre_comisionista,p.codigo_seguimiento, c.telefono AS telefono_comisionista
            FROM pedidos p
            JOIN usuarios u ON p.id_usuario = u.id
            LEFT JOIN usuarios c ON p.id_comisionista = c.id -- LEFT JOIN para pedidos sin asignar
            WHERE p.id_usuario = ? 
            ORDER BY p.fecha_creacion DESC";
            
    $stmt = $conexion->prepare($sql);
    
    if ($stmt === false) {
        error_log("Error en la preparación de obtener_pedidos_cliente: " . $conexion->error);
        return false;
    }
    
    // El ID del usuario es un entero ('i')
    $stmt->bind_param('i', $id_usuario_cliente); 
    
    if (!$stmt->execute()) {
        error_log("Error en la ejecución de obtener_pedidos_cliente: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $resultado = $stmt->get_result();
    $pedidos = $resultado->fetch_all(MYSQLI_ASSOC);
    
    $stmt->close();
    
    return $pedidos;
}

function obtener_pedidos_comisionistas($estado_filtro = null, $id_comisionista_filtro = null){
    global $conexion;
    $parametros = [];
    $tipos = '';

    $sql = "SELECT p.id, p.id_usuario, u.nombre AS nombre_cliente, 
                p.id_direccion, p.codigo_seguimiento, p.fecha_creacion, p.estado, p.id_comisionista, 
                c.nombre AS nombre_comisionista, 
                d.codigo_postal,
                CONCAT(IFNULL(d.calle,''), ' ', IFNULL(d.numero,''), ', ', IFNULL(d.ciudad,'')) AS direccion_entrega
          FROM pedidos p
          JOIN usuarios u ON p.id_usuario = u.id
          LEFT JOIN direcciones d ON p.id_direccion = d.id
          LEFT JOIN usuarios c ON p.id_comisionista = c.id
          WHERE 1=1 "; // Quitamos p.activo = 0 o lo cambiamos a p.activo = 1

    // A. Filtro por ID de Comisionista (Pedidos que ya son míos)
    if ($id_comisionista_filtro !== null && is_numeric($id_comisionista_filtro) && $id_comisionista_filtro > 0) {
        $sql .= "AND p.id_comisionista = ? ";
        $tipos .= 'i';
        $parametros[] = $id_comisionista_filtro;
    } 
    // B. Filtro de COLA DE TRABAJO (Solo si pasamos null explícitamente)
    else if ($id_comisionista_filtro === null) {
        // Buscamos lo que sea NULL o lo que sea 0
        $sql .= "AND (p.id_comisionista IS NULL OR p.id_comisionista = 0) "; 
    }

    // C. Filtro de ESTADO
    if ($estado_filtro !== null && is_string($estado_filtro)) {
        $sql .= "AND p.estado = ? ";
        $tipos .= 's';
        $parametros[] = $estado_filtro;
    }

    $sql .= " ORDER BY p.fecha_creacion DESC";
    
    $stmt = $conexion->prepare($sql);
    if (!empty($parametros)) {
        $stmt->bind_param($tipos, ...$parametros);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();
    $pedidos = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $pedidos;
}

function obtener_pedidos_cerrados($id_comisionista) {
    global $conexion;
    
    // Usamos 'IN' para buscar múltiples estados: 'Entregado' y 'Completado'
    $sql = "
        SELECT 
            p.id, p.fecha_creacion, p.estado, 
            u.nombre AS nombre_cliente
        FROM pedidos p
        JOIN usuarios u ON p.id_usuario = u.id
        WHERE p.id_comisionista = ?
        AND p.estado IN ('Entregado', 'Completado')
        ORDER BY p.fecha_creacion DESC";
        
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        error_log("Error de preparación en obtener_pedidos_cerrados: " . $conexion->error);
        return [];
    }
    
    $stmt->bind_param('i', $id_comisionista);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $pedidos = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $pedidos;
}
 
function actualizar_estado_pedido($id_pedido, $nuevo_estado, $id_comisionista) {
    global $conexion;

    // 1. Verificamos primero quién es el dueño actual del pedido
    $checkSql = "SELECT id_comisionista, estado FROM pedidos WHERE id = ?";
    $stmtCheck = $conexion->prepare($checkSql);
    $stmtCheck->bind_param("i", $id_pedido);
    $stmtCheck->execute();
    $resultado = $stmtCheck->get_result()->fetch_assoc();
    $stmtCheck->close();

    if (!$resultado) return false; // El pedido no existe

    $dueño_actual = $resultado['id_comisionista'];

    // 2. Lógica de actualización
    if ($dueño_actual === null || $dueño_actual == 0) {
        // Si no tiene dueño, se lo asignamos al que lo está moviendo ahora
        $sql = "UPDATE pedidos SET estado = ?, id_comisionista = ? WHERE id = ?";
        $tipos = "sii";
        $parametros = [$nuevo_estado, $id_comisionista, $id_pedido];
    } else if ($dueño_actual == $id_comisionista) {
        // Si es el dueño, solo actualizamos el estado
        $sql = "UPDATE pedidos SET estado = ? WHERE id = ? AND id_comisionista = ?";
        $tipos = "sii"; // nota: el orden de parámetros abajo es estado, id, comisionista
        $parametros = [$nuevo_estado, $id_pedido, $id_comisionista];
    } else {
        // Si el dueño es otra persona, no permitimos el cambio
        return false;
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($tipos, ...$parametros);
    $exec = $stmt->execute();
    $filas = $stmt->affected_rows;
    $stmt->close();

    // Retornamos true si se ejecutó. 
    // Usamos >= 0 porque si el estado es el mismo, affected_rows es 0 pero no es un error.
    return $exec; 
}

function contar_pedidos_por_estado($id_comisionista, $estado) {
    global $conexion;
    
    $sql = "
        SELECT COUNT(id) AS total 
        FROM pedidos 
        WHERE id_comisionista = ? AND estado = ?";
        
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        error_log("Error de preparación en contar_pedidos_por_estado: " . $conexion->error);
        return 0;
    }
    
    $stmt->bind_param('is', $id_comisionista, $estado);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    $stmt->close();
    
    return (int)($fila['total'] ?? 0);
}

function obtener_pedidos_para_administracion($estado) {
    global $conexion;
    
    $sql = "
        SELECT 
            p.id, 
            u.nombre AS nombre_cliente,
            c.nombre AS nombre_comisionista
        FROM pedidos p
        JOIN usuarios u ON p.id_usuario = u.id
        LEFT JOIN usuarios c ON p.id_comisionista = c.id
        WHERE p.estado = ?
        ORDER BY p.fecha_creacion ASC";
        
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        error_log("Error de preparación en obtener_pedidos_para_administracion: " . $conexion->error);
        return [];
    }
    
    $stmt->bind_param('s', $estado);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $pedidos = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $pedidos;
}


function get_badge_clase($estado) {
    switch ($estado) {
        case 'Pendiente':
            return 'bg-danger'; 
        case 'Asignado':
            return 'bg-info-subtle text-info border-0';
        case 'EnCamino':
            return 'bg-warning-subtle text-dark border-0'; 
        case 'Recogido':
            return 'bg-primary-subtle text-primary border-0'; 
        case 'Entregado':
            return 'bg-success '; 
        case 'Completado':
            return 'bg-dark'; 
        default:
            return 'bg-secondary';
    }
}

function contar_pedidos_activos_memoria($pedidos_array) {
    if (empty($pedidos_array)) return 0;
    
    // Filtra y cuenta los pedidos cuyo estado NO sea 'Completado' o 'Cancelado'
    $activos = array_filter($pedidos_array, function($pedido) {
        return $pedido['estado'] !== 'Completado' && $pedido['estado'] !== 'Cancelado';
    });
    return count($activos);
}


function contar_pedidos_por_estado_memoria($pedidos_array, $estado) {
    if (empty($pedidos_array)) return 0;
    
    // Filtra y cuenta los pedidos cuyo estado es exactamente el que buscamos
    $contados = array_filter($pedidos_array, function($pedido) use ($estado) {
        return $pedido['estado'] === $estado;
    });
    return count($contados);
}

function obtener_pedido_completo_por_id($id_pedido) {
    global $conexion;
    $sql = "SELECT 
                p.*, 
                u.nombre AS nombre_cliente, -- Agregamos el nombre del usuario
                d.calle, d.numero, d.ciudad, d.codigo_postal, d.etiqueta, d.referencias,
                IFNULL(GROUP_CONCAT(CONCAT(dp.cantidad, ' - ', dp.descripcion_item) SEPARATOR '||'), '') as items
            FROM pedidos p
            LEFT JOIN usuarios u ON p.id_usuario = u.id -- Unión con la tabla usuarios
            LEFT JOIN direcciones d ON p.id_direccion = d.id
            LEFT JOIN detalle_pedido dp ON p.id = dp.id_pedido
            WHERE p.id = ?
            GROUP BY p.id";
            
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function borrar_pedido_logico($id_pedido) {
    global $conexion;
    try {
        $sql = "UPDATE pedidos SET activo = 1 WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_pedido);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Pedido eliminado correctamente'];
        } else {
            throw new Exception("No se pudo actualizar el estado del pedido.");
        }
    } catch (Exception $e) {
        error_log("Error en borrar_pedido_logico: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}


function actualizar_pedido_y_direccion($id_pedido, $datos_p, $datos_d) {
    global $conexion;
    
    try {
        $conexion->begin_transaction();

        // 1. Actualizar Pedido
        $sql1 = "UPDATE pedidos SET descripcion_pedido = ? WHERE id = ?";
        $stmt1 = $conexion->prepare($sql1);
        $stmt1->bind_param("si", $datos_p['descripcion'], $id_pedido);
        $stmt1->execute();

        // 2. Obtener el ID de dirección
        $sql2 = "SELECT id_direccion FROM pedidos WHERE id = ?";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bind_param("i", $id_pedido);
        $stmt2->execute();
        $id_dir = $stmt2->get_result()->fetch_assoc()['id_direccion'] ?? null;

        if (!$id_dir) throw new Exception("Dirección no vinculada.");

        // 3. Actualizar Dirección
        $sql3 = "UPDATE direcciones SET calle=?, numero=?, ciudad=?, codigo_postal=?, referencias=? WHERE id=?";
        $stmt3 = $conexion->prepare($sql3);
        $stmt3->bind_param("sssssi", 
            $datos_d['calle'], 
            $datos_d['numero'], 
            $datos_d['ciudad'], 
            $datos_d['codigo_postal'], 
            $datos_d['referencias'], 
            $id_dir
        );
        $stmt3->execute();

        $conexion->commit();
        return ['success' => true, 'message' => 'Cambios guardados'];

    } catch (Exception $e) {
        $conexion->rollback();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}


function obtenerRangosDimensiones($conexion) {
    global $conexion;
    $sql = "SELECT * FROM configuracion_precios ORDER BY precio_min ASC";
    $resultado = mysqli_query($conexion, $sql);
    
    $dimensiones = [];
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $dimensiones[] = $fila;
        }
    }
    return $dimensiones;
}


/**
 * Confirma el precio de un pedido y cambia su estado a Confirmado
 */
function confirmar_precio_pedido($id_pedido, $precio) {
    global $conexion;
    
    $estado_confirmado = 'Confirmado';
    $sql = "UPDATE pedidos SET precio_final = ?, estado_precio = ? WHERE id = ?";
    
    $stmt = $conexion->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("dsi", $precio, $estado_confirmado, $id_pedido);
    $resultado = $stmt->execute();
    $stmt->close();
    
    return $resultado;
}


//para recuperar contraseñas
/**
 * Genera un token de recuperación y lo guarda en la DB
 */
function generarTokenRecuperacion($email, $conexion) {
    // 1. Verificar si el email existe
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 0) {
        $stmt->close();
        return false;
    }
    $stmt->close(); // Importante cerrar para evitar el error "Commands out of sync"
    // Creamos token y expiración
    $token = bin2hex(random_bytes(32));
    $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

    //Guardamos en DB
    $update = $conexion->prepare("UPDATE usuarios SET reset_token = ?, token_expira = ? WHERE email = ?");
    $update->bind_param("sss", $token, $expira, $email); // Tres strings: token, fecha y email
    $resultado_final = $update->execute();
    $update->close();

    return $resultado_final ? $token : false;
}

/**
 * Valida un token y actualiza la contraseña del usuario
 */
function actualizarPasswordConToken($token, $nueva_password, $conexion) {
    // 1. Hashear la nueva contraseña
    $password_hasheada = password_hash($nueva_password, PASSWORD_BCRYPT);

    // 2. Actualizar si el token es válido y no ha expirado
    $sql = "UPDATE usuarios SET 
            password = ?, 
            reset_token = NULL, 
            token_expira = NULL 
            WHERE reset_token = ? AND token_expira > NOW()";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $password_hasheada, $token);
    $stmt->execute();
    $afectados = $stmt->affected_rows; // Verifica si realmente se cambió algo
    $stmt->close();

    return $afectados > 0;
}

//Funciones no usadas

// function actualizar_rol_usuario($id_usuario, $nuevo_rol) {
//     global $conexion;
//     $sql = "UPDATE usuarios SET rol = ? WHERE id = ?";
//     $stmt = $conexion->prepare($sql);
//     if ($stmt === false) { return false; }
//     // El nuevo rol es cadena ('s'); el ID es entero ('i').
//     $stmt->bind_param('si', $nuevo_rol, $id_usuario);
//     if ($stmt->execute()) {
//         $exito = $stmt->affected_rows > 0;
//         $stmt->close();
//         return $exito;
//     } else {
//         error_log("Error al actualizar rol de usuario: " . $stmt->error);
//         $stmt->close();
//         return false;
//     }
// }

// function actualizar_estados_masivamente(array $pedidos_ids, $nuevo_estado, $id_comisionista) {
//     global $conexion;
    
//     if (empty($pedidos_ids)) {
//         return 0;
//     }
    
//     // 1. Crear placeholders para la cláusula IN (?, ?, ?)
//     $placeholders = implode(',', array_fill(0, count($pedidos_ids), '?'));
    
//     $sql = "UPDATE pedidos SET estado = ? WHERE id IN ($placeholders) AND id_comisionista = ?";
    
//     $stmt = $conexion->prepare($sql);
    
//     if (!$stmt) {
//         error_log("Error de preparación SQL masiva: " . $conexion->error);
//         return -1;
//     }
    
//     // 2. Determinar tipos y parámetros
//     // 's' para el estado, 'i' repetido para cada ID de pedido, 'i' para el ID del comisionista.
//     $tipos = 's' . str_repeat('i', count($pedidos_ids)) . 'i';
//     $params = array_merge([$nuevo_estado], $pedidos_ids, [$id_comisionista]);

//     // Usar bind_param dinámicamente
//     $refs = [];
//     foreach ($params as $key => $value) {
//         $refs[$key] = &$params[$key];
//     }
    
//     call_user_func_array([$stmt, 'bind_param'], array_merge([$tipos], $refs));
    
//     $exito = $stmt->execute();
    
//     if (!$exito) {
//         error_log("Error de ejecución SQL masiva: " . $stmt->error);
//         $stmt->close();
//         return -1;
//     }
    
//     $filas_afectadas = $stmt->affected_rows;
//     $stmt->close();
    
//     return $filas_afectadas;
// }

