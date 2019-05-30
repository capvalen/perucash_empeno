<?php 
session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Perucash - Sistema para control de préstamos y empeños</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="css/icofont.css">


<style>
body{ background-color: #8627b9;}
#delivery{color:#d40000}
.display-5{font-size: 2rem;font-weight: 300;line-height: 1.2;}
.display-6{font-size: 1.5rem;font-weight: 300;line-height: 1.2;}
</style>
</head>

<body class="mb-5 pb-5">
<div class="container  text-center mb-5 pb-5" >
	<img  class="img-responsive py-5" style="width: 350px;" src="http://perucash.com/app/images/empresa.png?version=1.1" alt="">
	<img  class="img-responsive py-5" style="width: 350px;" src="images/gatostudio.jpg?ver=1.0.1" alt="">
		<h2 class="display-5 text-white pb-5">Hola <strong><?php echo $_SESSION['userData']['first_name']; ?></strong> Gracias por ingresar a nuestro website</h2>
		<h2 class="display-6 text-white pb-5">Ahora eres un invitado de Perucash <br> ¿Qué debes hacer ahora?<br>- Debes comunicarte con la administración o con soporte para que active tu cuenta.</h2>
   
	<a class="btn btn-dark" href="php/desconectar.php"><i class="icofont icofont-simple-left-up"></i> Cerrar sessión del aplicativo</a>
	
	

</div>
	
</body>
</html>