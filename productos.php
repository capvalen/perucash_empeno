<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Productos: PeruCash</title>

		<!-- Bootstrap Core CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Custom CSS -->
		<link href="css/sidebarDeslizable.css?version=1.0.5" rel="stylesheet">
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link href="css/estilosElementosv3.css?version=3.0.31" rel="stylesheet">
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">
		<link href="css/bootstrap-select.min.css" rel="stylesheet">
		
</head>

<body>
<style>
	.paPrestamo{

		margin: 10px 0;
		padding: 15px;
		border: 1px solid #e3e3e3;
		border-radius: 6px;
		cursor: pointer;
	}
	.paPrestamo:hover{
		background-color: #f5f5f5;
		transition: all 0.6s ease-in-out;
	}
	.h3Nombres{margin-top: 0px;}
	.divDatosProducto p{font-size: 13px;transition: all 0.4s ease-in-out; cursor:default; color: #546e7a;}
	.divDatosProducto p:hover{font-size: 16px; transition: all 0.4s ease-in-out; color:#2979ff; }
	.divImagen img{border-radius: 7px;}
	.divBotonesAccion{margin: 15px 0;}
	.tab-pane li{list-style: none;}
	.tab-pane li{margin:5px 0;text-indent: -.7em;}
	.tab-pane li::before {
		content: "• ";
		color: #ab47bc;
	}
	.contenedorDatosCliente a{
		color: #ab47bc;
	}
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
					<a href="registro.php"><i class="icofont icofont-washing-machine"></i> Registro</a>
			</li>
			<li class="active">
					<a href="#!"><i class="icofont icofont-cube"></i> Productos</a>
			</li>
			<li>
					<a href="#!"><i class="icofont icofont-shopping-cart"></i> Cuadrar caja</a>
			</li>
			<li>
					<a href="#!" id="aCreditoNuevo"><i class="icofont icofont-ui-love-add"></i> Crédito nuevo</a>
			</li>
			<li>
					<a href="#!" id="aGastoExtra"><i class="icofont icofont-ui-rate-remove"></i> Gasto extra</a>
			</li>
			<li>
					<a href="#!" id="aIngresoExtra"><i class="icofont icofont-ui-rate-add"></i> Ingreso extra</a>
			</li>
			<li>
					<a href="#!"><i class="icofont icofont-ui-copy"></i> Reportes</a>
			</li>
			<li>
					<a href="#!"><i class="icofont icofont-users"></i> Usuarios</a>
			</li>
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
	<div class="container-fluid noSelect">				 

		<div class="row continer-fluid">
			<div class="col-xs-12 contenedorDeslizable contenedorDatosCliente ">
			<!-- Empieza a meter contenido 2.1 -->
				<div class="container row" style="margin-bottom: 20px;">
					<h2 class="h3Apellidos purple-text text-lighten-1">Nombre producto nuevo 001</h2>
					<div class="divBotonesEdicion" style="margin-bottom: 10px">
						<div class="btn-group">
						  <button type="button" class="btn btn-azul btn-outline dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="icofont icofont-settings"></i> <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
							<li><a href="#" id=""><i class="icofont icofont-shopping-cart"></i> Agregar nuevo item</a></li>
						  </ul>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 divImagen">
						<img src="images/imgBlanca.png" class="img-responsive" alt="">
					</div>
					<div class="col-xs-12 col-sm-7 divDatosProducto">
						<p>Código de producto: #<span>155</span></p>
						<p>Dueño: <a href="cliente.php" class="spanDueno">Carlos Alex, Pariona Valencia</a></p>
						<p>Registrado: <span>Lunes, 13 de enero de 2018</span></p>
						<p>Cantidad: <span>5</span> unds.</p>
						<p>Estado del producto: <strong class="blue-text text-accent-3">En almacén</strong></p>
						<p>Estado del sub-préstamo: <strong class="blue-text text-accent-3">Vigente</strong></p>
						<p>Adquisición: <span>Por alquiler</span></p>
						<p>Último pago: <span>Aún no hay pago</span></p>
					</div>
				</div>
				<div class="container row">
					<ul class="nav nav-tabs">
					<li class="active"><a href="#tabIntereses" data-toggle="tab">Intereses</a></li>
					<li><a href="#tabMovEstados" data-toggle="tab">Estados</a></li>
					<li><a href="#tabMovFinancieros" data-toggle="tab">Financiero</a></li>
					
					</ul>
					<div class="tab-content">
					<!--tab content-->
						<div class="tab-pane fade in active container-fluid" id="tabIntereses">
						<!--Inicio de pestaña interior 01-->
							<h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Seccion intereses</h4>
							<ul>
								<li>Capital pendiente: <span>S/. 150.00</span></li>
								<li>Tiempo de intereses: <span>7 días</span></li>
								<li>Razón del cálculo: <span>Interés acumulado al 4% diario (mayor a 29 días).</span></li>
								<li>Interés: <span>4% = S/. 6.00</span></li>
								<li>Deuda total: <span><strong>S/. 156.00</strong></span></li>
							</ul>
						<!--Fin de pestaña interior 01-->
						</div>
						<div class="tab-pane fade container-fluid" id="tabMovEstados">
						<!--Inicio de pestaña interior 02-->
							<h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Seccion de estados</h4>
							<ul>
								<li>Registrado	13/01/2018 03:37 p.m.	bmanrique</li>
								<li>En almacén	13/01/2018 03:50 p.m.	giordan</li>
							</ul>
						<!--Fin de pestaña interior 02-->
						</div>
						<div class="tab-pane fade container-fluid" id="tabMovFinancieros">
						<!--Inicio de pestaña interior 03-->
							<h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Seccion Financiera</h4>
							<ul>
								<li>Capital o desembolso	13/01/2018 03:37 p.m.	S/. 700.00	bmanrique</li>
							</ul>
						<!--Fin de pestaña interior 03-->
						</div>
					<!-- Fin de tab content -->
	            	</div>
				</div>
			</div>
			
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


</div>

<?php include 'php/modals.php'; ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>


<!-- Bootstrap Core JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script src="js/inicializacion.js?version=1.0.3"></script>
<script src="js/bootstrap-select.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>

<!-- Menu Toggle Script -->
<script>
$.interesGlobal=4;
$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	var target = $(e.target).attr("href");
	//console.log(target);
	if(target=='#tabIntereses'){
		//$.queMichiEs='nada'; console.log('tabnada')
		
	}
	if(target=='#tabMovEstados'){
		
	}
});
</script>

</body>

</html>
