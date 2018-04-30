<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Registro: PeruCash</title>

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
		
</head>

<body>

<style>
.btnMasterEntrada{ width: 60%; height: 150px; font-size: 24px; }
.btnMasterEntrada i{font-size: 48px;}
.spanNomProductov3{font-size: 17px;}
.divMonto{color: #191c1f;}
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
			<li>
					<a href="reportes.php"><i class="icofont icofont-ui-copy"></i> Reportes</a>
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
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable ">
			<!-- Empieza a meter contenido 2 -->
			<h2 class="purple-text text-lighten-1">Registro de Clientes, Productos y Compras <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="contenedorBienvenida">
				<h4>Bienvenido a PeruCash, para empezar selecciona que tipo de producto deseas registrar</h4>
				<div class="row"><br>
					<div class="col-sm-6 text-center">
						<button class="btn btn-morado btn-outline btnMasterEntrada" id="btnRemateMaster"><i class="icofont icofont-deal"></i> <br>Remate</button>
					</div>
					<div class="col-sm-6 text-center">
						<button class="btn btn-morado btn-outline btnMasterEntrada" id="btnCompraMaster"><i class="icofont icofont-shopping-cart"></i> <br>Compra</button>
					</div>
				</div>
			</div>
			<span class="hidden queMichiEs"></span>
			<div class="contenedorDatosCliente hidden">
					<div class="row">
					<div class="col-sm-6 col-md-3"><label><span class="txtObligatorio">*</span> D.N.I.: </label><input type="text" class="form-control" id="txtDni" placeholder="Número del documento de identidad" maxlength="8" size="8" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');"></div>
					<div class="col-sm-6 col-md-3"><label><span class="txtObligatorio">*</span> Apellidos:</label><input type="text" class="form-control mayuscula" id="txtApellidos" placeholder="Apellidos completos" autocomplete="off"></div>
					<div class="col-sm-6 col-md-3"><label><span class="txtObligatorio">*</span> Nombres:</label><input type="text" class="form-control mayuscula" id="txtNombres" placeholder="Nombres completos" autocomplete="off"></div>
				</div>
				<div class="row">
					<div class="col-sm-6"><label>Dirección domiciliaria:</label><input type="text" class="form-control mayuscula" id="txtDireccion" placeholder="Dirección del cliente" autocomplete="off"></div>
					<div class="col-sm-3"><label>Correo electrónico:</label><input type="text" class="form-control" id="txtCorreo" placeholder="Correo electrónico del cliente" autocomplete="off"></div>
					
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-3"><label><span class="txtObligatorio">*</span> Celular:</label><input type="text" class="form-control" id="txtCelular" placeholder="Número de celular" autocomplete="off"></div>
					<div class="col-sm-6 col-md-3"><label><span class="txtObligatorio">*</span> Otro número de referencia:</label><input type="text" class="form-control" id="txtFono" placeholder="Número de Tlf. o Cel. extra" autocomplete="off"></div>
				</div>

				
			<!-- Fin de contenido 2 -->
			</div>
			<div class="row contenedorDatosProductos hidden">
						<div class="col-sm-12 ">
						<div class="row text-center hidden">
							<button class="btn btn-outline btn-morado" id="btnAddNewProd"><i class="icofont icofont-plus"></i> Insertar un nuevo producto</button><br>
						</div><br>
							
						<div class="row">
						<div class="col-xs-12" style="">
							<div class="panel panel-default list-group-panel">
							<div class="panel-body">
								<ul class="list-group list-group-header">
									<li class="list-group-item list-group-body">
									<div class="row"><strong>
										<div class="col-xs-8 text-left">Nombre producto</div>
										<div class="col-xs-2 text-center">Precio</div>
										<div class="col-xs-2 text-center">Porcentaje Semanal</div></strong>
									</div>
									</li>
								</ul>
								<ul class="list-group list-group-body" id="ulListadoProductos">
									<div class="row text-center">
										<div class="container-fluid" id="conjuntoElementos"></div>
										<div class="col-xs-8 colNewProduct" > <p><i class="icofont icofont-plus"></i> <span >Agregar nuevos productos </span> <br><small class="tipProducto">Pulse para agregar</small> </p>
										</div>
										<div class="col-xs-2 text-left purple-text text-lighten-1 text-center"><strong>S/. <span class="spanTotalSumasv3"></span></strong></div>
									</div>
								</ul>
							</div>
							</div>
						</div>
						</div>
					
						<div class="col-sm-12 text-right">
							<button class="btn btn-default btn-lg btn-outline pull-left" id="btnVolver"><i class="icofont icofont-rounded-double-left"></i> Volver</button>
							<button class="btn btn-azul btn-lg btn-outline" id="btnCronogramaPagosVer"><i class="icofont icofont-chart-histogram-alt"></i> Cronograma de pagos</button>
							<button class="btn btn-azul btn-lg btn-outline" id="btnGuardarDatos"><i class="icofont icofont-diskette"></i> Guardar</button>
							<button class="btn btn-azul sr-only btn-lg btn-outline" id="btnGuardarCompra"><i class="icofont icofont-diskette"></i> Guardar compra</button>
						</div>
					</div>
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
			<div class="row"> <span class="sr-only" id="iddeLi">-1</span>
				<div class="col-xs-8"><label for="">Tipo de producto <span class="txtObligatorio">*</span></label> 
					<div  id="divSelectProductoListado">
						<select class="selectpicker mayuscula" id="sltProductoListado" title="Tipo de producto..."  data-width="100%" data-live-search="true" data-size="15">
							<?php require 'php/listarProductosTipos.php'; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-4"><label for="">Cantidad <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center" id="txtQProduc" autocomplete="off"></div>
			</div>
			<div class="row ">
				<div class="col-xs-8"><label for="">Nombre, marca o características <span class="txtObligatorio">*</span></label> <input type="text" class="form-control mayuscula" id="txtNameProduc" placeholder="Sea específico con las características" autocomplete="off"></div>
				<div class="col-xs-4 hidden" id="divParaVehiculos">
					<label for="">Placa <span class="txtObligatorio">*</span></label> <input type="text" class="form-control mayuscula soloLetras" id="txtPlacaProduc" placeholder="" autocomplete="off" style="text-transform:uppercase;">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4"><label for="">Capital total S/. <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center txtNumeroDecimal" id="txtCapitalProduc" value="0.00" autocomplete="off"></div>
				<div class="col-xs-4"><label for="">Interés Semanal % <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center" id="txtInteresProduc" value="4" autocomplete="off"></div>
				<div class="col-xs-4"><label for="">Fecha de ingreso <span class="txtObligatorio">*</span></label>
					<div class="sandbox-container"><input id="dtpFechaInicio" type="text" class="form-control text-center" autocomplete="off"></div>	
				</div>
			</div>
			<div class="row container-fluid hidden">
				<p class="deep-purple-text text-lighten-1">Revisa éstos detalles antes de recibir el artículo:</p>
				<div class="datosCheck container-fluid"></div>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

<!-- Bootstrap Core JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/inicializacion.js?version=1.0.11"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/bootstrap-select.js?version=1.0.1"></script>
<script type="text/javascript" src="js/impotem.js?version=1.0.4"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>

<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
$.interesGlobal=4;
datosUsuario();

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});
$('#btnBienvenido').click(function () {
	$('.modal-Bienvenido').modal('show');
});
$('#btnAddNewProd').click(function () {
//divSelectProductoListado
	$('#iddeLi').text('-1')
	$('#txtQProduc').val(1);
	$('#txtNameProduc').val('');
	
	$('#txtCapitalProduc').val('0.00');
	if( $('.queMichiEs').attr('data-id')=='esCompra'){
		$('#txtInteresProduc').val(0).attr('readonly', true);
	}else{
		$('#txtInteresProduc').val($.interesGlobal).attr('readonly', false);
	}
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('#txtObservacionProduc').val('')
	$('.modal-nuevoProductoLista .divError').addClass('hidden');
	$('#sltProductoListado').selectpicker('val','');
	$('.modal-nuevoProductoLista').modal('show');
});
$('#btnAgregarItem').click(function () {
	var cantItem=$('#txtQProduc').val();
	var nomItem=$('#txtNameProduc').val();
	var capiItem=$('#txtCapitalProduc').val();
	var interesItem=$('#txtInteresProduc').val();
	var fechaItem=$('#dtpFechaInicio').val();
	var itemPlaca=$('#txtPlacaProduc').val();
	
	var tipoItem=$('#divSelectProductoListado').children().find('.selected').text();
	var tipoItemID=$('#divSelectProductoListado').find('.selected a').attr('data-tokens');
	var tipoItemStr=$('#sltProductoListado').selectpicker('val');
	
	var observaItem='';
	if($('#txtObservacionProduc').val()==''){
		observaItem='Sin observaciones';
	}else{
		observaItem=$('#txtObservacionProduc').val();
	}
	

	if(tipoItem==''){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Tiene que seleccionar un tipo de producto'); }
	else if(cantItem<=0){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('La cantidad No puede ser negativa o cero'); }
	else if(nomItem==''){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Ingrese un nombre de producto'); }
	else if(itemPlaca<1 && $('.queMichiEs').attr('data-id')=='esRemate' && (tipoItemID==1 || tipoItemID==11 || tipoItemID==42) ){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Ingrese la placa del vehículo'); }
	else if(capiItem<1){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('El monto prestado no puede ser negativo o cero'); }
	else if(interesItem<=0 && $('.queMichiEs').attr('data-id')=='esRemate' ){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('El interés no puede ser negativo o cero'); }
	else if(fechaItem==''){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Tiene que ingresar una fecha de inicio de préstamo'); }
	else if( $('#iddeLi').text()!='-1'){console.log('repe')}
	else{
		$('#conjuntoElementos').append(`<li class="list-group-item">
				<div class="row rowProduct">
					<div class="col-xs-8 text-left"> <p><span class="icoMedia"><i class="icofont icofont-cube"></i></span> <span class="spanCantidadv3">${cantItem}</span><span class="spanUnd"> und. </span><span class="mayuscula">${tipoItem}: <span class="spanNomProductov3">${nomItem}</span> <span class="spanPlacaVehiculo " style="text-transform:uppercase">${itemPlaca}</span></span> <br><small class="mayuscula  tipProducto"><span class="spanTipov3ID hidden">${tipoItemID}</span> <span class="spanTipoStrv3 sr-only">${tipoItemStr}</span> <span class="spanObservacionv3">${observaItem}</span></small> </p>
					<span class="sr-only spanfechaIngresov3">${fechaItem}</span>  </div>
					<div class="col-xs-2 divMonto" >S/. <span class="spanPrecioEmpv3">${capiItem}</span></div>
					<div class="col-xs-2 divMonto pull-right"><span class="spanInteresv3">${interesItem}</span>% </div>
				</div><button class="btn btn-sm btn-danger btn-outline btn-sinBorde pull-right btnBorrarFila" sytle="background-color: transparent;"><i class="icofont icofont-close"></i></button>
				</li>`);
		calcularTotalesParc();
	
	$('.modal-nuevoProductoLista').modal('hide');
	}

});
$('.colNewProduct').click(function () {
	$('.modal-nuevoProductoLista #btnActualizarItem').addClass('hidden');
	$('.modal-nuevoProductoLista #btnAgregarItem').removeClass('hidden');
	$('#txtPlacaProduc').val('').parent().addClass('hidden');
	$('#btnAddNewProd').click();
});
$('#conjuntoElementos').on('click', '.rowProduct', function () { 
	$('#iddeLi').text($(this).parent().index())
	$('#txtQProduc').val($(this).find('.spanCantidadv3').text());
	$('#sltProductoListado').selectpicker('val', $(this).find('.spanTipoStrv3').text());
	$('#txtNameProduc').val($(this).find('.spanNomProductov3').text());
	
	$('#txtCapitalProduc').val($(this).find('.spanPrecioEmpv3').text());
	$('#txtInteresProduc').val($(this).find('.spanInteresv3').text());
	$('#dtpFechaInicio').val($(this).find('.spanfechaIngresov3').text());
	$('#txtObservacionProduc').val($(this).find('.spanObservacionv3').text());


	$('.modal-nuevoProductoLista #btnActualizarItem').removeClass('hidden');
	$('.modal-nuevoProductoLista #btnAgregarItem').addClass('hidden');
	$('.modal-nuevoProductoLista .divError').addClass('hidden');
	$('.modal-nuevoProductoLista').modal('show');
	/*var nomItem=$('#txtNameProduc').val();
	var capiItem=$('#txtCapitalProduc').val();
	var interesItem=$('#txtInteresProduc').val();
	var fechaItem=$('#dtpFechaInicio').val();
	var observaItem=$('#txtObservacionProduc').val();
	var tipoItem=$('#divSelectProductoListado').children().find('.selected').text();*/
});
function calcularTotalesParc() {
	var sumaTotales=0;
	if(  $('.spanPrecioEmpv3').length==0 ){
		$('.spanTotalSumasv3').text('0.00');
	}
	$.each( $('.spanPrecioEmpv3'), function (i, elem) {//console.log($(elem).text())
		sumaTotales+=parseFloat($(elem).text());
		$('.spanTotalSumasv3').text(sumaTotales.toFixed(2));
	});
}
$('#ulListadoProductos').on('click', '.btnBorrarFila', function () {
	$(this).parent().remove();
	$('.modal-nuevoProductoLista').modal('hide');
	calcularTotalesParc();
});
$('#btnActualizarItem').click(function () {
	//var index=$(this).parent().parent().index();
	var index = $('#iddeLi').text();
	
	var cantItem=$('#txtQProduc').val();
	var nomItem=$('#txtNameProduc').val();
	var capiItem=$('#txtCapitalProduc').val();
	var interesItem=$('#txtInteresProduc').val();
	var fechaItem=$('#dtpFechaInicio').val();
	var tipoItem=$('#divSelectProductoListado').children().find('.selected').text();
	var tipoItemID=$('#divSelectProductoListado').find('.selected a').attr('data-tokens');
	var tipoItemStr=$('#sltProductoListado').selectpicker('val');
	var itemPlaca=$('#txtPlacaProduc').val();
	
	var observaItem='';
	if($('#txtObservacionProduc').val()==''){
		observaItem='Sin observaciones';
	}else{
		observaItem=$('#txtObservacionProduc').val();
	}
	

	if(tipoItem==''){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Tiene que seleccionar un tipo de producto'); }
	else if(cantItem<=0){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('La cantidad No puede ser negativa o cero'); }
	else if(nomItem==''){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Ingrese un nombre de producto'); }
	else if(capiItem<1){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('El monto prestado no puede ser negativo o cero'); }
	else if(interesItem<=0){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('El interés no puede ser negativo o cero'); }
	else if(fechaItem==''){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Tiene que ingresar una fecha de inicio de préstamo'); }
	else{ //console.log(index)
		$('#conjuntoElementos li').eq(index).remove();
		$('#conjuntoElementos').append(`<li class="list-group-item animated fadeIn">
			    <div class="row rowProduct">
			        <div class="col-xs-8 text-left"> <p><span class="icoMedia"><i class="icofont icofont-cube"></i></span> <span class="spanCantidadv3">${cantItem}</span> und. <span class="mayuscula">${tipoItem}: <span class="spanNomProductov3">${nomItem}</span> <span class="spanPlacaVehiculo mayuscula" style="text-transform:uppercase">${itemPlaca}</span></span> <br><small class="mayuscula  tipProducto"><span class="spanTipov3ID hidden">${tipoItemID}</span> <span class="spanTipoStrv3 sr-only">${tipoItemStr}</span> <span class="spanObservacionv3">${observaItem}</span></small> </p>
			        <span class="sr-only spanfechaIngresov3">${fechaItem}</span> </div>
			        <div class="col-xs-2 divMonto" >S/. <span class="spanPrecioEmpv3">${capiItem}</span></div>
			        <div class="col-xs-2 divMonto pull-right"><span class="spanInteresv3">${interesItem}</span>% </div></div>
			    <button class="btn btn-sm btn-danger btn-outline btn-sinBorde pull-right btnBorrarFila" sytle="background-color: transparent;"><i class="icofont icofont-close"></i></button>
			    </li>`);
		calcularTotalesParc();
	
	$('.modal-nuevoProductoLista').modal('hide');
	}
});
$('#someSwitchOptionWarning').change(function (e) {
	if($(this).is(':checked')){//true
		$('#lblSWQueEs').text('Compras: Todos los productos son compras sin interés.').css('color', '#5CB85C');
		$('#dtpFechaVencimiento').attr("disabled", 'true');
		$('#divSimulado').addClass('sr-only');
		$('#btnGuardarDatos').addClass('sr-only');
		$('#btnGuardarCompra').removeClass('sr-only');


	}else{//false
		$('#lblSWQueEs').text('Empeño: Todos los items son empeños con interés.').css('color', '#0082ff');
		$('#dtpFechaVencimiento').removeAttr("disabled");
		$('#divSimulado').removeClass('sr-only');
		$('#btnGuardarDatos').removeClass('sr-only');
		$('#btnGuardarCompra').addClass('sr-only');
	}
	$('#txtNombreProducto').focus();
});
$('#txtDni').focusout(function () {
if($('#txtDni').val()!=''){
	$.ajax({url: 'php/encontrarCliente.php', type:'POST', data:{ dniCli:$('#txtDni').val() }}).done(function (resp) {// console.log(resp);
		// console.log(JSON.parse(resp).length)
		if(JSON.parse(resp).length==1 ){
			$.each(JSON.parse(resp), function (i, dato) {
				$('#txtIdCliente').val(dato.idCliente); /*.attr("disabled", 'true')*/
				$('#txtApellidos').val(dato.cliApellidos); /*.attr("disabled", 'true')*/
				$('#txtNombres').val(dato.cliNombres); /*.attr("disabled", 'true')*/
				$('#txtDireccion').val(dato.cliDireccion); /*.attr("disabled", 'true')*/
				$('#txtCorreo').val(dato.cliCorreo); /*.attr("disabled", 'true')*/
				$('#txtCelular').val(dato.cliCelular); /*.attr("disabled", 'true')*/
			});
		}
		else{
			$('#txtIdCliente').val('').removeAttr("disabled");
				$('#txtApellidos').val('').removeAttr("disabled");
				$('#txtNombres').val('').removeAttr("disabled");
				$('#txtDireccion').val('').removeAttr("disabled");
				$('#txtCorreo').val('').removeAttr("disabled");
				$('#txtCelular').val('').removeAttr("disabled");
		}
	});
}
});
$('#btnGuardarDatos').click(function () {

	switch( $('.queMichiEs').attr('data-id') ){
		case 'esCompra':
			if($('.rowProduct').length==0){
				$('#spanMalo').text('La lista de items no puede estar vacía.');
				$('.modal-GuardadoError').modal('show');
			}else{
				var jsonProductos= [];
				$.each( $('.rowProduct'), function (i, elem) {
					jsonProductos.push({'cantidad': $(elem).find('.spanCantidadv3').text(), nombre: $(elem).find('.spanNomProductov3').text(),
						tipoProducto: $(elem).find('.spanTipov3ID').text(), montoDado: $(elem).find('.spanPrecioEmpv3').text(), 
						fechaIngreso: moment($(elem).find('.spanfechaIngresov3').text(), 'DD/MM/YYYY').format('YYYY-MM-DD')+' '+moment().format('H:mm'), fechaRegistro: moment($(elem).find('.spanfechaIngresov3').text(), 'DD/MM/YYYY').format('YYYY-MM-DD')+' '+moment().format('H:mm'), 
						observaciones: $(elem).find('.spanObservacionv3').text().replace('Sin observaciones', '')
					});
					//console.log(jsonProductos);
					if($('.rowProduct').length-1==i){
						$.ajax({url: 'php/insertarCompraSoloV3.php', type: 'POST',  data: {jsonProductos: jsonProductos, idUser: $.JsonUsuario.idUsuario }}).done(function (resp) {
							if($.isNumeric(resp)){
								window.location= 'productos.php?idProducto='+resp;
							}
							//console.log(resp)
						});
					}
				});
				
			}
		break;
		case 'esRemate':
			if( $('#txtDni').val().length<8){
				$('#spanMalo').text('El DNI no es correcto'); 
				$('.modal-GuardadoError').modal('show');
			}
			else if( $('#txtApellidos').val()=='' || $('#txtNombres').val()==''){
				$('#spanMalo').text('Nombres y apellidos incorrecto o vacío.');
				$('.modal-GuardadoError').modal('show');
			}
			else if( $('#txtCelular').val()=='' || $('#txtFono').val()==''){
				$('#spanMalo').text('Tiene que haber dos números de teléfono del cliente y alguna referencia (familiar, amigo, pareja).');
				$('.modal-GuardadoError').modal('show');
			}else if($('.rowProduct').length==0){
				$('#spanMalo').text('La lista de items no puede estar vacía.');
				$('.modal-GuardadoError').modal('show');
			}else{
				var jsonProducto= [];
				var jsonCliente= [];
				jsonCliente.push({dniCli: $('#txtDni').val(), apellidoCli: $('#txtApellidos').val(), nombresCli: $('#txtNombres').val(), direccionCli: $('#txtDireccion').val(), correoCli: $('#txtCorreo').val(), celularCli: $('#txtCelular').val(), fijoCli: $('#txtFono').val() });
				$.each( $('.rowProduct'), function (i, elem) {
					jsonProducto.push({cantidad: $(elem).find('.spanCantidadv3').text(), nombre: $(elem).find('.spanNomProductov3').text(),
						tipoProducto: $(elem).find('.spanTipov3ID').text(), montoDado: $(elem).find('.spanPrecioEmpv3').text(), 
						fechaIngreso: moment($(elem).find('.spanfechaIngresov3').text(), 'DD/MM/YYYY').format('YYYY-MM-DD'), fechaRegistro: moment($(elem).find('.spanfechaIngresov3').text(), 'DD/MM/YYYY').format('YYYY-MM-DD')+' '+moment().format('H:mm'), interes: $(elem).find('.spanInteresv3').text(),
						observaciones: $(elem).find('.spanObservacionv3').text().replace('Sin observaciones', ''), placa: $(elem).find('.spanPlacaVehiculo').text()
					});
					console.log(jsonProducto);
					if($('.rowProduct').length-1==i){
						$.ajax({url: 'php/insertarAlquilerv3.php', type: 'POST',  data: {jsonCliente:jsonCliente, jsonProductos: jsonProducto, idUser: $.JsonUsuario.idUsuario, total: $('.spanTotalSumasv3').text() }}).done(function (resp) { console.log(resp);
						if($.isNumeric(resp)){ 
							window.location= 'cliente.php?idCliente='+resp;
						}
						});
					}
				});
			}
		break;
	}
});
$('#txtBuscarNivelGod').keyup(function (e) {
	var valor= $(this).val();
    if(code==13){
    	e.preventDefault();
    	if($.isNumeric(valor)){
    		//Buscar código interno, DNI's, celulares o teléfonos
    		$.ajax({url:'php/listarBuscarIdProducto.php', type:'POST', data: {campo: valor}}).done(function (resp) {
    			// body...
    		});
    	}else{
    		//Buscar descripciones, apellidos
    	}
    }

});
$('.btnMasterEntrada').click(function () {
	$('.contenedorBienvenida').addClass('hidden');
	if( $(this).attr('id')=='btnRemateMaster'){
		$('.contenedorDatosCliente').addClass('animated fadeInRight').removeClass('hidden');
		$('.contenedorDatosProductos').addClass('animated fadeInRight').removeClass('hidden');
		$('.queMichiEs').attr('data-id', 'esRemate');
		$('#txtDni').focus();
	}else{
		$('.contenedorDatosProductos').addClass('animated fadeInRight').removeClass('hidden');
		$('.queMichiEs').attr('data-id', 'esCompra');
	}
});
$('#btnVolver').click(function () {
	$('.contenedorBienvenida').removeClass('hidden').addClass('animated fadeInLeft');
	$('.contenedorDatosCliente').removeClass('animated fadeInRight').addClass('hidden');
	$('.contenedorDatosProductos').removeClass('animated fadeInRight').addClass('hidden');
});
$('#divSelectProductoListado').on('click', '.optProducto ', function () {
	var tipo = $('#divSelectProductoListado').find('.selected a').attr('data-tokens');
	
	if( (tipo==1 || tipo==11 || tipo==42) &&  $('.queMichiEs').attr('data-id')=='esRemate' ){
		$('#divParaVehiculos').removeClass('hidden');
	}else{
		$('#divParaVehiculos').addClass('hidden');
	}
	$.ajax({url: 'php/listarTipoProductoRecomendaciones.php', type: 'POST', data: {idTipo: tipo }}).done(function (resp) {
		//console.log(resp)
		if(resp==''){
			$('.datosCheck').parent().addClass('hidden');
		}else{
			$('.datosCheck').html(resp).parent().addClass('animated fadeIn').removeClass('hidden');
		}
	});
});
</script>
<?php } ?>
</body>

</html>