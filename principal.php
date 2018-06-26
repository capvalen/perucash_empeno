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
		<link href="css/estilosElementosv3.css?version=3.0" rel="stylesheet">
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="shortcut icon" href="images/favicon.png">
		
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
				<div class="col-lg-12 contenedorDeslizable fondoGeo">
				<!-- Empieza a meter contenido principal dentro de estas etiquetas -->
					<h2 class="purple-text text-lighten-1">Bienvenido a PerúCash <small><?php require 'php/version.php'; ?></small></h2>
						<h3>Adaptado y re-diseñado por: <small>Carlos Pariona Valencia</small></h3>
						<h4>Contáctame a: 
							<span style="font-size: 30px">
								<a href="http://www.info-cat.com/" style="color:black; text-decoration: none;"><i class="icofont icofont-earth"></i></a>
								<a href="https://www.facebook.com/infocat.soluciones/" style="color:#3C5A99; text-decoration: none;" ><i class="icofont icofont-social-facebook" ></i></a>
								<a href="https://www.facebook.com/capvalen" style="color:#0080FF; text-decoration: none;"><i class="icofont icofont-social-facebook-messenger" ></i></a>
								<a href="https://twitter.com/info_gato" style="color: #5EAADE; text-decoration: none;"><i class="icofont icofont-social-twitter"></i></a>
							</span>
						</h4>
						<hr>
						<p><strong>DATOS IMPOTANTES:</strong> Cras sed neque diam. Morbi nec ligula nisl. Nam rhoncus lectus lorem, eget posuere turpis aliquet quis. Duis rutrum pulvinar pharetra. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas iaculis scelerisque gravida. Suspendisse vitae lacinia arcu. In eget ante eu dui egestas aliquam. Nam at tortor non nulla cursus blandit.</p>
						<p>
						Praesent aliquam velit sit amet odio convallis, id scelerisque enim dignissim. Pellentesque tincidunt accumsan enim. Aenean elementum commodo vestibulum. Donec sed nulla enim. Morbi pharetra eget elit nec imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed sed diam eget dolor consectetur finibus. Etiam quis tincidunt dolor. Etiam bibendum semper nulla. Cras quis feugiat quam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ex enim, elementum viverra sagittis et, tristique in dui. Sed luctus neque orci, nec pharetra augue condimentum vel. Maecenas id pellentesque lacus.</p>
						<p>
						Fusce non turpis sed ligula mollis convallis. Morbi ullamcorper arcu et nisi interdum, gravida vestibulum ligula ornare. Mauris ac nisi et neque posuere molestie nec convallis magna. Cras faucibus molestie enim, non porttitor massa vehicula ut. Integer maximus mauris nulla, id rhoncus diam ornare et. Suspendisse sit amet feugiat purus, eu convallis tellus. Nullam blandit vulputate mi, vitae tempor ligula aliquet vitae. Aliquam at hendrerit odio. Proin varius eleifend lectus nec condimentum. Pellentesque vulputate in sem sed tincidunt. Sed eu luctus nisl. Nullam a malesuada nisl. Phasellus quam nisl, pulvinar non lorem non, lobortis aliquet diam. Nam ante metus, sodales nec malesuada sagittis, placerat nec arcu.</p>
						<p>
						
						Fusce non turpis sed ligula mollis convallis. Morbi ullamcorper arcu et nisi interdum, gravida vestibulum ligula ornare. Mauris ac nisi et neque posuere molestie nec convallis magna. Cras faucibus molestie enim, non porttitor massa vehicula ut. Integer maximus mauris nulla, id rhoncus diam ornare et. Suspendisse sit amet feugiat purus, eu convallis tellus. Nullam blandit vulputate mi, vitae tempor ligula aliquet vitae. Aliquam at hendrerit odio. Proin varius eleifend lectus nec condimentum. Pellentesque vulputate in sem sed tincidunt. Sed eu luctus nisl. Nullam a malesuada nisl. Phasellus quam nisl, pulvinar non lorem non, lobortis aliquet diam. Nam ante metus, sodales nec malesuada sagittis, placerat nec arcu.</p><p>
						Fusce non turpis sed ligula mollis convallis. Morbi ullamcorper arcu et nisi interdum, gravida vestibulum ligula ornare. Mauris ac nisi et neque posuere molestie nec convallis magna. Cras faucibus molestie enim, non porttitor massa vehicula ut. Integer maximus mauris nulla, id rhoncus diam ornare et. Suspendisse sit amet feugiat purus, eu convallis tellus. Nullam blandit vulputate mi, vitae tempor ligula aliquet vitae. Aliquam at hendrerit odio. Proin varius eleifend lectus nec condimentum. Pellentesque vulputate in sem sed tincidunt. Sed eu luctus nisl. Nullam a malesuada nisl. Phasellus quam nisl, pulvinar non lorem non, lobortis aliquet diam. Nam ante metus, sodales nec malesuada sagittis, placerat nec arcu.</p>
						
						<p><button class="btn btn-morado btn-outline" id="btnBienvenido"><i class="icofont icofont-social-meetme"></i> Mostrar Saludo de bienvenida</button> <button class="btn btn-success btn-outline"><i class="icofont icofont-check"></i> Aprobar</button> <button class="btn btn-danger btn-outline"><i class="icofont icofont-close"></i> Cancelar</button></p>
				<!-- Fin de contenido principal -->
				</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

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
<script type="text/javascript" src="js/inicializacion.js?version=1.0.36"></script>

<!-- Menu Toggle Script -->
<script>

$(document).ready(function(){
});
$('#btnBienvenido').click(function () {
	$('.modal-Bienvenido').modal('show');
});
</script>

</body>

</html>
