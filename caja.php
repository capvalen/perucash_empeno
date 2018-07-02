<?php
if (!isset($_GET['fecha'])) { //si existe lista fecha requerida
	$_GET['fecha']=date('Y-m-d');
}
 ?>
<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Caja: PeruCash</title>

		<!-- Bootstrap Core CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Custom CSS -->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.7" >
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.51" >
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
		<link rel="stylesheet" href="css/bootstrap-select.min.css?version=0.2" >
		<link rel="stylesheet" href="css/animate.css" >

		<link rel="stylesheet" href="css/bootstrap-material-datetimepicker.css?version=2.0.2" />
		<link rel="stylesheet" href="iconfont/material-icons.css"> <!--Iconos en: https://design.google.com/icons/-->

</head>

<body>
<style>
#overlay {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.65); /* Black background with opacity */
    z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
   /* Add a pointer on hover */
}
#overlay .text{position: absolute;
    top: 50%;
    left: 50%;
    font-size: 36px;
    color: white;
    user-select: none;
    transform: translate(-50%,-50%);}
hr{ margin-bottom: 5px;}
h3{ margin-top: 5px;}
.pheader{background-color: #a35bb4;padding: 10px 10px; color: white; font-size: 17px; display: block;
clear: left; }

table{color:#5f5f5f;}
th{color:#a35bb4}
#dtpFechaIniciov3{color: #a35bb4;}
#txtMontoApertura, #txtMontoCierre {font-size: 26px;}
</style>
<div id="overlay">
	<div class="text"><i class="icofont icofont-leaf"></i> Guardando data...</div>
</div>

<div id="wrapper">
	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
	<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid">

		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable contenedorDatosCliente">
			<!-- Empieza a meter contenido 2 -->
			<h2 class="purple-text text-lighten-1">Cuadre de caja <small><?php echo $_COOKIE['ckAtiende']; ?></small></h2>
			<div class="container-fluid">
				<div class="row col-sm-7"><h3 class="purple-text" style="margin-top: 21px;"><span class="glyphicon glyphicon-piggy-bank"></span> Reporte de caja </h3></div>
			</div>
			
			<div class="container-fluid">
					<p class="pheader col-xs-7">Acciones en caja</p>
					<div class="panel panel-default container-fluid" style="padding: 18px 0;">
						<!-- <div class="col-xs-12 col-sm-6 text-center">
							<button class="btn btn-azul btn-outline btn-lg" id="btnCajaAbrir"><i class="icofont icofont-coins"></i> Aperturar Caja</button>
						</div>
						<div class="col-xs-12 col-sm-6 text-center">
							<button class="btn btn-warning btn-outline btn-lg" id="btnCajaCerrar"><i class="icofont icofont-money-bag"></i> Cerrar caja</button>
						</div> -->
						<?php require 'php/cajaActivaHoy.php'; ?>
					</div>
			</div>
			
			<div class="container-fluid  ">
				<p class="pheader col-xs-7">Filtros</p>
				<div class="panel panel-default container-fluid ">
					<div class=" col-xs-12 col-sm-7 ">
						<div style="padding: 10px;">
							<p style="color: #a35bb4;">Por: <?php require "php/historialCierres.php"; ?></p>
								<p style="color: #a35bb4;">Fecha: <strong id="strFechaAhora"></strong></p>
						</div>
					</div>
					<div class="col-xs-12 col-sm-5">
						<p style="color: #a35bb4;"><strong>Seleccione fecha de reporte:</strong></p>
							<input type="text" id="dtpFechaIniciov3" class="form-control text-center" placeholder="Fecha para controlar citas">
						<!--<div class="sandbox-container"><input id="dtpFechaIniciov3" type="text" class="form-control text-center inputConIco" placeholder="" style="color: #a35bb4;" autocomplete="off"> <span class="icoTransparent"><i class="icofont icofont-caret-down"></i></span></div> -->
					</div>
				</div>
			</div>

<!-- 			<div class="container-fluid">
	<p class="pheader col-xs-6">Clientes atentidos</p>
	<div class=" panel panel-default  ">
		<table class="table table-hover">
		<thead>
			<tr> <th>ID Caja</th> <th>Producto</th> <th>Datos de Cliente</th> <th>Razón <i class="icofont icofont-long-arrow-right"></i> Usuario</th> <th>Cantidad</th> </tr> </thead>
		<tbody>
			<?php
			require_once 'php/reporteCajaDiaTR.php';
			?>
		</tbody> </table>
	</div>
</div> -->
			<div class="container-fluid col-xs-12 ">
				<h4 class="pheader">Entradas de dinero</h4>
				<div class=" panel panel-default">
					<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Motivo de ingreso</th> <th>Usuario</th> <th>Cantidad</th> </tr> </thead>
					<tbody>
					<?php
						require_once 'php/reporteIngresoDia.php';
					?>
					</tbody> </table>
				</div>
			</div>
			<div class="container-fluid col-xs-12 ">
				<h4 class="pheader">Salidas de dinero</h4>
				<div class=" panel panel-default  ">
					<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Motivo de egreso</th> <th>Usuario</th> <th>Cantidad</th> </tr> </thead>
					<tbody>
					<?php
						require_once 'php/reporteEgresoDia.php';
					?>
					</tbody> </table>
				</div>
			</div>
			<div class="container-fluid col-xs-12 text-center">
				<h4 class="pheader">Efectivo total del día: <strong>S/ <span id="spanResultadoFinal"></span></strong></h4>
			</div>
			<!-- Fin de contenido 2 -->
			</div>

        </div>
    </div>    
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<?php if( $_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 ){ ?>
<!-- Modal para Abrir caja  -->
<div class="modal fade modal-aperturarCaja" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-primary">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Apertura de caja</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
				<p>¿Con qué monto inicias?</p>
				<input type="number" class="form-control input-lg text-center esDecimal" id="txtMontoApertura" value="0.00">
				<p>¿Alguna observación?</p>
				<input type="text" class="form-control input-lg text-center" id="txtObsApertura">
			</div>
		</div>
		<div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>	<br>
		<div class="modal-footer">
			<button class="btn btn-azul btn-outline" id="btnGuardarApertura"><i class="icofont icofont-save"></i> Guardar</button>
		</div>
	</div>
	</div>
</div>
</div>

<!-- Modal para Cerrar caja  -->
<div class="modal fade modal-cerrarCaja" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-warning">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Cierre de caja</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
				<p>¿Con qué monto estás cerrando?</p>
				<input type="number" class="form-control input-lg text-center esDecimal" id="txtMontoCierre" value="0.00">
				<p>¿Alguna observación?</p>
				<input type="text" class="form-control input-lg text-center" id="txtObsCierre">
			</div>
		</div>
		<div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>	<br>
		<div class="modal-footer">
			<button class="btn btn-warning btn-outline" id="btnGuardarCierre"><i class="icofont icofont-save"></i> Guardar</button>
		</div>
	</div>
	</div>
</div>
</div>
<?php } ?>

<?php include 'footer.php'; ?>
<script type="text/javascript" src="js/moment-precise-range.js"></script>
<script type="text/javascript" src="js/bootstrap-material-datetimepicker.js?version=2.0.5"></script>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<!-- Menu Toggle Script -->
<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();



$('#dtpFechaIniciov3').val('<?php
		if (isset($_GET['fecha'])) { //si existe lista fecha requerida
			$date = new DateTime($_GET['fecha']);
			echo  $date->format('d/m/Y');
		}else{ //sino existe lista la fecha de hoy
			echo date('d/m/Y');
		}
		?>');
moment.locale('es');

$('#spanResultadoFinal').text(parseFloat(parseFloat($('#strSumaEntrada').text() ) - parseFloat($('#strSumaSalida').text() )).toFixed(2));
$('#dtpFechaIniciov3').change(function () {
	//console.log(moment($('#dtpFechaIniciov3').val(), 'DD/MM/YYYY').isValid())
	if(moment($('#dtpFechaIniciov3').val(), 'DD/MM/YYYY').isValid()){
		window.location='caja.php?fecha='+encodeURIComponent( moment($('#dtpFechaIniciov3').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') );
	}
});
$('#dtpFechaIniciov3').bootstrapMaterialDatePicker({
	format: 'DD/MM/YYYY',
	lang: 'es',
	time: false,
	weekStart: 1,
	cancelText : 'Cerrar',
	nowButton : true,
	switchOnClick : true,
	okText: 'Aceptar', nowText: 'Hoy'
});
$('#btnCajaAbrir').click(function () {
	$('.modal-aperturarCaja').modal('show');
});
$('#btnGuardarApertura').click(function () {
	var monto = parseFloat($('#txtMontoApertura').val());
	var obs = $('#txtObsApertura').val();

	if( $('#txtMontoApertura').val() == '' || monto <0){
		$('.modal-aperturarCaja .divError').removeClass('hidden').find('.spanError').text('Error con el monto'); 
	}else{
		$.ajax({url: 'php/cajaAperturar.php', type: 'POST', data:{
			monto: monto, obs: obs
		}}).done((resp)=> { //console.log(resp);
			if(resp==1){
				location.reload();
			}
		});
	}
});
$('#btnCajaCerrar').click(()=> {
	$('.modal-cerrarCaja').modal('show');
});
$('#btnGuardarCierre').click(function () {
	var monto = parseFloat($('#txtMontoCierre').val());
	var obs = $('#txtObsCierre').val();

	if( $('#txtMontoCierre').val() == '' || monto <0){
		$('.modal-cerrarCaja .divError').removeClass('hidden').find('.spanError').text('Error con el monto de cierre'); 
	}else{
		$.ajax({url: 'php/cajaCierreHoy.php', type: 'POST', data:{
			monto: monto, obs: obs
		}}).done((resp)=> {
			//location.reload();
			$('#btnCajaCerrar').remove();
			$('.modal-cerrarCaja').modal('hide');
			$('.modal-GuardadoCorrecto #spanBien').text('¿Deseas imprimir el ticket de cierre?');
			$('.modal-GuardadoCorrecto #h1Bien').html( '<button class="btn btn-negro btn-outline" id="btnPrintTCierre"><i class="icofont icofont-print"></i> Ticket de cierre</button>');
			$('.modal-GuardadoCorrecto').modal('show');
		});
	}
});
$('.modal-GuardadoCorrecto').on('click', '#btnPrintTCierre', function (e) {
	console.log('hola');
});
</script>
<?php } ?>

</body>

</html>
