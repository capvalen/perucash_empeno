<?php 
require("php/conkarl.php");
?>
<!DOCTYPE html>
<html lang="es">
<?php 
if( isset($_GET['idProducto'])){
	$sql = mysqli_query($conection,"select p.*, concat (c.cliApellidos, ' ' , c.cliNombres) as cliNombres, tp.tipoDescripcion, tp.tipColorMaterial, prodActivo, esCompra, u.usuNombres FROM producto p inner join Cliente c on c.idCliente=p.idCliente inner join prestamo_producto prp on prp.idProducto=p.idProducto inner join tipoProceso tp on tp.idTipoProceso=prp.presidTipoProceso 
inner join usuario u on u.idUsuario=p.idUsuario
WHERE p.idProducto=".$_GET['idProducto'].";");
$rowProducto = mysqli_fetch_array($sql, MYSQLI_ASSOC);

/*$carpeta = 'images/productos/'.$_GET['idProducto'];
if (!file_exists($carpeta)) {
	mkdir($carpeta, 0777, true);
}*/
}
?>

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
		<link href="css/flexslider.css?version=1.0.2" rel="stylesheet">
		<link href="css/lightbox.css" rel="stylesheet">
		
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
	.divImagen li{list-style-type:none!important;}
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
	#tabAdvertencias li{width: 85%;}
	.mensaje{    float: left;    margin-bottom: 10px;
	width: 85%;
	border-radius: 5px;
	padding: 5px;
	display: flex;
	background: #ab47bc; color: white;box-shadow: 3px 3px 2px rgba(0, 0, 0, 0.13);
	/* background: whitesmoke; */}
	.mensaje:before {
	width: 0;
	height: 0;
	content: "";
	top: -5px;
	left: -14px;
	position: relative;
	border-style: solid;
	border-width: 0 13px 13px 0;
	border-color: transparent #ab47bc transparent transparent;
	/*border-color: transparent whitesmoke transparent transparent;*/
}
.textoMensaje{ padding-left: 30px }
.mensaje small{color: #f7f7f7;/* color: #6b6b6b; */}
.rowFotos{margin: 0 auto;}
.divFotoGestion{border-color:#f3f3f3; border: dashed 2px #9a9a9a;
	 border-radius: 5px;
	width: 22%;
	height: 100px;
	margin: 0 5px;padding-right: 5px;
	padding-left: 5px;padding-top: 5px;}
#tablita th{cursor: pointer;}
</style>
<div class="" id="wrapper">

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
					<a href="productos_search.php"><i class="icofont icofont-cube"></i> Productos</a>
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
				<div class="container-fluid row">
				<h2 class="purple-text text-lighten-1">Gestión de productos 11</h2>
				<div class="col-md-6">
					Seleccione la categoría de productos que desea ver:
				<div id="cmbCategoriasParaVer">
				<select class="selectpicker mayuscula" id="miCmbCategoriaLista" title="Categorías..."  data-width="100%" data-live-search="true" data-size="15">
					<option class="optProducto mayuscula" data-tokens="99">Aún activos</option>
					<option class="optProducto mayuscula" data-tokens="16">Con intención de pago</option>
					<option class="optProducto mayuscula" data-tokens="97">Compras</option>
					<option class="optProducto mayuscula" data-tokens="23">En almacén</option>
					<option class="optProducto mayuscula" data-tokens="24">En prórroga</option>
					<option class="optProducto mayuscula" data-tokens="18">En remate</option>
					<option class="optProducto mayuscula" data-tokens="19">En venta</option>
					<option class="optProducto mayuscula" data-tokens="17">Extraviados</option>
					<option class="optProducto mayuscula" data-tokens="98">Por vencer</option>
					<option class="optProducto mayuscula" data-tokens="25">Registro nuevo</option>
					<option class="optProducto mayuscula" data-tokens="20">Ya rematados</option>
					<option class="optProducto mayuscula" data-tokens="15">Ya retirados</option>
					<option class="optProducto mayuscula" data-tokens="21">Ya vendidos</option>
				</select></div>
				</div>
				</div>
				<div class="container-fluid row">
			<?php 
			if( isset($_GET["idCat"]) ){
			$cadena= "call listarCategoriaEnProductos('".$_GET['idCat']."');";
			//$sql="call listarCategoriaEnProductos (".$_GET['idCat']."')";
				if( $_GET['idCat']=='99'){echo "<h3 class='purple-text text-lighten-1'>Aún activos</h3>"; $cadena= "call listarProductosActivos();"; /*caso especial */}
				if( $_GET['idCat']=='97'){echo "<h3 class='purple-text text-lighten-1'>Compras</h3>"; $cadena= "call listarSoloCompras();"; /*caso especial */}
				if( $_GET['idCat']=='11, 15'){ echo "<h3 class='purple-text text-lighten-1'>Ya retirados</h3>"; /*caso especial*/}
				if( $_GET['idCat']=='24'){ echo "<h3 class='purple-text text-lighten-1'>En prórroga</h3>"; /*caso especial*/}
				if( $_GET['idCat']=='16'){ echo "<h3 class='purple-text text-lighten-1'>Con intención de pago</h3>";}
				if( $_GET['idCat']=='23'){ echo "<h3 class='purple-text text-lighten-1'>En almacén</h3>";}
				if( $_GET['idCat']=='18'){ echo "<h3 class='purple-text text-lighten-1'>En remate</h3>";}
				if( $_GET['idCat']=='19'){ echo "<h3 class='purple-text text-lighten-1'>En venta</h3>";}
				if( $_GET['idCat']=='17'){ echo "<h3 class='purple-text text-lighten-1'>Extraviados</option</h3>";}
				if( $_GET['idCat']=='98'){ echo "<h3 class='purple-text text-lighten-1'>Por vencer</h3>";}
				if( $_GET['idCat']=='25'){ echo "<h3 class='purple-text text-lighten-1'>Registro nuevo</h3>";}
				if( $_GET['idCat']=='20'){ echo "<h3 class='purple-text text-lighten-1'>Ya rematados</h3>";}
				if( $_GET['idCat']=='21'){ echo "<h3 class='purple-text text-lighten-1'>Ya vendidos</h3>";}
				/*if( $_GET['idCat']=='21'){ echo 'hola';}*/
			$sql = mysqli_query($conection, $cadena);
			
			// if (!$sql) { ////codigo para ver donde esta el error
			//     printf("Error: %s\n", mysqli_error($conection));
			//     exit();
			// }?>
				<table class="table table-hover" id="tablita">
					<thead>
					<tr>
						<th data-sort="float">Cod. <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Producto <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="string">Dueño <i class="icofont icofont-expand-alt"></i></th>
						<th data-sort="float">Capital <i class="icofont icofont-expand-alt"></i></th>
					</tr>
					</thead>
					<tbody><?php
				while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
				{?>
					<tr>
						<td><a href="productos.php?idProducto=<?php echo $row['idProducto']; ?>"><?php echo $row['idProducto']; ?></a></td>
						<td class="mayuscula"><a href="productos.php?idProducto=<?php echo $row['idProducto']; ?>"><?php echo $row['prodNombre']; ?></a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=<?php echo $row['idCliente']; ?>"><?php echo $row['cliNombres']; ?></a></td>
						<td><?php echo $row['pCapital']; ?></td>
					</tr><?php
				}
				mysqli_close($conection); //desconectamos la base de datos
				}
				?>
					</tbody>
				</table>
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
<script type="text/javascript" src="js/impotem.js?version=1.0.4"></script>
<script src="js/bootstrap-select.js"></script>
<script type="text/javascript" src="js/stupidtable.min.js"></script>

<!-- Menu Toggle Script -->
<script>
datosUsuario();

$(document).ready(function(){
	moment.locale('es');
	$('#tablita').stupidtable();
});
$('#miCmbCategoriaLista').on('changed.bs.select', function () {
	var categoria= $(this).parent().find('.selected a').attr('data-tokens');
	switch(categoria){
		case '99': /*Hacer algo en aun activos*/ location.href='productos_search.php?idCat=99';break;
		case '98': /*Hacer algo en Por vencer*/ location.href='productos_search.php?idCat=98';break;
		case '16': /*Hacer algo en intencion de pago*/ location.href='productos_search.php?idCat=16';break;
		case '23': /*Hacer algo en */ location.href='productos_search.php?idCat=23';break;
		case '24': /*Hacer algo en */ location.href='productos_search.php?idCat=24';break;
		case '18': /*Hacer algo en */ location.href='productos_search.php?idCat=18';break;
		case '19': /*Hacer algo en */ location.href='productos_search.php?idCat=19';break;
		case '17': /*Hacer algo en */ location.href='productos_search.php?idCat=17';break;
		case '25': /*Hacer algo en */ location.href='productos_search.php?idCat=25';break;
		case '20': /*Hacer algo en */ location.href='productos_search.php?idCat=20';break;
		case '15': /*Hacer algo en */ /*11, 15*/ location.href='productos_search.php?idCat='+ encodeURIComponent('11, 15'); break;
		case '21': /*Hacer algo en */ location.href='productos_search.php?idCat=21';break;
		case '97': /*Hacer algo en */ location.href='productos_search.php?idCat=97';break;
	}

});
</script>

</body>

</html>
