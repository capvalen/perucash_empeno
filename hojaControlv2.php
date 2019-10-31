<?php
$agencia = $_COOKIE['ckSucursal'];
if($_GET['compra'] =='0'){ $compra = 'Artículo en garantía';}else{ $compra = 'Compra';}
$dueno= ucwords($_GET['dueno']);
$code= $_GET['idProd'];
$clase = $_GET['clase'];
if($_GET['und']==1){$und = $_GET['und'].' Und.';}else{ $und = $_GET['und'].' Unds.';}
$producto = ucwords($_GET['producto']);

$fecha = str_replace ('a las ', '', $_GET['fecha']);
$usuario= $_GET['usuario'];
$dni= $_GET['dni'];
$precio = number_format($_GET['precio'],2);

?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Hoja Informativa</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<style>
.mayuscula{text-transform: capitalize;}
.fuerte{font-weight: 700;}
.masJunto{margin-top: 5px;margin-bottom: 5px;}
img{margin: 0 auto; width: 80%;}
hr{margin-top: 10px;margin-bottom: 10px;}
#primDiv{border-right: 1px dashed #808080;}

</style>
<div class="container">
	<div class="row">
	<div class="col-xs-6 text-center" id="primDiv">
		<div class="col-xs-12" id="logoEmpresa"><img src="images/empresa.png?version=1.1" class="img-responsive" alt="">
		<h4 class='fuerte'>Hoja Informativa «<?= $agencia; ?>»</h4>
		<hr>
		<h1 class="fuerte masJunto">#<?= $code; ?></h1>
		<h4 class='masJunto'><?= $und; ?> <?= $clase; ?></h4>
		<h3 class='masJunto'><?= $producto; ?></h3>
		<h3 class='masJunto'>S/ <?= $precio; ?></h3>
		<hr>
		<h4><?= $dueno; ?></h4>
		<h4><?= $dni; ?></h4>
		<hr>
		<h4><?= $compra; ?></h4>
		<h5><?= $fecha; ?></h5>
		<h5><?= $usuario; ?></h5>
		</div>
	</div>

	<div class="col-xs-6 text-center">
		<div class="col-xs-12" id="logoEmpresa"><img src="images/empresa.png?version=1.1" class="img-responsive" alt="">
		<h4 class='fuerte'>Hoja Informativa «<?= $agencia; ?>»</h4>
		<hr>
		<h1 class="fuerte masJunto">#<?= $code; ?></h1>
		<h4 class='masJunto'><?= $und; ?> <?= $clase; ?></h4>
		<h3 class='masJunto'><?= $producto; ?></h3>
		<h3 class='masJunto'>S/ <?= $precio; ?></h3>
		<hr>
		<h4><?= $dueno; ?></h4>
		<h4><?= $dni; ?></h4>
		<hr>
		<h4><?= $compra; ?></h4>
		<h5><?= $fecha; ?></h5>
		<h5><?= $usuario; ?></h5>
		</div>
	</div>

	</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
window.print();	//Activa la impresion apenas cargo todo
});
/*Determina si se imprimio o se cancelo, para cerrar la pesataña activa*/
/* (function () {
	var afterPrint = function () {
	window.top.close();
	};
	if (window.matchMedia) {
		var mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function (mql) {
				//alert($(mediaQueryList).html());
				if (mql.matches) {
				} else { afterPrint(); }
		});
	}
	window.onafterprint = afterPrint;
}()); */
</script>
</div>
</body>
</html>