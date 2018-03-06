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
		<link href="css/sidebarDeslizable.css?version=1.0.5" rel="stylesheet">
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link href="css/estilosElementosv3.css?version=3.0.33" rel="stylesheet">
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">
		<link href="css/bootstrap-select.min.css" rel="stylesheet">
		
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
		
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable contenedorDatosCliente">
			<!-- Empieza a meter contenido 2 -->
			<h2 class="purple-text text-lighten-1">Registro de cliente y productos nuevos <small><?php echo $_COOKIE['ckAtiende']; ?></small></h2>
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
			<div class="col-lg-12 contenedorDeslizable contenedorDatosProductos noselect">
			<!-- Empieza a meter contenido 2 -->
				<div class="container" style="padding-top: 20px;">
					<div class="material-switch pull-left " >
						<input id="someSwitchOptionWarning" type="checkbox"/>
						<label for="someSwitchOptionWarning" class="label-success" ></label>

					</div>
					<label for="someSwitchOptionWarning" id="lblSWQueEs" style="padding-top: 0;margin-left: 10px; color: #0082ff; transition: all 0.3s ease-in-out;"> Empeño: Todos los items son empeños con interés.</label>
				</div>
				<div class="row">
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
										<div class="col-xs-2 text-center">Porcentaje</div></strong>
									</div>
									</li>
								</ul>
								<ul class="list-group list-group-body" id="ulListadoProductos">
									<div class="row text-center">
										<div class="container-fluid" id="conjuntoElementos"></div>
										<div class="col-xs-8 colNewProduct" > <p><i class="icofont icofont-plus"></i> <span >Agregar nuevos productos </span> <br><small class="tipProducto">Pulse para agregar</small> </p>
										</div>
										<div class="col-xs-2 text-left purple-text text-lighten-1"><strong>S/. <span class="spanTotalSumasv3"></span></strong></div>
									</div>
								</ul>
							</div>
							</div>
						</div>
						</div>
					
						<div class="col-sm-12 text-right">
							<button class="btn btn-azul btn-lg btn-outline" id="btnCronogramaPagosVer"><i class="icofont icofont-chart-histogram-alt"></i> Cronograma de pagos</button>
							<button class="btn btn-azul btn-lg btn-outline" id="btnGuardarDatos"><i class="icofont icofont-diskette"></i> Guardar empeño</button>
							<button class="btn btn-azul sr-only btn-lg btn-outline" id="btnGuardarCompra"><i class="icofont icofont-diskette"></i> Guardar compra</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script src="js/inicializacion.js?version=1.0.3"></script>
<script type="text/javascript" src="js/impotem.js?version=1.0.4"></script>
<script src="js/bootstrap-select.js?version=1.0.1"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>

