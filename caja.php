<?php
if (!isset($_GET['fecha'])) { //si existe lista fecha requerida
	$_GET['fecha']=date('Y-m-d');
}
 ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Caja: PeruCash</title>
	<?php include "header.php"; ?>
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
.pheader li>a{color: #a35bb4;}
.pheader li>a:hover{color: #a35bb4;background: #f2f2f2;}

table{color:#5f5f5f;}
th{color:#a35bb4}
#dtpFechaIniciov3{color: #a35bb4;}
#txtMontoApertura, #txtMontoCierre, #txtMontoPagos {font-size: 26px;}
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
		<div class="row "> <!-- noSelect -->
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

			<div class="container-fluid col-xs-12 ">
				<div class="pheader">
					<h4 >Entradas de dinero </h4>
					<?php 
					if(date('Y-m-d')==$_GET['fecha']){ ?>
						<div class="dropdown">
							<button class="btn btn-default dropdown-toggle pull-right " type="button" id="dropdownEntradas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-top: -37px; color: #a35bb4;"><i class="icofont icofont-ui-rate-add"></i> <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownEntradas">
								<?php include "php/omitidasEntradasLI.php"; ?>
							</ul>
						</div>
					<?php } ?>
				</div>
				
				<div class=" panel panel-default">
					<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Producto</th> <th>Motivo de ingreso</th> <th>Usuario</th> <th>Cantidad</th> <th>Moneda</th> <th>Obs.</th> </tr> </thead>
					<tbody>
					<?php
						require_once 'php/reporteIngresoDia.php';
					?>
					</tbody> </table>
				</div>
			</div>
			<div class="container-fluid col-xs-12 ">
				<div class="pheader">
					<h4>Salidas de dinero</h4>
					<?php 
					if(date('Y-m-d')==$_GET['fecha']){ ?>
						<div class="dropdown">
							<button class="btn btn-default dropdown-toggle pull-right " type="button" id="dropdownEntradas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="margin-top: -37px; color: #a35bb4;"><i class="icofont icofont-ui-rate-remove"></i> <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownEntradas">
								<?php include "php/omitidasSalidasLI.php"; ?>
							</ul>
						</div>
					<?php } ?>
				</div>
				<div class=" panel panel-default  ">
					<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Producto</th> <th>Motivo de egreso</th> <th>Usuario</th> <th>Cantidad</th> <th>Moneda</th> <th>Obs.</th> </tr> </thead>
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

<div class="modal fade modal-pagoMaestro" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Insertar proceso especial</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<p>Rellene cuidadosamente la siguiente información</p>
					<label for="">Tipo de proceso</label>
					<div id="cmbEstadoPagos">
					<h5 id="h5TipoPago"></h5></div>
					<label for="">Monto S/</label>
					<input type="number" class="form-control input-lg mayuscula text-center esMoneda" id="txtMontoPagos" val="0.00" autocomplete="off">
					<label for="">Método de pago</label>
					<div id="divCmbMetodoPago">
						<select class="form-control selectpicker" id="sltMetodopago" title="Métodos..."  data-width="100%" data-live-search="true" data-size="15">
							<?php include 'php/listarMonedaOPT.php'; ?>
						</select>
					</div> <br>
					<label for="">¿Observaciones?</label>
					<input type="text" class="form-control input-lg mayuscula" id="txtObsPagos" autocomplete="off">
					<div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError">La cantidad de producto no puede ser cero o negativo.</span></div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-azul btn-outline" id="btnInsertPagoOmiso" ><i class="icofont icofont-bubble-down"></i> Insertar proceso</button>
		</div>
		</div>
	</div>
</div>

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
<script type="text/javascript" src="js/bootstrap-material-datetimepicker.js?version=2.0.6"></script>
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

$('#spanResultadoFinal').text(parseFloat(parseFloat($('#strSumaEntrada').text().replace(',', '.') ) - parseFloat($('#strSumaSalida').text().replace(',', '.') )).toFixed(2));
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
		}}).done((resp)=> { console.log(resp);
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
$('.aLiProcesos').click(function() {
	//console.log($(this).attr('data-id'));
	$('#h5TipoPago').text($(this).text());
	$('#cmbEstadoPagos').attr('data-id', $(this).attr('data-id') );
	$('.modal-pagoMaestro').modal('show');
});
$(".modal-pagoMaestro").on("shown.bs.modal", function () { $('#txtMontoPagos').val('0.00').focus(); });
<?php if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8) { ?>
$('#btnInsertPagoOmiso').click(()=> {
	var idMoneda= $('#divCmbMetodoPago').find('.selected a').attr('data-tokens');
	if(idMoneda == null ){
		$('.modal-pagoMaestro .divError').removeClass('hidden').find('.spanError').text('Debes seleccionar un método de pago primero');
	}else{
		$.ajax({url: 'php/insertarProcesoOmiso.php', type: 'POST', data: {
			tipo: $('#cmbEstadoPagos').attr('data-id'),
			valor: $('#txtMontoPagos').val(),
			moneda: idMoneda,
			obs: $('#txtObsPagos').val()
		}}).done((resp)=> {
			if(resp== true){
				location.reload();
			}else{
				$('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: \n' + resp);
				$('.modal-GuardadoError').modal('show');
			}
		});
	}
});
<?php } ?>









//
</script>
<?php } ?>

</body>

</html>
