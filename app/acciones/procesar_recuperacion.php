<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../funciones/funciones.php';

// Rutas de las librerías (Ya verificamos que están en esta carpeta)
// Reemplazamos los require anteriores por estos:
require_once 'C:/MAMP/htdocs/RFcomisiones/libs/PHPMailer/Exception.php';
require_once 'C:/MAMP/htdocs/RFcomisiones/libs/PHPMailer/PHPMailer.php';
require_once 'C:/MAMP/htdocs/RFcomisiones/libs/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $token = generarTokenRecuperacion($email, $conexion);
    $base = "http://localhost/RFcomisiones/";
    $proyecto = "RFcomisiones"; 

    if ($token) {
       // Ruta exacta hacia el archivo físico
        // Así es como lo verá el usuario en su mail y navegador
$link = "http://localhost/RFcomisiones/reset_password?token=" . $token;
        // $link = BASE_URL . "reset_password?token=" . $token;
        $mail = new PHPMailer(true);

        try {
            // --- CONFIGURACIÓN GMAIL ---
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'agusstincamera@gmail.com';      // Tu correo de Gmail
            $mail->Password = 'bdms rucn ndxy vcpv';      // Tu clave de APP (16 letras)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Remitente y Destinatario
            $mail->setFrom('agusstincamera@gmail.com', 'RFcomisiones');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer password - RFcomisiones';
            $mail->Body = "
    <p>Para cambiar tu clave, haz clic en el siguiente botón:</p>
    <a href='$link' style='display:inline-block; background-color:#0d6efd; color:white; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold;'>
        RESTABLECER MI CONTRASEÑA
    </a>
    <br><br>
    <p>Si el botón no funciona, copia y pega este link en tu navegador:</p>
    <p>$link</p>";

            $mail->send();
            notificar('success', '¡Correo enviado!', "Revisá tu bandeja de entrada.");

        } catch (Exception $e) {
            die("Error enviando: " . $mail->ErrorInfo);
        }
    } else {
        notificar('success', 'Verificá tu correo', "Si el usuario existe, recibirás un mail.");
    }

    header("Location: " . BASE_URL . "solicitar_reset");
    exit;
}