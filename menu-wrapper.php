<?php 
$nomArchivo = basename($_SERVER['PHP_SELF']); ?>
<div id="sidebar-wrapper">
	<ul class="sidebar-nav">
		<div class="logoEmpresa ocultar-mostrar-menu">
			<img class="img-responsive" src="images/empresa.png?version=1.1" alt="">
		</div>
		<li <?php if($nomArchivo =='principal.php') echo 'class="active"'; ?>>
				<a href="#!"><i class="icofont icofont-home"></i> Inicio</a>
		</li>
		<li <?php if($nomArchivo =='registro.php') echo 'class="active"'; ?>>
				<a href="registro.php"><i class="icofont icofont-ui-music-player"></i> Registro</a>
		</li>
		<li <?php if($nomArchivo =='principal.php') echo 'class="active"'; ?>>
				<a href="#!"><i class="icofont icofont-cube"></i> Productos</a>
		</li>
		<li <?php if($nomArchivo =='caja.php') echo 'class="active"'; ?>>
				<a href="caja.php"><i class="icofont icofont-shopping-cart"></i> Caja</a>
		</li>
		<li <?php if($nomArchivo =='cochera.php') echo 'class="active"'; ?>>
				<a href="cochera.php"><i class="icofont icofont-car-alt-1"></i> Cochera</a>
		</li>
		<li <?php if($nomArchivo =='reportes.php') echo 'class="active"'; ?>>
				<a href="reportes.php"><i class="icofont icofont-ui-copy"></i> Reportes</a>
		</li>
		<li <?php if($nomArchivo =='verificacion.php') echo 'class="active"'; ?>>
				<a href="verificacion.php"><i class="icofont icofont-medal"></i> Verificación</a>
		</li>
		<li <?php if($nomArchivo =='almacen.php') echo 'class="active"'; ?>>
				<a href="almacen.php"><i class="icofont icofont-box"></i> Almacén</a>
		</li>
		<?php if( $_COOKIE['ckPower']==1){ ?>
		<li <?php if($nomArchivo =='usuarios.php') echo 'class="active"'; ?>>
				<a href="#!"><i class="icofont icofont-users"></i> Usuarios</a>
		</li>
		<li <?php if($nomArchivo =='configuraciones.php') echo 'class="active"'; ?>>
				<a href="configuraciones.php"><i class="icofont icofont-settings"></i> Configuraciones</a>
		</li>
		 <?php } ?>
		<li>
				<a href="#!" class="ocultar-mostrar-menu"><i class="icofont icofont-swoosh-left"></i> Ocultar menú</a>
		</li>
	</ul>
</div>
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
						 <li id="liDatosPersonales"><a href="#!" style="padding-top: 12px;"><p> <span class="icoUser"><i class="icofont icofont-ui-user"></i></span><span class="mayuscula" id="menuNombreUsuario"><?php echo $_SESSION['nomCompleto']; ?></span></p><p class="icoUser"><i class="icofont icofont-archive"></i> <?= $_COOKIE['ckSucursal'];?></p></a></li>
						 <li class="text-center"><a href="php/desconectar.php"><span class="visible-xs">Cerrar Sesión</span><i class="icofont icofont-ui-power"></i></a></li>
					</ul>

				</div>
		</div>
		</nav>
	</div>
</div>