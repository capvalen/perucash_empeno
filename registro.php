<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Principal: PeruCash</title>

		<!-- Bootstrap Core CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Custom CSS -->
		<link href="css/sidebarDeslizable.css?version=1.0.5" rel="stylesheet">
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link href="css/estilosElementosv3.css?version=3.0.2" rel="stylesheet">
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="shortcut icon" href="images/peto.png" />
		
</head>

<body>

<div id="wrapper">

	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			
			<div class="logoEmpresa ocultar-mostrar-menu">
				<img class="img-responsive" src="images/empresa.png?version=1.1" alt="">
			</div>
			<li class="active">
					<a href="#!"><i class="icofont icofont-home"></i> Inicio</a>
			</li>
			<li>
					<a href="registro.php"><i class="icofont icofont-washing-machine"></i> Registro</a>
			</li>
			<li>
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
							<div class="btn-group has-clear hidden"><label for="txtBuscarNivelGod" class="text-muted visible-xs">Buscar algo:</label>
								<input type="text" class="form-control" id="txtBuscarNivelGod" placeholder="&#xeded;">
								<span class="form-control-clear glyphicon glyphicon-remove-circle form-control-feedback hidden"></span>
							</div>
						 </li>
						 <li id="liDatosPersonales"><a href="#!" style="padding-top: 12px;"><p> <span id="icoUser"><i class="icofont icofont-ui-user"></i></span><span class="mayuscula" id="menuNombreUsuario">Pariona Valencia, Carlos Alex</span></p></a></li>
							
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
				<h2 class="purple-text text-lighten-1">Registro de cliente y producto</h2>
			<!-- Fin de contenido principal -->
			</div>
		</div>
		<div class="">
			<div class="col-lg-6 contenedorDeslizable contenedorDatosCliente">
			<!-- Empieza a meter contenido 2 -->
				<div class="row">
					<div class="col-sm-4"><label>D.N.I.:</label><input type="number" class="form-control" id="txtDni" placeholder="Número del documento de identidad" maxlength="8" size="8"></div>
				</div>
				<div class="row">
						<div class="col-sm-6"><label>Apellidos:</label><input type="text" class="form-control mayuscula" id="txtApellidos" placeholder="Apellidos completos"></div>
					<div class="col-sm-6"><label>Nombres:</label><input type="text" class="form-control mayuscula" id="txtNombres" placeholder="Nombres completos"></div>
					<div class="col-sm-6"><label>Dirección domiciliaria:</label><input type="text" class="form-control mayuscula" id="txtDireccion" placeholder="Dirección del cliente"></div>
					<div class="col-sm-6"><label>Correo electrónico:</label><input type="text" class="form-control" id="txtCorreo" placeholder="Correo electrónico del cliente"></div>
					<div class="col-sm-6"><label>Celular:</label><input type="text" class="form-control" id="txtCelular" placeholder="Número de celular(es)"></div>
				</div>
			<!-- Fin de contenido 2 -->
			</div>
			<div class="col-lg-6 contenedorDeslizable contenedorDatosProductos">
			<!-- Empieza a meter contenido 2 -->
				<div class="container">
					<div class="material-switch pull-left ">
						<input id="someSwitchOptionWarning" type="checkbox"/>
						<label for="someSwitchOptionWarning" class="label-success" ></label>

					</div>
					<label for="someSwitchOptionWarning" id="lblSWQueEs" style="padding-top: 0;margin-left: 10px; color: #606bdc; transition: all 0.3s ease-in-out;"> Artículo(s) con intereses</label>
				</div>
				<div class="row">
						<div class="col-sm-12">
						<div class="row text-center">
							<button class="btn btn-outline btn-morado" id="btnAddNewProd"><i class="icofont icofont-plus"></i> Agregar producto</button>
						</div>
							
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias aperiam velit perspiciatis animi cum qui, modi labore totam cumque, accusantium, aut sed eius quia obcaecati earum assumenda necessitatibus rem ad!</p>
						
					
					
						<div class="col-sm-12 text-center">
							<button class="btn btn-primary btn-lg btn-outline" id="btnCronogramaPagosVer"><i class="icofont icofont-chart-histogram-alt"></i> Cronograma de pagos</button>
							<button class="btn btn-primary btn-lg btn-outline" id="btnGuardarDatos"><i class="icofont icofont-diskette"></i> Guardar empeño</button>
							<button class="btn btn-primary sr-only btn-lg btn-outline" id="btnGuardarCompra"><i class="icofont icofont-diskette"></i> Guardar compra</button>
						</div>
					</div>
			<!-- Fin de contenido 2 -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

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
			<div class="row">
				<div class="col-xs-6"><label for="">Tipo de producto</label> <input type="text" class="form-control"></div>
				<div class="col-xs-6"><label for="">Cantidad</label> <input type="number" class="form-control"></div>
			</div>
			<div class="row">
				<div class="col-xs-8"><label for="">Nombre del artículo</label> <input type="text" class="form-control"></div>
				<div class="col-xs-4"><label for="">Marca</label> <input type="text" class="form-control"></div>
				
			</div>
			<div class="row">
				<div class="col-xs-4"><label for="">Capital</label> <input type="number" class="form-control" value="0.00"></div>
				<div class="col-xs-4"><label for="">Interés a aplicar (%)</label> <input type="number" class="form-control" value="4"></div>
				<div class="col-xs-4"><label for="">Fecha de ingreso</label>
					<div class="sandbox-container"><input id="dtpFechaInicio" type="text" class="form-control text-center"></div>	
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12"><label for="">Observación</label> <textarea  type="text" class="form-control" cols=2></textarea></div>
			</div>
		</div>
        </div>
			
		<div class="modal-footer">
			<button class="btn btn-success btn-outline" data-dismiss="modal" ><i class="icofont icofont-social-meetme"></i> Aceptar</button>
		</div>
	</div>
	</div>
</div>
</div>

<!-- Modal para decir Bienvenido  -->
<div class="modal fade modal-Bienvenido" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-success">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Saludos</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
			<p><strong>Bienvenido!</strong> Gracias por usar el panel deslizable</p>
			</div>
		</div>
        </div>
			
		<div class="modal-footer">
			<button class="btn btn-success btn-outline" data-dismiss="modal" ><i class="icofont icofont-social-meetme"></i> Aceptar</button>
		</div>
	</div>
	</div>
</div>
</div>

	
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>


<!-- Bootstrap Core JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script src="js/inicializacion.js?version=1.0.1"></script>

<!-- Menu Toggle Script -->
<script>

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
});
$('#btnBienvenido').click(function () {
	$('.modal-Bienvenido').modal('show');
});
$('#btnAddNewProd').click(function () {
	$('.modal-nuevoProductoLista').modal('show');
});
</script>

</body>

</html>
