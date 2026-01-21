<?php
/**
 * ARCHIVO DE EJEMPLO DE CONFIGURACIÓN DE CORREO (SMTP)
 * Renombra este archivo a 'email_config.php' y completa con tus credenciales.
 */

// Configuración de SMTP para el envío de correos
define('SMTP_HOST', 'smtp.gmail.com');          // Servidor (ej: smtp.gmail.com)
define('SMTP_USER', 'TU_EMAIL@gmail.com');      // Tu dirección de correo
define('SMTP_PASS', 'TU_CLAVE_DE_APLICACION');  // Clave de aplicación generada en tu cuenta
define('SMTP_PORT', 587);                       // Puerto (587 para TLS)
define('SMTP_FROM', 'no-reply@tu-dominio.com'); // Remitente
define('SMTP_NAME', 'Nombre de tu Sistema');    // Nombre que aparecerá en el correo

/**
 * NOTA: Si usas Gmail, recuerda generar una "Contraseña de aplicación" 
 * desde la configuración de seguridad de tu cuenta de Google.
 */
?>