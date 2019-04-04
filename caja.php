<?php
date_default_timezone_set('America/Lima');
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
hr{ margin-bottom: 5px;}
h3{ margin-top: 5px;}
.pheader{background-color: #a35bb4;padding: 10px 10px; color: white; font-size: 17px; display: block;
clear: left; }
.pheader li>a{color: #a35bb4;}
.pheader li>a:hover{color: #a35bb4;background: #f2f2f2;}
table{color:#5f5f5f;}
th{color:#a35bb4}
#dtpFechaIniciov3{color: #a35bb4;}
#txtMontoApertura, #txtMontoCierre, #txtMontoPagos, #txtPasarPagos {font-size: 26px;}
a{color: #a35bb4;}
a:focus, a:hover { color: #62286f; }
</style>


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

			<div class="container-fluid  ">
				<p class="pheader col-xs-12"><i class="icofont icofont-filter"></i> Filtros</p>
				<div class="panel panel-default container-fluid ">
					<div class="col-xs-12 col-sm-6 col-md-3">
						<p style="color: #a35bb4;"><strong>Seleccione fecha de reporte:</strong></p>
							<input type="text" id="dtpFechaIniciov3" class="form-control text-center" placeholder="Fecha para controlar citas">
						<!--<div class="sandbox-container"><input id="dtpFechaIniciov3" type="text" class="form-control text-center inputConIco" placeholder="" style="color: #a35bb4;" autocomplete="off"> <span class="icoTransparent"><i class="icofont icofont-caret-down"></i></span></div> -->
					</div>
					<div class=" col-xs-12 col-sm-6 col-md-4">
						<div>
              <p style="color: #a35bb4;"><strong>Por:</strong> </p> <?php require "php/historialCierres.php"; ?>
							<!-- <p style="color: #a35bb4;">Fecha: <strong id="strFechaAhora"></strong></p> -->
						</div>
					</div>
				</div>
			</div>
	
			<div class="container-fluid">
					<p class="pheader col-xs-12"><i class="icofont icofont-hard-disk"></i> Datos del cuadre</p>
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
			
			<?php if( isset($_GET['cuadre']) ): ?>		
			<div class="row container-fluid ">
				<div class="pheader">
					<h4> <i class="icofont icofont-plus-circle"></i> Entradas de dinero </h4>
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
				
				<div class=" panel panel-default" id="divEntradas">
					<div class="table-responsive">
						<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Producto</th> <th>Motivo de ingreso</th> <th>Usuario</th> <th>Cantidad</th> <th>Moneda</th> <th>Obs.</th> </tr> </thead>
						<tbody>
						<?php
						if( ! isset($_GET['cuadre']) ):
							// require_once 'php/reporteIngresoDia.php';+
							echo "<tr><td>Tiene que seleccionar en el filtro el cuadre que desea ver</td></tr>";
						else:
							require_once 'php/reporteIngresoDiaxCuadre.php';
						endif;
						?>
						</tbody> </table>
					</div>
				</div>
			</div>
			<div class="row container-fluid  ">
				<div class="pheader">
					<h4><i class="icofont icofont-minus-circle"></i> Salidas de dinero</h4>
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
				<div class=" panel panel-default " id="divSalidas">
					<div class="table-responsive">
						<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Producto</th> <th>Motivo de egreso</th> <th>Usuario</th> <th>Cantidad</th> <th>Moneda</th> <th>Obs.</th> </tr> </thead>
						<tbody>
						<?php
							if( ! isset($_GET['cuadre']) ):
								// require_once 'php/reporteEgresoDia.php';+
								echo "<tr><td>Tiene que seleccionar en el filtro el cuadre que desea ver</td></tr>";
							else:
								require_once 'php/reporteEgresoDiaxCuadre.php';
							endif;
						?>
						</tbody> </table>
					</div>
				</div>
			</div>
			<div class="row container-fluid ">
				<div class="pheader"><h4><i class="icofont icofont-fax"></i> Resumen <strong> <span id="spanResultadoFinal"></span></strong></h4></div>
				<div class="panel panel-default">
					<div class="container-fluid" style="padding:20px;">
						<p class=""><strong>Apertura:</strong> S/ <span id="spanAperturaDia"></span></p>
						<hr>
						<p class=""><strong>*** Entradas ***</strong></p>
						<p class=""><strong>Efectivo:</strong> S/ <span id="spanAperturaEfectivo"></span></p>
						<p class=""><strong>Tarjetas:</strong>  S/ <span id="spanAperturaTarjetas"></span></p>
						<p class=""><strong>Depósitos bancarios:</strong>  S/ <span id="spanAperturaBancos"></span></p>
						<hr>
						<p class=""><strong>*** Salidas ***</strong></p>
						<p class=""><strong>Efectivo:</strong>  S/ <span id="spanCierreEfectivo"></span></p>
						<p class=""><strong>Tarjetas:</strong>  S/ <span id="spanCierreTarjetas"></span></p>
						<p class=""><strong>Depósitos bancarios:</strong>  S/ <span id="spanCierreBancos"></span></p>
						<hr>
						<p class=""><strong>Cierre Efectivo Manual:</strong>  S/ <span id="spanTotalEfectivo"></span></p>
						<p class=""><strong>Cierre Efectivo Sistema:</strong>  S/ <span id="spanTotalSistema"></span></p>
						<hr>
						<p class=""><strong>Resumen: <span id="spanSobra"></span> </strong></p>
					</div>
				</div>
				
			</div>
	<?php endif; //if de isset ?>
			<!-- Fin de contenido 2 -->
			</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
    </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<?php if( $_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 || $_COOKIE['ckPower']==4 ){ ?>
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
					<label id="lblMontoEntregar" for="">Monto en efectivo: (S/)</label>
					<input type="number" class="form-control input-lg mayuscula text-center esMoneda" id="txtMontoPagos" val="0.00" autocomplete="off">
					<label id="lblMontoPasar" for="">Pasar por el P.O.S.: (S/)</label>
					<input type="number" class="form-control input-lg mayuscula text-center esMoneda" id="txtPasarPagos" val="0.00" autocomplete="off">
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
				<input type="number" class="form-control input-lg text-center esDecimal" id="txtMontoApertura" value="0.00" >
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
				<input type="text" class="form-control input-lg text-center" id="txtObsCierre" autocomplete="off">
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

<!-- Modal para Cambiar entrada de caja  -->
<div class="modal fade" id="modalCambiarEntradaCaja" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-warning">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Cambiar apertura de caja</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
				<p>¿Qué monto deseas poner en la apertura?</p>
				<input type="number" class="form-control input-lg text-center esDecimal inputGrande" id="txtMontoChangeApertura" value="0.00">
				<p>¿Alguna observación extra?</p>
				<input type="text" class="form-control input-lg text-center mayuscula" id="txtObsChangeApertura" autocomplete="off">
			</div>
		</div>
		<div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>	<br>
		<div class="modal-footer">
			<button class="btn btn-warning btn-outline" id="btnUpdateApertura"><i class="icofont icofont-save"></i> Actualizar</button>
		</div>
	</div>
	</div>
</div>
</div>

<!-- Modal para Cambiar salida de caja  -->
<div class="modal fade" id="modalCambiarSalidaCaja" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-warning">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Cambiar cierre de caja</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
				<p>¿Qué monto deseas poner en el cierre?</p>
				<input type="number" class="form-control input-lg text-center esDecimal inputGrande" id="txtMontoChangeCierre" value="0.00">
				<p>¿Alguna observación extra?</p>
				<input type="text" class="form-control input-lg text-center mayuscula" id="txtObsChangeCierre" autocomplete="off">
			</div>
		</div>
		<div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>	<br>
		<div class="modal-footer">
			<button class="btn btn-warning btn-outline" id="btnUpdateCierre"><i class="icofont icofont-save"></i> Actualizar</button>
		</div>
	</div>
	</div>
</div>
</div>
<!--Modal Para insertar pago maestro -->
<div class="modal fade modal-cajaMaestra" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Modificar pago caja</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<p>Rellene cuidadosamente la siguiente información</p>
					<label for="">Tipo de pago</label>
					<div id="cmbEstadoPagos2">
					<select class="selectpicker mayuscula" id="spTipoPago2" title="Tipos de pago..."  data-width="100%" data-live-search="true" data-size="15">
						<?php require 'php/detallePagosOPT.php'; ?>
					</select></div>
					<label for="">Fecha de pago</label>
					<input id="dtpCajaFechaPago" type="text" class="form-control input-lg text-center" autocomplete="off">
					<label class="hidden" for="">Método de pago</label>
					<div class="hidden" id="divCmbMetodoPago">
						<select class="form-control selectpicker" id="sltCajaMetodopago" title="Métodos..."  data-width="100%" data-live-search="true" data-size="15">
							<?php include 'php/listarMonedaOPT.php'; ?>
						</select>
					</div>
					<label for="">Métodos de pago</label>
					<div id="divCmbMetodoPago2">
						<select class="form-control selectpicker" id="sltMetodopago2" title="Métodos..."  data-width="100%" data-live-search="true" data-size="15">
							<?php include 'php/listarMonedaOPT.php'; ?>
						</select>
					</div> <br>
					<label for="">Monto de pago S/</label>
					<input type="number" class="form-control input-lg mayuscula text-center " id="txtCajaMontoPagos" autocomplete="off" style="font-size: 20px;">
					<label for="">¿Observaciones?</label>
					<input type="text" class="form-control input-lg mayuscula" id="txtCajaObsPagos" autocomplete="off">
					<label for="">¿Activo?</label>
					<select name="" id="sltActivoV2" class="form-control">
						<option value="0">Inactivo</option>
						<option value="1">Activo</option>
					</select>
					<div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-outline" id="btnUpdateCajaMaestra" ><i class="icofont icofont-bubble-down"></i> Actualizar registro caja</button>
		</div>
		</div>
	</div>
</div>
<?php } ?>

<?php include 'footer.php';?>
<script type="text/javascript" src="js/moment-precise-range.js"></script>
<script type="text/javascript" src="js/bootstrap-material-datetimepicker.js?version=2.0.8"></script>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<!-- Menu Toggle Script -->
<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();
$(document).ready(function () {
	$('#sltHistorialCierres').change(function () {
		window.location.href = 'caja.php?cuadre='+$('#sltHistorialCierres').val();
	});
});
$('#dtpFechaIniciov3').val('<?php
		if (isset($_GET['fecha'])) { //si existe lista fecha requerida
			$date = new DateTime($_GET['fecha']);
			echo  $date->format('d/m/Y');
		}else{ //sino existe lista la fecha de hoy
			echo date('d/m/Y');
		}
		?>');
moment.locale('es');
<? if(isset($_GET[cuadre])){ ?>
calculoTicketVirtual();

function calculoTicketVirtual() {

	var apertura = parseFloat($('#spanApertura').text().replace(',', '.'));
	var cierre = parseFloat($('#spanCierrev3').text().replace(',', '.'));
	var cuadre =0, sobra =0;

	var efectivosEntrada = parseFloat($('#strSumaEntrada').attr('data-efectivo').replace(',', '.'));
	var tarjetasEntrada = parseFloat($('#strSumaEntrada').attr('data-tarjeta').replace(',', '.'));
	var bancosEntrada = parseFloat($('#strSumaEntrada').attr('data-banco').replace(',', '.'));

	var efectivosSalida = parseFloat($('#strSumaSalida').attr('data-efectivo').replace(',', '.'));
	var tarjetasSalida = parseFloat($('#strSumaSalida').attr('data-tarjeta').replace(',', '.'));
	var bancosSalida = parseFloat($('#strSumaSalida').attr('data-banco').replace(',', '.'));

	$('#spanAperturaDia').text(apertura.toFixed(2));

	$('#spanAperturaEfectivo').text(efectivosEntrada.toFixed(2));
	$('#spanAperturaTarjetas').text(tarjetasEntrada.toFixed(2));
	$('#spanAperturaBancos').text(bancosEntrada.toFixed(2));

	$('#spanCierreEfectivo').text(efectivosSalida.toFixed(2));
	$('#spanCierreTarjetas').text(tarjetasSalida.toFixed(2));
	$('#spanCierreBancos').text(bancosSalida.toFixed(2));
	

	cuadre = parseFloat(apertura+efectivosEntrada-efectivosSalida-tarjetasSalida).toFixed(2);
	sobra = parseFloat(cuadre-cierre);
	$('#spanTotalEfectivo').text(cierre.toFixed(2));
	$('#spanTotalSistema').text( cuadre);
	
	if(sobra ==0){
		$('#spanSobra').text('Cuadre exacto');
	}
	if(sobra <0){
		$('#spanSobra').text('Falta S/ '+ (0-sobra).toFixed(2));		
	}
	if(sobra > 0){
		$('#spanSobra').text('Sobra S/ '+ sobra.toFixed(2));		
	}
	
	//$('#spanResultadoFinal').text(parseFloat( parseFloat($('#strSumaEntrada').text().replace(',', '.')) - parseFloat($('#strSumaSalida').text().replace(',', '.')) + parseFloat($('#spanApertura').text().replace(',', '.')) ).toFixed(2));
}
<? } ?>

<?php if(isset($_GET['cuadre'])){ ?>
	$('#sltHistorialCierres').val(<?php echo $_GET['cuadre']; ?>);
<?php } ?>
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
$('#dtpCajaFechaPago').bootstrapMaterialDatePicker({
	format: 'DD/MM/YYYY h:mm a',
	lang: 'es',
	shortTime : true,
	weekStart: 1,
	cancelText : 'Cerrar',
	nowButton : true,
	switchOnClick : true,
	okText: 'Aceptar', nowText: 'Hoy'
});
$('#btnCajaAbrir').click(function () {
	$.ajax({url: 'php/listarUltimoCuadreValor.php', type: 'POST'}).done(function(resp) {
		$('#txtMontoApertura').val(parseFloat(resp).toFixed(2));
		$('#txtMontoApertura').attr('data-val',resp);
	});
	$('.modal-aperturarCaja').modal('show');
});
$('#btnGuardarApertura').click(function () {
	pantallaOver(true);
	var monto = parseFloat($('#txtMontoApertura').attr('data-val'));
	var obs = $('#txtObsApertura').val();

	if( $('#txtMontoApertura').val() == '' || monto <0){
		$('.modal-aperturarCaja .divError').removeClass('hidden').find('.spanError').text('Error con el monto'); 
	}else{
		$.ajax({url: 'php/cajaAperturar.php', type: 'POST', data:{
			monto: monto, obs: obs
		}}).done((resp)=> { console.log(resp);
			pantallaOver(false);
			if(resp==1){
				location.reload();
			}
		});
	}
});
$('#btnCajaCerrar').click(()=> {
	var sumaIngresos=0;
	var sumaTarjetas=0;
	$.each( $("#divEntradas .tdMoneda") , function(i, objeto){
		if( $(objeto).attr('data-id')==1 ){
			sumaIngresos+=parseFloat( $(objeto).prev().find('.spanCantv3').text());
		}else{
			sumaTarjetas+=parseFloat( $(objeto).prev().find('.spanCantv3').text());
		}
		
	});
	//console.log(sumaIngresos.toFixed(2))
	$('#spanResultadoFinal').attr('data-sumaIngreso',sumaIngresos.toFixed(2));
	$('#spanResultadoFinal').attr('data-sumaTarjetas',sumaTarjetas.toFixed(2));
	$('.modal-cerrarCaja').modal('show');
});
$('.modal-cerrarCaja').on('shown.bs.modal', function () { 
	$('#txtMontoCierre').focus();
});
$('#btnGuardarCierre').click(function () {
	var monto = parseFloat($('#txtMontoCierre').val());
	var obs = $('#txtObsCierre').val();
	var sumaEfectivo =0;
	var sumaVisa=0;
	var sumaMastercard=0;
	var sumaBanco=0;
	var sumaSalidaEfectivo = 0
	var sumaSalidaTarjeta = 0

	if( $('#txtMontoCierre').val() == '' || monto <0){
		$('.modal-cerrarCaja .divError').removeClass('hidden').find('.spanError').text('Error con el monto de cierre'); 
	}else{
		console.log('Abrio con: ' + $('#spanApertura').text());
		console.log('Cierra con: ' + $('#txtMontoCierre').val());
		$.each( $('#divEntradas .tdMoneda') , function(i, mone){
			if($(mone).attr('data-id')==1){
				sumaEfectivo+=parseFloat($(mone).prev().find('.spanCantv3').text());
			}else if($(mone).attr('data-id')==2){
				sumaBanco+=parseFloat($(mone).prev().find('.spanCantv3').text());
			}else if($(mone).attr('data-id')==3){
				sumaMastercard+=parseFloat($(mone).prev().find('.spanCantv3').text());
			}else if($(mone).attr('data-id')==4){
				sumaVisa+=parseFloat($(mone).prev().find('.spanCantv3').text());
			}
		});
		$.each( $('#divSalidas .tdMoneda') , function(i, mone){
			if($(mone).attr('data-id')==1){
				sumaSalidaEfectivo+=parseFloat($(mone).prev().find('.spanCantv3').text());
			}else if($(mone).attr('data-id')==3 || $(mone).attr('data-id')==4){
				sumaSalidaTarjeta+=parseFloat($(mone).prev().find('.spanCantv3').text());
			}
		});
		$('#spanResultadoFinal').attr('sumaEfectivo',sumaEfectivo);
		$('#spanResultadoFinal').attr('sumaBanco',sumaBanco);
		$('#spanResultadoFinal').attr('sumaMastercard',sumaMastercard);
		$('#spanResultadoFinal').attr('sumaVisa',sumaVisa);
		$('#spanResultadoFinal').attr('sumaSalidaEfectivo',sumaSalidaEfectivo);
		$('#spanResultadoFinal').attr('sumaSalidaTarjeta',sumaSalidaTarjeta);
		
		$.ajax({url: 'php/cajaCierreHoy.php', type: 'POST', data:{
			monto: monto, obs: obs
		}}).done((resp)=> { console.log(resp);
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
	$.ajax({url: 'http://127.0.0.1/perucash/printTicketCierre.php', type: 'POST', data: {
		apertura: $('#spanApertura').text(),
		cierre: $('#txtMontoCierre').val(),
		efectivoEntrada: $('#spanResultadoFinal').attr('sumaEfectivo'),
		tarjetaEntrada : parseFloat($('#spanResultadoFinal').attr('sumaMastercard')) + parseFloat($('#spanResultadoFinal').attr('sumaVisa')),
		bancos :$('#spanResultadoFinal').attr('sumaBanco'),
		efectivoSalida: $('#spanResultadoFinal').attr('sumaSalidaEfectivo'),
		tarjetaSalida: $('#spanResultadoFinal').attr('sumaSalidaTarjeta'),
		usuario: '<?= $_COOKIE['ckAtiende']; ?>'
	}}).done(function(resp) {
		console.log(resp)
	});

});
$('.aLiProcesos').click(function() {
	//console.log($(this).attr('data-id'));
	$('#lblMontoEntregar').text('Monto: S/');
	$('#lblMontoPasar').addClass('hidden');
	$('#txtPasarPagos').addClass('hidden');
	if( $(this).attr('data-id')=="74" ){
		$('#lblMontoEntregar').text('Monto en efectivo: (S/)');
		$('#lblMontoPasar').removeClass('hidden');
		$('#txtPasarPagos').removeClass('hidden').val('0.00');
	}
	$('#h5TipoPago').text($(this).text());
	$('#cmbEstadoPagos').attr('data-id', $(this).attr('data-id') );
	$('.modal-pagoMaestro').modal('show');
});
$(".modal-pagoMaestro").on("shown.bs.modal", function () { $('#txtMontoPagos').val('0.00').focus(); });
<?php if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 || $_COOKIE['ckPower']==4) { ?>
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
$('.btnEditarCajaMaestra').click(function() { 
	var padre = $(this).parent().parent();
	$('#txtCajaMontoPagos').val( padre.find('.spanCantv3').text() );
	$('#txtCajaObsPagos').val( padre.find('.tdObservacion').text() );
	$('#sltMetodopago2').selectpicker('val', padre.find('.tdMoneda').text() );
	$('#spTipoPago2').selectpicker('val', padre.find('.tpIdDescripcion').text() );
	$('#btnUpdateCajaMaestra').attr('data-caja', padre.attr('data-id') );
	$('#sltActivoV2').val(padre.attr('data-activo'));
	$('#dtpCajaFechaPago').bootstrapMaterialDatePicker('setDate',moment(padre.find('.fechaPagov3').text(), 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY hh:mm a'));

	$('.modal-cajaMaestra').modal('show');
});
$('#btnUpdateCajaMaestra').click(function() {
	var idProc= $('#cmbEstadoPagos2').find('.selected a').attr('data-tokens');
	var mone = $('#divCmbMetodoPago2').find('.selected a').attr('data-tokens');
	var padre = $(this).parent().parent();
	$.ajax({url: 'php/actualizarCaja.php', type: 'POST', data: { 
		idCaj: $('#btnUpdateCajaMaestra').attr('data-caja'),
		pproceso: idProc,
		ffecha: moment($('#dtpCajaFechaPago').val(), 'DD/MM/YYYY hh:mm a').format('YYYY-MM-DD HH:mm'),
		vvalor: $('#txtCajaMontoPagos').val(),
		oobs: $('#txtCajaObsPagos').val(),
		mmoneda: mone,
		aactivo: $('#sltActivoV2').val()
	 }}).done(function(resp) { console.log(resp)
		$('.modal-cajaMaestra').modal('hide');
		if(resp=='1'){
			location.reload();
		}
	});
});

<?php }
if( in_array( $_COOKIE['ckPower'], $soloDios)){ ?>
$('#btnCambiarApertura').click(function() {
	$('#txtMontoChangeApertura').val( $('#spanApertura').text());
	$('#modalCambiarEntradaCaja').modal('show');
});
$('#btnCambiarCierre').click(function() {
	$('#txtMontoChangeCierre').val( $('#spanCierrev3').text());
	$('#modalCambiarSalidaCaja').modal('show');
});
$('#btnUpdateApertura').click(function() {
	pantallaOver(true);
	if( $('#txtMontoChangeApertura').val()>=0 ){
		$.ajax({url: 'php/updateAperturaCaja.php', type: 'POST', data: { cuadre: '<? if(isset($_GET["cuadre"])){ echo $_GET["cuadre"]; }else{ echo ""; } ?>', nueVal: $('#txtMontoChangeApertura').val(), nueObs: $('#txtObsChangeApertura').val() }}).done(function(resp) {
			if(resp!='-1'){
				location.reload();
				pantallaOver(false);
			}
		});
	}
});
$('#btnUpdateCierre').click(function() {
	pantallaOver(true);
	if( $('#txtMontoChangeCierre').val()>=0 ){
		$.ajax({url: 'php/updateCierreCaja.php', type: 'POST', data: { cuadre: '<? if(isset($_GET["cuadre"])){ echo $_GET["cuadre"]; }else{ echo ""; } ?>', nueVal: $('#txtMontoChangeCierre').val(), nueObs: $('#txtObsChangeCierre').val() }}).done(function(resp) {
			if(resp!='-1'){
				location.reload();
				pantallaOver(false);
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
