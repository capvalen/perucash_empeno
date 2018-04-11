<?php session_start();
require("php/conkarl.php");
?>
<!DOCTYPE html>
<html lang="es">

<?php 
if( isset($_GET['idCliente'])){
	$sql = mysqli_query($conection,"SELECT * FROM `Cliente` where idCliente = '".$_GET['idCliente']."';");
	$rowCliente = mysqli_fetch_array($sql, MYSQLI_ASSOC);
}
?>
<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Cliente: PeruCash</title>

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
	.rate i{padding-left: 3px; cursor: pointer;}
	.divBotonesPrestamo .dropdown-menu>li>a{color: #0078D7;}
	.divBotonesPrestamo .dropdown-menu>li>a:focus, .divBotonesPrestamo .dropdown-menu>li>a:hover {
	    text-decoration: none;
	    background-color: #f5f5f5;
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
			<li class="active">
					<a href="registro.php"><i class="icofont icofont-washing-machine"></i> Registro</a>
			</li>
			<li>
					<a href="#!"><i class="icofont icofont-cube"></i> Productos</a>
			</li>
			<li>
					<a href="#!"><i class="icofont icofont-shopping-cart"></i> Cuadrar caja</a>
			</li>
			<li>
					<a href="#!" id="aGastoExtra"><i class="icofont icofont-ui-rate-remove"></i> Gasto extra</a>
			</li>
			<li>
					<a href="#!" id="aIngresoExtra"><i class="icofont icofont-ui-rate-add"></i> Ingreso extra</a>
			</li>
			<li>
					<a href="reportes.php"><i class="icofont icofont-ui-copy"></i> Reportes</a>
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
	<div class="container-fluid">				 
		<div class="row">
			<div class="col-lg-12 contenedorDeslizable">
			<!-- Empieza a meter contenido principal dentro de estas etiquetas -->
				<h2 class="purple-text text-lighten-1">Reporte de cliente</h2>
			<!-- Fin de contenido principal -->
			</div>
		</div>
		<div class="row contenedorDeslizable">
			<div class="col-xs-12 col-md-4 contenedorDatosCliente text-center">
			<!-- Empieza a meter contenido 2.1 -->
				<span><img src="images/user.png" class="img-responsive" style="margin: 0 auto;"></span>
				<h3 class="h3Apellidos mayuscula"><?php echo $rowCliente['cliApellidos']; ?></h3>
				<h3 class="h3Nombres mayuscula"><?php echo $rowCliente['cliNombres']; ?> <button class="btn btn-primary btn-outline" id="spanEditarDatoClient"><i class="icofont icofont-marker"></i></button></h3>
				<span class="rate yellow-text text-darken-2" style="font-size: 18px;">
					<?php
						for ($i=0; $i <5 ; $i++) { 
							if($i<$rowCliente['cliCalificacion']){
								echo '<i class="icofont icofont-ui-rating"></i>';
							}else{echo '<i class="icofont icofont-ui-rate-blank"></i>';}
						}
					 ?></span>
				<h5 class="grey-text text-lighten-1"><i class="icofont icofont-ui-v-card"></i> <?php echo $rowCliente['cliDni']; ?></h5>
				<h5 class="grey-text text-lighten-1 mayuscula"><i class="icofont icofont-home"></i> <?php echo $rowCliente['cliDireccion']; ?></h5>
				<h5 class="grey-text text-lighten-1"><i class="icofont icofont-phone"></i> <?php if($rowCliente['cliCelular']==''){ echo 'Sin datos';}else{ echo $rowCliente['cliCelular'];} ?></h5>
				<h5 class="grey-text text-lighten-1"><i class="icofont icofont-phone"></i><?php if($rowCliente['cliFijo']==''){ echo 'Sin datos';}else{ echo $rowCliente['cliFijo'];} ?></h5>
			<!-- Fin de contenido 2.1 -->
			</div>
			<div class="col-xs-12 col-md-6 contenedorDatosCliente">
			<!-- Empieza a meter contenido 2.2 -->
				<div class="divPrestamo">
				<?php
				$i=0;
				if( isset($_GET['idCliente'])){
					$sql = mysqli_query($conection,"SELECT * FROM `prestamo` where idCliente= '".$_GET['idCliente']."' order by idPrestamo desc;");
					while($rowPrestamos = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
						echo "<h4>Préstamo #P{$rowPrestamos['idPrestamo']} <span class='hidden h4Prestamo'>{$rowPrestamos['idPrestamo']}</span></h4>";
						echo "<div class='divBotonesPrestamo' style='margin-bottom: 10px'>
						<div class='btn-group'>
						  <button type='button' class='btn btn-azul btn-outline dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
							<i class='icofont icofont-settings'></i> <span class='caret'></span>
						  </button>
						  <ul class='dropdown-menu'>
							<li><a href='#' class='btnNuevoItemProdPrestamo'><i class='icofont icofont-shopping-cart'></i> Agregar nuevo item</a></li>
						  </ul>
						</div>
					</div>";
						$i++;
						$j=0;
						$sqlNue = mysqli_query($conection,"SELECT p.*,  tp.tipoDescripcion, tp.tipColorMaterial FROM `prestamo_producto` pp inner join `producto` p on pp.idProducto=p.idProducto inner join tipoProceso tp on tp.idTipoProceso=pp.presidTipoProceso where pp.idPrestamo= {$rowPrestamos['idPrestamo']};");
						while($rowPrestamosProductos = mysqli_fetch_array($sqlNue, MYSQLI_ASSOC)){
							echo "<div class=' paPrestamo'><strong>
						<div class='row {$rowPrestamosProductos['tipColorMaterial']}'>
							<div class='hidden codRegistro'>{$rowPrestamosProductos['idProducto']}</div>
							<div class='col-xs-5 mayuscula'>{$rowPrestamosProductos['prodNombre']}</div>
							<div class='col-xs-3'>S/. ".number_format($rowPrestamosProductos['prodMontoEntregado'],2)."</div>
							<div class='col-xs-4'><i class='icofont icofont-info-circle'></i> {$rowPrestamosProductos['tipoDescripcion']} <span class='pull-right grey-text'><i class='icofont icofont-rounded-right'></i></span></div>
						</div>
					</strong></div>";
							$j++;
						}
					}
					
				}
				?>
					<!-- <div class=" paPrestamo"><strong>
						<div class="row yellow-text text-darken-2">
							<div class="hidden codRegistro">850</div>
							<div class="col-xs-5">Producto 002</div>
							<div class="col-xs-3">S/. 68.00</div>
							<div class="col-xs-4"><i class="icofont icofont-info-circle"></i> Vendido <span class="pull-right grey-text"><i class="icofont icofont-rounded-right"></i></span></div>
						</div>
					</strong></div><div class=" paPrestamo"><strong>
						<div class="row amber-text text-darken-3">
							<div class="hidden codRegistro">850</div>
							<div class="col-xs-5">Producto 003</div>
							<div class="col-xs-3">S/. 80.00</div>
							<div class="col-xs-4"><i class="icofont icofont-info-circle"></i> En venta <span class="pull-right grey-text"><i class="icofont icofont-rounded-right"></i></span></div>
						</div>
					</strong></div>
									</div> -->
			<!-- Fin de contenido 2.2 -->
			</div>
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


</div>

<!-- Modal para agregar nuevo producto  -->
<div class="modal fade modal-nuevoProductoLista" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog " role="document">
	<div class="modal-content">
		<div class="modal-header-morado">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Agregar nuevo producto</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row"> <span class="sr-only" id="iddeLi">-1</span>
				<div class="col-xs-8"><label for="">Tipo de producto <span class="txtObligatorio">*</span></label> <span class="hidden" id="deQuePrestamoViene"></span>
					<div  id="divSelectProductoListado">
						<select class="selectpicker mayuscula" id="sltProductoListado" title="Tipo de producto..."  data-width="100%" data-live-search="true" data-size="15">
							<?php require 'php/listarProductosTipos.php'; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-4"><label for="">Cantidad <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center" id="txtQProduc" autocomplete="off"></div>
			</div>
			<div class="row ">
				<div class="col-xs-8"><label for="">Nombre del artículo y características <span class="txtObligatorio">*</span></label> <input type="text" class="form-control mayuscula" id="txtNameProduc" placeholder="Sea específico con las características" autocomplete="off"></div>
			</div>
			<div class="row">
				<div class="col-xs-4"><label for="">Capital total S/. <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center txtNumeroDecimal" id="txtCapitalProduc" value="0.00" autocomplete="off"></div>
				<div class="col-xs-4"><label for="">Interés Semanal % <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center" id="txtInteresProduc" value="4" autocomplete="off"></div>
				<div class="col-xs-4"><label for="">Fecha de ingreso <span class="txtObligatorio">*</span></label>
					<div class="sandbox-container"><input id="dtpFechaInicio" type="text" class="form-control text-center" autocomplete="off"></div>	
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12"><label for="">¿Más características?</label> <textarea  type="text" class="form-control mayuscula" id="txtObservacionProduc" cols=2 placeholder="¿Algún comentario que rescatar?"></textarea></div>
			</div>

		</div>
		</div>
			
		<div class="modal-footer">
			<div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError">La cantidad de producto no puede ser cero o negativo.</span></div>
			<button class="btn btn-morado btn-outline hidden" id='btnActualizarItem' ><i class="icofont icofont-refresh"></i> Actualizar item</button>
			<button class="btn btn-morado btn-outline" id='btnAgregarItem' ><i class="icofont icofont-social-meetme"></i> Agregar item</button>
		</div>
	</div>
	</div>
</div>
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
$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});
$('#aaccionesCaja').click(function () {
	$('.modal-accionesCaja').modal('show');
});
$('.paPrestamo').click(function () {
	var codigo=$(this).find('.codRegistro').text()
	window.location='productos.php?idProducto='+codigo;
});
$('.rate i').click(function () {
	var calificacion=$(this).index()+1;
	$.ajax({url:'php/puntarCliente.php', type: 'POST', data: {estrellas: calificacion , idCli: <?php echo $_GET['idCliente']; ?> }}).done(function (resp) {
		console.log(resp);
	});
	$('.rate').children().remove();
	for (var i = 0; i < 5; i++) {
		if(i<calificacion){
			$('.rate').append('<i class="icofont icofont-ui-rating"></i>');
		}else{
			$('.rate').append('<i class="icofont icofont-ui-rate-blank"></i>');
		}
	}
});
$('.btnNuevoItemProdPrestamo').click(function () {
	$('#deQuePrestamoViene').text( $(this).parent().parent().parent().parent().parent().find('.h4Prestamo').text() );
	$('.modal-nuevoProductoLista').modal('show');
});
</script>
</body>
</html>
