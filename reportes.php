<?php session_start();
require("php/conkarl.php");
?>
<!DOCTYPE html>
<html lang="es">


<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Reporte: PeruCash</title>

		<!-- Bootstrap Core CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Custom CSS -->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.7" >
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.5">
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.51" >
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
		<link rel="stylesheet" href="css/bootstrap-select.min.css?version=0.2" >
		
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
					<?php require 'php/detalleReporteOPT.php'; ?>
					</select></div>
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
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<!-- Menu Toggle Script -->
<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();

$(document).ready(function(){
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
	switch(estado){
		case '24':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosProrrogav3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
				$('tbody').children().remove();
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
			$('tbody').children().remove();
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
				$('tbody').children().remove();
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
				$('tbody').children().remove();
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
				$('tbody').children().remove();
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
				$('tbody').children().remove();
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
		case '72':
		$('#tablita').find('#tdUltPago').html('Días vencido <i class="icofont icofont-expand-alt"></i>');
			$.ajax({url: 'php/listarProductosVigentesv3.php', type: 'POST' }).done(function (resp) { //console.log(resp);
				$('tbody').children().remove();
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
		default:
			$.ajax({url: 'php/listadoProductosEstado.php', type: 'POST', data:{ estado: estado }}).done(function (resp) { //console.log(resp);
			$('tbody').children().remove();
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
</script>
<?php } ?>
</body>

</html>
