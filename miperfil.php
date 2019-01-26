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
			<div class="col-lg-12 contenedorDeslizable  container-fluid">
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Mi perfil <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="row">
            <div class="col-xs-12 col-sm-6 col-lg-3 container-fluid">
               <h4>Mis datos:</h4>
               <label for="">Mi nombre</label> <input type="text" id="txtNombre" class="form-control" readonly value="<?= $_COOKIE['cknomCompleto']?>">
               <label for="">Cambiar contraseña:</label> <input type="text" id="txtNombre" class="form-control">
                <input type="text" id="txtNombre" class="form-control">
            </div>
            <div class="col-xs-12 col-sm-6 col-lg-3 "></div>
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
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});

</script>
<?php } ?>
</body>

</html>