<?php ?>
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
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.5" >
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.46" >
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
</style>
<div id="overlay">
	<div class="text"><i class="icofont icofont-leaf"></i> Guardando data...</div>
</div>

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
			<li class="active">
					<a href="caja.php"><i class="icofont icofont-shopping-cart"></i> Caja</a>
			</li>
			<li>
					<a href="cochera.php"><i class="icofont icofont-car-alt-1"></i> Cochera</a>
			</li>
			<li>
					<a href="reportes.php"><i class="icofont icofont-ui-copy"></i> Reportes</a>
			</li>
			<?php if( $_COOKIE['ckPower']==1){ ?>
			<li>
					<a href="#!"><i class="icofont icofont-users"></i> Usuarios</a>
			</li>
			<li>
					<a href="configuraciones.php"><i class="icofont icofont-settings"></i> Configuraciones</a>
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

		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable contenedorDatosCliente">
			<!-- Empieza a meter contenido 2 -->
			<h2 class="purple-text text-lighten-1">Cuadre de caja <small><?php echo $_COOKIE['ckAtiende']; ?></small></h2>
			<div class="container-fluid">
				<div class="row col-sm-7"><h3 class="purple-text" style="margin-top: 21px;"><span class="glyphicon glyphicon-piggy-bank"></span> Cierre de caja </h3></div>
			</div>
			<div class="container-fluid">
				<div class="col-xs-12 col-sm-8">
					<p class="pheader">Datos de Cuadre</p>


				<div class="panel panel-default ">
					<div style="padding: 10px;">
						<p style="color: #a35bb4;">Por: <strong><?php echo $_COOKIE['usuario']; ?></strong></p>
						<p style="color: #a35bb4;">Fecha: <strong id="strFechaAhora"></strong></p>
					</div>
				</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<p style="color: #a35bb4;"><strong>Seleccione fecha de reporte:</strong></p>
					<input type="text" id="dtpFechaIniciov3" class="form-control text-center" placeholder="Fecha para controlar citas">
					<!--<div class="sandbox-container"><input id="dtpFechaIniciov3" type="text" class="form-control text-center inputConIco" placeholder="" style="color: #a35bb4;" autocomplete="off"> <span class="icoTransparent"><i class="icofont icofont-caret-down"></i></span></div> -->

				</div>
			</div>

			<div class="container-fluid">
				<p class="pheader col-xs-6">Clientes atentidos</p>
				<div class=" panel panel-default  ">
					<table class="table table-hover">
					<thead>
						<tr> <th>ID Caja</th> <th>Producto</th> <th>Datos de Cliente</th> <th>Razón <i class="icofont icofont-long-arrow-right"></i> Usuario</th> <th>Cantidad</th> </tr> </thead>
					<tbody>
						<?php
						if (isset($_GET['fecha'])) { //si existe lista fecha requerida
							require_once 'php/reporteCajaDiaTR.php';
						}else{ //sino existe lista la fecha de hoy
							$_GET['fecha']=date('Y-m-d');
							require_once 'php/reporteCajaDiaTR.php';
						}
						?>
					</tbody> </table>
				</div>
			</div>
			<div class="container-fluid col-xs-12 col-sm-6">
				<h4 class="pheader">Entradas de dinero</h4>
				<div class=" panel panel-default  ">
					<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Motivo de ingreso</th> <th>Usuario</th> <th>Cantidad</th> </tr> </thead>
					<tbody>
						<?php
						if (isset($_GET['fecha'])) { //si existe lista fecha requerida
							require_once 'php/reporteIngresoDia.php';
						}else{ //sino existe lista la fecha de hoy
							$_GET['fecha']=date('Y-m-d');
							require_once 'php/reporteIngresoDia.php';
						}
						?>
					</tbody> </table>
				</div>
			</div>
			<div class="container-fluid col-xs-12 col-sm-6">
				<h4 class="pheader">Salidas de dinero</h4>
				<div class=" panel panel-default  ">
					<table class="table table-hover">  <thead> <tr> <th>#</th> <th>Motivo de egreso</th> <th>Usuario</th> <th>Cantidad</th> </tr> </thead>
					<tbody>
						<?php
						if (isset($_GET['fecha'])) { //si existe lista fecha requerida
							require_once 'php/reporteEgresoDia.php';
						}else{ //sino existe lista la fecha de hoy
							$_GET['fecha']=date('Y-m-d');
							require_once 'php/reporteEgresoDia.php';
						}
						?>
					</tbody> </table>
				</div>
			</div>
			<div class="container-fluid col-xs-12 text-center">
				<h4 class="pheader">Efectivo total del día: <strong>S/. <span id="spanResultadoFinal"></span></strong></h4>
			</div>
			<!-- Fin de contenido 2 -->
			</div>

</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/inicializacion.js?version=1.0.11"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/bootstrap-select.js?version=1.0.1"></script>
<script type="text/javascript" src="js/impotem.js?version=1.0.4"></script>
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

$('#spanResultadoFinal').text(parseFloat(parseFloat($('#strSumaClientes').text())-parseFloat($('#strSumaSalida').text())+parseFloat($('#strSumaEntrada').text())).toFixed(2));
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
</script>
<?php } ?>

</body>

</html>
