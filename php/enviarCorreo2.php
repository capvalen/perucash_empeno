<?php 

require 'phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer();

$mail -> Host = 'smtp.gmail.com';

$mail -> isSMTP();

$mail-> SMTPAuth = true;

$mail-> Username = 'informes@perucash.com';

$mail-> Password = 'AAAfAve8eST3g';

$mail-> SMTPSecure = 'ssl';

$mail-> Port= 465;

$mail-> Subject = 'test';

$mail-> Body = 'este es el cuerpo';

$mail-> setFrom ( 'informes@perucash.com', 'Informes');

$mail-> addAddress( 'infocat2.0@gmail.com');

if($mail-> send())
	echo "Mensaje enviado";
else
	echo "Hubo un error". $mail->ErrorInfo;;

 ?>