<!-- Menu Toggle Script -->
<script>
datosUsuario();
$.interesGlobal=4;
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
	$('#txtInteresProduc').val($.interesGlobal);
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
	
	var tipoItem=$('#divSelectProductoListado').children().find('.selected').text();
	var tipoItemStr=$('#divSelectProductoListado').children().find('.selected a').attr('data-tokens');
	
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
	else if( $('#iddeLi').text()!='-1'){console.log('repe')}
	else{
		$('#conjuntoElementos').append(`<li class="list-group-item">
				<div class="row rowProduct">
					<div class="col-xs-8 text-left"> <p><span class="icoMedia"><i class="icofont icofont-cube"></i></span> <span class="spanCantidadv3">${cantItem}</span><span class="spanUnd"> und. </span><span class="mayuscula spanNomProductov3">${nomItem}</span> <br><small class="mayuscula  tipProducto"><span class="spanTipov3">${tipoItem}</span> <span class="spanTipoStrv3 sr-only">${tipoItemStr}</span> - <span class="spanObservacionv3">${observaItem}</span></small> </p>
					<span class="sr-only spanfechaIngresov3">${fechaItem}</span>  </div>
					<div class="col-xs-2" >S/. <span class="spanPrecioEmpv3">${capiItem}</span></div>
					<div class="col-xs-2 pull-right"><span class="spanInteresv3">${interesItem}</span>% </div>
				</div><button class="btn btn-sm btn-danger btn-outline btn-sinBorde pull-right btnBorrarFila" sytle="background-color: transparent;"><i class="icofont icofont-close"></i></button>
				</li>`);
		calcularTotalesParc();
	
	$('.modal-nuevoProductoLista').modal('hide');
	}

});
$('.colNewProduct').click(function () {
	$('.modal-nuevoProductoLista #btnActualizarItem').addClass('hidden');
	$('.modal-nuevoProductoLista #btnAgregarItem').removeClass('hidden');
	$('#btnAddNewProd').click();
});
$('#conjuntoElementos').on('click', '.rowProduct', function () { 
	$('#iddeLi').text($(this).parent().index())
	$('#txtQProduc').val($(this).find('.spanCantidadv3').text());
	$('#sltProductoListado').selectpicker('val', $(this).find('.spanTipov3').text());
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
	var index=$(this).parent().parent().index();
	
	var cantItem=$('#txtQProduc').val();
	var nomItem=$('#txtNameProduc').val();
	var capiItem=$('#txtCapitalProduc').val();
	var interesItem=$('#txtInteresProduc').val();
	var fechaItem=$('#dtpFechaInicio').val();
	var tipoItem=$('#divSelectProductoListado').children().find('.selected').text();
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
	else if(capiItem<1){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('El monto prestado no puede ser negativo o cero'); }
	else if(interesItem<=0){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('El interés no puede ser negativo o cero'); }
	else if(fechaItem==''){ $('.modal-nuevoProductoLista .divError').removeClass('hidden').find('.spanError').text('Tiene que ingresar una fecha de inicio de préstamo'); }
	else{ console.log(index)
		$('#conjuntoElementos li').eq(index).remove();
		$('#conjuntoElementos').append(`<li class="list-group-item">
			    <div class="row rowProduct">
			        <div class="col-xs-8 text-left"> <p><span class="icoMedia"><i class="icofont icofont-cube"></i></span> <span class="spanCantidadv3">${cantItem}</span> und. <span class="mayuscula spanNomProductov3">${nomItem}</span> <br><small class="mayuscula  tipProducto"><span class="spanTipov3">${tipoItem}</span> <span class="spanTipoStrv3 sr-only">${tipoItemStr}</span> - <span class="spanObservacionv3">${observaItem}</span></small> </p>
			        <span class="sr-only spanfechaIngresov3">${fechaItem}</span> </div>
			        <div class="col-xs-2" >S/. <span class="spanPrecioEmpv3">${capiItem}</span></div>
			        <div class="col-xs-2 pull-right"><span class="spanInteresv3">${interesItem}</span>% </div></div>
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
	$.ajax({url: 'php/encontrarCliente.php', type:'POST', data:{ dniCli:$('#txtDni').val() }}).done(function (resp) {
		// console.log(JSON.parse(resp).length)
		if(JSON.parse(resp).length==1){
			$.each(JSON.parse(resp), function (i, dato) {
				$('#txtIdCliente').val(dato.idCliente); /*.attr("disabled", 'true')*/
				$('#txtApellidos').val(dato.cliApellidos); /*.attr("disabled", 'true')*/
				$('#txtNombres').val(dato.cliNombres); /*.attr("disabled", 'true')*/
				$('#txtDireccion').val(dato.cliDireccion); /*.attr("disabled", 'true')*/
				$('#txtCorreo').val(dato.cliCorreo); /*.attr("disabled", 'true')*/
				$('#txtCelular').val(dato.cliCelular); /*.attr("disabled", 'true')*/
			})
		}
		/*else{
			$('#txtIdCliente').val('').removeAttr("disabled");
				$('#txtApellidos').val('').removeAttr("disabled");
				$('#txtNombres').val('').removeAttr("disabled");
				$('#txtDireccion').val('').removeAttr("disabled");
				$('#txtCorreo').val('').removeAttr("disabled");
				$('#txtCelular').val('').removeAttr("disabled");
		}*/
		
	});
});
$('#btnGuardarDatos').click(function () {
	$("#overlay").css({display: 'block'});
	var jsonProductos= new Array();
	var jsonCliente= new Array();
	var fechaProducto= '';
	var idTipo='';
	jsonCliente.push({dniCli: $('#txtDni').val(), apellidosCli: $('#txtApellidos').val(), nombreCli: $('#txtNombres').val(), direccionCli: $('#txtDireccion').val(), correoCli: $('#txtCorreo').val(), celularCli: $('#txtCelular').val(), cotroCelularCli: $('#txtFono').val() });
	$.each( $('.rowProduct'), function (i, producto) {
	
	if( $(producto).find('.spanfechaIngresov3').text() != moment().format('DD/MM/YYYY')){ fechaProducto = moment( $(producto).find('.spanfechaIngresov3').text(), 'DD-MM-YYYY').format('YYYY-MM-DD')+' '+moment().format('HH:mm'); }else{
		 fechaProducto =moment().format('YYYY-MM-DD HH:mm');
	}
	
	jsonProductos.push({ cantProd: $(producto).find('.spanCantidadv3').text(), tipoProd: $(producto).find('.spanTipoStrv3').text(), descripProd: $(producto).find('.spanNomProductov3').text(), capitalProd: $(producto).find('.spanPrecioEmpv3').text(), intereProd: $(producto).find('.spanInteresv3').text(), fechaIngProd: fechaProducto, extraProd: $(producto).find('.spanObservacionv3').text().replace('Sin observaciones', '')});
	});

	
	$.ajax({url: 'php/insertarProductov3.php', type: 'POST', data: {jCliente:jsonCliente, jdata:jsonProductos, capital: $('.spanTotalSumasv3').text(), jusuario: $.JsonUsuario }}).done(function (resp) { console.log(resp)
		if(parseInt(resp)>0){
			location.href = 'cliente.php?idCliente='+resp
		}else{
			$('#spanMalo').text('Hubo un error interno y no se pudo guardar.');
			$('.modal-GuardadoError').modal('show');
		}
	});
	$("#overlay").css({display: 'block'});

	
	/*if( $('#txtDni').val().length<8){
		$('#spanMalo').text('El DNI no es correcto');
		$('.modal-GuardadoError').modal('show');
	}
	else if( $('#txtApellidos').val()=='' || $('#txtApellidos').val()==''){
		$('#spanMalo').text('Nombres y apellidos incorrecto o vacío.');
		$('.modal-GuardadoError').modal('show');
	}
	else if( $('#txtCelular').val()=='' || $('#txtFono').val()==''){
		$('#spanMalo').text('Ingese un teléfono fijo y un celular.');
		$('.modal-GuardadoError').modal('show');
	}else if($('.rowProduct').length==0){
		$('#spanMalo').text('La lista de items no puede estar vacía.');
		$('.modal-GuardadoError').modal('show');
	}else{
		ACA VA EL CONTENIDO DE ARRIBA
	}*/
});
$('#txtBuscarNivelGod').keyup(function (e) {
	var code = e.which; // recommended to use e.which, it's normalized across browsers
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
$('#btnCronogramaPagosVer').click(function () {
	
});
</script>

</body>

</html>
