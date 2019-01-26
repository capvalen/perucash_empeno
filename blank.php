<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Créditos: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>

<style>

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
			<h2 class="purple-text text-lighten-1">Registro de Clientes, Productos y Compras <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			

				
			<!-- Fin de contenido principal -->
			</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
   </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
$.interesGlobal=4;
datosUsuario();

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});

</script>
<?php } ?>
</body>

</html>