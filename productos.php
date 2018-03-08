<?php session_start();
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
</style>
<div class="noselect" id="wrapper">

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
			<li class="hidden">
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
	<div class="container-fluid noSelect">				 

		<div class="row continer-fluid">
			<div class="col-xs-12 contenedorDeslizable contenedorDatosCliente ">
			<!-- Empieza a meter contenido 2.1 -->
				<div class="container row" style="margin-bottom: 20px;">
					<h2 class="h3Apellidos mayuscula purple-text text-lighten-1"><?php echo  $rowProducto['prodNombre']; ?></h2>
					<div class="divBotonesEdicion" style="margin-bottom: 10px">
						<div class="btn-group">
						  <button type="button" class="btn btn-azul btn-outline dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="icofont icofont-settings"></i> <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
							<li><a href="#" id="liAGestionrFotos"><i class="icofont icofont-shopping-cart"></i> Gestionar fotos</a></li>
							<li><a href="#" id="liImprimirHojaControl"><i class="icofont icofont-page"></i> Hoja de control</a></li>
						  </ul>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 divImagen">
						<?php 
						$directorio = "images/productos/".$_GET['idProducto'];
						if(file_exists($directorio)){$ficheros  = scandir($directorio, 1);
						$cantImg=0; ?>
						<div class="flexslider">
							<ul class="slides">
							   <?php 
									foreach($ficheros as $archivo)
									{
										$cantImg++;/*".$directorio."/".$archivo."*/
										if (eregi("jpeg", $archivo) || eregi("jpg", $archivo) || eregi("png", $archivo)){
									        /*echo $directorio."/".$archivo;*/
									        echo "<li><a href='".$directorio."/".$archivo."' data-lightbox='image-1'><img src='".$directorio."/".$archivo."' class='img-responsive' ></a></li>";
									    }
									}/*<img src="images/imgBlanca.png" class="img-responsive" alt="">*/
								?>
							</ul>
						</div>
						<?php if($cantImg==2){ echo '<li><a href="images/imgBlanca.png" data-lightbox="image-1"><img src="images/imgBlanca.png" class="img-responsive" ></a></li>';}}else{echo '<li><a href="images/imgBlanca.png" data-lightbox="image-1"><img src="images/imgBlanca.png" class="img-responsive" ></a></li>';} ?>
					</div>
					<div class="col-xs-12 col-sm-7 divDatosProducto">
						<p>Código de producto: #<span><?php echo $rowProducto['idProducto']; ?></span></p>
						<p class="mayuscula">Dueño: <a href="cliente.php?idCliente=<?php echo $rowProducto['idCliente']; ?>" class="spanDueno"><?php echo $rowProducto['cliNombres']; ?></a></p>
						<p class="hidden">Registrado: <span><?php echo $rowProducto['prodFechaRegistro']; ?></span></p>
						<p>Préstamo incial: S/. <?php echo number_format($rowProducto['prodMontoEntregado'],2); ?></p>
						<p>Cantidad: <span><?php echo $rowProducto['prodCantidad']; ?> </span><?php echo $rowProducto['prodCantidad']==='1' ? 'Und.' : 'Unds.' ?></p>
						<p>Adquisición: <span><?php echo $rowProducto['esCompra']==='0' ? 'Compra' : 'Por alquiler' ?></span></p>
						<p>Estado del producto: <strong class="<?php echo $rowProducto['tipColorMaterial']; ?> estadoProducto"><?php echo $rowProducto['tipoDescripcion'] ?></strong></p>
						<p>Estado del sub-préstamo: <strong class="<?php echo $rowProducto['tipColorMaterial']; ?>"><?php echo $rowProducto['prodActivo']==='0' ? 'No vigente' : 'Vigente' ?></strong></p>
					</div>
				</div>
				<div class="container row">
					<ul class="nav nav-tabs">
					<li class="active"><a href="#tabIntereses" data-toggle="tab">Intereses</a></li>
					<li><a href="#tabMovEstados" data-toggle="tab">Estados y movimientos</a></li>
					<li class="hidden"><a href="#tabMovFinancieros" data-toggle="tab">Financiero</a></li>
					<li><a href="#tabAdvertencias" data-toggle="tab">Observaciones y advertencias</a></li>
					
					</ul>
					<div class="tab-content">
					<!--tab content-->
						<div class="tab-pane fade in active container-fluid" id="tabIntereses">
						<!--Inicio de pestaña interior 01-->
							<h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección intereses</h4>
						<?php 
						if($rowProducto['esCompra']===1){
							?>
							<ul><li>Ésta compra no genera intereses.</li></ul>
							<?php
						}else{
							if($rowProducto['prodActivo']==='0'){
							?><ul><li>El producto ya no genera intereses por haber finalizado.</li></ul><?php	
							}else{
								$sqlIntereses=mysqli_query($conection, "SELECT round(p.preCapital,2) as preCapital, p.desFechaContarInteres, datediff( now(), desFechaContarInteres ) as diferenciaDias, preInteres FROM `prestamo_producto` p where idProducto=".$_GET['idProducto']);
								$rowInteres=mysqli_fetch_assoc($sqlIntereses);
								?>
							<ul>
								<li>Saldo pendiente: <span>S/. <?php echo $rowInteres['preCapital']; ?></span></li>
								<li>Tiempo de intereses: <span><?php if($rowInteres['diferenciaDias']==='0'){echo 1;}else{echo $rowInteres['diferenciaDias'];} ?> días</span></li>
							<?php if($rowInteres['diferenciaDias']>=1 && $rowInteres['diferenciaDias']<=28 ){ ?>
								<li>Razón del cálculo: <span><strong>Interés simple</strong> (del día 1 al 28).</span></li>
								<li>Interés: <span><?php echo $rowInteres['preInteres']; ?>% = S/. <?php $interesJson= $rowInteres['preCapital']*$rowInteres['preInteres']/100; echo $interesJson; ?></span></li>
							<?php }else { 
								$_GET['inicio']=floatval($rowInteres['preCapital']);
								if($rowInteres['diferenciaDias']==='0'){$_GET['numhoy']=1;}else{$_GET['numhoy']=$rowInteres['diferenciaDias'];}
								
								$_GET['interes']=$rowInteres['preInteres'];
								$resultado=(require_once "php/calculoInteresAcumuladoDeValorv3.php");
								// var_dump($resultado);
								$interesJson= $resultado[0]['pagarAHoy'];
								$gastosAdmin=0;
								?>
								<li>Razón del cálculo: <span><strong>Interés acumulado diario</strong> (más allá del día 29).</span></li>
								<li>Interés: <span><?php echo $rowInteres['preInteres']; ?>% = S/. <?php echo number_format($interesJson,2); ?></span></li>
							<?php if($rowInteres['diferenciaDias']>=29 ){ $gastosAdmin=10; ?>
								<li>Gastos admnistrativos: <span>S/. 10.00</span></li>
							<?php }} ?>
								<li>Deuda total para hoy: <span><strong>S/. <?php echo number_format($interesJson+$rowInteres['preCapital']+$gastosAdmin,2);  ?></strong></span></li>
							</ul>
							<?php 
							}
						} ?>
						<!--Fin de pestaña interior 01-->
						</div>
						<div class="tab-pane fade container-fluid" id="tabMovEstados">
						<!--Inicio de pestaña interior 02-->
							<h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección de estados &amp; Movimientos</h4>
							<ul>
								<li>Registrado por <span class="spanQuienRegistra"><?php echo $rowProducto['usuNombres']; ?></span>: <span class="spanFechaFormat"><?php echo $rowProducto['prodFechaRegistro']; ?></span> <button class="btn btn-sm btn-azul btn-outline btnImprimirTicket" data-boton="<?php echo 0;/*$rowProducto['idTipoProceso']*/ ?>"><i class="icofont icofont-print"></i></button></li>
								<?php $i=0; 
								$sqlEstado=mysqli_query($conection, "SELECT * FROM `reportes_producto` rp inner join `detallereporte` dr on dr.idDetalleReporte=rp.idDetalleReporte where idProducto=".$_GET['idProducto'].";");
								while($rowEstados = mysqli_fetch_array($sqlEstado, MYSQLI_ASSOC)){
									echo "<li>{$rowEstados['repoDescripcion']} por  <span class='spanQuienRegistra'>{$rowEstados['repoUsuario']}</span>, con S/. <span class='spanCantv3'>".number_format($rowEstados['repoValorMonetario'],2)."</span>: <span class='spanFechaFormat'>{$rowEstados['repoFechaOcurrencia']}</span> <button class='btn btn-sm btn-azul btn-outline btnImprimirTicket' data-boton={$rowEstados['idDetalleReporte']}><i class='icofont icofont-print'></i></button></li>";
									$i++;
								} ?>
							</ul>
						<!--Fin de pestaña interior 02-->
						</div>
						<div class="tab-pane fade container-fluid" id="tabMovFinancieros">
						<!--Inicio de pestaña interior 03-->
							<h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección Financiera</h4>
							<ul>
								<li>Capital o desembolso	13/01/2018 03:37 p.m.	S/. 700.00	bmanrique</li>
							</ul>
						<!--Fin de pestaña interior 03-->
						</div>
						<div class="tab-pane fade container-fluid" id="tabAdvertencias">
						<!--Inicio de pestaña interior 04-->
							<h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección Observaciones y Advertencias antes de rematar</h4>
							<?php if($rowProducto['prodObservaciones']<>''){ ?>
								<div class="mensaje"><div class="texto"><p><strong>Observaciones</strong></p> <p class="textoMensaje"><?php echo $rowProducto['prodObservaciones']; ?></p> </div></div>
							<?php } ?>
							<div class="conjuntoMensajes">
							<?php
							$sqlMensajes=mysqli_query($conection, "SELECT a.*, u.usuNombres FROM `avisos` a inner join usuario u on u.idUsuario= a.idUsuario where idProducto=".$_GET['idProducto'].";");
							while($rowMensajes = mysqli_fetch_array($sqlMensajes, MYSQLI_ASSOC)){ ?>
								<div class="mensaje"><div class="texto"><p><strong><?php echo $rowMensajes['usuNombres']; ?></strong> <small><i class="icofont icofont-clock-time"></i> <span class="spanFechaFormat"><?php echo $rowMensajes['aviFechaAutomatica']; ?></span></small></p> <p class="textoMensaje"><?php echo $rowMensajes['aviMensaje']; ?></p> </div></div>
							<?php } ?>
							</div>
							<div class="dejarMensaje"><input type="text" class="form-control mayuscula" id="txtDejarMensaje" placeholder="¿Qué mensaje dejó para el cliente?" style="width: 85%; display: inline-block;"> <button class="btn btn-default" id="btnDejarMensaje" style="margin-top: -3px;"><i class="icofont icofont-location-arrow"></i></button></div>
							
						<!--Fin de pestaña interior 04-->
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

