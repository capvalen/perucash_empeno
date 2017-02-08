<!DOCTYPE html>
<html  lang="es">
<head>
<meta charset="UTF-8">
	<title></title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/anubis.css">
</head>
<body>
<main>
	<div class="row text-center"><strong>Listado de productos por recoger - PerúCash</strong></div><br>
	<div class="row"><strong>
		<div class="col-xs-3">Apellidos y Nombres</div>
		<div class="col-xs-2">Registro</div>
		<div class="col-xs-2">Caduca</div>
		<div class="col-xs-1">Móvil</div>
		<div class="col-xs-1">Monto S/.</div>
		<div class="col-xs-3">Garantía</div></strong>

	</div>
	<div  id="divNoFinalizados">
		<?php echo $_GET['codigoTabla']; ?>

	</div>
	<div class="push-left"><br> Total de clientes: <em id="totCli"></em>.</div>


	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/moment.js"></script>
	
</main>
</body>
</html>