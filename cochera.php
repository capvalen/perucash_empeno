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
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.51" >
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
	<?php include 'menu-wrapper.php' ?>
	<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable ">
			<!-- Empieza a meter contenido 2 -->
			<h2 class="purple-text text-lighten-1">Cochera <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="contenedorBienvenida">
				<h5>Bienvenido a la cochera de PeruCash, para empezar selecciona que tipo de proceso deseas realizar</h5>
				<div class="row"><br>
					<div class="col-sm-6 text-center">
						<button class="btn btn-morado btn-outline btnMasterEntrada" id="btnRemateMaster"><i class="icofont icofont-truck-alt"></i> <br>Ingreso</button>
					</div>
					<div class="col-sm-6 text-center">
						<button class="btn btn-morado btn-outline btnMasterEntrada" id="btnCompraMaster"><i class="icofont icofont-delivery-time"></i> <br>Salida</button>
					</div>
				</div>
			</div>
			<span class="hidden queMichiEs"></span>
			<div class="contenedorDatosCliente hidden">
				<div class="row">
				<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3" style="">
					<div class="panel panel-default list-group-panel">
					<div class="panel-body">
					<h5 class="text-center purple-text text-lighten-1">Ingreso de Vehículo</h5>
						<div class="col-xs-12 col-sm-6 col-sm-offset-3 text-center">
							<label for="">Tipo de vehículo</label>
							<div  id="divSelectVehiculoListado">
							<select class="selectpicker mayuscula" id="sltVehiculosListado" title="Tipo de producto..."  data-width="100%" data-live-search="true" data-size="15">
								<?php require 'php/tipovehiculoOPT.php'; ?>
							</select>
							</div>
							<label for="" style="font-size: 24px; color: #a35bb4;padding-bottom: 10px;" id="lblPrecioIngreso"></label>
							<label for="">Placa del vehículo:</label><input type="text" id="txtPlacaEntrada" class="form-control input-block input-lg text-center soloLetras" style="text-transform:uppercase;font-size: 26px;">
							<label for="">Fecha de ingreso</label>
							<div class="sandbox-container"><input id="dtpFechaInicio" type="text" class="form-control text-center input-block input-lg text-center txtPlacas" autocomplete="off" style="font-size: 20px;"></div>

							<div class="divError text-left hidden animated fadeIn" id="divErrorIngreso"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>
						</div>
					</div>
					</div>
				</div>
				</div>

				<div class="col-sm-12 text-right">
					<button class="btn btn-default btn-lg btn-outline pull-left btnVolver"><i class="icofont icofont-rounded-double-left"></i> Volver</button>
					<button class="btn btn-azul btn-lg btn-outline btnGuardarDatos" id=""><i class="icofont icofont-diskette"></i> Guardar</button>
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
							<button class="btn btn-default btn-lg btn-outline pull-left btnVolver"><i class="icofont icofont-rounded-double-left"></i> Volver</button>
							<button class="btn btn-azul btn-lg btn-outline btnGuardarDatos" ><i class="icofont icofont-diskette"></i> Guardar</button>
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

<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<!-- Menu Toggle Script -->
<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});
$('.btnMasterEntrada').click(function () {
	$('.contenedorBienvenida').addClass('hidden');
	if( $(this).attr('id')=='btnRemateMaster'){
		$('.contenedorDatosCliente').addClass('animated fadeInRight').removeClass('hidden');
		$('.queMichiEs').attr('data-id', 'esIngreso');
	}else{
		$('.contenedorDatosProductos').addClass('animated fadeInRight').removeClass('hidden');
		$('.queMichiEs').attr('data-id', 'esSalida');
	}
});
$('.btnVolver').click(function () {
	$('.contenedorBienvenida').removeClass('hidden').addClass('animated fadeInLeft');
	$('.contenedorDatosCliente').removeClass('animated fadeInRight').addClass('hidden');
	$('.contenedorDatosProductos').removeClass('animated fadeInRight').addClass('hidden');
});

$('.btnGuardarDatos').click(function () {
	$('#divErrorIngreso').addClass('hidden');
	var idTipoCarro=$('#divSelectVehiculoListado').find('.selected a').attr('data-tokens');
	
	switch($('.queMichiEs').attr('data-id')){
		case 'esIngreso':
			if(isNaN(parseInt(idTipoCarro))){
				$('#divErrorIngreso').removeClass('hidden').find('.spanError').text('Falta seleccionar un tipo de vehículo.');
			}
			else if($('#txtPlacaEntrada').val()==''){
				$('#divErrorIngreso').removeClass('hidden').find('.spanError').text('Tiene que ingresar una placa.');
			}else if($('#txtPlacaEntrada').val().length<5){
				$('#divErrorIngreso').removeClass('hidden').find('.spanError').text('No es una placa válida.');
			}else{
				var fecha='';
				if( $('#dtpFechaInicio').val() != moment().format('DD/MM/YYYY')){
					fecha =moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')+' '+moment().format('HH:mm');
				}else{
					fecha =moment().format('YYYY-MM-DD HH:mm');
				}
				$.ajax({url: 'php/insertarPlacaMovimiento.php', type: 'POST', data: {placa: $('#txtPlacaEntrada').val(), fecha: fecha, precio: '2'  }}).done(function (resp) {
					console.log(resp);
				});
			}
		break;
	}
});
$('#divSelectVehiculoListado').on('click', '.optVehiculo', function () {
	
	var id= $('#divSelectVehiculoListado').find('.selected a').attr('data-tokens');
	$.ajax({url: 'php/solicitarPrecioTipoVehiculo.php', type: 'POST', data: {idTipo: id}}).done(function (resp) {
		if(parseInt(resp)>0){
			$('#lblPrecioIngreso').text('S/. '+parseFloat(resp).toFixed(2) + ' por día.');
			$('#txtPlacaEntrada').focus();
		}
	});
	
});
</script>
<?php } ?>
</body>
</html>