<!--Modal Para asignar nuevo estado al producto-->
<div class="modal fade modal-asignarEstado" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header-warning">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Asignar un nuevo estado</h4>
			</div>
			<div class="modal-body">
				<div id="cmbEstadoProd">
				<select class="selectpicker mayuscula" title="Nuevo estado..."  data-width="100%" data-live-search="true" data-size="15">
					<?php require 'php/detalleReporteOPT.php'; ?>
				</select></div>
				<input type="text" class="form-control" id="txtComentarioEstado" placeholder="Comentario extra">
			</div>
			<div class="modal-footer">
			<button class="btn btn-warning btn-outline" data-dismiss="modal" id="btnActualizarEstado" ><i class="icofont icofont-check"></i> Actualizar</button>
		</div>
		</div>
	</div>
</div>

<!--Modal Para gestionar fotos-->
<div class="modal fade modal-gestionarFotos" tabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header-warning">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Gestionar fotos</h4>
			</div>
			<div class="modal-body">
				<div>
					<div class="row rowFotos"> 
					<?php if($cantImg<=2){ ?>
						<div class="col-xs-3 divFotoGestion" id="foto1"></div>
						<div class="col-xs-3 divFotoGestion" id="foto2"></div>
						<div class="col-xs-3 divFotoGestion" id="foto3"></div>
						<div class="col-xs-3 divFotoGestion" id="foto4"></div>
					<?php }else{
						foreach($ficheros as $archivo)
								{
									$cantImg++;/*".$directorio."/".$archivo."*/
									if (eregi("jpeg", $archivo) || eregi("jpg", $archivo) || eregi("png", $archivo)){
								      echo "<div class='col-xs-3 divFotoGestion' id='foto{$i}'><img src='".$directorio."/".$archivo."' class='img-responsive' ></div>";
								    }else{ echo '<div class="col-xs-3 divFotoGestion" id="foto3"></div>';}
								}/*<img src="images/imgBlanca.png" class="img-responsive" alt="">*/
						} ?>
					</div><br>
					<div class="row ">
					<?php for ($i=0; $i < 2 ; $i++) { 
						if($i<=$cantImg){?>
						<div class="col-xs-3 text-center"><button class="btn btn-azul btn-outline" boton-tag="2"><i class="icofont icofont-cloud-upload"></i></button></div>
						<?php
							}else{?>
						<div class="col-xs-3 text-center"><button class="btn btn-default btn-outline" boton-tag="2"><i class="icofont icofont-close-circled"></i></button></div>
						<?php }
					} ?>
						
						
						<div class="col-xs-3 text-center"><button class="btn btn-default btn-outline" boton-tag="3"><i class="icofont icofont-close-circled"></i></button></div>
						<div class="col-xs-3 text-center"><button class="btn btn-default btn-outline" boton-tag="4"><i class="icofont icofont-close-circled"></i></button></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			<button class="btn btn-warning btn-outline" data-dismiss="modal" id="btnActualizarEstado" ><i class="icofont icofont-check"></i> Actualizar</button>
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
<script type="text/javascript" src="js/impotem.js?version=1.0.4"></script>
<script src="js/bootstrap-select.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>
<script src="js/jquery.flexslider.js"></script>
<script src="js/lightbox.js"></script>
<script type="text/javascript" src="js/jquery.printPage.js?version=1.4"></script>


