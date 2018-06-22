<?php session_start();
include 'php/conkarl.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Configuraciones: PeruCash</title>

		<!-- Bootstrap Core CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Custom CSS -->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.5" >
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.51" >
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
		<link rel="stylesheet" href="css/bootstrap-select.min.css?version=0.2" >
		<link rel="stylesheet" href="css/animate.css" >
		
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