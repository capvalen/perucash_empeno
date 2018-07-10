<?php session_start();
include 'php/conkarl.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Configuraciones: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>

<style>

</style>
<div id="wrapper">

	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
	
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable ">
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Registro de Clientes, Productos y Compras <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="panel panel-default">
				<div class="panel-body">
					<p><strong>Inventario</strong></p>
			<?php $sql = mysqli_query($conection,"SELECT `inventarioActivo` FROM `configuraciones` WHERE 1");
				$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
				$activo= $row['inventarioActivo'];
				if($activo=='1'){ $activSi='checked';$activNo=''; }else{ $activSi='';$activNo='checked'; }
			?>
					<div class="row">
						<div class="col-sm-12 col-md-6"><p>Habilitar la opción para realizar un inventario el inventario</p></div>
						<div class="col-sm-12 col-md-6"><form action=""><label class="radio-inline">
						  <input type="radio" id="rdbActivarInventario" name="inlineRadioOptions" id="inlineRadio1" value="option1" <?php echo $activSi; ?>> Activo
						</label>
						<label class="radio-inline">
						  <input type="radio" id="rdbDesactivarInventario" name="inlineRadioOptions" id="inlineRadio2" value="option2" <?php echo $activNo; ?>> Desactivado
						</label></form></div>
					</div>
				</div>
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

<?php if ( isset($_COOKIE['ckidUsuario']) && $_COOKIE['ckPower']=='1'  ){?>

<script>
$.interesGlobal=4;
datosUsuario();

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});
$('#rdbActivarInventario').click(function () {
	$.ajax({url: 'php/activarInventario.php', type: 'POST'}).done(function (resp) { console.log(resp);
		if(resp=='1'){
			$('#spanBien').text('Se activó el inventario.');
			$('.modal-GuardadoCorrecto').modal('show');
		}else{
			$('#spanMalo').text('Hubo un error actualizando.');
			$('.modal-GuardadoError').modal('show');
		}
	});
});
$('#rdbDesactivarInventario').click(function () {
	$.ajax({url: 'php/desactivarInventario.php', type: 'POST'}).done(function (resp) { console.log(resp);
		if(resp=='1'){
			$('#spanBien').text('Se activó el inventario.');
			$('.modal-GuardadoCorrecto').modal('show');
		}else{
			$('#spanMalo').text('Hubo un error actualizando.');
			$('.modal-GuardadoError').modal('show');
		}
	});
});
</script>
<?php } ?>
</body>

</html>