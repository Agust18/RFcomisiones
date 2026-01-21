<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Llamamos a los archivos de tu carpeta libs
require_once __DIR__ . '/../../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer/SMTP.php';

class Mailer {
    public static function enviarRecuperacion($emailDestino, $token) {
        $config = require __DIR__ . '/../config/mail.php';
        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->SMTPAuth   = $config['auth'];
            $mail->Username   = $config['user'];
            $mail->Password   = $config['pass'];
            $mail->SMTPSecure = $config['secure'];
            $mail->Port       = $config['port'];
            $mail->CharSet    = 'UTF-8';

            // Destinatarios
            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($emailDestino);

            // Contenido (Diseño minimalista RFcomisiones)
            $mail->isHTML(true);
            $mail->Subject = 'Recuperar Contraseña - RFcomisiones';
            
            $enlace = "https://tusitio.com/reset-password?token=" . $token;

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 40px; border: 1px solid #f0f0f0; border-radius: 20px;'>
                    <h2 style='color: #0d6efd; margin-bottom: 20px;'>RFcomisiones</h2>
                    <p style='color: #444;'>Hola, solicitaste restablecer tu contraseña. Haz clic en el botón para continuar:</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='$enlace' style='background: #0d6efd; color: white; padding: 12px 25px; text-decoration: none; border-radius: 50px; font-weight: bold;'>RESTABLECER AHORA</a>
                    </div>
                    <p style='font-size: 12px; color: #999;'>Si no hiciste esta solicitud, ignora este mensaje.</p>
                </div>
            ";

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
