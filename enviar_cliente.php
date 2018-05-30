<?php
$destino=$_POST["correoelectronico"];
$desde='From: Perucash<informes@perucash.com>'."\r\n";
$desde.='Content-type:text/html;charset=UTF-8';
$asunto='Análisis de crédito';
$mensaje='
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Interfaz de Envio al correo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body style="background-color: #f1f1f1">

<div class="header">
  <h2 style="font-family: Arial,Helvetica,sans-serif;letter-spacing: 1.1px; padding: 18px;text-align: center;background-color: #f1f1f1;color:#808080;font-size: 20px;margin-top:10px ;margin-bottom:0px">Perucash</h2>
</div>

<div class="row" style="margin-left:20% ">
  <div class="column" style="background-color: #f1f1f1; width: 20%">
  </div>
  <div class="column" style="background-color: #fff;width:520px; float: left;  width: 70%;  padding: 10px; border-radius:20px">
    <img src="http://www.perucash.com/wp-content/uploads/2018/05/hombre-pensando01.jpg" width="100%" height="auto" style="border-radius:20px 20px 0 0;" >
  </div>
  <div class="column" style="background-color: #f1f1f1;  width: 20%;  padding: 10px;  ">
  </div>
  <div style="width:100%">
    <div class="column" style="background-color: #f1f1f1;  width: 20%;  padding: 10px;  ">
    </div>
    <div class="column" style="padding: 12px; background-color:#ffffff;   width: 70%;  padding: 10px; border-radius: 20px;">
      <h2 style="font-family: Arial,Helvetica,sans-serif; color:#4c4c4c; text-align:center;">Recibimos tu solicitud!</h2>
      <p style="font-family:Arial,Helvetica,sans-serif;color: #6d6d6d; text-align: justify; line-height:1.3; font-size:15px; padding: 0 40px;">Hola <strong>'.ucwords($_POST['nombre']).'</strong>. Gracias por solicitar un préstamo en nuestra plataforma. Nuestro personal está evaluando su información, para que Ud. pueda acceder a su crédito de <strong>S/. '.number_format(($_POST['monto']),2).' </strong> en cuotas de<strong> '.$_POST['tiempo'].' meses.</strong> </p>
      <p style="font-family:Arial,Helvetica,sans-serif; color: #6d6d6d; text-align: justify; font-size: 0.8rem;line-height:1.7; padding: 0 40px;" > Éste correo es automático. Cualquier inquietud al respecto por favor comunicarse con nuestro equipo de Soporte a través de través de los siguientes +51 943798696 / +51 933747892.</p>
      <div  style="text-align:center;cursor: pointer;">
        <button type="button" style="text-size:18px;display: inline-block; background-color: #e51d27;cursor: pointer;font-family: Arial,Helvetica,sans-serif;font-weight: bold; width: 250px; padding: 15px 32px; margin-top: 40px; letter-spacing: 0.8px;border-radius: 25px" name="button">
          <a style="color:#ffffff;text-decoration:none" href="http://www.perucash.com/consejos-para-prestamos/"> Aquí lee unos consejos! </a>
        </button>
      </div>
      <div class="footer" style="text-align:center">
        <p><a style="margin-bottom: 40px; background-color: #fff; padding: 10px; text-align: center; text-decoration:none; text-size:15px; font-family:Arial,Helvetica,sans-serif;  color: Gray" href="http://www.perucash.com/terminos-y-condiciones/" target="_blank">Leer términos y condiciones. </a> </p>
      </div>
    </div>
    <div class="column" style="background-color: #f1f1f1;  width: 20%;  padding: 10px;">

    </div>
  </div>
</div>
</body>
</html>



';

if(mail($destino, $asunto, $mensaje, $desde)){
	echo 'Mensaje ha sido enviado';
}
else{
	echo 'Hubo un error enviando correo';
}

?>
