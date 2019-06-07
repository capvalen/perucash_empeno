<?php session_start();
if( !isset($_SESSION['access_token'])){header('Location: index.php');}else{
	if( $_COOKIE['ckPower']=="7"){ header('Location: bienvenido.php'); } }
require("php/conkarl.php");
?>
<!DOCTYPE html>
<html lang="es">


<head>
	<title>Reporte: PeruCash</title>
	<?php include "header.php"; ?>		
</head>

<body>
<style>
	.paPrestamo{

		margin: 0;
		padding: 15px;
		border-top: 1px solid #e3e3e3;
		/*border-radius: 6px;*/
		transition: all 0.6s ease-in-out;
		cursor: pointer;
	}
	.paPrestamo:hover{
		background-color: #f5f5f5;
		transition: all 0.6s ease-in-out;
	}
	.h3Nombres{margin-top: 0px;}
	.rate i{padding-left: 3px;}
	.divBotonesPrestamo .dropdown-menu>li>a{color: #0078D7;}
	.divBotonesPrestamo .dropdown-menu>li>a:focus, .divBotonesPrestamo .dropdown-menu>li>a:hover {
		text-decoration: none;
		background-color: #f5f5f5;
	}
	#tablita th{cursor: pointer;}
	#spanError{color: #d50000;}
	.sandbox-container{display: inline-block;}
</style>
<div id="wrapper">

	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid">				 
		<div class="row">
			<div class="col-lg-12 contenedorDeslizable">
			<!-- Empieza a meter contenido principal dentro de estas etiquetas -->
				<h2 class="purple-text text-lighten-1">Reporte productos</h2>
				<div class="row">
					<p>¿Qué tipo de reporte quieres ver?</p>
					<div class="col-xs-12 col-sm-3" id="cmbEstadoProd">
					<select class="selectpicker mayuscula" title="Nuevo estado..." id="cmbEstadoCombo"  data-width="100%" data-live-search="true" data-size="15">
						<option class="optProducto mayuscula" data-tokens="9999">Analítica</option>
					<?php require 'php/detalleReporteOPT.php'; ?>
					</select></div>
					<div class=" divAnalitica col-xs-12 col-sm-8 hidden">
					<div class="col-xs-12 col-xs-4 ">
						<select class="form-control" id="sltFiltroAnalitica">
							<option value="0">Seleccione una opción</option>
							<option value="4">Amortizaciones</option>
							<option value="11">Gastos de almuerzo</option>
							<option value="10">Gastos relacionados</option>
							<option value="2">Intereses cobrados</option>
							<option value="7">Inyecciones (otros)</option>
							<option value="16">Inyecciones (por inversionistas)</option>
							<option value="17">Inyecciones (por tarjetas)</option>
							<option value="9">Inyecciones (por socios)</option>
							<option value="12">Pagos de almuerzo</option>
							<option value="13">Pagos de servicios</option>
							<option value="3">Préstamos finalizados</option>
							<option value="1">Préstamos y Desembolsos</option>
							<option value="6">Rematados</option>
							<option value="14">Liquidación</option>
							<option value="8">Retiro por socios</option>
							<option value="15">Tarjetas</option>
							<option value="5">Vendidos</option>
						</select>
					</div>
					<div class="col-xs-12 col-xs-4 divInternosReportes">
						<input type="text" id="dtpFechaIniciov3" class="form-control text-center" placeholder="Fecha para filtrar datos">
					</div>
					<div class="col-xs-12 col-xs-4 divInternosReportes">
						<button class="btn btn-success btn-outline" id="btnFiltroAnaticica">Filtrar <i class="icofont icofont-search-alt-1"></i></button>
					</div>
					<div class="col-xs-8 hidden" id="divReporteMayor">
					<div class="sandbox-container">
						<div class="input-daterange input-group" id="datepickerMayor">
							<input type="text" class="input-sm form-control" id="inputFechaInicio" name="start" />
							<span class="input-group-addon" style="font-size: 12px;">hasta</span>
							<input type="text" class="input-sm form-control" id="inputFechaFin" name="end" />
						</div>
					</div>
					<button class="btn btn-primary btn-outline " id="filtroMayor" style="margin-top: -3rem;"><i class="icofont icofont-search"></i></button>
					</div>
					
					<!-- <div class="container-fluid" style="margin-top: 10px;">
						<div class="col-xs-8"></div>
						<div class="col-xs-4"></div>
					</div> -->
				<label class="hidden" id="spanError"><i class="icofont icofont-warning"></i>Tiene que seleccionar un tipo de producto</label>
				</div>
				</div>
				<div class="table-responsive container-fluid hidden" id="tablasReporteMayor">
					
				</div>
				
				<div class="row tablaResultados table-responsive">
					<table class="table table-hover" id="tablita">
					<thead>
					  <tr>
						<th data-sort="int"># <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="int" id="thUnds">Unds. <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Descripción del producto <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Dueño del producto <i class="icofont icofont-expand-alt"></i></th>
						<th id="tdFechaUltPago" data-sort="int">Fecha <i class="icofont icofont-expand-alt"></i></th>
						<th id="tdUltPago" data-sort="int">Último Pago <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="float" class="hidden" id="tventa">Venta S/<i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="float">Capital S/<i class="icofont icofont-expand-alt"></i></th>
						
					  </tr>
					</thead>
					<tbody>
					</tbody>
				  </table>
				</div>
				
			<!-- Fin de contenido principal -->
			</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
    </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<?php include 'footer.php'; ?>
<script src="js/stupidtable.min.js"></script>
<script src="js/bootstrap-material-datetimepicker.js"></script>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<!-- Menu Toggle Script -->
<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();
$('#dtpFechaIniciov3').bootstrapMaterialDatePicker({ format : 'YYYY-MM', lang : 'es', weekStart : 1 , time: false});

$(document).ready(function(){
	$('#dtpFechaIniciov3').bootstrapMaterialDatePicker('setDate', moment());
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, clearBtn: true, todayBtn: "linked", todayHighlight: true}); //para activar las fechas
	$('.sandbox-container .input-daterange').datepicker({language: "es", autoclose: true,  clearBtn: true, todayBtn: "linked", todayHighlight: true});

	$('.input-daterange').data('datepicker').pickers[0].setDates(moment().format('DD/MM/YYYY'));

	$('#tablita').stupidtable();
	$.each( $('.spanFechaFormat'), function (i, dato) {
		var nueFecha=moment($(dato).text());
		$(dato).text(nueFecha.format('LLLL'));
	});
});
$('#cmbEstadoCombo').change(function () {
	pantallaOver(true);
	var estado=$('#cmbEstadoProd').find('li.selected a').attr('data-tokens');
	var sumaElementos=0;
	console.log(estado);
	$('#tablita').find('#tdUltPago').html('Último pago <i class="icofont icofont-expand-alt"></i>');
	$('tbody').children().remove();
	$('tfoot').children().remove();
	$('.divAnalitica').addClass('hidden');
	$('#thUnds').removeClass('hidden');
	$('#tdFechaUltPago').addClass('hidden');
	$('#tventa').addClass('hidden');
	$('#tdUltPago').text('Último pago');
	$('#tablita .cabezaExtra').remove();
	switch(estado){
		case '23':
		$('#tablita').find('#tdUltPago').html('Fecha de compra <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarInventarioAlmacen.php', type: 'POST' }).done(function (resp) { //console.log(resp);
			pantallaOver(false);
				if(JSON.parse(resp).length==0){
					$('tbody').append(`
					<tr>
						<td class="mayuscula">No existen artículos en ésta categoría</td>
						<td class="mayuscula"></td>
						<td></td>
					</tr>`);
				}
				var data = JSON.parse(resp);
				$.each(data, function (i, dato) {
					sumaElementos+=parseFloat(dato.preCapital);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td>${dato.prodCantidad}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}"></a></td>
						<td data-sort-value="${moment(dato.cubFecha).format('X')}">${moment(dato.cubFecha, 'YYYY-MM-DD').format('DD/MM/YYYY')}</td>
						<td>${parseFloat(dato.preCapital).toFixed(2)}</td>
					</tr>`);
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			}); break;
		case '24':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosProrrogav3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
				pantallaOver(false);
				if(JSON.parse(resp).length==0){
					$('tbody').append(`
					<tr>
						<td class="mayuscula">No existen artículos en ésta categoría</td>
						<td class="mayuscula"></td>
						<td></td>
					</tr>`);
				}
				var data = JSON.parse(resp);
				$.each(data, function (i, dato) {
					sumaElementos+=parseFloat(dato.preCapital);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td>${dato.prodCantidad}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.preCapital).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '29':
			$.ajax({url: 'php/listarInventarioPorEstado.php', type: 'POST', data:{ estado: 0}}).done(function (resp) {
				pantallaOver(false);
			if(JSON.parse(resp).length==0){
				$('tbody').append(`
				 <tr>
					<td class="mayuscula">No existen artículos en ésta categoría</td>
					<td class="mayuscula"></td>
					<td></td>
				</tr>`)
			}
			var data = JSON.parse(resp);
			$.each(JSON.parse(resp), function (i, dato) {
				sumaElementos+=parseFloat(dato.prodMontoEntregado);
				$('tbody').append(`
				 <tr>
					<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a> Obs. <em class="mayuscula">${dato.invObservaciones}</em></td>
					<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
					<td><span class="spanFechaFormat">${dato.invFechaInventario}</span></td>
				</tr>`);
				if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
			});
		});
		break;
		case '30':
			$.ajax({url: 'php/listarInventarioPorEstado.php', type: 'POST', data:{ estado: 1}}).done(function (resp) { //console.log(resp);
				pantallaOver(false);
				if(JSON.parse(resp).length==0){
					$('tbody').append(`
					<tr>
						<td class="mayuscula">No existen artículos en ésta categoría</td>
						<td class="mayuscula"></td>
						<td></td>
					</tr>`);
				}
				var data = JSON.parse(resp);
				$.each(JSON.parse(resp), function (i, dato) {
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr>
					 	<td>${dato.idProducto}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a> Obs. <em class="mayuscula">${dato.invObservaciones}</em></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td><span class="spanFechaFormat">${dato.invFechaInventario}</span></td>
					</tr>`);
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '37':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosEmpenosv3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
				pantallaOver(false);
				if(JSON.parse(resp).length==0){
					$('tbody').append(`
					<tr>
						<td class="mayuscula">No existen artículos en ésta categoría</td>
						<td class="mayuscula"></td>
						<td></td>
					</tr>`);
				}
				var data = JSON.parse(resp);
				$.each(data, function (i, dato) {
					sumaElementos+=parseFloat(dato.preCapital);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td>${dato.prodCantidad}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.preCapital).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '38':
		$('#tablita').find('#tdUltPago').html('Fecha de compra <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarSoloCompras.php', type: 'POST' }).done(function (resp) { //console.log(resp);
				pantallaOver(false);
				if(JSON.parse(resp).length==0){
					$('tbody').append(`
					<tr>
						<td class="mayuscula">No existen artículos en ésta categoría</td>
						<td class="mayuscula"></td>
						<td></td>
					</tr>`);
				}
				var data = JSON.parse(resp);
				$.each(data, function (i, dato) { console.log(dato)
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td>${dato.prodCantidad}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${moment(dato.prodFechaInicial).format('X')}">${moment(dato.prodFechaInicial, 'YYYY-MM-DD').format('DD/MM/YYYY')}</td>
						<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '68':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
		$('#tablita thead tr').append('<th class="cabezaExtra">Detalle.</th>');
			$.ajax({url: 'php/listarProductosVencidos.php', type: 'POST' }).done(function (resp) { //console.log(resp);
				pantallaOver(false);
				if(JSON.parse(resp).length==0){
					$('tbody').append(`
					<tr>
						<td class="mayuscula">No existen artículos en ésta categoría</td>
						<td class="mayuscula"></td>
						<td></td>
					</tr>`);
				}
				var data = JSON.parse(resp);
				$.each(data, function (i, dato) {
					sumaElementos+=parseFloat(dato.preCapital);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td>${dato.prodCantidad}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}#tabMovEstados">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.preCapital).toFixed(2)}</td>
						<td>${dato.cliCelular}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '72':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosVigentesv3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
				pantallaOver(false);
				if(JSON.parse(resp).length==0){
					$('tbody').append(`
					<tr>
						<td class="mayuscula">No existen artículos en ésta categoría</td>
						<td class="mayuscula"></td>
						<td></td>
					</tr>`);
				}
				var data = JSON.parse(resp);
				$.each(data, function (i, dato) {
					sumaElementos+=parseFloat(dato.preCapital);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td>${dato.prodCantidad}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.preCapital).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '78':
			$('#thUnds').addClass('hidden');
			$('#tdUltPago').text('Desembolso');
			$.ajax({url: 'php/listarCreditosOnline.php', type: 'POST'}).done(function(resp) {
				pantallaOver(false);
				$('tbody').html(resp);
			});
		break;
		case '9999':
			$('.divAnalitica').removeClass('hidden');
			pantallaOver(false);
		break;
		default:
			$.ajax({url: 'php/listadoProductosEstado.php', type: 'POST', data:{ estado: estado }}).done(function (resp) { //console.log(resp);
				pantallaOver(false);
			if(JSON.parse(resp).length==0){
				$('tbody').append(`
				<tr>
					<td class="mayuscula">No existen artículos en ésta categoría</td>
					<td class="mayuscula"></td>
					<td></td>
				</tr>`);
			}
			var data = JSON.parse(resp);
			$.each(JSON.parse(resp), function (i, dato) {
				sumaElementos+=parseFloat(dato.prodMontoEntregado);
				$('tbody').append(`
				 <tr><td>${dato.idProducto}</td>
					<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
					<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
					<td data-sort-value="${moment(dato.desFechaContarInteres).format('X')}">${moment(dato.desFechaContarInteres).format('DD/MM/YYYY')}</td>
					<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
				</tr>`);
				if(data.length==i+1){
					$('#tablita').append(`<tfoot><th><td></td><td></td>
						<td><strong>Total: </strong></td>
						<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
						</th></tfoot>`);
				}
			});
		});
		break;
		
	}
});
$('#btnFiltroAnaticica').click(()=> {
	$('#spanError').addClass('hidden');
	var sumaElementos=0, sumaCapitales=0;
	if( $('#sltFiltroAnalitica').val()==0){
		$('#spanError').removeClass('hidden');
		$('#spanError').text('Tienes que seleccionar un filtro');
	}else if( $('#dtpFechaIniciov3').val()=='' ){
		$('#spanError').removeClass('hidden');
		$('#spanError').text('Fecha incorrecta');
	}else{
		$('tbody').children().remove();
		$('tfoot').children().remove();
		$('#tdFechaUltPago').addClass('hidden');
		$('.divInternosReportes').removeClass('hidden');
		switch ($('#sltFiltroAnalitica').val()) {
			case '1':
				$.ajax({url: 'php/reportePrestamosMes.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val() }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td><td></td>
							<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
							<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			case '2':
				$.ajax({url: 'php/reporteInteresesCobrados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val() }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td><td></td>
							<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '3':
				$('#tdFechaUltPago').removeClass('hidden');
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 32 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						sumaCapitales += parseFloat(dato.prodMontoEntregado);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td><td></td>
							<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
							<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								<td>S/ ${parseFloat(sumaCapitales).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '4':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 45 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp); console.log( data );
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td>
							<td></td>
							<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '5':
				$('#tventa').removeClass('hidden');
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 21 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td><td></td>
							<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
							<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '6':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 20 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td>
							<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '7':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 31 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '8':
				$.ajax({url: 'php/reporteRetiroSocios.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 41 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '9':
				$.ajax({url: 'php/reporteRetiroSocios.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 80 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '10':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 42 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '11':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 87 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '12':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 86 }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			case '13':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 88 }}).done((resp)=> {
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			case '14':  //liquidación
			
			break;
			case '15':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 74 }}).done((resp)=> {
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			case '16':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 89 }}).done((resp)=> {
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			case '17':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val(), proceso: 90 }}).done((resp)=> {
					var data = JSON.parse(resp);
					if(resp=='[]'){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría o intervalo de fechas</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${i+1}</td>
							<td class="mayuscula"></td>
							<td class="mayuscula">${dato.tipoDescripcion}</a> </td>
							<td class="mayuscula"><em>${dato.cajaObservacion}</em></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('DD/MM/YYYY')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/ ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			default:
				break;
		}
	}
});
$('#sltFiltroAnalitica').click(function() {
	if( $('#sltFiltroAnalitica').val()=='14' ){
		$('.divInternosReportes').addClass('hidden');
		$('.tablaResultados').addClass('hidden');
		$('#divReporteMayor').removeClass('hidden');
		$('#tablasReporteMayor').removeClass('hidden');
	}else{
		$('.divInternosReportes').removeClass('hidden');
		$('.tablaResultados').removeClass('hidden');
		$('#divReporteMayor').addClass('hidden');
		$('#tablasReporteMayor').addClass('hidden');
	}
});
$('#filtroMayor').click(function() {
	//console.log( 'hola' );
	$.ajax({url: 'php/reporteLiquidacion.php', type: 'POST', data: {fecha1: moment($('#inputFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'), fecha2:moment($('#inputFechaFin').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') }}).done(function(resp) {
		$('#tablasReporteMayor').html(resp);
	});
});
</script>
<?php } ?>
</body>

</html>
