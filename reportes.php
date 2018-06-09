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
		<link href="css/sidebarDeslizable.css?version=1.0.5" rel="stylesheet">
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link href="css/estilosElementosv3.css?version=3.0.29" rel="stylesheet">
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">
		<link href="css/bootstrap-select.min.css" rel="stylesheet">
		
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
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			
			<div class="logoEmpresa ocultar-mostrar-menu">
				<img class="img-responsive" src="images/empresa.png?version=1.1" alt="">
			</div>
			<li>
					<a href="#!"><i class="icofont icofont-home"></i> Inicio</a>
			</li>
			<li>
					<a href="registro.php"><i class="icofont icofont-ui-music-player"></i> Registro</a>
			</li>
			<li>
					<a href="#!"><i class="icofont icofont-cube"></i> Productos</a>
			</li>
			<li>
					<a href="caja.php"><i class="icofont icofont-shopping-cart"></i> Caja</a>
			</li>
			<li>
					<a href="cochera.php"><i class="icofont icofont-car-alt-1"></i> Cochera</a>
			</li>
			<li class="active">
					<a href="reportes.php"><i class="icofont icofont-ui-copy"></i> Reportes</a>
			</li>
			<li>
					<a href="verificacion.php"><i class="icofont icofont-medal"></i> Verificación</a>
			</li>
			<?php if( $_COOKIE['ckPower']==1){ ?>
			<li>
					<a href="#!"><i class="icofont icofont-users"></i> Usuarios</a>
			</li>
			<li>
					<a href="#!"><i class="icofont icofont-settings"></i> Configuraciones</a>
			</li>
			 <?php } ?>
			<li>
					<a href="#!" class="ocultar-mostrar-menu"><i class="icofont icofont-swoosh-left"></i> Ocultar menú</a>
			</li>
		</ul>
	</div>
			<!-- /#sidebar-wrapper -->
<div class="navbar-wrapper">
	<div class="container-fluid">
		<nav class="navbar navbar-fixed-top encoger">
			<div class="container">
				<div class="navbar-header ">
				<a class="navbar-brand ocultar-mostrar-menu" href="#"><img id="imgLogoInfocat" class="img-responsive" src="images/logoInfocat.png" alt=""></a>
					<button type="button" class="navbar-toggle collapsed" id="btnColapsador" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
				</div>
				<div id="navbar" class="navbar-collapse collapse ">
					<ul class="nav navbar-nav">
						<li class="hidden down"><a href="#" class="dropdown-toggle active" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">HR <span class="caret"></span></a>
								<ul class="dropdown-menu">
										<li><a href="#">Change Time Entry</a></li>
										<li><a href="#">Report</a></li>
								</ul>
							</li>
					</ul>
					<ul class="nav navbar-nav pull-right">
						 <li>
							<div class="btn-group has-clear "><label for="txtBuscarNivelGod" class="text-muted visible-xs">Buscar algo:</label>
								<input type="text" class="form-control" id="txtBuscarNivelGod" placeholder="&#xedef;">
								<span class="form-control-clear icofont icofont-close form-control-feedback hidden" style="color:#777;padding-top: 9px;"></span>
							</div>
						 </li>
						 <li id="liDatosPersonales"><a href="#!" style="padding-top: 12px;"><p> <span id="icoUser"><i class="icofont icofont-ui-user"></i></span><span class="mayuscula" id="menuNombreUsuario"><?php echo $_SESSION['nomCompleto']; ?></span></p></a></li>
						 <li class="text-center"><a href="php/desconectar.php"><span class="visible-xs">Cerrar Sesión</span><i class="icofont icofont-ui-power"></i></a></li>
					</ul>
						
				</div>
		</div>
		</nav>
	</div>
</div>
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
						<th data-sort="int">N° <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Descripcion producto <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Dueño del producto <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="int">Último Pago <i class="icofont icofont-expand-alt"></i></th>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>


<!-- Bootstrap Core JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/inicializacion.js?version=1.0.38"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/impotem.js?version=1.0.4"></script>
<script type="text/javascript" src="js/bootstrap-select.js?version=1.0.1"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>
<script type="text/javascript" src="js/stupidtable.min.js"></script>

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
	console.log(estado);
	switch(estado){
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
			$.each(JSON.parse(resp), function (i, dato) {
				$('tbody').append(`
				 <tr>
					<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a> Obs. <em class="mayuscula">${dato.invObservaciones}</em></td>
					<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
					<td><span class="spanFechaFormat">${dato.invFechaInventario}</span></td>
				</tr>`);
			});
		});
		break;
		case '30':
			$.ajax({url: 'php/listarInventarioPorEstado.php', type: 'POST', data:{ estado: 1}}).done(function (resp) { console.log(resp);
			$('tbody').children().remove();
			if(JSON.parse(resp).length==0){
				$('tbody').append(`
				<tr>
					<td class="mayuscula">No existen artículos en ésta categoría</td>
					<td class="mayuscula"></td>
					<td></td>
				</tr>`);
			}
			$.each(JSON.parse(resp), function (i, dato) {
				$('tbody').append(`
				 <tr>
				 	<td>${i+1}</td>
					<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a> Obs. <em class="mayuscula">${dato.invObservaciones}</em></td>
					<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
					<td><span class="spanFechaFormat">${dato.invFechaInventario}</span></td>
				</tr>`)
			});
		});
		break;
		default:
			$.ajax({url: 'php/listadoProductosEstado.php', type: 'POST', data:{ estado: estado }}).done(function (resp) { console.log(resp);
			$('tbody').children().remove();
			if(JSON.parse(resp).length==0){
				$('tbody').append(`
				<tr>
					<td class="mayuscula">No existen artículos en ésta categoría</td>
					<td class="mayuscula"></td>
					<td></td>
				</tr>`);
			}
			$.each(JSON.parse(resp), function (i, dato) {
				$('tbody').append(`
				 <tr><td>${i+1}</td>
					<td class="mayuscula"><a href="cliente.php?idCliente=${dato.idCliente}">${dato.cliNombres}</a></td>
					<td class="mayuscula"><a href="productos.php?idProducto=${dato.idProducto}">${dato.prodNombre}</a></td>
					<td data-sort-value="${moment(dato.desFechaContarInteres).format('X')}">${moment(dato.desFechaContarInteres).format('YYYY-MM-DD')}</td>
					<td>${parseFloat(dato.prodMontoEntregado).toFixed(2)}</td>
				</tr>`);
			});
		});
		break;
	}
});
</script>
<?php } ?>
</body>

</html>
