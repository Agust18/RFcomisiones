<?php
/**
 * ARCHIVO DE EJEMPLO DE CONFIGURACIÓN
 * Renombra este archivo a 'db.php' y completa tus credenciales.
 */

if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    // Ajusta '/RFcomisiones/' si tu carpeta tiene otro nombre
    define('BASE_URL', $protocol . $host . '/RFcomisiones/');
}

// Configuración de la base de datos
$servidor = "localhost"; 
$usuario  = "TU_USUARIO";      // Ejemplo 
$password = "TU_PASSWORD";     // Ejemplo 
$database = "nombre_tu_db";    // Ejemplo 

// Conexión con MySQLi 
$conexion = new mysqli($servidor, $usuario, $password, $database);

// Verificación de conexión
if ($conexion->connect_error){
    die("❌ Error de conexión a la Data Base: " . $conexion->connect_error);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


?>