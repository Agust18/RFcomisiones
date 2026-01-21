<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// define('BASE_URL', "/");
// En config/db.php
if (!defined('BASE_URL')) {
    // Detecta si es http o https
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    // Detecta el dominio (localhost)
    $host = $_SERVER['HTTP_HOST'];
    // Define la constante con la carpeta del proyecto
    define('BASE_URL', $protocol . $host . '/RFcomisiones/');
}
// para conectarnos necesitamos 
// SERVIDOR O IP , USUARIO , PASSWORD ,DATABASE.

$servidor ="localhost"; // o 127.0.0.1
$usuario = "root"; 
$password = "root";
$database = "rfcomisiones";


// Declarar conexion con mysqly 
// declarar los parametros en el mismo orden declarado arriba
$conexion = new mysqli($servidor,$usuario,$password,$database);

// calcular un error
if ($conexion->connect_error){
    die("❌ Error de conexión a la Data Base: " . $conexion->connect_error); // Usamos die() para detener la ejecución si falla
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// NOTA IMPORTANTE: La variable $conexion ahora es GLOBAL.
// Si queremos usarla en otros archivos, debemos usar la palabra GLOBAL.
// Ejemplo:
// global $conexion;
// $conexion->query("SQL AQUI");
// OJO: Esto es solo si usamos funciones. Si no usamos funciones, no es necesario usar GLOBAL.
?>