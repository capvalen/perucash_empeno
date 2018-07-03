<?php   ?>
<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Registro: PeruCash</title>

		<!-- Bootstrap Core CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Custom CSS -->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.6" >
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.5">
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.51" >
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
		<link rel="stylesheet" href="css/bootstrap-select.min.css?version=0.2" >
		<link rel="stylesheet" href="css/animate.css" >
		
</head>

<body>

<style>
.btnAgregarAlmacen{padding: 2px;}
</style>
<div id="wrapper">
	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
	<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable ">
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Almacén <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="row">
			<div class="col-md-6">
				<select class="form-control" id="cmbEstantes">
					<?php require 'php/listarEstantesOPT.php'; ?>
				</select>
			</div>
			</div>
			<br>
			<div class="row container-fluid table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center" >Piso / Sección</th>
							<th class="text-center" >A</th>
							<th class="text-center" >B</th>
							<th class="text-center" >C</th>
							<th class="text-center" >D</th>
							<th class="text-center" >E</th>
							<th class="text-center" >F</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="text-center">1</th>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
						</tr>
						<tr>
							<th class="text-center">2</th>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
						</tr>
						<tr>
							<th class="text-center">3</th>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
						</tr>
						<tr>
							<th class="text-center">4</th>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
						</tr>
						<tr>
							<th class="text-center">5</th>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
							<td><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
						</tr>
					</tbody>
				</table>
			</div>

				
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->



<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
$.interesGlobal=4;
datosUsuario();

$(document).ready(function(){
	console.log( '0');
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});

</script>
<?php } ?>
</body>

</html>