<!-- Menu Toggle Script -->
<script>
datosUsuario();

$(document).ready(function(){
	moment.locale('es');
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
	$.each( $('.spanFechaFormat'), function (i, dato) {
		var nueFecha=moment($(dato).text());
		$(dato).text(nueFecha.format('LLLL'));
	});

});
$(window).load(function() {
	$('.flexslider').flexslider({
	 animation: "slide"
	});
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
$('#btnDejarMensaje').click(function () {
	
	if( $('#txtDejarMensaje').val()!==''){
		$.ajax({
			url: 'php/dejarMensaje.php',
			type:'POST',
			data: {
				mensaje: $('#txtDejarMensaje').val(),
				idUser: $.JsonUsuario.idUsuario,
				idProducto: <?php echo $_GET['idProducto']; ?>
			}
		}).done(function (resp) {
			moment.locale('es');
			$('.conjuntoMensajes').append(`<div class="mensaje"><div class="texto"><p><strong>${$.JsonUsuario.usunombres}</strong> <small><i class="icofont icofont-clock-time"></i> ${moment().format('LLLL')}</small></p> <p class="textoMensaje">${ $('#txtDejarMensaje').val()}</p> </div></div>`);
			$('#txtDejarMensaje').val('');
		});
	}
});
$('#txtDejarMensaje').keyup(function (e) {
	var code = e.which;
	if(code==13 ){	e.preventDefault();
		$('#btnDejarMensaje').click();
	}
});
$('.estadoProducto').click(function () {
	$('.modal-asignarEstado').modal('show');
});
$('#btnActualizarEstado').click(function () {
	var idEstado=$('#cmbEstadoProd').find('li.selected a').attr('data-tokens');
	$.ajax({url: 'php/cambiarEstadoProducto.php', type: 'POST', data:{
		estado: idEstado,
		idProd: <?php echo $_GET['idProducto']; ?>,
		usuario: $.JsonUsuario.usunombres,
		comentario: $('#txtComentarioEstado').val()
	}}).done(function (resp) {
		location.reload();;
	});
});
$('.btnImprimirTicket').click(function () {
	var queMonto, queTitulo;
	var queUser = $(this).parent().find('.spanQuienRegistra').text();
	queMonto=$(this).parent().find('.spanCantv3').text();
	switch( $(this).attr('data-boton') ){
		case '0':
			queTitulo='    * Registro de Producto *';queMonto='0.00'; break;
		case '1':
			queTitulo='     * Venta de Producto *'; break;
		case '2':
			queTitulo='    * Adelanto de interés *'; break;
		case '3':
			queTitulo='    * Crédito finalizado *'; break;
		case '8':
			queTitulo='    * Retiro de artículo *'; break;
	}
	var queArticulo =$('.h3Apellidos').text();
	var queDueno = $('.spanDueno').text();
	if(queUser=='' || queUser==' '){
		queUser='Sistema PeruCash';
	}
	var queFecha = $(this).parent().find('.spanFechaFormat').text();
	$.ajax({url: 'http://192.168.1.131/perucash/printTicketv3.php', type: 'POST', data: {
		codigo: "<?php echo $_GET['idProducto']; ?>",
		titulo: queTitulo,
		fecha: queFecha.replace('a las ', ''),
		cliente: queDueno,
		articulo: queArticulo,
		monto: queMonto,
		usuario: queUser
	} }).done(function (resp) { 
		// body...
	});
});
$('#liAGestionrFotos').click(function() {
	$('.modal-gestionarFotos').modal('show');
});
$('#liImprimirHojaControl').click(function () {
	loadPrintDocument(this,{
		url: "hojaControl.php?idProd="+<?php if( isset ($_GET['idProducto']) ){echo $_GET['idProducto'];}else{echo 0;}?>,
		attr: "href",
		message:"Tu documento está siendo creado"
	});
});
</script>

</body>

</html>
