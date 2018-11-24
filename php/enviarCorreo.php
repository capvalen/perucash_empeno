<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 'On');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

require "conkarl.php";

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';


$sql="SELECT usuEMail, s.nomBD FROM `usuario` u
left join sucursal s on u.idSucursal = s.idSucursal
where usuNick = '{$_POST['queUser']}' and s.idSucursal = 1;";

$resultado=$cadena->query($sql);
if ($resultado->num_rows==1){
    $row=$resultado->fetch_assoc();
    
    
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        ///$mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.mailgun.org';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'login@compramostodo.com';                 // SMTP username
        $mail->Password = '';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
    
        //Recipients
        $mail->setFrom('servidor@infocatsoluciones.com', 'Servidor infocat');
        $mail->addAddress("{$row['usuEMail']}");     // Add a recipient
        //$mail->addAddress($_POST['correo']);               // Name is optional
    
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Mensaje desde la web infocat';
        $mail->Body    = 'Ud solicitó el siguiente enlace de restablecimiento de contraseña en la plataforma PeruCash.com.'.'<br><br>'.
        "Link generado para {$_POST['queUser']}: ".
        "<a href='http://perucash.com".str_replace( 'php/enviarCorreo.php', '', $_SERVER['REQUEST_URI'])."newLogin.php?solicita=".$base58->encode($row['usuEMail'])."'>Continuar por acá...</a>"."<br>".
        '------------------------------------------------------'.'<br>'.
        'Enviado desde el formulario de <a href="https://perucash.com">Peru Cash</a>'.'<br>'.
        '<em>No responda a éste mensaje autogenerado.<em>';
    
        $mail->send();
        echo 'Mensaje enviado, en breve le respondremos. Saludos';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}


?>