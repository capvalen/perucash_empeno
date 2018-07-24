<?php session_start();
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
					<div class="col-xs-12 col-sm-6" id="cmbEstadoProd">
					<select class="selectpicker mayuscula" title="Nuevo estado..." id="cmbEstadoCombo"  data-width="100%" data-live-search="true" data-size="15">
						<option class="optProducto mayuscula" data-tokens="9999">Analítica</option>
					<?php require 'php/detalleReporteOPT.php'; ?>
					</select></div>
					<div class=" divAnalitica col-xs-12 col-sm-6 hidden">
					<select class="form-control" id="sltFiltroAnalitica">
						<option value="0">Seleccione una opción</option>
						<option value="1">Préstamos y Desembolsos</option>
						<option value="2">Intereses cobrados</option>
						<option value="3">Préstamos finalizados</option>
					</select>
					<div class="container-fluid" style="margin-top: 10px;">
					<div class="col-xs-8"><input type="text" id="dtpFechaIniciov3" class="form-control text-center" placeholder="Fecha para filtrar datos"></div>
					<div class="col-xs-4"><button class="btn btn-success btn-outline" id="btnFiltroAnaticica">Filtrar <i class="icofont icofont-search-alt-1"></i></button></div>
					</div>
				<label class="hidden" id="spanError"><i class="icofont icofont-warning"></i>Tiene que seleccionar un tipo de producto</label>
				</div>
				</div>
				
				<div class="row tablaResultados table-responsive">
					<table class="table table-hover" id="tablita">
					<thead>
					  <tr>
						<th data-sort="int"># <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Descripcion producto <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Dueño del producto <i class="icofont icofont-expand-alt"></i></th>
						<th id="tdUltPago" data-sort="int">Último Pago <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="float">Capital S/.<i class="icofont icofont-expand-alt"></i></th>
					  </tr>
					</thead>
					<tbody>
					</tbody>
				  </table>
				</div>
				
			<!-- Fin de contenido principal -->
			</div>
		
		</div>
		</div>
		
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


</div>

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
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
	$('#tablita').stupidtable();
	$.each( $('.spanFechaFormat'), function (i, dato) {
		var nueFecha=moment($(dato).text());
		$(dato).text(nueFecha.format('LLLL'));
	});
});
$('#cmbEstadoCombo').change(function () {
	var estado=$('#cmbEstadoProd').find('li.selected a').attr('data-tokens');
	var sumaElementos=0;
	console.log(estado);
	$('#tablita').find('#tdUltPago').html('Último pago <i class="icofont icofont-expand-alt"></i>');
	$('tbody').children().remove();
	$('tfoot').children().remove();
	$('.divAnalitica').addClass('hidden');
	switch(estado){
		case '23':
		$('#tablita').find('#tdUltPago').html('Fecha de compra <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarInventarioAlmacen.php', type: 'POST' }).done(function (resp) { //console.log(resp);
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
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}"></a></td>
						<td data-sort-value="${moment(dato.cubFecha).format('X')}">${moment(dato.cubFecha, 'YYYY-MM-DD').format('DD/MM/YYYY')}</td>
						<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			}); break;
		case '24':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosProrrogav3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
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
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '29':
			$.ajax({url: 'php/listarInventarioPorEstado.php', type: 'POST', data:{ estado: 0}}).done(function (resp) {
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
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
			});
		});
		break;
		case '30':
			$.ajax({url: 'php/listarInventarioPorEstado.php', type: 'POST', data:{ estado: 1}}).done(function (resp) { //console.log(resp);
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
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '37':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosEmpenosv3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
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
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '38':
		$('#tablita').find('#tdUltPago').html('Fecha de compra <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarSoloCompras.php', type: 'POST' }).done(function (resp) { //console.log(resp);
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
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${moment(dato.prodFechaInicial).format('X')}">${moment(dato.prodFechaInicial, 'YYYY-MM-DD').format('DD/MM/YYYY')}</td>
						<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '68':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosVencidos.php', type: 'POST' }).done(function (resp) { //console.log(resp);
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
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}#tabMovEstados">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '72':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosVigentesv3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
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
					sumaElementos+=parseFloat(dato.prodMontoEntregado);
					$('tbody').append(`
					<tr><td>${dato.idProducto}</td>
						<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
						<td data-sort-value="${dato.diasDeuda}">${dato.diasDeuda}</td>
						<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
					</tr>`);
					
					if(data.length==i+1){
						$('#tablita').append(`<tfoot><th><td></td><td></td>
							<td><strong>Total: </strong></td>
							<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
							</th></tfoot>`);
					}
				});
			});
		break;
		case '9999':
			$('.divAnalitica').removeClass('hidden');
		break;
		default:
			$.ajax({url: 'php/listadoProductosEstado.php', type: 'POST', data:{ estado: estado }}).done(function (resp) { //console.log(resp);
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
					<td data-sort-value="${moment(dato.desFechaContarInteres).format('X')}">${moment(dato.desFechaContarInteres).format('YYYY-MM-DD')}</td>
					<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
				</tr>`);
				if(data.length==i+1){
					$('#tablita').append(`<tfoot><th><td></td><td></td>
						<td><strong>Total: </strong></td>
						<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
						</th></tfoot>`);
				}
			});
		});
		break;
		
	}
});
$('#btnFiltroAnaticica').click(()=> {
	$('#spanError').addClass('hidden');
	var sumaElementos=0;
	if( $('#sltFiltroAnalitica').val()==0){
		$('#spanError').removeClass('hidden');
		$('#spanError').text('Tienes que seleccionar un filtro');
	}else if( $('#dtpFechaIniciov3').val()=='' ){
		$('#spanError').removeClass('hidden');
		$('#spanError').text('Fecha incorrecta');
	}else{
		$('tbody').children().remove();
		$('tfoot').children().remove();
		switch ($('#sltFiltroAnalitica').val()) {
			case '1':
				$.ajax({url: 'php/reportePrestamosMes.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val() }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp.length==0){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría</td>
							<td class="mayuscula"></td>
							<td></td>
						</tr>`);
					}
					$.each(data, function (i, dato) {
						sumaElementos+=parseFloat(dato.cajaValor);
						$('tbody').append(`
						<tr><td>${dato.idProducto}</td>
							<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
							<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('YYYY-MM-DD')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			case '2':
				$.ajax({url: 'php/reporteInteresesCobrados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val() }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp.length==0){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría</td>
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
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('YYYY-MM-DD')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
				case '3':
				$.ajax({url: 'php/reportePagosFinalizados.php', type: 'POST', data: { fecha: $('#dtpFechaIniciov3').val() }}).done((resp)=> { //console.log(resp)
					var data = JSON.parse(resp);
					if(resp.length==0){
						$('tbody').append(`
						<tr>
							<td class="mayuscula">No existen artículos en ésta categoría</td>
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
							<td data-sort-value="${moment(dato.cajaFecha).format('X')}">${moment(dato.cajaFecha).format('YYYY-MM-DD')}</td>
							<td>${parseFloat(dato.cajaValor).toFixed(2)}</td>
						</tr>`);
						
						if(data.length==i+1){
							$('#tablita').append(`<tfoot><th><td></td><td></td>
								<td><strong>Total: </strong></td>
								<td>S/. ${parseFloat(sumaElementos).toFixed(2)}</td>
								</th></tfoot>`);
						}
					});
				}); break;
			default:
				break;
		}
	}
});

</script>
<?php } ?>
</body>

</html>
