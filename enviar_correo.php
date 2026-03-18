<?php

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';
require 'conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'omarolguin3529@gmail.com';
    $mail->Password = 'ezfefhxijlidntdx';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('omarolguin3529@gmail.com', 'Sistema de prueba');

    $sql = "SELECT email FROM usuarios WHERE tipo_usuario = 'administrador'";
    $resultado = $conexion->query($sql);
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $mail->addAddress($fila['email']);
        }}


    $mail->Subject = 'Prueba de correo';
    $mail->Body = 'Este es un correo de prueba enviado desde PHP';

    $mail->send();

    echo "Correo enviado correctamente";

} catch (Exception $e) {
    echo "Error al enviar: {$mail->ErrorInfo}";
}