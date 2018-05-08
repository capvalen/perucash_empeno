<?php
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
session_start();
require 'php/conkarl.php';
if(isset($_SESSION['Atiende'])){
	print_r( $_COOKIE['dato']);
	?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Administración de préstamos y empeños - PerúCash</title>
	<meta charset="UTF-8">

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="shortcut icon" href="images/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">
	<link rel="stylesheet" type="text/css" href="css/anatsunamun.css?version=2.0.18">
	<link rel="stylesheet" type="text/css" href="css/icofont.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-table.css">
</head>
<body>


<div class="container formulario col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
<div class="text-center hidden-print">
<img src="images/logo.png">
<h3>Perú Cash<small> Sistema de préstamos y empeños</small></h3></div>
<?php if ( $_SESSION['Power']== 1){ ?>
<div class="row hidden-print">
	<div class="col-xs-6 col-md-offset-3"><div><label for="">Ud. está viendo los datos de la oficina:</label> <select class="form-control" name="Achinga" id="cmbOficinasTotal">
		<?php  include "php/listarSucursalesMenos1.php"; ?>	
	</select> <button class="btn form-control btn-info hidden" id="btnProbar">Probar</button></div></div>
</div>
	<br>
<?php  } ?>

<div class="panel with-nav-tabs panel-morado hidden-print">
	<div class="panel-heading">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tabRegistro" data-toggle="tab"><i class="icofont icofont-favourite"></i> Registro</a></li>
			<li><a href="#tabBusqueda" data-toggle="tab"><i class="icofont icofont-favourite"></i> Búsqueda</a></li>
			<li><a href="#tabDetalle" data-toggle="tab"><i class="icofont icofont-favourite"></i> Artículos vencidos <span class="badge" id="spanContarVenc"></span></a></li>
			<li><a href="#tabNoFinalizados" data-toggle="tab"><i class="icofont icofont-favourite"></i> Listado de no finalizados <span class="badge" id="spanContarNoFin"></span></a></li>
			<li><a href="#tabTodosProductos" data-toggle="tab"><i class="icofont icofont-favourite"></i> Todos los artículos</a></li>
			<li><a href="#tabCajaCuadre" data-toggle="tab"><i class="icofont icofont-calculator"></i> Caja</a></li>
			<?php if ( $_SESSION['Power']== 1){ ?>
			<li><a href="#tabCrearUsuario" data-toggle="tab"><i class="icofont icofont-badge"></i> Gestión de usuarios</a></li>
			<li><a href="#tabCrearOficina" data-toggle="tab"><i class="icofont icofont-badge"></i> Gestión de oficinas</a></li>
			<li class="sr-only"><a href="#tabAprobarFinalizados" data-toggle="tab"><i class="icofont icofont-badge"></i> Aprobar finalizados</a></li>
			<li><a href="#tabConfirmarMovimientos" data-toggle="tab"><i class="icofont icofont-badge"></i> Confirmar movimientos</a></li>
			<li><a href="#tabAjustes" data-toggle="tab"><i class="icofont icofont-badge"></i> Ajustes generales</a></li>
			<?php  } ?>
			</ul>
	</div>
	<div class="panel-body">
		 <div class="tab-content container-fluid">
	<div class="tab-pane fade in active" id="tabRegistro">
	
	<div class="row rowClienteDatos">
		<div class="col-sm-6">
			<div class="panel panel-cielo">
				<div class="panel-heading"><h3 class="panel-title">Datos de cliente:</h3></div>
				<div class="panel-body">
					<div class="col-sm-12"><input type="number" class=" form-control hidden " id="txtIdCliente"></div>
					
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
				</div>
			</div>
			<div class="panel panel-cielo">
				<div class="panel-heading"><h3 class="panel-title">Opciones extras de caja</h3></div>
				<div class="panel-body">
					<button class="btn btn-lg btn-success btn-outline" id="btnIngresoDineroExtra"><i class="icofont icofont-ui-rate-add"></i> Ingreso de dinero</button>
					<button class="btn btn-lg btn-danger btn-outline" id="btnEgresoDineroExtra"><i class="icofont icofont-ui-rate-remove"></i> Salida de dinero</button>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="panel panel-cielo">
				<div class="panel-heading"><h3 class="panel-title">Datos del bien que ingresa:</h3></div>
				<div class="panel-body">
					<div class="col-sm-12">
						<div class="container">
							<div class="material-switch pull-left ">
								<input id="someSwitchOptionWarning" type="checkbox"/>
								<label for="someSwitchOptionWarning" class="label-success" ></label>

							</div>
							<label for="someSwitchOptionWarning" id="lblSWQueEs" style="padding-top: 0;margin-left: 10px; color: #606bdc; transition: all 0.3s ease-in-out;"> Artículo con intereses</label>
						</div>
						
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label>Objeto que ingresa a la tienda:</label> <input type="text" class="form-control mayuscula" id="txtNombreProducto" placeholder="Detalle el nombre y características del objeto.">
						</div>
						<div class="col-sm-6"><label>Monto entregado (S/.):</label> <input type="number" id="txtMontoEntregado" class="form-control" placeholder="Monto S/." min ="0" step="1"></div>
						<div class="col-sm-6 hidden"><label>Interés (%):</label> <input type="number" id="txtMontoInteres" class="form-control" placeholder="% de Interés" value="10" min ="0" step="1" disabled></div>
						<div class="col-sm-6"><label>Fecha de ingreso:</label> <div class="sandbox-container"><input  id="dtpFechaInicio" type="text" class="form-control"></div></div>
						<div class="col-sm-6 hidden "><label>Vencimiento:</label> <div class="sandbox-container"><input  id="dtpFechaVencimiento" type="text" class="form-control"></div></div>
						<div class="col-sm-6"><label>Observaciones o datos extras:</label> <textarea  class="form-control mayuscula" id="txtObservaciones" rows="3" placeholder="¿Alguna observación o dato extra que desees recordar luego?"></textarea></div>
						
						<div class="sr-only">
							<div class="col-sm-12 text-center" id="divSimulado"><label>Simulado:</label>
							<h4> <span ><p>Periodo: <span id="spanPeriodo"></span></p></span>
							<span ><p>Interes: <span id="spanIntGene"></span></p></span>
							<p>Intereses: <span id="spanInteres">S/. 0.00</span></p> <p>Monto total a pagar: S/.  <span id="spanMonto">0.00</span></p></h4>
							</div>
							<br>
						</div>
					
					<div class="col-sm-12">
						<p style="color: #d27f19/*1976d2*/; padding-top: 20px;"> <i class="icofont icofont-exclamation-circle" style="color: #f57f17"></i> <strong >Última actualización: <em>Interés simple y compuesto</em></strong>
						<br>
						* InteresDiario= 4%/7 = 0.04/7 <br>
* Día 1 al 7: 4% interés mínimo por defecto<br>
* Día 7 al 28: El cálculo del interés se hace hasta el día exacto= montoInicial * Suma Días * InteresDiario<br>
* Día 29 a más: Interés compuesto de: montoInicial * 28 * 16%<br>

						<!-- Para un empeño tiene 2 semanas para cancelar el <strong>9.5% acumulado</strong> lueg, se sumará el 0.683% diario.<br> -->
						<!-- De S/. 5001 a más de préstamo tiene 24 días para cancelar el 10% luego de ello, se sumará el 4% diario.<br>
						20602337147: EARA93CX
						20602337147: EARA93CX
						 -->
						</p>
					</div>
						<div class="col-sm-12 text-center">
							<button class="btn btn-primary btn-lg btn-outline" id="btnCronogramaPagosVer"><i class="icofont icofont-chart-histogram-alt"></i> Cronograma de pagos</button>
							<button class="btn btn-primary btn-lg btn-outline" id="btnGuardarDatos"><i class="icofont icofont-diskette"></i> Guardar empeño</button>
							<button class="btn btn-primary sr-only btn-lg btn-outline" id="btnGuardarCompra"><i class="icofont icofont-diskette"></i> Guardar compra</button>
						</div>
					</div>

				</div>
			</div>
			</div>
			
			<div class="row col-sm-6 col-sm-offset-3">
				<!-- <div class="pull-left"><button class="btn btn-success btn-lg btn-outline" id="btnLimpiarDatos"><i class="icofont icofont-eraser"></i> Limpiar datos</button></div> -->
				<div class="text-center">
				</div>
			</div>
		
	</div>

	
	<!-- fin de tab pane 1 -->
	</div>
	<div class="tab-pane fade  " id="tabBusqueda">
		<div class="row">
			<!-- <div class="col-sm-4 hidden">
				<label>Búsqueda por propietario del producto:</label>
				<input type="text" class="form-control" id="txtBuscarPersona" placeholder="Ingrese Nombre o Dni del cliente">
			</div> -->
			<div class="col-sm-4">
				<label>Búsqueda por propietario del producto:</label>
				<input type="text" class="form-control" id="txtBuscarPersona2" placeholder="Ingrese Nombre o Dni del cliente">
			</div>
			<div class="col-sm-4">
				<label>Búsqueda por producto:</label>
				<input type="text" class="form-control" id="txtBuscarProducto" placeholder="Ingrese el producto o código">
			</div>
			<div class="col-sm-4">
				<label>Listar movimientos por fecha:</label>
				<div class="sandbox-container input-group" ><input  id="dtpFechaListarMovimientos" type="text" class="form-control text-center" placeholder="Ingrese la fecha">
					<div class="input-group-btn">
						<button class="btn btn-morado btn-outline" id="btnFiltrarMovimientos"><i class="icofont icofont-search-alt-1"></i></button>
					</div>
					</div>
				</div>
		</div>
		<br>
		<div class="divResultadosPorPersona">
		<div class="row well">
			<p><strong><i class="icofont icofont-user-alt-2"></i> Datos del cliente</strong> <span class="hidden" id="spanIdCliente"></span></p>
			<div class="col-sm-6"><label>Apellidos y Nombres: </label> <span class="text-primary mayuscula" id="spanApellido"></span>, <span class="text-primary mayuscula" id="spanNombre"></span></div>
			<div class="col-sm-6"><label>Documento de Identidad: </label> <span class="text-primary mayuscula"  id="spanDni"></span></div>
			<div class="col-sm-6"><label>Dirección domiciliaria: </label> <span class="text-primary mayuscula"  id="spanDireccion"></span></div>
			<div class="col-sm-6"><label>Correo electrónico: </label> <span class="text-primary "  id="spanCorreo"></span></div>
			<div class="col-sm-6"><label>Celular(es): </label> <span class="text-primary mayuscula"  id="spanCelular"></span></div>
			<div class="col-sm-6"><button class="btn btn-negro btn-outline " id="btnEditarDatoCliente"><i class="icofont icofont-edit"></i> Actualizar datos</button></div>
		</div>
		<p><strong><i class="icofont icofont-cube"></i> Listado de artículos adquiridos por el cliente:</strong></p>
		
		<div class="row well hidden" id="rowWellFijo">
			<span class="hidden" id="lblIdProductosEnc"><?php echo $_GET['idprod']; ?></span>
			<div class="col-xs-12">
				<div class="alert-message alert-message-primary">
					<h3 style="display: inline-block;" class="text-primary mayuscula" id="spanProducto"> </h3> <h3 style="display: inline-block;"><span class="sr-only" id="spanVigenciaProducto"></span><small id="smallh3Producto"></small></h3>
					<h5 class="text-primary"><strong>Código del Producto: # <span id="spanCodProd"></span></strong></h5>
					<h5><strong>Valorizado:</strong> <span>S/. </span><span id="spanMontoDado">0.00</span> <span class="sr-only" id="spanSrCapital"></span> <span class="sr-only" id="spanSrInteres">0</span> <strong>Actualizado:</strong> <span id="spanPeriodo2"></span> <span id="smallPeriodo"></span> </h5>
					<h5><strong>Intereses:</strong> S/. <span id="h5SrInteres">0</span> <span id="h5DetalleInteres"></span></h5>
					<h5 class="sr-only" id="h5contCargoAdmin"><strong>Cargos administrativos:</strong> S/. <span id="h5CargoAdmin">10.00</span> </h5>
					<p><small>Obs.</small> <small class="mayuscula" id="spanObservacion">Ninguna</small></p>

				</div>
				<div class="col-xs-12 " id="contenedorPrestamos"></div>
				<!-- <div class="col-xs-12">
				<div class="container alert-message alert-message-warning">
					<h4>Pagos a cuenta:</h4>
					<table class="" data-toggle="table">
					<thead style="background-color: #ffcd87; color: #9a6b29;#676767;">
					<tr >
						<th>Fecha</th>
						<th>Pagó</th>
						<th>Descripción</th>
						<th>Responsable</th>
					</tr>
					</thead>
					<tbody  style="background-color: white;">
					<tr>
						<td>
						   Cuenta 1
						</td>
						<td>122</td>
						<td>An extended Bootstrap table with radio, checkbox</td>
						<td>Demo</td>
					</tr>
					<tr>
						<td>
							Cuenta 2
						</td>
						<td>11</td>
						<td>Show/hide password plugin for twitter bootstrap.
						<td>Demo</td>
						</td>
					</tr>
					<tr>
						<td>
							Cuenta 3
						</td>
						<td>4</td>
						<td>Demo</td>
						<td>my blog</td>
					</tr>
					</tbody>
				</table>
				</div>
				</div> -->

			</div>


			<!-- <div class="col-sm-6"><label><i class="icofont icofont-cube"></i> Producto: </label> </div>
			<div class="col-sm-6"><label><i class="icofont icofont-ui-tag"></i> Interés: </label> <span class="text-success"><span id="spanPorcent"></span>%</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de inicio: </label> <span class="text-success" id="spanFechaInicio"> <span class="sr-only" id="spanFechaInicioNum"></span></span></div>
			<div class="col-sm-6 hidden"><label><i class="icofont icofont-tasks-alt"></i> Fecha de límite de pago: </label> <span class="text-success" id="spanFechaFin"></span> <span class="sr-only" id="spanFechaFinNum"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Registrado: </label> </small></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Monto entregado: </label> <span class="text-success">S/. </span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Intereses generados: </label> <span class="text-success">S/. <span id="spanIntGenerado">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Total al día de hoy: </label> <span class="text-success">S/. <span id="spanPagar">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Observaciones: </label> <em></em></div>
			<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Adelantos: </label> <span class="text-success">S/. <span id="spanAdelanto">0.00</span></span></div> -->
			<!-- <div class="col-sm-6 col-sm-offset-6"><label><i class="icofont icofont-chat"></i> Deuda del cliente: </label> <span class="text-success">S/. <span id="spanDeudaFinal">0.00</span></span></div> -->

			
			

			<div class="col-xs-12 sr-only" id="contenedorVigente" >
				<div class="alert-message alert-message-success">
					<h4>Artículo vigente: <small>El artículo se encuentra dentro del plazo, pasa a posible remate en: <strong id="contDiasPosRem"></strong> días.</small></h4>
				</div>
			</div>
			<div class="col-xs-12 sr-only" id="contenedorExpiro" >
				<div class="alert-message alert-message-danger">
					<h4>Artículo expirado: <small>El artículo se encuentra fuera de los plazos razonales, se sugiere <strong>rematar</strong> </small> <small id="spanValordeMeses"></small></h4>
				</div>
			</div>
			<div class="col-xs-12 sr-only" id="contenedorAdelantos" >
				<div class="alert-message alert-message-warning">
					<h4>Actividad con el producto</h4>
					<p>Por: <strong><span class="mayuscula" id="QuienOcurre"></span></strong>, el día: <strong><span id="FechaOcurrencia"></span></strong>, <strong><span id="casoOcurrencia"></span></strong> por el monto: S/. <span id="montoOcurrencia"></span> estado: <strong><span id="QuienApruebaMov"></span><span id="estadoAprobacionMov"></span></strong>.</p>
				</div>
			</div>
			<div class="col-xs-12 sr-only" id="contenedorFinalizados" >
				<div class="alert-message alert-message-morado">
						<h4>Artículo finalizado</h4>
						<p>Por: <strong><span class="mayuscula" id="QuienFinalizoFin"></span></strong>, el día: <strong><span id="FechaFinalizo"></span></strong> Monto: S/. <span id="finalizaMonto"></span> por: <strong><span id="QuienApruebaFin"></span>, <span id="estadoAprobacionFin"></span></strong>.</p>
				</div>
			</div>
			
			<div class="col-sm-8 col-sm-offset-2 hidden" id="divBotonesFijos">
				<?php if( $_SESSION['Power']<>3 ){ ?>
				<button class="btn btn-morado btn-outline" id="btn-imprimirTicketFijo"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
				<button class="btn btn-morado btn-outline" id="btn-imprimirHojaControl"><i class="icofont icofont-copy-alt"></i> Hoja de control</button>
				<!-- <button class="btn btn-morado btn-outline" id="btn-imprimirImpresoraFijo"><i class="icofont icofont-print"></i> Voucher en impresora</button> -->
				<button class="btn btn-indigo btn-outline sr-only" id="btn-FinalizarImpuestoFijo"><i class="icofont icofont-pie-chart"></i> Cancelar interés</button>
				<button class="btn btn-indigo btn-outline sr-only" id="btn-AdelantoPrestamoFijo"><i class="icofont icofont-rocket"></i> Adelantar pago</button>
				<button class="btn btn-success btn-outline sr-only" id="btn-DesembolsoFijo"><i class="icofont icofont-rocket"></i> Desembolso</button>
				<button class="btn btn-indigo btn-outline sr-only" id="btn-PagoACuentaFijo"><i class="icofont icofont-pie-chart"></i> Ingreso de dinero</button>
				<button class="btn btn-danger btn-outline sr-only" id="btn-FinalizarPrestamoFijo"><i class="icofont icofont-rocket"></i> Finalizar préstamo</button>
				<button class="btn btn-danger btn-outline sr-only" id="btn-RetirarPrestamoFijo"><i class="icofont icofont-rocket"></i> Retirar artículo</button>
				<button class="btn btn-morado btn-outline sr-only" id="btn-imprimirRetiro"><i class="icofont icofont-animal-cat-alt-4"></i> Imprimir ticket retirado</button>
				<?php } ?>
				<?php 
				if ($_SESSION['Power']==1){
					?> <button class="btn btn-danger btn-outline" id="btn-EliminarDB"><i class="icofont icofont-snowy-thunder"></i> Eliminar de la BD</button>
					<?php } ?>
			</div>
		</div>
		<div id="rowWellCambiante">
		</div>
	</div>
	
	</div>

	<div class="tab-pane fade" id="tabDetalle">
	<button class="btn btn-negro btn-outline btn-lg btnSoloPrint"><i class="icofont icofont-print"></i> Imprimir éste reporte</button>
	<p>Se encontraron <strong id="strFaltan"> </strong> Artículos vencidos de más de 30 días.</p>
		<div class="row hidden-xs"><strong>
			<div class="col-sm-4">Nombre del artículo</div>
			<div class="col-sm-1">Monto S/.</div>
			<div class="col-sm-1">Más interés</div>
			<div class="col-sm-3">Dueño</div>
			<div class="col-sm-2">Fecha último pago</div></strong>
		</div>
		<div id="divProdAVencerse">
			<p>Sin elementos que mostrar</p>
		</div>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
			<div class="panel panel-default" id="cuadroPanelVencidos">
			  <div class="panel-heading"><strong>Resumen de cuadro finalizados: </strong> <span class="spandia"></span></div>
			  <div class="panel-body">
				<p><strong>Total de productos: </strong> <span class="spanCuadrProd"> 0 productos</span></p>
				<p><strong>Suma total de montos: </strong> <span class="spanCuadrSumaMont">S/. 0.00</span></p>
				<p><strong>Suma total con intereses: </strong> <span class="spanCuadrSumaInt">S/. 0.00</span></p>
			  </div>
			</div>
		</div>
		</div>

	<!-- fin de tab pane 1 -->
	</div>
	<div class="tab-pane fade" id="tabNoFinalizados">
	<button class="btn btn-negro btn-outline btn-lg btnSoloPrint"><i class="icofont icofont-print"></i> Imprimir éste reporte</button>
	<button class="btn btn-negro btn-outline sr-only" id="btnImprimirNoFinalizados"><i class="icofont icofont-print"></i> Imprimir éste reporte</button>
		<div class="row"><strong>
			<div class="col-sm-3">Apellidos y Nombres</div>
			<div class="col-sm-2">Registro</div>
			<div class="col-sm-2 hidden">Caduca</div>
			<div class="col-sm-1">Entregado</div>
			<div class="col-sm-1">Más interés</div>
			<div class="col-sm-4">Garantía</div></strong>

		</div>

		<div  id="divNoFinalizados">
			
		</div>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
			<div class="panel panel-default" id="cuadroPanelNoFinalizados">
			  <div class="panel-heading"><strong>Resumen de cuadro finalizados: </strong> <span class="spandia"></span></div>
			  <div class="panel-body">
				<p><strong>Total de productos: </strong> <span class="spanCuadrProd"> 0 productos</span></p>
				<p><strong>Suma total de montos: </strong> <span class="spanCuadrSumaMont">S/. 0.00</span></p>
				<p><strong>Suma total con intereses: </strong> <span class="spanCuadrSumaInt">S/. 0.00</span></p>
			  </div>
			</div>
		</div>
		</div>
	<!-- fin de tab pane 3 -->
	</div>
	<div class="tab-pane fade" id="tabTodosProductos">
		<button class="btn btn-morado btn-outline btn-lg" id="btnListaTodosProductosImprimirv3"><i class="icofont icofont-database"></i> Ver todos los productos en una lista</button><br>
		A continuación se muestran todos los items ordenados alfabéticamente en bloques de 30 items. <br>
		<nav aria-label="Page navigation">
					<ul class="pagination">
						<li>
							<a href="#" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
							</a>
						</li>
		<?php 
		$cantTotal= include 'php/returnConteoDatos.php';
			$bloques=intval($cantTotal/30);
			
			$ultimo= $cantTotal%30;
			

			for ($i=1; $i <= $bloques ; $i++) { 
				?>
						<li <?php if($i==1){ echo 'class="active"';} ?>><a href="#" aria-label="<?php echo $i; ?>"><?php echo $i; ?></a></li>
				<?php
			}
			if ($ultimo<30 && $ultimo==0){}
			else{ ?>
						<li><a href="#" aria-label="<?php echo $i; ?>"><?php echo $bloques+1; ?></a></li>
				<?php }
		 ?>

		 <li>
							<a href="#" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
							</a>
						</li>
					</ul>
				</nav>
			<div class="row " id="divTotalProductos"><div class="fa-spin text-left"><i class="icofont icofont-spinner"></i> </div></div>
	</div>

	<div class="tab-pane fade" id="tabCajaCuadre">
		<!-- inicio de tab pane caja -->
		
			<h2>Cuadre de Caja <small class="mayuscula" id="h1Usuario"></small></h2>
		<div class="row">
			<div class="col-xs-6">
				<h4 class="text-center" style="color: #c70000;">Egresos de caja</h4>
				<table class="" data-toggle="table" style=" font-size: 13px; background: #ff5d5d; color: white;">
					<thead>
					<tr >
						<th>Hora</th>
						<th>Monto S/.</th>
						<th>Detalle</th>
						<th>Observaciones</th>
					</tr>
					</thead>
					<tbody  style="background-color: white; color: #c70000;">
					<tr>
						<td>
						   Cuenta 1
						</td>
						<td>122</td>
						<td>An extended Bootstrap table with radio, checkbox</td>
						<td>Demo</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="col-xs-6">
				<h4 class="text-center" style=" color: #006bc7;">Entradas a caja</h4>
				<table class="" data-toggle="table" style="font-size: 13px; background: #5d96ff; color: white;">
					<thead>
					<tr >
						<th>Hora</th>
						<th>Monto S/.</th>
						<th>Detalle</th>
						<th>Observaciones</th>
					</tr>
					</thead>
					<tbody  style="background-color: white; color: #006bc7;">
					<tr>
						<td>
						   Cuenta 1
						</td>
						<td>122</td>
						<td>An extended Bootstrap table with radio, checkbox</td>
						<td>Demo</td>
					</tr>
					<tr>
						<td>
							Cuenta 2
						</td>
						<td>11</td>
						<td>Show/hide password plugin for twitter bootstrap.
						<td>Demo</td>
						</td>
					</tr>

					</tbody>
				</table>
			</div>
		
		</div>
		<!-- fin de tab pane caja -->
	</div>

	<?php if ( $_SESSION['Power']== 1){ ?>
	<div class="tab-pane fade" id="tabCrearUsuario">
		<div class="row well">
		<p><strong>Creación de usuarios</strong></p>
			<div class="col-xs-12 col-sm-6"><strong>Apellidos</strong> <input type="text" class="form-control mayuscula" id="txtApellUser" placeholder="Apellidos del usuario"></div>
			<div class="col-xs-12 col-sm-6"><strong>Nombres</strong> <input type="text" class="form-control mayuscula" id="txtNombrUser" placeholder="Nombres del usuario"></div>
			<div class="col-xs-12 col-sm-6"><strong>Nick</strong> <input type="text" class="form-control" id="txtNickUser" placeholder="Nick del usuario para ingresar al sistema" disabled></div>
			<div class="col-xs-12 col-sm-6"><strong>Contraseña</strong> <input type="password" class="form-control" id="txtPassUser" placeholder="Contraseña"></div>
			<div class="col-xs-12 col-sm-6"><strong>Repita la contraseña</strong> <input type="password" class="form-control" id="txtPassUser2" placeholder="Confirmar la contraseña"></div>
			<div class="col-xs-12 col-sm-6"><strong>Nivel</strong> 
				<select id="cmbNivelSel" class="form-control">
					<option value="0">Seleccione uno</option>
					<?php include 'php/listarPoderes.php'; ?>
				</select>
			</div>
			
			<div class="col-xs-12 col-sm-6"><strong>Oficina</strong>
				<select  id="cmbSucurSel" class="form-control">
					<option value="0">Seleccione uno</option>
					<?php include 'php/listarSucursales.php'; ?>
				</select>
			</div>
			<div class="col-xs-12 text-center"> <button id="btnGuardarUsuario" style="margin-top: 5px;" class="btn btn-outline btn-morado"><i class="icofont icofont-user-alt-7"></i> Generar nuevo ususario</button></div>
			
		</div>
		<div class="row well" id="divListadoUser"><h3>Listado de usuarios activos:</h3>
		<!-- <p><button class="btn btn-negro btn-xs btn-outline"><i class="icofont icofont-key"></i></button> <button class="btn btn-danger btn-xs btn-outline"><i class="icofont icofont-minus-square"></i></button> 1. Carlos Alex Pariona Valencia asignado a «Las Retamas» sucursal</p>
		<p><button class="btn btn-negro btn-xs btn-outline"><i class="icofont icofont-key"></i></button> <button class="btn btn-danger btn-xs btn-outline"><i class="icofont icofont-minus-square"></i></button> 2. Carlos Alex Pariona Valencia</p>
		<p><button class="btn btn-negro btn-xs btn-outline"><i class="icofont icofont-key"></i></button> <button class="btn btn-danger btn-xs btn-outline"><i class="icofont icofont-minus-square"></i></button> 3. Carlos Alex Pariona Valencia</p> -->
		</div>
	<!-- fin de tab pane usuarios -->
	</div>

	<div class="tab-pane fade" id="tabCrearOficina">
		<div class="row well">
			<p><strong>Creación de oficinas</strong></p>
			<div class="col-xs-12 col-sm-6">
				<strong>Nombre de nueva oficina</strong>
				<input type="text" class="form-control mayuscula" id="txtNomOfic" placeholder="Nombra de la nueva oficina">
			</div>
			<div class="col-xs-12 col-sm-6">
				<strong>Dirección de la nueva oficina</strong>
				<input type="text" class="form-control mayuscula" id="txtDirecOfic" placeholder="Dirección de la nueva oficina">
			</div>
			<div class="col-xs-12 text-center"><br>
				<button class="btn btn-control btn-negro btn-outline" id="btnGuardarOffice"><i class="icofont icofont-industries-alt-5"></i> Guardar oficina</button>
			</div>

		</div>
		<div class="row well" id="divListadoOffi"><h3>Listado de oficinas activas:</h3>

		</div>
	</div>

		<div class="tab-pane fade" id="tabAprobarFinalizados">
		<div class="row well">
			<p><strong>Listado de artículos por aprobar</strong></p>
			<div class="col-xs-12 ">
				<div >
					<div class="row"><strong>
					<div class="col-sm-5">Producto</div>
					<div class="col-sm-2">Monto Cancelado</div>
					<div class="col-sm-2">Responsable</div>
					<div class="col-sm-2">Fecha</div>
					</strong></div>
				</div>
				<div id="divListaPorFinalizar">
				</div>
				</div>
			</div>
		</div>


		<div class="tab-pane fade" id="tabConfirmarMovimientos">
		<div class="row ">
			<p>Confirmar movimientos diarios, apoyo a controlar las actividades del personal. Ordenado de más actual a más antigüo.</p>
			<div class="col-xs-12 ">
				<div >
					<div class="row"><strong>
					<div class="col-sm-3">Producto</div>
					<div class="col-sm-1">Monto</div>
					<div class="col-sm-1">Responsable</div>
					<div class="col-sm-2">Fecha</div>
					<div class="col-sm-2">Movimiento</div>
					<div class="col-sm-2">Acciones</div>
					</strong></div>
				</div>
				<div id="divListaPorConfirmar">
					<p>No hay artículos que mostrar</p>
					<!-- <div class="row">
					<div class="col-sm-3">Producto ABC</div>
					<div class="col-sm-1">S/. 25.50</div>
					<div class="col-sm-1">Carlos Alex</div>
					<div class="col-sm-2">Lunes, 21 Junio 2017</div>
					<div class="col-sm-2">El cliente adelantó un monto a su préstamo</div>
					<div class="col-sm-2">
						<button class="btn btn-primary btn-outline sr-only" style="padding: 6px 6px;"><i class="icofont icofont-check"></i> Aceptar</button> 
						<button class="btn btn-morado btn-outline " style="padding: 6px 6px;"><i class="icofont icofont-exchange"></i> Retirar</button>
						<button class="btn btn-danger btn-outline " style="padding: 6px 6px;"><i class="icofont icofont-sale-discount"></i> Rematar</button>
						
					</div>
					</div> -->
				</div>
			</div>
		</div>
		</div>

	</div>
	
	

	</div>

	<?php } ?>

	</div>
	<br><div class="pull-right hidden-print"><p style="margin-top: 10px;">Sessión actual: <em class="mayuscula"><strong><?php echo $_SESSION['Sucursal'].' - '; ?><span id="spanUsuario"><?php echo $_SESSION['Atiende'] ?></span> </strong></em>
	<button class="btn btn-sm btn-morado btn-outline" id="btnCambiarMiContras"><i class="icofont icofont-key"></i> Cambiar mi contraseña</button> 
	<button class="btn btn-sm btn-morado btn-outline" id="btnCerrarSesion"><i class="icofont icofont-exit"></i> Cerrar sesión</button> </p></div>
	</div>
</div>
<div class="visible-print-block" id="divSoloParaPrint"></div>

<!-- Modal para editar los datos de los usuarios  -->
	<div class="modal fade modal-editarDatosUsuarios" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header-indigo">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Actualizar datos del cliente</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
				<div class="alert alert-danger hidden">
					<strong>Ups!</strong> <span class="spanError"></span>
				</div>
				<div class="row">
					<label for="">Apellidos</label> <span class="sr-only" id="userModalId"></span>
					<input type="text" class="form-control mayuscula" id="txtUsapellido">
				</div>
				<div class="row">
					<label for="">Nombres:</label>
					<input type="text" class="form-control mayuscula" id="txtUsnombre">
				</div>
				<div class="row">
					<label for="">Nick:</label>
					<input type="text" class="form-control" id="txtUsnick">
				</div>
				<div class="row">
					<label for="">Contraseña:</label><span> *Almacenada, no se mostrará*</span><br>
					<button class="btn btn-negro btn-outline btn-md" id="btnAsignarNuevaPassUsr"><i class="icofont icofont-pixels"></i> Asignar nueva contraseña</button>
				</div>
				<div class="row container-fluid hidden" id="cambContraseña">
					<div class="row">
					<label class="text-primary" for="">Contraseña:</label>
					<input type="password" class="form-control" id="txtUsPss1">
						<label class="text-primary" for="">Confirme su contraseña:</label>
						<input type="password" class="form-control" id="txtUsPss2">
					</div>
				</div>
				<div class="row">
					<label for="">Nivel:</label>
					<select class="form-control" id="cmbLvlUser">
						<?php include 'php/listarPoderes.php'; ?>
					</select>
				</div>
				<div class="row">
					<label for="">Oficina:</label>
					<select class="form-control" id="cmbOficinasUser">
						<?php  include "php/listarSucursales.php"; ?>
					</select>
				</div>
				</div>
				
				
			</div>
				
			<div class="modal-footer">
				<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> Cancelar actualización</button>
				<button class="btn btn-morita btn-outline" id="btnActualizarDataUser"><i class="icofont icofont-social-meetme"></i> Actualizar usuario</button></div>
		</div>
		</div>
	</div>


<!-- Modal para editar los datos de los clientes  -->
	<div class="modal fade modal-editarDatosCliente" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header-wysteria">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Actualizar datos del cliente</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
				<div class="row">
					<label for="">D.N.I:</label>
					<input type="number" class="form-control" id="txtddni">
				</div>
				<div class="row">
					<label for="">Apellidos</label>
					<input type="text" class="form-control mayuscula" id="txtaapellido">
				</div>
				<div class="row">
					<label for="">Nombres:</label>
					<input type="text" class="form-control mayuscula" id="txtnnombre">
				</div>
				<div class="row">
					<label for="">Dirección:</label>
					<input type="text" class="form-control mayuscula" id="txtddireccion">
				</div>
				<div class="row">
					<label for="">Correo electrónico:</label>
					<input type="text" class="form-control" id="txteemail">
				</div>
				<div class="row">
					<label for="">Celular(es):</label>
					<input type="text" class="form-control" id="txtccelular">
				</div>
				</div>
				
				
			</div>
				
			<div class="modal-footer">
				<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> Cancelar actualización</button>
				<button class="btn btn-morita btn-outline" id="btnActualizarDataCliente"><i class="icofont icofont-social-meetme"></i> Actualizar</button></div>
		</div>
		</div>
	</div>

<!-- Modal para mostrar los clientes coincidentes -->
	<div class="modal fade modal-mostrarResultadosCliente" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header-indigo">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Resultados de la búsqueda</h4>
			</div>
			<div class="modal-body">
				<div class="row container-fluid"> <strong>
					<div class="col-xs-6">Nombre de Cliente</div>
					<div class="col-xs-3">Dni</div>
					<div class="col-xs-3">Celular</div></strong>
					
				</div>
				<div class="row container-fluid" id="rowUsuarioEncontrado">
					
				</div>
				
			</div>
			<div class="modal-footer"> <button class="btn btn-primary btn-outline" data-dismiss="modal"></i><i class="icofont icofont-alarm"></i> Aceptar</button></div>
		</div>
		</div>
	</div>

<!-- Modal para mostrar los clientes coincidentes -->
	<div class="modal fade modal-mostrarResultadosProducto" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header-indigo">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Resultados de la búsqueda</h4>
			</div>
			<div class="modal-body">
				<div class="row container-fluid"> <strong>
					<div class="col-xs-4">Producto</div>
					<div class="col-xs-3">Nombre de Cliente</div>
					<div class="col-xs-2">Fecha ocurrencia</div>
					<div class="col-xs-2">Monto inicial</div></strong>
				</div>
				<div class="" id="rowProductoEncontrado">
					
				</div>
				
			</div>
			<div class="modal-footer"> <button class="btn btn-primary btn-outline" data-dismiss="modal"></i><i class="icofont icofont-alarm"></i> Aceptar</button></div>
		</div>
		</div>
	</div>

<!-- Modal para Ingresar dinero -->
	<div class="modal fade modal-IngresarDineroExtra" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Ingreso de dinero a caja</h4>
			</div>
			<div class="modal-body container-fluid text-center">
				<p>¿Qué monto ingresa a caja?</p>
				<input type="number" class="form-control text-center" id="txtModalMontoIngresoCaja" step="1" min="0" value='0.00'>
				<p>¿Motivo por que ingresa?</p>
				<input type="text" class="form-control text-center mayuscula" id="txtModalRazonIngresoCaja" >
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> Cerrar</button>
			<button class="btn btn-success btn-outline" id="btnIngresarDineroSuma" ><i class="icofont icofont-check"></i> Agregar a caja</button></div>
		</div>
		</div>
	</div>
<!-- Modal para Retirar dinero -->
	<div class="modal fade modal-EgresarDineroExtra" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-warning">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Retiro de dinero de caja</h4>
			</div>
			<div class="modal-body container-fluid text-center">
				<p>¿Qué monto se retira de caja?</p>
				<input type="number" class="form-control text-center" id="txtModalMontoEgresoCaja" step="1" min="0" value='0.00'>
				<p>¿Motivo por que egresa?</p>
				<input type="text" class="form-control text-center mayuscula" id="txtModalRazonEgresoCaja" >
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> Cerrar</button>
			<button class="btn btn-warning btn-outline" id="btnEgresarDineroSuma" ><i class="icofont icofont-check"></i> Retirar de caja</button></div>
		</div>
		</div>
	</div>



<!-- Modal para indicar que se guardó con éxito -->
	<div class="modal fade modal-adelantarPago" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Adelantar pago</h4>
			</div>
			<div class="modal-body container-fluid text-center">
				<p>Ingrese una cantidad para agregarlo como adelanto. <span class="sr-only" id="spanIdProdxAdelanto"></span><span class="sr-only" id="idDivDatos"></span><span class="sr-only" id="tipoFijooMovil"></span></p>
				<p class="text-danger hidden" id="pAdelantoMal">No puede ingresar un valor negativo o vacío.</p>
				<div class="col-sm-4 col-sm-offset-4"><input type="number" class="form-control text-center" id="txtAdelantPagoMonto" value='0.00' step=1 min=0></div>
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" id="btnCancelarIngreso" data-dismiss="modal" ><i class="icofont icofont-close"></i> Cancelar ingreso</button>
			<button class="btn btn-primary btn-outline" id="btnIngresarPago" ><i class="icofont icofont-check"></i> Ingresar pago e imprimir ticket</button></div>
		</div>
		</div>
	</div>
	


 <!-- Modal para indicar que esta seguro de retirando -->
	<div class="modal fade modal-EstaSeguroRetirarMovimiento" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-wysteria">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Retirar movimiento</h4>
			</div>
			<div class="modal-body"> <span class="sr-only idSelecRow"></span>
				Estas intentando <strong>retirar</strong> el movimiento generado del producto «<strong class="strProd mayuscula"></strong>» por «<strong class="strUser"></strong>» ¿Es correcto?
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> No, cancelar</button>
			<button class="btn btn-morita btn-outline " id="btnRetirarMovimientoModal"><i class="icofont icofont-check"></i> Sí, seguro</button></div>
		</div>
		</div>
	</div>



 <!-- Modal para indicar que esta seguro de rematar -->
	<div class="modal fade modal-EstaSeguroRematarMovimiento" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-danger">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Rematar producto</h4>
			</div>
			<div class="modal-body"> <span class="sr-only idSelecRow"></span>
				Estas intentando <strong>rematar</strong> movimiento generado del producto «<strong class="strProd mayuscula"></strong>» por «<strong class="strUser"></strong>» ¿Es correcto?
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> No, cancelar</button>
			<button class="btn btn-morita btn-outline " id="btnRematarMovimientoModal"><i class="icofont icofont-check"></i> Sí, seguro</button></div>
		</div>
		</div>
	</div>

 <!-- Modal para indicar que esta seguro de aceptar -->
	<div class="modal fade modal-EstaSeguroAceptarMovimiento" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Aceptar movimiento</h4>
			</div>
			<div class="modal-body"> <span class="sr-only idSelecRow"></span>
				Estas intentando <strong>aceptar</strong> movimiento generado del producto «<strong class="strProd mayuscula"></strong>» por «<strong class="strUser"></strong>» ¿Es correcto?
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> No, cancelar</button>
			<button class="btn btn-morita btn-outline " id="btnAceptarMovimientoModal"><i class="icofont icofont-check"></i> Sí, seguro</button></div>
		</div>
		</div>
	</div>

 <!-- Modal para indicar que se guardó con éxito -->
	<div class="modal fade modal-EstaSeguroFinalizar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-wysteria">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Confirmación</h4>
			</div>
			<div class="modal-body">
				Estas aceptando el producto como finalizado ¿Es correcto?
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> No, cancelar</button>
			<button class="btn btn-morita btn-outline " id="btnSeguroAprobar"><i class="icofont icofont-check"></i> Sí, seguro</button></div>
		</div>
		</div>
	</div>


 <!-- Modal para indicar que se guardó con éxito -->
	<div class="modal fade modal-InteresSeguro" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-wysteria">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Confirmación de impuestos</h4>
			</div>
			<div class="modal-body">
				¿Está seguro que desea <strong>cancelar los intereses del día de hoy</strong> por el monto de S/. <span id="spanInteresSeguro"></span>?<span class="sr-only" id="srNombreSeg"></span><span class="sr-only" id="srProductoSeg"></span><span class="sr-only" id="idInteresSeguro"></span>
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> No quiero</button>
			<button class="btn btn-morita btn-outline " id="btnInteresFinalizar"><i class="icofont icofont-check"></i> Sí, seguro</button></div>
		</div>
		</div>
	</div>

 <!-- Modal para cambiar contraseña -->
	<div class="modal fade modal-cambiarMiContraseña" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-wysteria">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Cambiar mi contraseña</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<p>Ten en cuenta que debes ingresar todos los datos correctos para que puedas usar tu nueva contraseña.</p>
					
					<div class="row">
						<label for="">Tu contraseña actual</label>
						<input type="password" class="form-control" id="txtMiPassAnt">
					</div>
					<div class="row">
						<label for="">Tu nueva contraseña</label>
						<input type="password" class="form-control" id="txtMiPassNuevo">
					</div>
					<div class="row">
						<label for="">Repite tu nueva contraseña</label>
						<input type="password" class="form-control" id="txtMiPassNuevo2">
					</div>
					<p class="text-danger hidden"></p>
				</div>
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> Cancelar</button>
			<button class="btn btn-morita btn-outline " id="btnCambiarSeguroPass"><i class="icofont icofont-check"></i> Asignar</button></div>
		</div>
		</div>
	</div>

 <!-- Modal para indicar que esta seguro -->
	<div class="modal fade modal-PagoACuenta" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-wysteria">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Amortizar pago</h4>
			</div>
			<div class="modal-body">
				¿Cuánto está amortizando el cliente? Pendientes por el producto <strong>«<span class="mayuscula" id="spanProdDeuda">camara digital nikon coopix p530</span>»</strong>:
				<ul>
					<li>Capital: S/. <span id="spanAmortizarDeuda">300.00</span></li>
					<li>Interés: S/. <span id="spanInteresAmortizaDeuda"></span></li>
					<li>Gastos adm.: S/. <span id="spanDeudaGastos"></span></li>
				</ul>
				<span class="sr-only" id="sr-idProductov3"></span><span class="sr-only" id="sr-MontInicialv3"></span><span class="sr-only" id="sr-montInteresv3"></span>
				<input type="number" class="form-control text-center" id="txtPagarACuenta" min="0" value="0" step="1">
				<p class="text-danger hidden"></p>
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> Cancelar</button>
			<button class="btn btn-morita btn-outline " id="btnPagarACuenta"><i class="icofont icofont-check"></i> Ingresar pago</button></div>
		</div>
		</div>
	</div>

 <!-- Modal para indicar que esta seguro -->
	<div class="modal fade modal-EstaSeguro" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-wysteria">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Confirmación</h4>
			</div>
			<div class="modal-body">
				¿Está seguro que desea realizar ésta operación con el monto S/. <span id="montoSeguro"></span>?<span class="sr-only" id="idEstaseguro"></span>
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-close"></i> No quiero</button>
			<button class="btn btn-morita btn-outline " id="btnSeguroFinalizar"><i class="icofont icofont-check"></i> Sí, seguro</button></div>
		</div>
		</div>
	</div>

 <!-- Modal para indicar que se guardó con éxito -->
	<div class="modal fade modal-ventaGuardada" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Datos guardados</h4>
			</div>
			<div class="modal-body">
				<strong><i class="icofont icofont-social-smugmug"></i> Enhorabuena,</strong> sus datos fueron guardados correctamente.
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-primary btn-outline btnAceptarGuardado" data-dismiss="modal" ><i class="icofont icofont-check"></i> Ok, aceptar</button>
			<button class="btn btn-primary btn-outline btnCompraGuardado sr-only" data-dismiss="modal" ><i class="icofont icofont-check"></i> Ok, aceptar</button>
			</div>
		</div>
		</div>
	</div>

<!-- Modal para solo aceptar que se no guardo nignun dato -->
<div class="modal fade modal-datoNoGuardado" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-danger">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Hubo un problema guardando</h4>
		</div>
		<div class="modal-body">
			<strong><i class="icofont icofont-cat-alt-4"></i> Lo sentimos,</strong> hubo un error interno, porfavor comunícalo.
		</div>
		<div class="modal-footer"> 
		
		<button class="btn btn-danger btn-outline" data-dismiss="modal" data-dismiss="modal"><i class="icofont icofont-close"></i> Salir</button>
		</div>
	</div>
</div>
</div>

<!-- Modal para Eliminar un producto de la BD -->
<div class="modal fade modal-eliminarProducto" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-danger">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Eliminar producto</h4>
		</div>
		<div class="modal-body">
			<p>¿Deseas eliminar el producto de la BD?</p>
		</div>
		<div class="modal-footer"> 
		<button class="btn btn-default btn-outline" data-dismiss="modal" data-dismiss="modal"><i class="icofont icofont-close"></i> Salir</button>
		<button class="btn btn-danger btn-outline" id="btnModalEliminarDB" data-dismiss="modal"><i class="icofont icofont-close"></i> Eliminar</button>
		</div>
	</div>
</div>
</div>


<!-- Modal para solo aceptar que se guardo un dato -->
<div class="modal fade modal-datoGuardado" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-primary">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Datos guardados exitósamente</h4>
		</div>
		<div class="modal-body">
			<strong><i class="icofont icofont-social-smugmug"></i> Enhorabuena,</strong> sus datos fueron guardados correctamente. <p id="pDatoGuardadoInfo"></p>
		</div>
		<div class="modal-footer"> 
		
		<button class="btn btn-primary btn-outline" data-dismiss="modal" data-dismiss="modal"><i class="icofont icofont-check"></i> Ok</button>
		</div>
	</div>
</div>
</div>

<!-- Modal para confirmar si deseas remover de la lista de prestamos? -->
<div class="modal fade modal-retirarPrestamoConfirmar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-danger">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Retirar artículo</h4>
		</div>
		<div class="modal-body"><span class="sr-only" id="idPrestamoAct"></span>
			<p>Estas por de retirar el artículo <strong><span class="mayuscula" id="spanModalRetirarProducto"></span></strong>. Capital: S/. <span id="spanCapitalRetiro"></span>, se valorizó en S/. <span id="spanValorizadoRetirar"></span> a la fecha ¿Con cuánto deseas retirarlo?</p>
			<input type="number" class="form-control text-center" id="txtModalRetirarPrestamo" value="0.00" min=0 step=1>
			<p>¿Desea agregar algún comentario extra?</p>
			<input type="text" class="form-control mayuscula" id="txtComentarioextraPrestamo">
			<p class="text-danger" id="pErrorRetirarPrestamo"></p>
		</div>
		<div class="modal-footer">
		<button class="btn btn-danger btn-outline" data-dismiss="modal"><i class="icofont icofont-close"></i> Cancelar</button>
		<button class="btn btn-danger btn-outline" id="btnModalRetirarPrestamo"><i class="icofont icofont-alarm"></i> Sí, retirar</button>
		</div>
	</div>
</div>
</div>

<!-- Modal para agregar capital al articulo -->
<div class="modal fade modal-desembolsoCapital" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-success">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Desembolso a artículo</h4>
		</div>
		<div class="modal-body"><span class="sr-only" id="idPrestamoAct"></span>
			<p>Estas por de desembolsar dinero a el artículo «<strong><span class="mayuscula" id="spanModalDesembolsoProducto"></span></strong>». Capital inicial: S/. <span id="spanCapitalBase"></span> ¿Cuánto deseas aumentar?</p>
			<input type="number" class="form-control text-center" id="txtModalDesembolsarPrestamo" value="0.00" min=0 step=1>
			<p>¿Desea agregar algún comentario extra?</p>
			<input type="text" class="form-control mayuscula" id="txtComentarioextraDesembolso">
			<p class="text-danger hidden" id="pErrorDesembolso"></p>
		</div>
		<div class="modal-footer">
		<button class="btn btn-danger btn-outline" data-dismiss="modal"><i class="icofont icofont-close"></i> Cancelar</button>
		<button class="btn btn-success btn-outline" id="btnModalAgregarDesembolso"><i class="icofont icofont-alarm"></i> Sí, aumentar</button>
		</div>
	</div>
</div>
</div>

<!-- Modal para indicar que falta completar campos o datos con error -->
<div class="modal fade modal-faltaCompletar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-danger">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Campos incorrectos o faltantes</h4>
		</div>
		<div class="modal-body">
			Ups, un error: <i class="icofont icofont-animal-squirrel"></i> <em id="lblFalta"></em>
		</div>
		<div class="modal-footer"> 
		
		<button class="btn btn-danger btn-outline" data-dismiss="modal"><i class="icofont icofont-alarm"></i> Ok, revisaré</button>
		</div>
	</div>
</div>
</div>
<!-- Modal para editar los datos de los clientes  -->
<div class="modal fade modal-detalleFinalizadosCliente" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header-wysteria">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Reporte del producto con cliente</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
				<label for="">D.N.I:</label>
				<input type="number" class="form-control" id="txtFindni" disabled>
			</div>
			<div class="row">
				<label for="">Apellidos y nombres</label>
				<input type="text" class="form-control mayuscula" id="txtFinapellido" disabled>
			</div>
			<div class="row">
				<label for="">Producto:</label>
				<input type="text" class="form-control mayuscula" id="txtFinproducto" disabled>
			</div>
			<div class="row">
				<label for="">Monto inicial:</label>
				<input type="text" class="form-control" id="txtFinmonto" disabled>
			</div>
			<div class="row">
				<label for="">Fecha de registro:</label>
				<input type="text" class="form-control" id="txtFinfecha" disabled>
			</div>
			</div>

		</div>
		<div class="modal-footer">
			<button class="btn btn-morita btn-outline" id="btnActualizarDataCliente" data-dismiss="modal"><i class="icofont icofont-social-meetme"></i> Aceptar</button>
			</div>
	</div>
	</div>
</div>
<!-- Modal para mostrar los movimientos realizados por un dia especifico -->
<div class="modal fade modal-mostrarMovimientosProductoFecha" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header-indigo">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Resultados de la búsqueda por fecha</h4>
		</div>
		<div class="modal-body">
			<div class="row container-fluid"> <strong>
				<div class="col-xs-5">Producto</div>
				<div class="col-xs-3">Accion</div>
				<div class="col-xs-2">Usuario</div>
				<div class="col-xs-2">Monto</div>
				</strong>
			</div>
			<div class="row container-fluid" id="rowMovimientoEncontradoFecha">
				
			</div>
			
		</div>
		<div class="modal-footer"> <button class="btn btn-primary btn-outline" data-dismiss="modal"><i class="icofont icofont-alarm"></i> Aceptar</button></div>
	</div>
	</div>
</div>
<!-- Modal para mostrar el proyectado de intereses acumulados -->
<div class="modal fade modal-mostrarProyectadoIntereses" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog  modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-cielo">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Intereses proyectados</h4>
		</div>
		<div class="modal-body">
			<div class="row container-fluid"> <strong>
				<div class="col-xs-6">Dia</div>
				<div class="col-xs-6">Monto</div>
				</strong>
			</div>
			<div class="row container-fluid" id="rowInteresesProyectado">
				
			</div>
			
		</div>
		<div class="modal-footer"> 
			<button class="btn btn-success btn-outline" data-dismiss="modal" id="btnImprTicketInterProyect"><i class="icofont icofont-ticket"></i> Imprimir ticket</button>
			<button class="btn btn-primary btn-outline" data-dismiss="modal"><i class="icofont icofont-alarm"></i> Aceptar</button></div>
	</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/impotem.js?version=1.0.4"></script>
<script type="text/javascript" src="js/jquery.printPage.js?version=1.4"></script>
<script type="text/javascript" src="js/bootstrap-table.min.js"></script>
<script>
$(document).ready(function () {
	datosUsuario();
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('#dtpFechaVencimiento').val(moment().add(1, 'days').format('DD/MM/YYYY'));
	var idNew = <?php if (isset($_GET["idprod"])) { echo $_GET["idprod"]; }else{ echo 0;} ?>;
	var idCompra=<?php if (isset($_GET["idcompr"])) { echo $_GET["idcompr"]; }else{ echo 0;} ?>;
	$.interesGlobal=0.04;
	$.idOficina=0;
	if( <?php echo $_SESSION['idSucursal']; ?> !=3){ $.idOficina =  <?php echo $_SESSION['idSucursal']; ?> }
	else{$.idOficina=$('#cmbOficinasTotal').val();}
	cambiodeOficina();
/*	$.JsonUsuario=[];
	$.JsonUsuario.push({'sucursal':'<?php echo $_SESSION['idSucursal']; ?>', 'nombre': '<?php echo $_SESSION['Atiende']; ?>', 'idusuario': '<?php echo $_SESSION['idUsuario']; ?>',
		'idoficina': '<?php echo $_SESSION['oficina']; ?>'
	 });*/
	
	if(parseInt(idNew)==0){ }//console.log('nada')
	else {
		$('#rowWellFijo').removeClass('hidden');
		$('#rowWellCambiante').addClass('hidden');

		console.log('solcitar producto con id: ' + idNew);
		var coleccionIDs=''; var contarDesde='';
		$.ajax({url: 'php/solicitarProductoPorId.php',type:'POST', data: {idProd: idNew}}). done(function (resp) { console.log(resp)
		var dato = JSON.parse(resp);  console.log(dato)
			
		if(dato.length>0){
		moment.locale('es');

		$('#spanIdCliente').text(dato[0].idCliente);
		$('#spanApellido').text(dato[0].cliApellidos);
		$('#spanNombre').text(dato[0].cliNombres);
		$('#spanDni').text(dato[0].cliDni);
		$('#spanDireccion').text(dato[0].cliDireccion);
		$('#spanCorreo').text(dato[0].cliCorreo);
		$('#spanCelular').text(dato[0].cliCelular);
		$('#spanProducto').text(dato[0].prodNombre)
		
		var fechaReg =moment(dato[0].desFechaContarInteres); //console.log(dato[0].desFechaContarInteres);
		var sumaCapital=0, sumaIntereses=0;

		if( dato[0].idSucursal==  <?php echo $_SESSION['idSucursal'] ?> ||  <?php echo $_SESSION['Power']  ?>==1){// console.log('misma sucursal');
			
			if(dato[0].prodObservaciones ==''){$('#spanObservacion').text('Ninguna');}else{$('#spanObservacion').html(dato[0].prodObservaciones);}

			moment.locale('es');

			/*Nuevo Código para de préstamos*/
			$.ajax({url:'php/listarPrestamosPorIdProducto.php', type:'POST', data:{idProd: idNew }}).done(function (resp) {
				//console.log(resp)
				$.each(JSON.parse(resp), function (i, jresp) { // console.log(jresp)
					coleccionIDs+=jresp.idPrestamo+','; console.log(coleccionIDs);
					var differencia=moment().diff(moment(jresp.desFechaContarInteres).format('YYYY-MM-DD'), 'days'); /*console.log(differencia)*/
					var diaInicial= moment(jresp.desFechaContarInteres).format('YYYY-MM-DD');
					
					$('#spanCodProd').text(idNew);
					if(diaInicial>=31){$('#spanPeriodo2').text(moment(diaInicial).fromNow());}
					// if(differencia==0){differencia=1;}
					$('#smallPeriodo').text('('+differencia +' días)');
					contarDesde=moment(jresp.desFechaContarInteres);
					//console.log($(`.divContUnPrestamo #${jresp.idPrestamo}`).length)
					$('#contenedorPrestamos').append(`<div class="row alert-message alert-message-warning divContUnPrestamo" id="${jresp.idPrestamo}">
						<h4>Préstamo #${i+1}: <small class="mayuscula">asociado a: ${$('#rowWellFijo #spanProducto').text()}</small></h4>
						<div class="col-xs-12">
						<table class="" data-toggle="table">
						<thead style="background-color: #ffcd87; color: #9a6b29;">
						<tr >
							<th>Descripción</th>
							<th>Fecha</th>
							<th>Cantidad</th>
							<th>Responsable</th>
						</tr>
						</thead>
						<tbody  style="background-color: white;">
						</tbody>
					</table></div>
					</div>`);
					//console.log('estado: '+jresp.preIdEstado);
					console.log('diffe: '+differencia);
					if(jresp.preIdEstado==11){ console.log('entra 11')
						$('#smallh3Producto').css({'color': '#f0ad4e'}).html('<i class="icofont icofont-chart-pie-alt"></i> Artículo retirado '+moment(jresp.preFechaInicio).fromNow()+ ' por '+ jresp.usunick);
						$('#btn-imprimirTicketFijo').addClass('sr-only');
						$('#btn-FinalizarPrestamoFijo').addClass('sr-only');
						$('#btn-RetirarPrestamoFijo').addClass('sr-only');
						$('#spanVigenciaProducto').text('11');
						$('#btn-imprimirRetiro').removeClass('sr-only');
					}
					else if(parseInt(differencia)>90){ console.log('entra 90') //tiene máximo 90 días, = 3 meses para recoger y generar intereses, luego ya no calcula
						$('#smallh3Producto').css({'color': '#f7221d'}).html('<i class="icofont icofont-chart-pie-alt"></i> Artículo expirado, se sugiere rematar, pasó el límite de 3 meses en almacén. ');
						$('#btn-imprimirTicketFijo').addClass('sr-only');
						$('#btn-FinalizarPrestamoFijo').addClass('sr-only');
						$('#btn-RetirarPrestamoFijo').removeClass('sr-only');

						//$('#contenedorExpiro').removeClass('sr-only');

						// $.ajax({url: 'php/calculoInteresAcumuladoDeValor.php?inicio='+dato[0].prodMontoEntregado+'&numhoy='+90, type:'POST'}).done(function (resp) {
						// 	var dato1= JSON.parse(resp)
						// 	//console.log(dato1);
						// 	$('#spanPorcent').text(parseFloat(dato1[2][0].intDiarioHoy*100).toFixed(2));
						// 	$('#spanPagar').text(parseFloat(dato1[2][0].pagarAHoy).toFixed(2));
						// 	$('#spanIntGenerado').text(parseFloat(dato1[2][0].pagarAHoy-dato1[0][0].montoInicial).toFixed(2));
						// 	$('#spanValordeMeses').text( '. Se sugiere una valorización de S/. ' +$('#spanPagar').text() );
						// });
					}
					else if(parseInt(differencia)<31){
						$('#smallh3Producto').css({'color': '#3cb30e'}).html('<i class="icofont icofont-chart-pie-alt"></i> Artículo Vigente, tiene '+parseInt(90-differencia) +' días para pasar a remate.' );
						$('#btn-imprimirTicketFijo').removeClass('sr-only');
						$('#btn-DesembolsoFijo').removeClass('sr-only');
						$('#btn-PagoACuentaFijo').removeClass('sr-only');
						$('#btn-RetirarPrestamoFijo').removeClass('sr-only');
						$('#h5CargoAdmin').text('0.00');
						$('#h5contCargoAdmin').addClass('sr-only');
					}
					else if(parseInt(differencia)>=32){
						$('#smallh3Producto').css({'color': '#3cb30e'}).html('<i class="icofont icofont-chart-pie-alt"></i> Artículo Vigente, tiene '+parseInt(90-differencia) +' días para pasar a remate.' );
						$('#btn-imprimirTicketFijo').removeClass('sr-only');
						$('#btn-DesembolsoFijo').removeClass('sr-only');
						$('#btn-PagoACuentaFijo').removeClass('sr-only');
						$('#btn-RetirarPrestamoFijo').removeClass('sr-only');
						$('#h5CargoAdmin').text('10.00');
						$('#h5contCargoAdmin').removeClass('sr-only');
					}
					else if(differencia==0){
						$('#smallh3Producto').css({'color': '#3cb30e'}).html('<i class="icofont icofont-chart-pie-alt"></i> Artículo Vigente, tiene '+parseInt(90-differencia) +' días para pasar a remate.' );
						$('#btn-imprimirTicketFijo').removeClass('sr-only');
						$('#btn-DesembolsoFijo').removeClass('sr-only');
						$('#btn-PagoACuentaFijo').removeClass('sr-only');
						$('#btn-RetirarPrestamoFijo').removeClass('sr-only');
						//$('#contenedorVigente').removeClass('sr-only'); $('#contDiasPosRem').text(parseInt(90-differencia));
						// $.ajax({url: 'php/calculoInteresAcumuladoDeValor.php?inicio='+dato[0].prodMontoEntregado+'&numhoy='+1, type:'POST'}).done(function (resp) {
						// 	var dato2= JSON.parse(resp)
						// 	$('#spanPorcent').text(parseFloat(dato2[2][0].intDiarioHoy*100).toFixed(2));
						// 	$('#spanPagar').text(parseFloat(dato2[2][0].pagarAHoy).toFixed(2));
						// 	$('#spanIntGenerado').text(parseFloat(dato2[2][0].pagarAHoy-dato2[0][0].montoInicial).toFixed(2));
						// });
					}
					else
					{
						$('#smallh3Producto').css({'color': '#3cb30e'}).html('<i class="icofont icofont-chart-pie-alt"></i> Artículo Vigente, tiene '+parseInt(90-differencia) +' días para pasar a remate.' );
						$('#btn-imprimirTicketFijo').removeClass('sr-only');
						$('#btn-DesembolsoFijo').removeClass('sr-only');
						$('#btn-PagoACuentaFijo').removeClass('sr-only');
						$('#btn-RetirarPrestamoFijo').removeClass('sr-only');
						//$('#contenedorVigente').removeClass('sr-only'); $('#contDiasPosRem').text(parseInt(90-differencia));
						// $.ajax({url: 'php/calculoInteresAcumuladoDeValor.php?inicio='+dato[0].prodMontoEntregado+'&numhoy='+differencia, type:'POST'}).done(function (resp) {
						// 	var dato3= JSON.parse(resp)
						// 	$('#spanPorcent').text(parseFloat(dato3[2][0].intDiarioHoy*100).toFixed(2));
						// 	$('#spanPagar').text(parseFloat(dato3[2][0].pagarAHoy).toFixed(2));
						// 	$('#spanIntGenerado').text(parseFloat(dato3[2][0].pagarAHoy-dato3[0][0].montoInicial).toFixed(2));
						// });
					}

				});
				//console.log(coleccionIDs.substring(0,coleccionIDs.length-1));
				$.ajax({url:'php/listarMontoPrestamoActual.php', type:'POST', data:{idProd: idNew }}).done(function (resp) { //console.log(resp);
					var jsonRespActual=JSON.parse(resp); //console.log(jsonRespActual[0].desCapital)
					var preCapi=parseFloat(jsonRespActual[0].preCapital);
					$('#spanMontoDado').text(preCapi.toFixed(2));
					$('#spanSrCapital').text(preCapi.toFixed(2));
					if($('#spanVigenciaProducto').text()!=11){
						var hastaHoyDias=moment().diff(moment(jsonRespActual[0].desFechaContarInteres).format('YYYY-MM-DD'),'days');

						var calcInt=0, interesVigente=0;
						//var montoIniciov2=resp;
						// console.log('hasta hoy: '+ hastaHoyDias);
						//console.log('php/calculoInteresAcumuladoDeValor.php?inicio='+jresp2.desCapital+'&numhoy='+hastaHoyDias);
						if(hastaHoyDias>90){hastaHoyDias=90;} /*if(hastaHoyDias==0 ){hastaHoyDias=1;}*/
						if(hastaHoyDias<=7){
							interesVigente=$.interesGlobal;
							calcInt=preCapi*interesVigente;
							
							$('#spanSrInteres').text(parseFloat(calcInt).toFixed(2));
							$('#h5SrInteres').text(parseFloat(calcInt).toFixed(2));
							$('#h5DetalleInteres').html(' por interés <strong>simple</strong> al 4% mínimo (del día 1 al 7).');
						}
						if(hastaHoyDias<=28 && hastaHoyDias>7 ){ 
							interesVigente=$.interesGlobal/7;
							calcInt=preCapi*interesVigente*hastaHoyDias;
							
							$('#spanSrInteres').text(parseFloat(calcInt).toFixed(2));
							$('#h5SrInteres').text(parseFloat(calcInt).toFixed(2));
							$('#h5DetalleInteres').html(' ('+parseFloat(interesVigente*hastaHoyDias*100).toFixed(2)+'%) por interés <strong>simple</strong> al 4% semanal (del día 3 al 28).');
						}
						if(hastaHoyDias>28){ 
							if(hastaHoyDias>30){}
							interesVigente=1.16;
							var nuevoCapital=parseFloat(preCapi*interesVigente).toFixed(2);
							
							$.ajax({url: 'php/calculoInteresAcumuladoDeValor.php?inicio='+nuevoCapital+'&numhoy='+hastaHoyDias, type: 'POST' }).done(function (resp) {
								//console.log(resp);
								var jsonInteres=JSON.parse(resp); 
								// $(`#contenedorPrestamos #${jresp2.idDesembolso}`).find('tbody').append(`
								// <tr><td>Interés ${parseFloat(jsonInteres[2][0].intDiarioHoy*100).toFixed(2)}% por <span class="spanHastaHoyInt">${hastaHoyDias}</span> días.</td>
								// <td>${moment(jresp2.desFechaContarInteres).fromNow()}</td>
								// <td>S/. ${parseFloat(jsonInteres[2][0].pagarAHoy.replace(",",'')-jresp2.desCapital).toFixed(2)}</td>
								// <td>-</td></tr>`);
								//.replace(",",'')
								console.log(jsonInteres[1][hastaHoyDias-28-1]);
								calcInt=parseFloat(parseFloat(jsonInteres[1][hastaHoyDias-28-1].intAcum-preCapi));
								$('#spanSrInteres').text(parseFloat(calcInt).toFixed(2));
								$('#h5SrInteres').text(parseFloat(calcInt).toFixed(2));
								$('#h5DetalleInteres').html(' por interés <strong>acumulado</strong> al 4% diario (mayor a 29 días).');
								
								//$('#h5DetalleInteres').text(' por '+ differencia +' días.'); console.log(calcInt)
							});
						}
					}
				})
				$.ajax({url:'php/listarDesembolsosPorPrestamos.php', type:'POST', data: {idsDesembolso: coleccionIDs.substring(0,coleccionIDs.length-1) }}).done(function (resp) { //console.log(resp);
					$.each(JSON.parse(resp), function (i,jresp2) { console.log(jresp2)
						//console.log(jresp2.desFechaContarInteres)
						
						sumaCapital+=parseFloat(jresp2.desCapital);
						$(`#contenedorPrestamos #${jresp2.idPrestamo}`).find('tbody').append(`
							<tr><td>Capital o desembolso</td>
							<td>${moment(jresp2.preFechaInicio).format('DD/MM/YYYY hh:mm a')}</td>
							<td>S/. ${parseFloat(jresp2.desCapital).toFixed(2)}</td>
							<td>${jresp2.usuNick}</td></tr>`);

						// if($('#spanVigenciaProducto').text()!=11){
						// 	var hastaHoyDias=moment().diff(moment(jresp2.desFechaContarInteres),'days');
						// 	console.log('hasta hoy: '+ hastaHoyDias);
						// 	//console.log('php/calculoInteresAcumuladoDeValor.php?inicio='+jresp2.desCapital+'&numhoy='+hastaHoyDias);
						// 	if(hastaHoyDias>90){hastaHoyDias=90;} if(hastaHoyDias==0){hastaHoyDias=1;}
						// 	$.ajax({url: 'php/calculoInteresAcumuladoDeValor.php?inicio='+jresp2.desCapital+'&numhoy='+hastaHoyDias, type: 'POST' }).done(function (resp) {
						// 		//console.log(resp)
						// 		var jsonInteres=JSON.parse(resp); //console.log(jsonInteres) //style="color: #f7221d"
						// 		// console.log(jsonInteres[2][0].pagarAHoy.replace(",",''))
						// 		// console.log(jresp2.desCapital)
						// 		$(`#contenedorPrestamos #${jresp2.idDesembolso}`).find('tbody').append(`
						// 		<tr><td>Interés ${parseFloat(jsonInteres[2][0].intDiarioHoy*100).toFixed(2)}% por <span class="spanHastaHoyInt">${hastaHoyDias}</span> días.</td>
						// 		<td>${moment(jresp2.desFechaContarInteres).fromNow()}</td>
						// 		<td>S/. ${parseFloat(jsonInteres[2][0].pagarAHoy.replace(",",'')-jresp2.desCapital).toFixed(2)}</td>
						// 		<td>-</td></tr>`);
						// 		sumaCapital+=parseFloat(jsonInteres[2][0].pagarAHoy.replace(",",'')-jresp2.desCapital);
						// 		sumaIntereses+=parseFloat(jsonInteres[2][0].pagarAHoy.replace(",",'')-jsonInteres[0][0].montoInicial);
						// 		$('#spanMontoDado').text(parseFloat(sumaCapital).toFixed(2));
						// 		$('#spanSrInteres').text(parseFloat(sumaIntereses).toFixed(2));

						// 	});
						// }
						$.ajax({url:'php/listarMovimientosCajaPorIdProducto.php', type:'POST', data: {idProd: idNew }}).done(function (resp) { //console.log(resp)
							//console.log(getObjects(,jresp2.desCapital))
							$.each(JSON.parse(resp), function (i, jsonCaja) {// console.log(sumaCapital)
								//console.log(jsonCaja)
								var diffBD=moment(jsonCaja.cajaFecha).diff(contarDesde, 'days');
								var diffHoy=moment().diff(contarDesde, 'days');
								//console.log(contarDesde)
								if(jsonCaja.idTipoProceso==9 && diffBD>=0){
									var inte=parseFloat($('#spanSrInteres').text())-jsonCaja.cajaValor;
									$('#spanSrInteres').text(parseFloat(inte).toFixed(2));
									$('#h5SrInteres').text(parseFloat(inte).toFixed(2));
								}
								if(jsonCaja.idTipoProceso==10 && diffBD==0 && diffHoy==0 ){
									var inte=parseFloat($('#spanSrInteres').text())-jsonCaja.cajaValor;
									$('#spanSrInteres').text(0);
									$('#h5SrInteres').text(0);
								}
								if(jsonCaja.idTipoProceso==10 && diffBD>0 ){
									var inte=parseFloat($('#spanSrInteres').text())-jsonCaja.cajaValor;
									$('#spanSrInteres').text(parseFloat(inte).toFixed(2));
									$('#h5SrInteres').text(parseFloat(inte).toFixed(2));
								}
								$(`#contenedorPrestamos #${jsonCaja.idPrestamo}`).find('tbody').append(`
							<tr><td class="hidden idCajaTd">${jsonCaja.idCaja}</td> <td>${jsonCaja.tipoDescripcion}</td>
							<td>${moment(jsonCaja.cajaFecha).format('DD/MM/YYYY hh:mm a')}</td>
							<td>S/. ${parseFloat(jsonCaja.cajaValor).toFixed(2)}</td>
							<td>${jsonCaja.usuNombres}</td></tr>`);

							});

								
							});
					});
					$(`#contenedorPrestamos `).find('table').bootstrapTable();
				});
				
			});

			/*Fin Código para de préstamos*/

			// var hoy = moment();
			// var fechaIni =moment(dato[0].prodFechaInicial);
			// var fechaFin=moment(dato[0].prodFechaVencimiento);
			//var differencia=hoy.diff(fechaIni, 'days'); //La diferencia en días desde el día de inicio de conteo de intereses
			//console.log('Tantos dias desde fecha incial a hoy: '+ differencia);
			
			moment.locale('es');
			//$('#spanProducto').text(dato[0].prodNombre);
			$('#spanFechaInicio').text(moment(dato[0].prodFechaInicial).format('dddd[,] DD MMMM YYYY'));
			$('#spanFechaFin').text(moment(dato[0].prodFechaVencimiento).format('dddd[,] DD MMMM YYYY'));
			$('#spanFechaInicioNum').text(dato[0].prodFechaInicial);
			$('#spanFechaFinNum').text(dato[0].prodFechaVencimiento);
			//$('#spanPeriodo2').text(moment(fechaIni).fromNow());
			//$('#smallPeriodo').text('('+differencia +' días)');
			
			//$('#spanMontoDado').text(parseFloat(dato[0].prodMontoEntregado).toFixed(2));
			$('#spanAdelanto').text(parseFloat(dato[0].prodAdelanto).toFixed(2));
			$('#spanDeudaFinal').text( parseFloat($('#spanPagar').text()- parseFloat( $('#spanAdelanto').text())).toFixed(2));

			if(dato[0].prodActivo==0){
				/*$('#divBotonesFijos').addClass('hidden');
				$('#btn-imprimirTicketFijo').addClass('hidden');
				$('#btn-imprimirImpresoraFijo').addClass('hidden');
				$('#btn-AdelantoPrestamoFijo').addClass('hidden');
				$('#btn-FinalizarImpuestoFijo').addClass('hidden');
				$('#btn-FinalizarPrestamoFijo').addClass('hidden');*/
				$('#contenedorFinalizados').removeClass('sr-only');
				$.ajax({url: 'php/listarMovimientoFinal.php', type: 'POST', data: {idProd: idNew }}).done(function (resp) {
					dato2=JSON.parse(resp); 
					//console.log(dato2)
					
					if(dato2.length==0){$('#finalizaMonto').parent().addClass('hidden');}
					else{
						$('#contenedorExpiro').addClass('sr-only');
						$('#QuienFinalizoFin').text(dato2[0].repoUsuario);
						$('#estadoAprobacionFin').text(dato2[0].prodQuienFinaliza);
						$('#QuienApruebaFin').text(dato2[0].repoQuienConfirma);

						$('#finalizaMonto').text(parseFloat(dato2[0].repoValorMonetario).toFixed(2));
						$('#FechaFinalizo').text(moment(dato2[0].prodFechaFinaliza).format('dddd[,] DD MMMM YYYY [a las] hh:mm a'));
					}
				});
				//console.log(moment().diff(fechaIni, 'days'))
			}
			else{$('#divBotonesFijos').removeClass('hidden');}
		}
		else{console.log('otra sucrusal');
		$('#rowWellFijo').html(`<div class="alert alert-danger ">
			<i class="icofont icofont-animal-cat-alt-4" style="font-size:24px"></i> <strong>¡Oh lo sentimos!</strong> Éste producto no está asignado a tu sucursal, se encuentra en «${dato[0].sucNombre}».
					</div>`);
		}
	}//fin de if lenght dato
	else{
		$('#spanProducto').removeClass('text-primary').addClass('text-danger').text('Código eliminado o todavía no está en uso.').parent().removeClass('alert-message-primary').addClass('alert-message-danger');
	}

		})
		$('.nav-tabs li').eq(1).children('a').click();
	}
	if(parseInt(idCompra)==0){}//no hacer nada
	else{

		$('.nav-tabs li').eq(1).children('a').click();
		$.ajax({url: 'php/solicitarDatosCompraCliente.php', type: 'POST', data: {idcomp: idCompra }}).done(function (resp) {
			var dato = JSON.parse(resp);
			//console.log(dato);
			moment.locale('es');
			$('#spanIdCliente').text(dato[0].idCliente);
			$('#spanApellido').text(dato[0].cliApellidos);
			$('#spanNombre').text(dato[0].cliNombres);
			$('#spanDni').text(dato[0].cliDni);
			$('#spanDireccion').text(dato[0].cliDireccion);
			$('#spanCorreo').text(dato[0].cliCorreo);
			$('#spanCelular').text(dato[0].cliCelular);

			$('#spanProducto').text(dato[0].compNombre);
			$('#spanPorcent').text(0);


			$('#rowWellFijo').removeClass('hidden');
			$('#spanFechaInicio').text(moment(dato[0].compFechaInicial).format('dddd[,] DD MMMM YYYY'));
			$('#spanPeriodo2').parent().addClass('sr-only');
			
			$('#spanMontoDado').text(parseFloat(dato[0].compMontoInicial).toFixed(2));
			$('#spanAdelanto').parent().parent().addClass('sr-only');
			$('#spanPagar').parent().parent().addClass('sr-only');

		});

	}
});


$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"});

$('#dtpFechaInicio').change(function () {
	calcularPeriodo();
});
$('#dtpFechaVencimiento').change(function () {
	calcularPeriodo();
});
function calculoIntereses(fechaAnterior, fechaVencimiento, montoDado, montoInteres){
	var resultado =[];
	var diferenciaDias, diferenciaSemanas, diferenciaRestoDias, montoaPagar, interesAcumulado;
	diferenciaDias = fechaVencimiento.diff(fechaAnterior, 'days');
		
	
	
	if(diferenciaDias<=6){diferenciaSemanas=0;diferenciaRestoDias=1; resultado.push({periodo: 'Hace ' + diferenciaDias + ' días'})}//$('#spanPeriodo2').text() }
	else{diferenciaSemanas =  parseInt(diferenciaDias/7); diferenciaRestoDias= diferenciaDias%7; resultado.push({periodo: 'Hace '+diferenciaSemanas + ' semanas y ' +diferenciaRestoDias + ' días'})}//$('#spanPeriodo2').text() }
	


	if(diferenciaRestoDias>0 ){
		diferenciaSemanas+=1
	}

	if(montoInteres==10){
		montoaPagar = parseFloat(montoDado)+(parseFloat(montoDado)*parseInt(montoInteres)/100);
		interesAcumulado=montoInteres;
	}
	else{	
		montoaPagar = parseFloat(montoDado)+(parseFloat(montoDado)*parseInt(montoInteres)/100)*(diferenciaSemanas);
		interesAcumulado=montoInteres*diferenciaSemanas;
	}

	resultado.push({monto:parseFloat(montoaPagar).toFixed(2), acumulado: interesAcumulado});
	
	return resultado;// parseFloat(montoaPagar).toFixed(2);
}
function calcularPeriodo(){

	var fechaAnterior, fechaVencimiento, diferenciaDias , diferenciaSemanas, diferenciaRestoDias, montoDado, montoInteres, montoaPagar;

	if($('#dtpFechaInicio').val()!='' && $('#dtpFechaVencimiento').val()!='' ){
		fechaAnterior = moment( $('#dtpFechaInicio').val(),'DD/MM/YYYY');
		fechaVencimiento= moment( $('#dtpFechaVencimiento').val(),'DD/MM/YYYY');
		diferenciaDias = fechaVencimiento.diff(fechaAnterior, 'days');


		
		if(diferenciaDias<=0){$('#lblFalta').text('La fecha de vencimiento no puede ser mejor o igual a la fecha de ingreso.')
			$('.modal-faltaCompletar').modal('show');montoDado =0;diferenciaSemanas=0;
			}
		else{
			if(diferenciaDias<=6){diferenciaSemanas=1;diferenciaRestoDias=1; $('#spanPeriodo').text(diferenciaDias + ' días'); }
			else{diferenciaSemanas = Math.ceil(diferenciaDias/7); diferenciaRestoDias= diferenciaDias%7; $('#spanPeriodo').text(diferenciaSemanas + ' semanas y ' +diferenciaRestoDias + ' días') }
		}

		
		if($('#txtMontoEntregado').val()==''){montoDado =0; } else{montoDado= parseFloat($('#txtMontoEntregado').val());}
		if($('#txtMontoInteres').val()==''){montoInteres =0; }else{
			if(diferenciaDias<=14 ){montoInteres =10/100}
			else{montoInteres = 4*diferenciaSemanas/100}
			//montoInteres = parseFloat($('#txtMontoInteres').val()/100);
		}
		//console.log('dias: '+ diferenciaDias +' semanas: '+diferenciaSemanas + ' interes: '+montoInteres);
		
		/*if(diferenciaRestoDias>0 ){
			diferenciaSemanas+=1
			//console.log('semana de impuesto: ' + parseInt(diferenciaSemanas+1) + ' Monto a pagar')
		}*/



		montoaPagar = montoDado+(montoDado*montoInteres);
		/*console.log('semana de impuesto: ' + parseInt(diferenciaSemanas))
		console.log('dado: '+montoDado+' interes '+montoInteres+' semana: '+ parseInt(diferenciaSemanas))
		console.log('a pagar ' + montoaPagar);*/
		$('#spanIntGene').text((montoInteres*100)+'%');
		$('#spanInteres').text('S/. '+ parseFloat(montoDado*montoInteres).toFixed(2))
		$('#spanMonto').text(montoDado.toFixed(2))
		
	}
	else{
		$('#spanPeriodo').text('Fecha inválida');
	}
}
$('#btnGuardarDatos').click(function () { //console.log($('#txtNombreProducto').val())
	calcularPeriodo();
	if(verificarTodoRellenado()){
		var fechav3= moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')+' ' + moment().format('HH:mm:ss');
		var idSucu=$.JsonUsuario.idsucursal;
		if(idSucu==3){idSucu=$('#cmbOficinasTotal').val();}
		if($('#txtIdCliente').val().length>0){//guardar sólo el producto
			$.ajax({url: 'php/insertarProductoSolo.php', type: 'POST', data:{
				productoNombre: $('#txtNombreProducto').val(),
				montoentregado: $('#txtMontoEntregado').val(),
				interes: 4, //$('#txtMontoInteres').val(),
				montopagar: $('#spanMonto').text(),
				fechainicial: moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				feachavencimiento: moment($('#dtpFechaVencimiento').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				observaciones: $('#txtObservaciones').val(),
				idCl: $('#txtIdCliente').val(),
				fechaRegistro: fechav3, idSucursal: idSucu
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', resp).removeClass('sr-only');
				$('.modal-ventaGuardada').find('.btnCompraGuardado').addClass('sr-only');
				//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
				limpiarCasillas();
				}
			});
		}else{//guardar ambos productos y cliente
		$.ajax({url: 'php/insertarProducto.php', type: 'POST', data:{
				nombre: $('#txtNombres').val(),
				apellido: $('#txtApellidos').val(),
				direccion: $('#txtDireccion').val(),
				dni: $('#txtDni').val(),
				email:  $('#txtCorreo').val(),
				celular: $('#txtCelular').val(),
				productoNombre: $('#txtNombreProducto').val(),
				montoentregado: $('#txtMontoEntregado').val(),
				interes: 4, //$('#txtMontoInteres').val(),
				montopagar: $('#spanMonto').text(),
				fechainicial: moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				feachavencimiento: moment($('#dtpFechaVencimiento').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				observaciones: $('#txtObservaciones').val(),
				fechaRegistro: fechav3, idSucursal: idSucu
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', resp).removeClass('sr-only');
				$('.modal-ventaGuardada').find('.btnCompraGuardado').addClass('sr-only');
				//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
				limpiarCasillas();
			}
			}).error(function () { console.log('error');
				// body...
			});
		}
	
	}//<- fin de if verificarTodoRellenado
	
});
$('body').on('click','.btnAceptarGuardado',function () {
	var id= $(this).attr('id');
	window.location.href = "aplicativo.php?idprod=" +id;
});
$('body').on('click','.btnCompraGuardado',function () {
	var id= $(this).attr('id');
	window.location.href = "aplicativo.php?idprod=" +id;
});

$('#btnLimpiarDatos').click(function () {
	limpiarCasillas();
});
$('#txtDni').focusout(function () {
	$.ajax({url: 'php/encontrarCliente.php', type:'POST', data:{ dniCli:$('#txtDni').val() }}).done(function (resp) {
		console.log(JSON.parse(resp).length)
		if(JSON.parse(resp).length==1){
			$.each(JSON.parse(resp), function (i, dato) {
				$('#txtIdCliente').val(dato.idCliente).attr("disabled", 'true');
				$('#txtApellidos').val(dato.cliApellidos).attr("disabled", 'true');
				$('#txtNombres').val(dato.cliNombres).attr("disabled", 'true');
				$('#txtDireccion').val(dato.cliDireccion).attr("disabled", 'true');
				$('#txtCorreo').val(dato.cliCorreo).attr("disabled", 'true');
				$('#txtCelular').val(dato.cliCelular).attr("disabled", 'true');
			})
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
})
function limpiarCasillas(){
	$('#tabRegistro input[type="text"]').val('');
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('#dtpFechaVencimiento').val(moment().add(1, 'days').format('DD/MM/YYYY'));
	
	$('#txtMontoEntregado').val('0.00');
	$('#txtMontoInteres').val('4');
	$('#txtObservaciones').val('');
}
function verificarTodoRellenado(){
	var estado = false; //||  $('#txtDireccion').val()=='' ||  $('#txtCelular').val()=='' 
	if ( $('#txtApellidos').val()=='' ||  $('#txtNombres').val()=='' ||   $('#txtDni').val()=='' ){
		$('#lblFalta').text('Falta ingresar algunos datos importantes del Cliente, por favor revíselos'); $('.modal-faltaCompletar').modal('show'); estado= false;}
	else if($('#txtNombreProducto').val()==''){$('#lblFalta').text('Estas olvidando poner un nombre al producto que se adquiere'); $('.modal-faltaCompletar').modal('show'); estado= false;}
	else if($('#txtMontoEntregado').val()=='' || $('#txtMontoEntregado').val()<=0 ){$('#lblFalta').text('Estas olvidando poner el monto entregado al producto que se adquiere'); $('.modal-faltaCompletar').modal('show'); estado= false;}
	else if($('#txtMontoInteres').val()=='' ||$('#txtMontoInteres').val()<=0 ){$('#lblFalta').text('Estas olvidando poner el interés semanal al producto que se adquiere'); $('.modal-faltaCompletar').modal('show'); estado= false;}
	else if($('#dtpFechaInicio').val()=='' ){$('#lblFalta').text('Estas olvidando poner poner una fecha inicial al producto que se adquiere'); $('.modal-faltaCompletar').modal('show'); estado= false;}
	else if($('#dtpFechaVencimiento').val()=='' || $('#dtpFechaInicio').val()==$('#dtpFechaVencimiento').val() ){$('#lblFalta').text('Hay un problema con la fecha final, no puede ser igual o menor a la fecha inicial'); $('.modal-faltaCompletar').modal('show'); estado= false;}
	else{ estado = true;}
	return estado;
}
$('#txtMontoEntregado').focusout(function () {
	calcularPeriodo();
	
});
$('#txtMontoInteres').focusout(function () {
	calcularPeriodo();
})
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e){
	 var target = $(e.target).attr("href") // activated tab
	 if(target=='#tabBusqueda'){
		// rellenarTabBusqueda();
	 }
	 if(target=='#tabDetalle'){
		
		
		$('#divProdAVencerse').children().remove();
		$.ajax({url: ' php/listarProductosVencidos.php', type: 'POST', data: {idSucursal: $.idOficina} }). done(function (resp) {
			var montSimpleAcum=0, montIntAcum=0;
			var dato = JSON.parse(resp); //console.log(dato)
			$('#cuadroPanelVencidos').find('.spandia').text(moment().format('DD/MM/YYYY'));
			$('#cuadroPanelVencidos').find('.spanCuadrProd').text(dato.length);
			$('#divSoloParaPrint').children().remove()

			$.each(dato, function (i, elem) { moment.locale('es'); //console.log(elem)
				var limite;
				if(elem.ultimoPago=='0000-00-00'){
					limite= moment(elem.desFechaContarInteres);;
				}else{
					limite=moment(elem.ultimoPago);
				}
				
				var hoy = moment();
				var fechaIni =moment(elem.desFechaContarInteres);
				respu=calculoIntereses(fechaIni, hoy, elem.prodMontoEntregado, 4);
				//console.log(respu)
				montSimpleAcum+=parseFloat(elem.prodMontoEntregado);
				montIntAcum+=parseFloat(respu[1].monto)

				$('#divProdAVencerse').append(`<div class="row">
			<div class="col-xs-4 mayuscula"><strong class="visible-xs-block hidden-print">Producto: </strong>${elem.idproducto}. ${elem.prodNombre}</div>
			<div class="col-xs-1"><strong class="visible-xs-block hidden-print">Monto: </strong>${parseFloat(elem.prodMontoEntregado).toFixed(2)}</div>
			<div class="col-xs-1"><strong class="visible-xs-block hidden-print">Monto: </strong>${parseFloat(respu[1].monto).toFixed(2)}</div>
			<div class="col-xs-3 mayuscula"><strong class="visible-xs-block hidden-print">Dueño: </strong>${elem.propietario}</div>
			<div class="col-xs-2 mayuscula"><strong class="visible-xs-block hidden-print">Fecha: </strong><span class="hidden-print">${limite.format('DD MMMM YYYY')}</span> <span class="visible-print-block">${limite.format('DD/MM/YYYY')}</span></div>
			<span class="col-xs-1 push-right hidden-print"> <a class="btn btn-negro btn-outline btnIcono" href="aplicativo.php?idprod=${elem.idproducto}"><i class="icofont icofont-eye"></i></a> </span>
			</div>`);

			});
			$('#cuadroPanelVencidos').find('.spanCuadrSumaMont').text('S/. '+ parseFloat( montSimpleAcum).toFixed(2));
			$('#cuadroPanelVencidos').find('.spanCuadrSumaInt').text('S/. '+ parseFloat( montIntAcum).toFixed(2));
			$('#divSoloParaPrint').html(`<h2>Listado de artículos Vencidos <small>Oficina: Las Retamas</small></h2><div class="row"><strong>
			<div class="col-xs-4">Nombre del artículo</div>
			<div class="col-xs-1">Monto S/.</div>
			<div class="col-xs-1">Más interés</div>
			<div class="col-xs-3">Dueño</div>
			<div class="col-xs-2">Fecha Registro</div></strong>
			</div>`+$('#divProdAVencerse').html());
		});
	 }
	 if(target=='#tabNoFinalizados'){
		$.ajax({url: 'php/listarTodosProductosNoVencidos.php', type: 'POST', data: {idSucursal: $.idOficina} }).done(function (resp) {
			//console.log(JSON.parse(resp))
			moment.locale('es');
			var montSimpleAcum=0, montIntAcum=0;
			$('#divNoFinalizados').children().remove();
			//, spanCuadrSumaMont, spanCuadrSumaInt
			var dato = JSON.parse(resp);
			$('#cuadroPanelNoFinalizados').find('.spandia').text(moment().format('DD/MM/YYYY'));
			$('#cuadroPanelNoFinalizados').find('.spanCuadrProd').text(dato.length);
			$('#divSoloParaPrint').children().remove();

			$.each(JSON.parse(resp), function (i, dato) {
				var hoy = moment();
				var fechaIni =moment(dato.prodFechaInicial);
				respu=calculoIntereses(fechaIni, hoy, dato.prodMontoEntregado, dato.prodInteres);
				//console.log(respu);
				montSimpleAcum+=parseFloat(dato.prodMontoEntregado);
				montIntAcum+=parseFloat(respu[1].monto);
				$('#divNoFinalizados').append(`<div class="row"><div class="col-xs-3 mayuscula">${i+1}. ${dato.cliApellidos}, ${dato.cliNombres}</div>
			<div class="col-xs-2"><span class="hidden-print">${moment(dato.prodFechaInicial).format('DD MMMM YYYY')}</span> <span class="visible-print-block">${moment(dato.prodFechaInicial).format('DD/MM/YYYY')}</span></div>
			<div class="col-xs-2 hidden">${moment(dato.prodFechaVencimiento).format('DD MMMM YYYY')}</div>
			<div class="col-xs-1 sr-only">${dato.cliCelular}</div>
			<div class="col-xs-1">${parseFloat(dato.prodMontoEntregado).toFixed(2)}</div>
			<div class="col-xs-1 hidden-print">${parseFloat(respu[1].monto).toFixed(2)}</div>
			<div class="col-xs-5 col-sm-4 mayuscula">${dato.prodNombre} <a class="btn btn-negro btn-outline btnIcono pull-right hidden-print" href="aplicativo.php?idprod=${dato.idProducto}"><i class="icofont icofont-eye"></i></a></div></div>`);
			});
			$('#cuadroPanelNoFinalizados').find('.spanCuadrSumaMont').text('S/. '+ parseFloat( montSimpleAcum).toFixed(2));
			$('#cuadroPanelNoFinalizados').find('.spanCuadrSumaInt').text('S/. '+ parseFloat( montIntAcum).toFixed(2));
			
			$('#divSoloParaPrint').html(`<h2>Listado de artículos no finalizados <small>Oficina: Las Retamas</small></h2><div class="row"><strong>
			<div class="col-xs-3">Apellidos y Nombres</div>
			<div class="col-xs-2">Registro</div>
			<div class="col-xs-2 hidden">Caduca</div>
			<div class="col-xs-1" style="padding-left:5px">Entregado</div>
			<div class="col-xs-4">Garantía</div></strong></div>`+$('#divNoFinalizados').html());
		});
	 }
	 if(target=='#tabTodosProductos'){
		llamarProductosPaginado(1,30);

	 }
	 if(target=='#tabCrearUsuario'){
		$.ajax({url: 'php/listarTodosUsuarios.php', type: 'POST'}).done(function (resp) {
			//console.log(JSON.parse(resp))
			moment.locale('es');
			$('#divListadoUser').children().remove();
			$.each(JSON.parse(resp), function (i, dato) {
				$('#divListadoUser').append(`<p><button class="btn btn-negro btn-xs btn-outline btnEditarUser"><i class="icofont icofont-key"></i></button> <button class="btn btn-danger btn-xs btn-outline btnEliminarUser"><i class="icofont icofont-minus-square"></i></button> <span class="sr-only idUser">${dato.idUsuario}</span> <span style="margin-left: 15px"> ${i+1}.</span> <strong class="mayuscula">${dato.nombre}</strong> asignado a «${dato.sucLugar}» sucursal con nivel: «${dato.descripcion}»</p>`);
				
			});
			
		});
	 }
	 if(target=='#tabCrearOficina'){
		$.ajax({url: 'php/listarSucDisplay.php', type:'POST'}).done(function (resp) {
			$('#divListadoOffi').children().remove();
			$.each(JSON.parse(resp), function (i, dato) {
				$('#divListadoOffi').append(`<p><button class="btn btn-negro btn-xs btn-outline"><i class="icofont icofont-key"></i></button> <button class="btn btn-danger btn-xs btn-outline btnEliminarOffice"><i class="icofont icofont-minus-square"></i></button> <span class="sr-only idSuc">${dato.idSucursal}</span> <span style="margin-left: 15px"> ${i+1}.</span> <strong class="mayuscula">${dato.sucNombre}</strong> ubicado en: «${dato.sucLugar}».</p>`);
				
			});
		});
	 }

	 if(target=='#tabAprobarFinalizados'){
		$.ajax({url: 'php/listarProductosPorAprobar.php', type:'POST', data: {oficina: $.idOficina}}).done(function (resp) {
			$('#divListaPorFinalizar').children().remove();
			var fechita=''; 
			$.each(JSON.parse(resp), function (i, dato) {
				moment.locale('es')
				if(moment(dato.prodFechaFinaliza,'').format('dddd[,] DD MMMM YYYY [a las] hh:mm')=='Fecha no válida'){fechita=''}else{fechita=moment(dato.prodFechaFinaliza,'').format('dddd[,] DD MMMM YYYY [a las] hh:mm')}

				$('#divListaPorFinalizar').append(`<div class="row">
					<div class="col-sm-5 mayuscula"><strong>${i+1}.</strong> ${dato.prodNombre}</div>
					<div class="col-sm-2">S/. ${parseFloat(dato.prodCuantoFinaliza).toFixed(2)}</div>
					<div class="col-sm-2">${dato.prodQuienFinaliza}</div>
					<div class="col-sm-2">${fechita}</div>
					<div class="col-sm-1"><a class="btn btn-morado btn-outline btnIcono btnBotonPorConcluir" ><i class="icofont icofont-fire-burn"></i></a></div>
					</div>`);
				
			});
		});
	 }

	 if(target=='#tabConfirmarMovimientos'){
		$.ajax({url:'php/listarMovimientosSinAprobar.php', type: 'POST', data:{oficina: $('#cmbOficinasTotal').val()}}).done(function (resp) {
			//console.log(JSON.parse(resp));
			$('#divListaPorConfirmar').children().remove();
			$.each(JSON.parse(resp), function (i, elem) {

				//console.log(elem)
				var boton='';
				if(elem.idDetalleReporte==2||elem.idDetalleReporte==1){
					boton=`<button class="btn btn-success btn-outline btnAceptarReporteGuar" style="padding: 6px 6px;"><i class="icofont icofont-check"></i> Aceptar</button>`;
				}
				else if(elem.idDetalleReporte==3){
					boton=`<button class="btn btn-morado btn-outline btnRetirarReporteGuar" style="padding: 6px 3px;"><i class="icofont icofont-exchange"></i> Retirado</button>
						<button class="btn btn-danger btn-outline btnRematarReporteGuar" style="padding: 6px 3px;"><i class="icofont icofont-sale-discount"></i> Rematado</button>`;
				}else{boton=`<button class="btn btn-success btn-outline btnAceptarReporteGuar" style="padding: 6px 6px;"><i class="icofont icofont-check"></i> Aceptar</button>`;}
				$('#divListaPorConfirmar').append(`
					<div class="row">
					<div class="idRowReporte sr-only">${elem.idReporte}</div>
					<div class="col-sm-3 mayuscula nomProd"><button class="btn btn-xs btn-primary btn-outline btn-verInfoFinalizado" id="${elem.idProducto}"><i class="icofont icofont-eye"></i></button> ${elem.prodNombre}</div>
					<div class="col-sm-1">S/. ${parseFloat(elem.repoValorMonetario).toFixed(2)}</div>
					<div class="col-sm-1 nomUsr">${elem.repoUsuario}</div>
					<div class="col-sm-2">${elem.repoFechaOcurrencia}</div>
					<div class="col-sm-2">${elem.repoDescripcion}</div>
					<div class="col-sm-2 botones">
						${boton}
					</div>
					</div>`)
			})
		});
	 }
	 if(target=='#tabCajaCuadre'){
		$('#h1Usuario').text($.JsonUsuario.usuapellido+', '+$.JsonUsuario.usunombres)
	 }
});

$('#divListaPorConfirmar').on('click', '.btnAceptarReporteGuar', function () {
	var padre= $(this).parent().parent();
	//console.log(padre.find('.idRowReporte').text());
	$('.modal-EstaSeguroAceptarMovimiento').find('.idSelecRow').text(padre.find('.idRowReporte').text());
	$('.modal-EstaSeguroAceptarMovimiento').find('.strProd').text(padre.find('.nomProd').text());
	$('.modal-EstaSeguroAceptarMovimiento').find('.strUser').text(padre.find('.nomUsr').text());
	$('.modal-EstaSeguroAceptarMovimiento').modal('show');
});
$('#divListaPorConfirmar').on('click', '.btnRetirarReporteGuar', function () {
	var padre= $(this).parent().parent();
	//console.log(padre.find('.idRowReporte').text());
	$('.modal-EstaSeguroRetirarMovimiento').find('.idSelecRow').text(padre.find('.idRowReporte').text());
	$('.modal-EstaSeguroRetirarMovimiento').find('.strProd').text(padre.find('.nomProd').text());
	$('.modal-EstaSeguroRetirarMovimiento').find('.strUser').text(padre.find('.nomUsr').text());
	$('.modal-EstaSeguroRetirarMovimiento').modal('show');
});
$('#divListaPorConfirmar').on('click', '.btnRematarReporteGuar', function () {
	var padre= $(this).parent().parent();
	//console.log(padre.find('.idRowReporte').text());
	$('.modal-EstaSeguroRematarMovimiento').find('.idSelecRow').text(padre.find('.idRowReporte').text());
	$('.modal-EstaSeguroRematarMovimiento').find('.strProd').text(padre.find('.nomProd').text());
	$('.modal-EstaSeguroRematarMovimiento').find('.strUser').text(padre.find('.nomUsr').text());
	$('.modal-EstaSeguroRematarMovimiento').modal('show');
});
$('#btnAceptarMovimientoModal').click(function () {
	var idCamb= $(this).parent().parent().find('.idSelecRow').text();
	$.ajax({url:'php/updateMovimientoAceptar.php', type:'POST', data:{idRepo: idCamb, idUser: $.JsonUsuario.nombre }}).done(function (resp) {
	 if(resp==1){
		$('.modal-EstaSeguroAceptarMovimiento').modal('hide');
		$('#divListaPorConfirmar .idRowReporte').each(function (i, elem ) { //console.log(elem)
			if($(elem).text()==idCamb){
				$(this).parent().find('.botones').children().remove();
				$(this).parent().find('.botones').append('<p>Se aceptó el movimiento por <strong>'+$.JsonUsuario.nombre +'</strong> </p>' );
			}
		})
	 }
	});
});
$('#btnRematarMovimientoModal').click(function () {
	var idCamb= $(this).parent().parent().find('.idSelecRow').text();
	$.ajax({url:'php/updateMovimientoRematar.php', type:'POST', data:{idRepo: idCamb, idUser: $.JsonUsuario.nombre }}).done(function (resp) {
	 if(resp==1){
		$('.modal-EstaSeguroRematarMovimiento').modal('hide');
		$('#divListaPorConfirmar .idRowReporte').each(function (i, elem ) { console.log(elem)
			if($(elem).text()==idCamb){
				$(this).parent().find('.botones').children().remove();
				$(this).parent().find('.botones').append('<p>Se registró como rematado por <strong>'+$.JsonUsuario.nombre +'</strong> </p>' );
			}
		})
	 }
	});
});
$('#btnRetirarMovimientoModal').click(function () {
	var idCamb= $(this).parent().parent().find('.idSelecRow').text();
	$.ajax({url:'php/updateMovimientoRetirar.php', type:'POST', data:{idRepo: idCamb, idUser: $.JsonUsuario.nombre }}).done(function (resp) {
	 if(resp==1){
		$('.modal-EstaSeguroRetirarMovimiento').modal('hide');
		$('#divListaPorConfirmar .idRowReporte').each(function (i, elem ) { console.log(elem)
			if($(elem).text()==idCamb){
				$(this).parent().find('.botones').children().remove();
				$(this).parent().find('.botones').append('<p>Producto retirado por <strong>'+$.JsonUsuario.nombre +'</strong> </p>' );
			}
		})
	 }
	});
});

//, , btnAceptarMovimientoModal

$('#divListaPorFinalizar').on('click','.btnBotonPorConcluir',  function () {
	$('.modal-EstaSeguroFinalizar').modal('show');
});

$('#txtMontoEntregado').keyup(function (e) {var code = e.which;
	if(code==13 && $('#txtMontoEntregado').val()!='' && $('#txtMontoEntregado').val()>='0'   ){	e.preventDefault();
		//console.log('enter')
		$('#btnCronogramaPagosVer').click();
	}
});
$('#txtPagarACuenta').keyup(function (e) {var code = e.which;
	if(code==13){	e.preventDefault();
		//console.log('enter')
		$('#btnPagarACuenta').click();
	}
});

$('#txtBuscarPersona').keyup(function (e) {var code = e.which;
	if(code==13 && $('#txtBuscarPersona').val()!=''  ){	e.preventDefault();
		//console.log('enter')
		$('#rowUsuarioEncontrado').children().remove();
		$.ajax({url: 'php/ubicarPersonaProductos.php', type: 'POST', data: {campo:$('#txtBuscarPersona').val() }}).done(function (resp) {
			dato = JSON.parse(resp);// console.log(dato)
			$.each(dato, function(i, elem){
				$('#rowProductoEncontrado').append(`<div class="row"><div class="col-xs-4 mayuscula">${elem.prodnombre}</div>
					<div class="col-xs-3 mayuscula eleNom">${elem.cliapellidos}, ${elem.clinombres}</div>
					<div class="col-xs-2">${moment(elem.prodfecharegistro).format('DD/MM/YYYY')}</div>
					<div class="col-xs-2">S/. ${parseFloat(elem.prodMontoEntregado).toFixed(2)}</div>
					<div class="col-xs-1"><button class="btn btn-negro btn-outline btnSelectProd" id="${elem.idproducto}"><i class="icofont icofont-tick-mark"></i></button></div></div>`)
			});
			$('.modal-mostrarResultadosCliente').modal('show');
		});
		
		
	}
});
$('#txtBuscarPersona2').keyup(function (e) {var code = e.which; 
	if(code==13 && $('#txtBuscarPersona').val()!=''  ){	e.preventDefault();
		//console.log('enter')
		$('#rowUsuarioEncontrado').children().remove();
		$('#rowProductoEncontrado').children().remove();
		$.ajax({url: 'php/listarProductosPorClienteODni.php', type: 'POST', data: {texto:$('#txtBuscarPersona2').val() }}).done(function (resp) {
			//console.log(resp);
			dato = JSON.parse(resp); console.log(dato)
			$.each(dato, function(i, elem){
				$('#rowProductoEncontrado').append(`<div class="row"><div class="col-xs-4 mayuscula">${elem.prodNombre}</div>
					<div class="col-xs-3 mayuscula eleNom">${elem.cliApellidos}, ${elem.cliNombres}</div>
					<div class="col-xs-2">${moment(elem.prodFechaRegistro).format('DD/MM/YYYY')}</div>
					<div class="col-xs-2">S/. ${parseFloat(elem.prodMontoEntregado).toFixed(2)}</div>
					<div class="col-xs-1"><button class="btn btn-negro btn-outline btnSelectProd" id="${elem.idProducto}"><i class="icofont icofont-tick-mark"></i></button></div></div>`);
			});
			$('.modal-mostrarResultadosProducto').modal('show');
		});
		
	}
});
$('#txtBuscarProducto').keyup(function (e) {var code = e.which;
	if(code==13 && $('#txtBuscarProducto').val()!=''  ){	e.preventDefault();
		//console.log('enter')
		var valorTexto=$('#txtBuscarProducto').val();
		$('#rowProductoEncontrado').children().remove();
		if($.isNumeric(valorTexto)){
			$.ajax({url: 'php/listarBuscarIdProducto.php', type: 'POST', data: {campo:$('#txtBuscarProducto').val() }}).done(function (resp) { //console.log(resp)
				dato = JSON.parse(resp); //console.log(dato)
				$.each(dato, function(i, elem){
					$('#rowProductoEncontrado').append(`<div class="row"><div class="col-xs-4 mayuscula">${elem.prodnombre}</div>
						<div class="col-xs-3 mayuscula eleNom">${elem.cliapellidos}, ${elem.clinombres}</div>
						<div class="col-xs-2">${moment(elem.prodfecharegistro).format('DD/MM/YYYY')}</div>
						<div class="col-xs-2">S/. ${parseFloat(elem.prodMontoEntregado).toFixed(2)}</div>
						<div class="col-xs-1"><button class="btn btn-negro btn-outline btnSelectProd" id="${elem.idproducto}"><i class="icofont icofont-tick-mark"></i></button></div></div>`);
				});
				$('.modal-mostrarResultadosProducto').modal('show');
			});
		}else{
			$.ajax({url: 'php/listarBuscarNombreProducto.php', type: 'POST', data: {campo:$('#txtBuscarProducto').val() }}).done(function (resp) { //console.log(resp)
				dato = JSON.parse(resp); //console.log(dato)
				$.each(dato, function(i, elem){
					$('#rowProductoEncontrado').append(`<div class="row"><div class="col-xs-4 mayuscula">${elem.prodnombre}</div>
						<div class="col-xs-3 mayuscula eleNom">${elem.cliapellidos}, ${elem.clinombres}</div>
						<div class="col-xs-2">${moment(elem.prodfecharegistro).format('DD/MM/YYYY')}</div>
						<div class="col-xs-2">S/. ${parseFloat(elem.prodMontoEntregado).toFixed(2)}</div>
						<div class="col-xs-1"><button class="btn btn-negro btn-outline btnSelectProd" id="${elem.idproducto}"><i class="icofont icofont-tick-mark"></i></button></div></div>`);
				});
				$('.modal-mostrarResultadosProducto').modal('show');
			});
		}
	}
});
$('.modal-mostrarResultadosProducto').on('click', '.btnSelectProd', function () {
	window.location.href = "aplicativo.php?idprod=" + $(this).attr('id');
});
$('#rowUsuarioEncontrado').on('click', '.btnSelectUser', function () {
	//console.log($(this).attr('id'));
	var indice=$(this).parent().parent().index();
	var nom= $('.rowEnc').eq(indice).find('.eleNom').text() ;
	
	$('#spanIdCliente').text($('.rowEnc').eq(indice).find('.eleIdCli').text());
	$('#spanApellido').text( nom.split(',')[0] );
	$('#spanNombre').text( nom.split(',')[1] );
	$('#spanDni').text( $('.rowEnc').eq(indice).find('.eleDni').text());
	$('#spanDireccion').text( $('.rowEnc').eq(indice).find('.eleDire').text());
	$('#spanCorreo').text( $('.rowEnc').eq(indice).find('.eleCorr').text());
	$('#spanCelular').text( $('.rowEnc').eq(indice).find('.eleCel').text());

	$('#rowWellFijo').addClass('hidden');
	$('#rowWellCambiante').removeClass('hidden');
	$('#rowWellCambiante').children().remove();

	$.ajax({ url: 'php/solicitarProductoPorCliente.php', type: 'POST', data: {idCli: $('.rowEnc').eq(indice).find('.eleIdCli').text() }}). done(function (resp) {
		//console.log(JSON.parse(resp))
		moment.locale('es');
		$.each(JSON.parse(resp), function (i, dato) { //console.log(dato);
			//var respu= calculoIntereses(moment(dato.prodFechaInicial), moment(), dato.prodMontoDado, dato.prodInteres )

			var hoy = moment();
			var fechaIni =moment(dato.prodFechaInicial);
			var fechaFin=moment(dato.prodFechaVencimiento);
			var differencia=hoy.diff(fechaIni, 'days');
			console.log('Tantos dias desde fecha incial a hoy: '+ differencia);
			if(parseInt(differencia)>30){
				$('#contenedorExpiro').removeClass('sr-only');
			}else{
				$('#contenedorVigente').removeClass('sr-only');
			}
		
			var intRS;
			if( hoy.diff(fechaIni, 'days') <=14){
				respu=calculoIntereses(fechaIni, hoy, dato.prodMontoEntregado, 10);
				intRS=10;
			}
			else{
				respu=calculoIntereses(fechaIni, hoy, dato.prodMontoEntregado, dato.prodInteres);
				intRS=dato.prodInteres;
			}

			var obsDin='';
			var intge=parseFloat(parseFloat(respu[1].monto).toFixed(2)-parseFloat(dato.prodMontoDado)).toFixed(2);
			if( dato.idSucursal==  <?php echo $_SESSION['idSucursal']  ?> || <?php echo $_SESSION['Power']  ?>==1 ){ console.log('misma sucursal');
				if(dato.prodObservaciones==''){ obsDin='Ninguna'} else{ obsDin= dato.prodObservaciones}
			
				
				//$('#spanPagar').text(parseFloat(respu[1].monto).toFixed(2));
				//$('#spanIntGenerado').text(parseFloat(parseFloat(respu[1].monto).toFixed(2)-parseFloat(dato.prodMontoDado)).toFixed(2));
				var botonAgregar='';
				if(parseInt(dato.prodActivo) ==1){
					botonAgregar = `<div class="col-sm-8 col-sm-offset-2">
					<button class="btn btn-morado btn-outline btn-imprimirTicketMovil"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
					<button class="btn btn-indigo btn-outline btn-FinalizarImpuestoMovil"><i class="icofont icofont-pie-chart"></i> Cancelar interés</button>
					<button class="btn btn-indigo btn-outline btn-AdelantoPrestamoMovil"><i class="icofont icofont-rocket"></i> Adelantar pago</button>
					<button class="btn btn-danger btn-outline btn-FinalizarPrestamoMovil"><i class="icofont icofont-rocket"></i> Finalizar préstamo</button>
				</div>`;
				}else{
					botonAgregar = `
					<div class="col-xs-12" >
						<div class="alert-message alert-message-morado">
								<h4>Producto finalizado</h4>
								<p>por: <strong><span class="mayuscula" id="QuienFinalizo">${dato.prodQuienFinaliza}</span></strong>, el día: <strong><span id="FechaFinalizo">${moment(dato.prodFechaFinaliza).format('LL')}</span></strong>, por el monto: S/. <span id="finalizaMonto">${parseFloat(dato.prodCuantoFinaliza).toFixed(2)}</span> estado de aprobación: <strong><span id="QuienAprueba">Todavía sin aprobación</span></strong>.</p>
						</div>
					</div>
					<div class="col-sm-8 col-sm-offset-2">
					<button class="btn btn-morado btn-outline btn-imprimirTicketMovil"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
				</div>`;}

				$('#rowWellCambiante').append(`<div class="row well" >
				<span class="hidden" id="lblIdProductosEnc">${dato.idProducto}</span>
				<div class="col-sm-6"><label><i class="icofont icofont-cube"></i> Producto: </label> <span class="text-success mayuscula" id="spanProducto">${dato.prodNombre}</span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-ui-tag"></i> Interés: </label> <span class="text-success"><span id="spanPorcent">${parseFloat(respu[1].acumulado).toFixed(0)}</span>%</span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de inicio: </label> <span class="text-success" id="spanFechaInicio">${moment(dato.prodFechaInicial).format('dddd[,] DD MMMM YYYY')}</span> <span class="sr-only" id="spanFechaInicioNum">${dato.prodFechaInicial}</span></div>
				<div class="col-sm-6 hidden"><label><i class="icofont icofont-tasks-alt"></i> Fecha de límite de pago: </label> <span class="text-success" id="spanFechaFin">${moment(dato.prodFechaVencimiento).format('dddd[,] DD MMMM YYYY')}</span> <span class="sr-only" id="spanFechaFinNum">${dato.prodFechaVencimiento}</span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Registrado: </label> <span class="text-success" id="spanPeriodo2">${respu[0].periodo}</span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Monto entregado: </label> <span class="text-success">S/. <span id="spanMontoDado">${parseFloat(dato.prodMontoDado).toFixed(2)}</span></span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Intereses generados: </label> <span class="text-success">S/. <span id="spanIntGenerado">${intge}</span></span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Total al día de hoy: </label> <span class="text-success">S/. <span id="spanPagar">${parseFloat(respu[1].monto).toFixed(2)}</span></span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Observaciones: </label> <em><span class="text-success mayuscula" id="spanObservacion"> ${obsDin}</span></em></div>
				<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Adelantos: </label> <span class="text-success">S/. <span id="spanAdelanto">${parseFloat(dato.prodAdelanto).toFixed(2)}</span></span></div>			
				${botonAgregar}
			</div>`);
		}
			else{console.log('otra sucursal');
			$('#rowWellCambiante').append(`<div class="alert alert-danger ">
				<i class="icofont icofont-animal-cat-alt-4" style="font-size:24px"></i> <strong>¡Oh lo sentimos!</strong> Éste producto está asignado a la oficina «${dato.sucNombre}».
						</div>`);}

			
		})
	})

	$('.modal-mostrarResultadosCliente').modal('hide');

});
$('#btn-imprimirTicketFijo').click(function () {
	moment.locale('es');
	//console.log($('#rowWellFijo #spanProducto').text()) 123
	//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'}); http://192.168.1.107
	$.ajax({url: 'http://192.168.1.133/perucash/printTicket.php', type: 'POST', data: {
		cod: <?php if(isset($_SESSION['idprod'])){ echo $_GET['idprod']; } else {echo 0;}?>,
		cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
		articulo: $('#rowWellFijo #spanProducto').text(),
		monto: parseFloat($('#rowWellFijo #spanSrCapital').text()).toFixed(2),
		obs: $('#rowWellFijo #spanObservacion').text(),
		hora : moment().format('h:mm a dddd DD MMMM YYYY'),
		usuario: $.JsonUsuario.usunombres
	}}).done(function(resp){console.log(resp);});
});
$('#btn-FinalizarPrestamoFijo').click(function () {
	var iProd=$('#rowWellFijo #lblIdProductosEnc').text();
	$('#idEstaseguro').text(iProd);
	$('#montoSeguro').text($('#rowWellFijo #spanPagar').text());
	$('.modal-EstaSeguro').modal('show');

});
$('#btn-RetirarPrestamoFijo').click(function () {
	var contenedor=$(this).parent().parent();
	var iProd=contenedor.find('#lblIdProductosEnc').text();
	//console.log(iProd);
	$('#spanModalRetirarProducto').text(contenedor.find('#spanProducto').text());
	$('#spanCapitalRetiro').text(contenedor.find('#spanSrCapital').text());
	$('#spanValorizadoRetirar').text(contenedor.find('#spanMontoDado').text());
	$('#idPrestamoAct').text(iProd);
	$('.modal-retirarPrestamoConfirmar').modal('show');
});
$('#btn-FinalizarImpuestoFijo').click(function () {
	var iProd=$('#rowWellFijo #lblIdProductosEnc').text();
	$('#idInteresSeguro').text(iProd);
	$('#spanInteresSeguro').text($('#rowWellFijo #spanIntGenerado').text());
	$('#srProductoSeg').text($('#rowWellFijo #spanProducto').text());
	$('#srNombreSeg').text($('#spanApellido').text()+', '+$('#spanNombre').text());
	
	$('.modal-InteresSeguro').modal('show');
});
$('#rowWellCambiante').on('click', '.btn-FinalizarImpuestoMovil', function () {
	var contenedor = $(this).parent().parent();
	//var indice = $(this).parent().parent().index();
	var iProd=contenedor.find('#lblIdProductosEnc').text();

	$('#idInteresSeguro').text(iProd);
	$('#spanInteresSeguro').text(contenedor.find('#spanIntGenerado').text());
	$('#srProductoSeg').text(contenedor.find('#spanProducto').text());
	$('#srNombreSeg').text($('#spanApellido').text()+', '+$('#spanNombre').text());
	$('.modal-InteresSeguro').modal('show');

});
$('#btnModalRetirarPrestamo').click(function () {
	var cuantoPaga=$('#txtModalRetirarPrestamo').val();
	if(cuantoPaga<0){ $('#pErrorRetirarPrestamo').removeClass('sr-only').text('No se puede ingresar valores negativos.');}
	else if(! $('#btnModalRetirarPrestamo').hasClass('disabled') ) {
		//guardar
		$('#btnModalRetirarPrestamo').addClass('disabled');
		$('#pErrorRetirarPrestamo').addClass('sr-only');
		var idSucu=$.JsonUsuario.idsucursal;
		if(idSucu==3){idSucu=$('#cmbOficinasTotal').val();}
		$.ajax({url:'php/updateFinalizarPrestamo.php', type: 'POST', data:{idProd: $('#idPrestamoAct').text() , monto:$('#spanCapitalRetiro').text() , idUser: $.JsonUsuario.idUsuario, valor: $('#spanValorizadoRetirar').text(),idSuc:idSucu, usuario:$.JsonUsuario.usunombres, paga: cuantoPaga, comentario: $('#txtComentarioextraPrestamo').val() }}).done(function (resp) { console.log(resp)
			$('.modal-InteresSeguro').modal('hide');
			if(resp=='1'){
				window.location.href = "aplicativo.php?idprod=" +$('#idPrestamoAct').text();
			}else{
				$('.modal-retirarPrestamoConfirmar').modal('hide');
				$('.modal-datoNoGuardado').modal('show');
			}
		});
	}
});
$('#btnSeguroFinalizar').click(function () {
	var iProd=$('#idEstaseguro').text();
	var valor=parseFloat($('#montoSeguro').text());

	//console.log(iProd)
	
	$.ajax({url:'php/updateFinalizarEstado.php', type: "POST", data: {idProd: iProd, monto: valor, nombreUser: $.JsonUsuario.usunombres }}).done(function (resp) { //console.log(resp)
		if(parseInt(resp)==1){
			//$('#btn-FinalizarPrestamoFijo').addClass('hide');
			$('.modal-EstaSeguro').modal('hide');
	}
		// body...
	});

	if( !$('#rowWellFijo').hasClass('hidden')){ console.log( 'Buscar Datos en fijo: '+ $('#rowWellFijo #spanProducto').text());
		$.ajax({url: 'http://localhost/perucash/printTicketFinalizado2.php', type: 'POST', data: {
			cod: $('#spanCodProd').text(),
			cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
			articulo: $('#rowWellFijo #spanProducto').text(),
			monto: parseFloat($('#rowWellFijo #spanPagar').text()).toFixed(2),
			obs: $('#rowWellFijo #spanObservacion').text(),
			hora : moment().format('h:mm a dddd DD MMMM YYYY'),
			usuario: $.JsonUsuario.nombre
		}}).done(function(resp){console.log(resp);});
		}
	if( !$('#rowWellCambiante').hasClass('hidden')){
		$.each($('#rowWellCambiante #lblIdProductosEnc'), function (i, elem) {
			if($(elem).text()==iProd){
					
				$.ajax({url: 'http://localhost/perucash/printTicketFinalizado2.php', type: 'POST', data: {
					cod: $('#spanCodProd').text(),
					cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
					articulo: $(elem).parent().find('#spanProducto').text(),
					monto: parseFloat( $(elem).parent().find('#spanPagar').text() ).toFixed(2),
					obs: $(elem).parent().find('#spanObservacion').text(),
					hora : moment().format('h:mm a dddd DD MMMM YYYY'),
					usuario: $.JsonUsuario.nombre
				}}).done(function(resp){console.log(resp);});

				return false;
			}

		});
	 console.log( 'Buscar Datos en dinamico');
	}

	window.location.href = "aplicativo.php?idprod=" +iProd;
 });

$('#btnInteresFinalizar').click(function () {
	var iProd=$('#idInteresSeguro').text();
	var valor=parseFloat($('#spanInteresSeguro').text());
	var articuloCalc='', montoCalc='';



	if( !$('#rowWellFijo').hasClass('hidden')){ console.log( 'Buscar Datos en fijo: '+ $('#rowWellFijo #spanProducto').text());
		
			articuloCalc= $('#rowWellFijo #spanProducto').text();
			montoCalc= $('#rowWellFijo #spanIntGenerado').text();
		}
	if( !$('#rowWellCambiante').hasClass('hidden')){
		$.each($('#rowWellCambiante #lblIdProductosEnc'), function (i, elem) {
			if($(elem).text()==iProd){
				articuloCalc= $(elem).parent().find('#spanProducto').text();
				montoCalc= parseFloat( $(elem).parent().find('#spanIntGenerado').text() ).toFixed(2);
				return false;
			}

		});
	 console.log( 'Buscar Datos en dinamico');
	}



	$.ajax({url:'php/updateFinalizarInteres.php', type: "POST", data: {idProd: iProd, monto: valor }}).done(function (resp) { console.log(resp)
		if(parseInt(resp)==1){
			//$('#btn-FinalizarPrestamoFijo').addClass('hide');
			$('.modal-EstaSeguro').modal('hide');
	}
		// body...
	});
	//console.log(articuloCalc+'\n'+montoCalc)

	$.ajax({url: 'http://localhost/perucash/printTicketInteres.php', type: 'POST', data: {
	cod: <?php if(isset($_SESSION['idprod'])){ echo $_GET['idprod']; } else {echo 0;}?>,
	cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
	articulo: articuloCalc,
	monto: montoCalc,
	hora : moment().format('h:mm a dddd DD MMMM YYYY'),
	usuario: $.JsonUsuario.nombre
}}).done(function(resp){console.log(resp);});

	window.location.href = "aplicativo.php?idprod=" +iProd;
});

$('#rowWellCambiante').on('click', '.btn-imprimirTicketMovil', function () {
	var contenedor = $(this).parent().parent();
	// console.log('nombre: '+ contenedor.find('#spanProducto').text());

	$.ajax({url: 'http://localhost/perucash/printTicket.php', type: 'POST', data: {
		cod: <?php if(isset($_SESSION['idprod'])){ echo $_GET['idprod']; } else {echo 0;}?>,
		cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
		articulo: contenedor.find('#spanProducto').text(),
		monto: parseFloat(contenedor.find('#spanMontoDado').text()).toFixed(2),
		obs: contenedor.find('#spanObservacion').text(),
		hora : moment().format('h:mm a dddd DD MMMM YYYY'),
		usuario: $.JsonUsuario.nombre
	}}).done(function(resp){console.log(resp);});
});
$('#rowWellCambiante').on('click', '.btn-FinalizarPrestamoMovil', function () {
	var contenedor = $(this).parent().parent();
	var indice = $(this).parent().parent().index();
	var iProd=contenedor.find('#lblIdProductosEnc').text();

	$('#idEstaseguro').text(iProd);
	$('#montoSeguro').text(contenedor.find('#spanPagar').text());
	$('.modal-EstaSeguro').modal('show');

	// $.ajax({url:'php/updateFinalizarEstado.php', type: "POST", data: {idProd: iProd }}).done(function (resp) { console.log(resp)
	// 	if(parseInt(resp)==1){$('#rowWellCambiante').eq(indice).find('.btn-FinalizarPrestamoMovil').addClass('hide'); }
	// });
});

/*$("#btnImprimirNoFinalizados").printPage({
	url: 'reporteNoFinalizados.html',
	attr: 'href',
	message: 'Espere mientras se crea el reporte.'
})*/
$('#btnImprimirNoFinalizados').click(function () {
	// body...
	var codigoCuadro=encodeURIComponent ($('#divNoFinalizados').html());
	//console.log(codigoCuadro)
	urlImpr='demoprint.html?codigoTabla='+codigoCuadro;
	//console.log(urlImpr);
	$('#btnImprimirNoFinalizados').printPage({
		url: urlImpr,
		attr: "href",
		message:"Tu documento está siendo creado"});
});
$('#btnCerrarSesion').click(function () {
	window.location.href = "php/desconectar.php" 
});
$('#txtNombrUser').focusout(function () {
	var nombr= $('#txtNombrUser').val()
	var apellido = $('#txtApellUser').val();
	
	$('#txtNickUser').val( nombr.substr(0,1) + apellido.replace(/\ /g, ''))//reemplaza todos los caracteres 'espacio' por no espacio
});
function variableUsuario(){
	//console.log( $('#cmbNivelSel').val())
	var estado=false;
	if( $('#txtApellUser').val() ==''  || $('#txtNombrUser').val() ==''){$('#lblFalta').text('Rellene los campos de apellidos y nombres.'); $('.modal-faltaCompletar').modal('show');}
	else if( $('#txtNickUser').val() =='' ){$('#lblFalta').text('Falta asignar un nick al usuario');  $('.modal-faltaCompletar').modal('show');}
	else if( $('#txtPassUser').val() ==""){$('#lblFalta').text('No puede dejar vacío el campo de contraseña.');  $('.modal-faltaCompletar').modal('show');}
	else if( $('#txtPassUser').val() != $('#txtPassUser2').val() ){$('#lblFalta').text('Las contraseñas no coinciden.');  $('.modal-faltaCompletar').modal('show');}
	else if( $('#cmbNivelSel').val() ==0 ){$('#lblFalta').text('Seleccione una categoría de Nivel.');  $('.modal-faltaCompletar').modal('show');}
	else if( $('#cmbSucurSel').val() ==0 ){$('#lblFalta').text('Seleccione una categoría de Sucursal.');  $('.modal-faltaCompletar').modal('show');}
	else{estado = true}
	return estado;
}
$('#btnGuardarUsuario').click(function () {
	//console.log('crea')
	if(variableUsuario()){
		$.ajax({ url: 'php/insertarUsuario.php', type: 'POST', data:{
			apellido: $('#txtApellUser').val(),
			nombre: $('#txtNombrUser').val(),
			nick: $('#txtNickUser').val(),
			passw: $('#txtPassUser').val(),
			nivel: $('#cmbNivelSel').val(),
			sucursal: $('#cmbSucurSel').val()
		} }).done(function (resp) {
			if(parseInt(resp)==1){
				$('#divListadoUser').prepend(`<p><button class="btn btn-negro btn-xs btn-outline"><i class="icofont icofont-key"></i></button> <button class="btn btn-danger btn-xs btn-outline"><i class="icofont icofont-minus-square"></i></button> ${$('#txtApellUser').val()} ${$('#txtNombrUser').val()} asignado a «${$('#cmbSucurSel').text}» sucursal</p>`);
				$('.modal-ventaGuardada').modal('show');
			}else{
				$('#lblFalta').text('Con la conexión.');  $('.modal-faltaCompletar').modal('show');
			}
			// body...
		})
	}
	// body...
// 	txtApellUser
// txtNombrUser
// txtNickUser
// txtPassUser
// txtPassUser2
// cmbNivelSel
// cmbSucurSel
});
$('#cmbNivelSel').change(function () {
	if($('#cmbNivelSel').val()==1){ 
		$('#cmbSucurSel').val('3');
	}else {$('#cmbSucurSel').val('0');}
});
function cambiodeOficina(){
	$.ajax({url:'php/contarVencidos.php', type: 'POST', data:{ idSucursal: $.idOficina }}).done(function (resp) { //console.log('resp ' + resp)
		$('#spanContarVenc').text(resp);
		$('#strFaltan').text(resp);
	});
	$.ajax({url:'php/contarNoFinalizados.php', type: 'POST', data:{ idSucursal: $.idOficina }}).done(function (resp) { //console.log('resp ' + resp)
		$('#spanContarNoFin').text(resp);
	});
}
$('#cmbOficinasTotal').change(function () {
	$.idOficina= $('#cmbOficinasTotal').val();
	cambiodeOficina();
});
$('.modal-adelantarPago').on('shown.bs.modal', function() {
	$('#txtAdelantPagoMonto').focus();
})
$('#btn-AdelantoPrestamoFijo').click(function () {
	console.log($(this).parent().parent().find('#lblIdProductosEnc').text())
	$('#spanIdProdxAdelanto').text($(this).parent().parent().find('#lblIdProductosEnc').text());
	$('#pAdelantoMal').addClass('hidden');
	$('#btnIngresarPago').removeClass('disabled');
	$('#idDivDatos').text(0);

	$('#tipoFijooMovil').text('fijo');

	$('.modal-adelantarPago').modal('show');
	
});
$('#rowWellCambiante').on('click', '.btn-AdelantoPrestamoMovil', function () {
	$('#spanIdProdxAdelanto').text($(this).parent().parent().find('#lblIdProductosEnc').text());
	$('#pAdelantoMal').addClass('hidden');
	$('#btnIngresarPago').removeClass('disabled');
	$('#idDivDatos').text($(this).parent().parent().index());
	$('#tipoFijooMovil').text('movil');
	$('.modal-adelantarPago').modal('show');
});
$('#btnCancelarIngreso').click(function () {
	$('.modal-adelantarPago').modal('hide');
});
$('#btnIngresarPago').click(function () {
	
	if($('#btnIngresarPago').hasClass('disabled')){ }
	else{
		$(this).addClass('disabled');
		if($('.modal-adelantarPago input').val()=='' || parseInt($('.modal-adelantarPago input').val())==0 ){ $('#pAdelantoMal').removeClass('hidden')}
		else{$('#pAdelantoMal').addClass('hidden');
		//Guardar data de adelanto
			guardarAdelanto($('.modal-adelantarPago input').val(), $('#spanIdProdxAdelanto').text(), $('#idDivDatos').text());

	}}
});
function guardarAdelanto(cant, produc, indexDatos){
	$.ajax({url:'php/insertarAdelantoAProducto.php', type:'POST', data: {monto: cant, idProd: produc}}).done(function (resp) { //console.log(resp);
		if(resp==1){
			var adela=parseFloat( $('#spanAdelanto').text());
			var dado = parseFloat($('#spanMontoDado').text());
			var articula;
			if($('#tipoFijooMovil').text()=='fijo'){
				$('#rowWellFijo').find('#spanMontoDado').text(parseFloat(dado-cant).toFixed(2));
				$('#rowWellFijo').find('#spanAdelanto').text( parseFloat(adela+parseFloat(cant)).toFixed(2) );
				$('#rowWellFijo').find('#spanObservacion').html( 'Se adelantó S/. '+ cant+' del monto S/. '+dado+' el día '+moment().format('DD/MM/YYYY')+'<br>'+$('#spanObservacion').text());
				$('#rowWellFijo').find('#btnIngresarPago').removeClass('disabled');
				articula=$('#rowWellFijo #spanProducto').text();


			}else{
				$('.divResultadosPorPersona').eq(indexDatos).find('#spanMontoDado').text(parseFloat(dado-cant).toFixed(2));
				$('.divResultadosPorPersona').eq(indexDatos).find('#spanAdelanto').text(parseFloat(adela+parseFloat(cant)).toFixed(2));
				$('.divResultadosPorPersona').eq(indexDatos).find('#spanObservacion').html( 'Se adelantó S/. '+ cant+' del monto S/. '+dado+' el día '+moment().format('DD/MM/YYYY')+'<br>'+$('#spanObservacion').text());			
				$('.divResultadosPorPersona').eq(indexDatos).find('#btnIngresarPago').removeClass('disabled');
				articula=$('.divResultadosPorPersona').eq(indexDatos).find('#spanProducto').text();
			}
			

			
			moment().locale('es');
			/*$.ajax({url: 'http://localhost/perucash/printTicketAdelanto.php', type: 'POST', data: {
				cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
				articulo: articula,
				adelanto: parseFloat(cant).toFixed(2),
				hora : moment().format('h:mm a dddd DD MMMM YYYY'),
				usuario: $('#spanUsuario').text()
			}}).done(function(resp){console.log(resp);
			});*/

			
		}
		else{$('#pAdelantoMal').removeClass('hidden').text('Hubo un problema con la conexión.');}
		$('.modal-adelantarPago').modal('hide');
		window.location.href = "aplicativo.php?idprod=" +produc;
	});

}$('#divListadoUser').on('click', '.btnEliminarUser', function () {
	console.log('limi')
});
$('#btnGuardarOffice').click(function () {
	if(!$('#btnGuardarOffice').hasClass('disabled')){
		$('#btnGuardarOffice').addClass('disabled');
		$.ajax({url: 'php/insertarSucursalNueva.php', type: 'POST', data: {nombre: $('#txtNomOfic').val() , direccion:$('#txtDirecOfic').val() }}).done(function (resp) {
			if(resp==1){
				$('.modal-ventaGuardada').modal('show');
			}else{
				$('#lblFalta').text('Con la conexión.');  $('.modal-faltaCompletar').modal('show');
				$('#btnGuardarOffice').removeClass('disabled');
			}
		});
	}

});
$('#divListadoOffi').on('click', '.btnEliminarOffice', function () {
	var ind= $(this).next().text();
	$.ajax({url: 'php/updateFinalizarSucursal.php', type: 'POST', data: {idSuc: ind}}).done(function (resp) {
		console.log(resp)
		if(resp==1){
			$('.modal-ventaGuardada').modal('show');
		}
	})
});

$('#btnEditarDatoCliente').click(function () {
	$('#txtddni').val($('.divResultadosPorPersona #spanDni').text());
	$('#txtaapellido').val($('.divResultadosPorPersona #spanApellido').text());
	$('#txtnnombre').val($('.divResultadosPorPersona #spanNombre').text());
	$('#txtddireccion').val($('.divResultadosPorPersona #spanDireccion').text());
	$('#txteemail').val($('.divResultadosPorPersona #spanCorreo').text());
	$('#txtccelular').val($('.divResultadosPorPersona #spanCelular').text());

	$('.modal-editarDatosCliente').modal('show');
});
$('#btnActualizarDataCliente').click(function () {
	if(!$('#btnActualizarDataCliente').hasClass('disabled')){
		$('#btnActualizarDataCliente').addClass('disabled');
		$.ajax({url:'php/actualizarDatosCliente.php', type: 'POST', data: {
		iid: $('#spanIdCliente').text(),
		appe:$('#txtaapellido').val(),
		nnomb:$('#txtnnombre').val(),
		ddni:$('#txtddni').val(),
		ddirecion:$('#txtddireccion').val(),
		eemail:$('#txteemail').val(),
		ccelular:$('#txtccelular').val()

	 }}).done(function (resp) { console.log(resp);
		$('.modal-editarDatosCliente').modal('hide');

		if(resp==1){
			$('.divResultadosPorPersona #spanDni').text($('#txtddni').val());
			$('.divResultadosPorPersona #spanApellido').text($('#txtaapellido').val());
			$('.divResultadosPorPersona #spanNombre').text($('#txtnnombre').val());
			$('.divResultadosPorPersona #spanDireccion').text($('#txtddireccion').val());
			$('.divResultadosPorPersona #spanCorreo').text($('#txteemail').val());
			$('.divResultadosPorPersona #spanCelular').text($('#txtccelular').val());
		}
		// body...
	 });
	}
	 
});
$('.pagination li').click(function () {
	//console.log( <?php echo $_SESSION['Power'] ?>)
	
	if(!$(this).hasClass('active')){
		$('.pagination li').removeClass('active');
		moment.locale('es');
		var desde, hasta;
		var aCargar= $(this).children().attr('aria-label');
		console.log(aCargar)
		if(aCargar=='Previous'){//cargar el bloque # 1
			$('.pagination li a[aria-label=1]').parent().addClass('active');
			desde=1; hasta =30;
		}
		else if(aCargar=='Next'){//cargar el úñtimo bloque 
			$(this).prev().addClass('active');
			hasta =$(this).prev().children().attr('aria-label')*30;
			desde=hasta-29;
		}
		else{
			
			$('.pagination li').find('a[aria-label='+aCargar+']').parent().addClass('active');
			hasta = aCargar*30;
			desde=hasta-29;
		}
		//console.log('desde:' +desde + '\n' + 'hasta '+hasta)
		llamarProductosPaginado(desde, hasta);

		
	}
});
function llamarProductosPaginado(desde, hasta){
	var fuente;
	//$('#divTotalProductos').children().remove();
	$('#divTotalProductos').html('<div class="fa-spin text-left"><i class="icofont icofont-spinner"></i> </div>');
	secuencia = desde;
	$.ajax({url:'php/listarProductosTotal.php', type: 'POST', data: {
			desdde: desde-1,
			hastta: hasta,
			idSuc: <?php echo $_SESSION['idSucursal']; ?>
		}
		}).done(function (resp) { //console.log(resp)
			$('#divTotalProductos').html('');
			$.each(JSON.parse(resp), function (i, elem) {
				var limite = moment(elem.prodFechaInicial);
				if(elem.prodActivo=='1'){fuente = 'text-primary'}
				else{fuente = 'text-mute'}
				$('#divTotalProductos').append(`<div class="row ${fuente}">
			<div class="col-sm-3 mayuscula"><strong class="visible-xs-block">Producto: </strong>${secuencia}. ${elem.prodNombre}</div>
			<div class="col-sm-1"><strong class="visible-xs-block">Monto: </strong>${parseFloat(elem.prodMontoEntregado).toFixed(2)}</div>
			<div class="col-sm-2 mayuscula"><strong class="visible-xs-block">Dueño: </strong>${elem.propietario}</div>
			<div class="col-sm-2 mayuscula"><strong class="visible-xs-block">Fecha: </strong><small>${elem.sucNombre}</small></div>
			<div class="col-sm-2 mayuscula"><strong class="visible-xs-block">Fecha: </strong>${moment(elem.prodFechaInicial).format('DD MMMM YYYY')}</div>
			<span class="col-sm-1 push-right"> <a class="btn btn-negro btn-outline btnIcono" href="aplicativo.php?idprod=${elem.idProducto}"><i class="icofont icofont-eye"></i></a> </span>
		</div>`);
				secuencia+=1;
			});
		});
}
$('#btn-imprimirImpresoraFijo').click(function () {
	
	urlImpr='php/reporteVoucherPrestamo.php?idProd='+$(this).parent().parent().find('#lblIdProductosEnc').text();
	//console.log(urlImpr);
	$('#btn-imprimirImpresoraFijo').printPage({
		url: urlImpr,
		attr: "href",
		message:"Tu documento está siendo creado"});

});
$('#divListadoUser').on('click', '.btnEditarUser', function () {
	var idCambiante=  $(this).parent().find('.idUser').text();

	$.ajax({url: 'php/listarUnUsuario.php', type: 'POST', data: {idUs:idCambiante}}).done(function (resp) {
		var dato= JSON.parse(resp);
		console.log(dato);
		$('.modal-editarDatosUsuarios').find('#userModalId').text(idCambiante);
		$('.modal-editarDatosUsuarios').find('#txtUsapellido').val(dato[0].usuApellido);
		$('.modal-editarDatosUsuarios').find('#txtUsnombre').val(dato[0].usuNombres);
		$('.modal-editarDatosUsuarios').find('#txtUsnick').val(dato[0].usuNick);
		$('.modal-editarDatosUsuarios').find('#cmbLvlUser').val(dato[0].idPoder);
		$('.modal-editarDatosUsuarios').find('#cmbOficinasUser').val(dato[0].idSucursal);
		$('.modal-editarDatosUsuarios').modal('show');
	});
	
});
$('#btnAsignarNuevaPassUsr').click(function () {
	$('#cambContraseña').removeClass('hidden');
	$(this).addClass('hidden');
});
$('#btnActualizarDataUser').click(function () { console.log($('#cmbLvlUser').val());
	if($('#btnAsignarNuevaPassUsr').hasClass('hidden')){
		if($('#txtUsPss1').val()=='' || $('#txtUsPss2').val()=='' ){
			$('.modal-editarDatosUsuarios .alert').removeClass('hidden');
			$('.modal-editarDatosUsuarios .spanError').text('Debe ingresar las contraseñas');
		}
		else if($('#txtUsPss1').val()!=$('#txtUsPss2').val()){
			$('.modal-editarDatosUsuarios .alert').removeClass('hidden');
			$('.modal-editarDatosUsuarios .spanError').text('Las contraseñas no son iguales');
		}
		else{
			$('.modal-editarDatosUsuarios .alert').addClass('hidden');
			$.ajax({url: 'php/updateUserDatosConPass.php', type: 'POST', data: {nombre: $('#txtUsnombre').val(), apellido: $('#txtUsapellido').val(), nick: $('#txtUsnick').val(), pass:  $('#txtUsPss1').val(), poder: $('#cmbLvlUser').val(), sucursal: $('#cmbOficinasUser').val() , idUser: $('#userModalId').text()  }}).done(function (resp) { console.log(resp);
				
			})

		}
		
	}
	else{
		$('.modal-editarDatosUsuarios .alert').addClass('hidden'); 
	}
		
	
});

$('#btnCambiarMiContras').click(function () {
	$('.modal-cambiarMiContraseña').modal('show');
});
$('#btnCambiarSeguroPass').click(function () {
	if($.antPassIsOk==true && $.CoincideNuevPass==true){
		//console.log('guardar')
		$.ajax({url: 'php/updatePassSinDatos.php', type: 'POST', data: {texto: $('#txtMiPassNuevo').val() }}).done(function (resp) {
			if (resp=='1'){
				$('.modal-cambiarMiContraseña').modal('hide');
			}
			
		});
	}
	else{
		$('.modal-cambiarMiContraseña .text-danger').text('Tienes algun dato erroneo, revísalo.')}
});
$('#txtMiPassAnt').focusout(function () {
	$.ajax({url: 'php/coincidePass.php', type: 'POST', data: {texto: $('#txtMiPassAnt').val()}}).done(function (resp) {
		var nuev=JSON.parse(resp)
		if(nuev[0].result==0){
			$.antPassIsOk=false;
			//$('#btnCambiarSeguroPass').addClass('disabled');
			$('.modal-cambiarMiContraseña .text-danger').text('No coincide tu contraseña anterior.').removeClass('hidden');
		}else{
			$.antPassIsOk=true;
			//$('#btnCambiarSeguroPass').removeClass('disabled');
			$('.modal-cambiarMiContraseña .text-danger').addClass('hidden');
		}
	
});
});
$('#txtMiPassNuevo2').focusout(function () {
	if($('#txtMiPassNuevo').val()!= $('#txtMiPassNuevo2').val()){
		$('.modal-cambiarMiContraseña .text-danger').text('Las nuevas contraseñas no coinciden.').removeClass('hidden');
		$.CoincideNuevPass=false;
		//$('#btnCambiarSeguroPass').addClass('disabled');
	}else{
		//$('#btnCambiarSeguroPass').removeClass('disabled');
		$.CoincideNuevPass=true;
		$('.modal-cambiarMiContraseña .text-danger').addClass('hidden');
	}
});
$('#someSwitchOptionWarning').change(function (e) {
	if($(this).is(':checked')){//true
		$('#lblSWQueEs').text('Producto como compra sin intereses').css('color', '#5CB85C');
		$('#dtpFechaVencimiento').attr("disabled", 'true');
		$('#divSimulado').addClass('sr-only');
		$('#btnGuardarDatos').addClass('sr-only');
		$('#btnGuardarCompra').removeClass('sr-only');


	}else{//false
		$('#lblSWQueEs').text('Artículo de empeño con intereses.').css('color', '#606bdc');
		$('#dtpFechaVencimiento').removeAttr("disabled");
		$('#divSimulado').removeClass('sr-only');
		$('#btnGuardarDatos').removeClass('sr-only');
		$('#btnGuardarCompra').addClass('sr-only');
	}
	$('#txtNombreProducto').focus();
});
$('#btnGuardarCompra').click(function () { //console.log($('#txtNombreProducto').val())
	if(verificarTodoRellenado()){
		var fechav3= moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')+' ' + moment().format('HH:mm:ss');
		var idSucu=$.JsonUsuario.idsucursal;
		if(idSucu==3){idSucu=$('#cmbOficinasTotal').val();}
		if($('#txtIdCliente').val().length>0){//guardar sólo el producto
			$.ajax({url: 'php/insertarCompraSolo.php', type: 'POST', data:{
				productoNombre: $('#txtNombreProducto').val(),
				montoentregado: $('#txtMontoEntregado').val(),
				fechainicial: moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				observaciones: $('#txtObservaciones').val(),
				idCl: $('#txtIdCliente').val(),
				fechaRegistro: fechav3, idSucursal: idSucu
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnCompraGuardado').attr('id', resp).removeClass('sr-only');
				$('.modal-ventaGuardada').find('.btnAceptarGuardado').addClass('sr-only');
				$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
				limpiarCasillas();
			}
			});
		}else{//guardar ambos productos y cliente
		$.ajax({url: 'php/insertarCompraNew.php', type: 'POST', data:{
				nombre: $('#txtNombres').val(),
				apellido: $('#txtApellidos').val(),
				direccion: $('#txtDireccion').val(),
				dni: $('#txtDni').val(),
				email:  $('#txtCorreo').val(),
				celular: $('#txtCelular').val(),
				productoNombre: $('#txtNombreProducto').val(),
				montoentregado: $('#txtMontoEntregado').val(),
				fechainicial: moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				observaciones: $('#txtObservaciones').val(),
				fechaRegistro: fechav3, idSucursal: idSucu
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnCompraGuardado').attr('id', resp).removeClass('sr-only');
				$('.modal-ventaGuardada').find('.btnAceptarGuardado').addClass('sr-only');
				$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
				limpiarCasillas();
			}
			}).error(function () { console.log('error');
				// body...
			});
		}
		
		
	}//<- fin de if verificarTodoRellenado
});
$('.modal-PagoACuenta').on('shown.bs.modal', function () { $(this).find('#txtPagarACuenta').focus(); });
$('#btn-PagoACuentaFijo').click(function () {
	//, 
	var contenedor = $(this).parent().parent();
	var iProd=contenedor.find('#lblIdProductosEnc').text();

	//console.log(contenedor.find('#spanMontoDado').text());
	
	$('#spanAmortizarDeuda').text(contenedor.find('#spanMontoDado').text());
	$('#spanInteresAmortizaDeuda').text(contenedor.find('#spanSrInteres').text());
	$('#spanProdDeuda').text(contenedor.find('#spanProducto').text());
	$('#spanDeudaGastos').text(contenedor.find('#h5CargoAdmin').text());
	$('#sr-idProductov3').text(iProd);
	$('#sr-MontInicialv3').text(contenedor.find('#spanMontoDado').text());
	$('#sr-montInteresv3').text(contenedor.find('#spanSrInteres').text());
	$('.modal-PagoACuenta').modal('show');
});
$('#btnPagarACuenta').click(function () {
	if( parseInt($('#txtPagarACuenta').val())>0 && $('#txtPagarACuenta').val()!='' ){
		$('.modal-PagoACuenta').find('.text-danger').addClass('hidden');
		//console.log( 'guardar '+ $('#txtPagarACuenta').val())
		var loquepaga=parseFloat($('#txtPagarACuenta').val());
		var totalDeuda= parseFloat($('#sr-MontInicialv3').text()) ;//parseFloat(montoInical) + parseFloat(interes)
		var interes= parseFloat($('#sr-montInteresv3').text());
		var montoInical=parseFloat(totalDeuda)-parseFloat(interes); //parseFloat( $('#sr-MontInicialv3').text()).toFixed(1);
		var idpro=$('#sr-idProductov3').text();
		var faltaPagar=0;
		//console.log(totalDeuda);
		/* Nota: No se puede redondear a 1 decimal y comprarlarlo porque lo considera como texto y no como número, no se puede comprar textos*/

		if(parseFloat(loquepaga)>= parseFloat(totalDeuda)){ console.log('pago toda su deuda, se libera');
			$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
			$.ajax({url:'php/insertarAmortizacionTodo.php', type:'POST', data:{idDese: $('.divContUnPrestamo').attr('id'), montInicial:montoInical, montInteres: interes, montPago:parseFloat(loquepaga).toFixed(2), idUser: $.JsonUsuario.idUsuario, idProd: idpro, usuario: $.JsonUsuario.usunombres, idSuc: $.JsonUsuario.idsucursal}}).done(function (resp) {// console.log(resp)
				if(parseInt(resp)>0){
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', idpro).removeClass('sr-only');
					$('.modal-ventaGuardada').modal('show');
					$.ajax({url: 'http://192.168.1.131/perucash/printTicketFinalizado2.php', type: 'POST', data: {
						codArt: $('#spanCodProd').text(),
						cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
						articulo: $('#spanProducto').text(),
						monto: parseFloat( loquepaga ).toFixed(2),
						obs: '',
						hora : moment().format('h:mm a dddd DD MMMM YYYY'),
						usuario: $.JsonUsuario.usunombres
					}}).done(function(resp){console.log(resp);
						
						window.location.href = "aplicativo.php?idprod=" +idpro;});}
				else{
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-datoNoGuardado').modal('show');}
			});
 		}
 		else if(parseFloat(loquepaga) == parseFloat(interes) ){
			console.log('canceló todo interés'); //Escenario que hay que ver cuanto hay de dinero
			$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
			$.ajax({url:'php/insertarAmortizacionSoloInteres.php', type:'POST', data:{idDese: $('.divContUnPrestamo').attr('id'), montInicial:montoInical, montInteres: interes, montPago:parseFloat(loquepaga).toFixed(2), idUser: $.JsonUsuario.idUsuario, idProd: idpro, usuario: $.JsonUsuario.usunombres, idSuc: $.JsonUsuario.idsucursal}}).done(function (resp) {// console.log(resp)
				if(parseInt(resp)>0){
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', idpro).removeClass('sr-only');
					$('.modal-ventaGuardada').modal('show');
					$.ajax({url: 'http://192.168.1.131/perucash/printTicketInteresCancelado.php', type: 'POST', data: {
						codArt: $('#spanCodProd').text(),
						cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
						articulo: $('#spanProducto').text(),
						monto: parseFloat( loquepaga ).toFixed(2),
						obs: '',
						hora : moment().format('h:mm a dddd DD MMMM YYYY'),
						usuario: $.JsonUsuario.usunombres
					}}).done(function(resp){console.log(resp);
						$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
						window.location.href = "aplicativo.php?idprod=" +idpro;});
				}
				else{
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-datoNoGuardado').modal('show');}
			});
		} 
 		else if(parseFloat(loquepaga) > parseFloat(interes) ){
			console.log('canceló todo su interés y'); //Escenario que hay que ver cuanto hay de dinero
			var sobraAmort=parseFloat(loquepaga)-parseFloat(interes);
			console.log('restar del capital: '+ sobraAmort.toFixed(2));
			//console.log(sobraAmort);
			//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
			$.ajax({url:'php/insertarAmortizacionMixto.php', type:'POST', data:{idDese: $('.divContUnPrestamo').attr('id'), montInicial:montoInical, montInteres: interes, montPago:parseFloat(loquepaga).toFixed(2), idUser: $.JsonUsuario.idUsuario, sobra: sobraAmort, idProd: idpro, usuario: $.JsonUsuario.usunombres, idSuc: $.JsonUsuario.idsucursal}}).done(function (resp) {// console.log(resp)
				if(parseInt(resp)>0){
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', idpro).removeClass('sr-only');
					$('.modal-ventaGuardada').modal('show');
					$.ajax({url: 'http://192.168.1.131/perucash/printTicketInteresCancelado.php', type: 'POST', data: {
						codArt: $('#spanCodProd').text(),
						cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
						articulo: $('#spanProducto').text(),
						monto: parseFloat( interes ).toFixed(2),
						obs: '',
						hora : moment().format('h:mm a dddd DD MMMM YYYY'),
						usuario: $.JsonUsuario.usunombres
					}}).done(function(resp){console.log(resp);});
					$.ajax({url: 'http://192.168.1.131/perucash/printTicketAmortizando.php', type: 'POST', data: {
						codArt: $('#spanCodProd').text(),
						cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
						articulo: $('#spanProducto').text(),
						monto: parseFloat( sobraAmort ).toFixed(2),
						obs: '',
						hora : moment().format('h:mm a dddd DD MMMM YYYY'),
						usuario: $.JsonUsuario.usunombres
					}}).done(function(resp){console.log(resp);
						//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
						window.location.href = "aplicativo.php?idprod=" +idpro;});
				}

				else{
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-datoNoGuardado').modal('show');}
			});

		}
		else if(parseFloat(loquepaga) < parseFloat(interes) ){
			faltaPagar= parseFloat(interes) -parseFloat(loquepaga);
			console.log('solo restar una parte de interés S/. '+ parseFloat(loquepaga).toFixed(2) + ' falta pagar '+faltaPagar);
			$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
			$.ajax({url:'php/insertarAmortizacionPocoInteres.php', type:'POST', data:{idDese: $('.divContUnPrestamo').attr('id'), montInicial:montoInical, montInteres: interes, montPago:parseFloat(loquepaga).toFixed(2), idUser: $.JsonUsuario.idUsuario, idProd: idpro, usuario: $.JsonUsuario.usunombres, idSuc: $.JsonUsuario.idsucursal}}).done(function (resp) {// console.log(resp)
				if(parseInt(resp)>0){
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', idpro).removeClass('sr-only');
					$('.modal-ventaGuardada').modal('show');
					$.ajax({url: 'http://192.168.1.131/perucash/printTicketInteresAdelanto.php', type: 'POST', data: {
						codArt: $('#spanCodProd').text(),
						cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
						articulo: $('#spanProducto').text(),
						monto: parseFloat( loquepaga ).toFixed(2),
						obs: '',
						hora : moment().format('h:mm a dddd DD MMMM YYYY'),
						usuario: $.JsonUsuario.usunombres
					}}).done(function(resp){console.log(resp);
						//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
						window.location.href = "aplicativo.php?idprod=" +idpro;});}
				else{
					$('.modal-PagoACuenta').modal('hide');
					$('.modal-datoNoGuardado').modal('show');}
			});
		}
	}
	else{
		$('.modal-PagoACuenta').find('.text-danger').text('No se permiten valores negativos').removeClass('hidden');
	}
});
$('#divListaPorConfirmar').on('click','.btn-verInfoFinalizado', function (argument) {
	var aBuscar= $(this).attr('id');
	$.ajax({url:'php/listarProductoEspecificoCliente.php', type: 'POST', data: { idProd: aBuscar }}).done(function (resp) {
		moment().locale('es');
		$.each(JSON.parse(resp), function (i, dato) {
			$('#txtFindni').val(dato.cliDni);
			$('#txtFinapellido').val(dato.cliApellidos + ', '+dato.cliNombres);
			$('#txtFinproducto').val(dato.prodNombre);
			$('#txtFinmonto').val(parseFloat(dato.prodMontoEntregado).toFixed(2));
			$('#txtFinfecha').val(moment(dato.prodFechaRegistro).format('LLLL'));
		});
		$('.modal-detalleFinalizadosCliente').modal('show');
	});
});
$('#btnFiltrarMovimientos').click(function () {
	if($('#dtpFechaListarMovimientos').val() !=''){
		var campo=moment($('#dtpFechaListarMovimientos').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
		$('#rowMovimientoEncontradoFecha').children().remove();
		$.ajax({url: 'php/listarMovimientosFechaDia.php', type: 'POST', data: {fecha: campo}}).done(function (resp) {
			dato2=JSON.parse(resp);  
			if(dato2.length==0){
				$('#rowMovimientoEncontradoFecha').append('<p>No se registraron movimientos de salida.</p>');
			}else{
				$.each(dato2, function (i, elem) {
				$('#rowMovimientoEncontradoFecha').append(`
					<div class="row">
						<div class="col-xs-5 mayuscula"><strong><button class="btn btn-outline btn-negro btn-sm btnAceptarGuardado" id="${elem.idproducto}"><i class="icofont icofont-eye"></i></button> 
						${$('#rowMovimientoEncontradoFecha .row').length+1}.</strong> ${elem.prodNombre}</div>
						<div class="col-xs-3">${elem.repoDescripcion}</div>
						<div class="col-xs-2 mayuscula">${elem.repoUsuario}</div>
						<div class="col-xs-2">S/. ${parseFloat(elem.repoValorMonetario).toFixed(2)}</div>
					</div>`);
				});
			}
			
			$('.modal-mostrarMovimientosProductoFecha').modal('show');
		});
		$.ajax({url: 'php/listarMovimientosRegistradosFechaDia.php', type: 'POST', data: {fecha: campo}}).done(function (resp) {//console.log(resp)
			dato3=JSON.parse(resp); // console.log(dato3);
			if(dato3.length==0){
				$('#rowMovimientoEncontradoFecha').append('<p>No se encontraron productos nuevos registrados.</p>');
			}else{
				$.each(dato3, function (i, elem) {
				$('#rowMovimientoEncontradoFecha').append(`
					<div class="row">
						<div class="col-xs-5 mayuscula"><button class="btn btn-outline btn-negro btn-sm btnAceptarGuardado" id="${elem.idproducto}"><i class="icofont icofont-eye"></i></button> 
						<strong>${$('#rowMovimientoEncontradoFecha .row').length+1}.</strong> ${elem.prodNombre}</div>
						<div class="col-xs-3">${elem.repoDescripcion}</div>
						<div class="col-xs-2 mayuscula">${elem.usuNombres}</div>
						<div class="col-xs-2">S/. ${parseFloat(elem.prodMontoEntregado).toFixed(2)}
						</div>
					</div>`);
				});
			}
			
			$('.modal-mostrarMovimientosProductoFecha').modal('show');
		});
	}
});
$('#btnCronogramaPagosVer').click(function () {
	var valor=0;
	$('#rowInteresesProyectado').children().remove();
	if( $('#txtMontoEntregado').val()=='' || $('#txtMontoEntregado').val()<=0 ){
		valor=0;
	}else{
		valor= $('#txtMontoEntregado').val();
		/*var fechav3= moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')
		$.ajax({url: 'php/inserttempo.php', type: 'POST', data:{fecha: fechav3+' ' + moment().format('HH:mm:ss') }}).done(function (resp) {
			console.log(resp);
		})*/
		$.ajax({url:'php/solicitarConfiguraciones.php', type: 'GET' }).done(function (resp) {
			console.log(resp)
		});
		$.ajax({url:'php/calculoInteresAcumuladoDeValor.php?inicio='+valor+'&numhoy='+1, type: 'GET' }).done(function (resp) {
		
		dato=JSON.parse(resp);
		//console.log(dato)

		if(valor<=5000){
			$('#rowInteresesProyectado').append(`<div class="row">
				<div class="col-xs-6 text-center">1 al 14</div>
				<div class="col-xs-6">S/. ${parseFloat(dato[1][14-1].intAcum).toFixed(2)}</div>
				</div>`);
			for (i=15; i <=30 ; i++) { 
			$('#rowInteresesProyectado').append(`<div class="row">
				<div class="col-xs-6 text-center">${dato[1][i].numDia}</div>
				<div class="col-xs-6">S/. ${parseFloat(dato[1][i].intAcum).toFixed(2)}</div>
				</div>`);
			}
		}else{
			$('#rowInteresesProyectado').append(`<div class="row">
				<div class="col-xs-6 text-center">1 al 24</div>
				<div class="col-xs-6">${dparseFloat(ato[1][24-1].intAcum).toFixed(2)}</div>
				</div>`);
			for (i=25; i <=30 ; i++) { 
			$('#rowInteresesProyectado').append(`<div class="row">
				<div class="col-xs-6 text-center">${dato[1][i].numDia}</div>
				<div class="col-xs-6">${parseFloat(dato[1][i].intAcum).toFixed(2)}</div>
				</div>`);
			}
		}

		
		$('.modal-mostrarProyectadoIntereses').modal('show');
	});
	}
	
});
$('#btnImprTicketInterProyect').click(function () {
	$.ajax({url: 'http://localhost/perucash/printTicketInteresAcumulado.php?inicial='+$('#txtMontoEntregado').val()+'&numhoy=1&inter='+'0.00683', type: 'POST', data: {
		cod: <?php if(isset($_SESSION['idprod'])){ echo $_GET['idprod']; } else {echo 0;}?>,
		cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
		articulo: $('#rowWellFijo #spanProducto').text(),
		monto: parseFloat($('#rowWellFijo #spanMontoDado').text()).toFixed(2),
		obs: $('#rowWellFijo #spanObservacion').text(),
		hora : moment().format('h:mm a dddd DD MMMM YYYY'),
		usuario: $.JsonUsuario.nombre
	}}).done(function(resp){console.log(resp);});
});
$('#btnListaTodosProductosImprimirv3').click(function () {
	if($.JsonUsuario.idsucursal==3){
		window.open('php/imprTodosProductos.php?idSuc=' +$('#cmbOficinasTotal').val(), '_blank');
	}
	else{
		window.open('php/imprTodosProductos.php?idSuc=' +$.JsonUsuario.idsucursal, '_blank');
		//window.location.href = "php/imprTodosProductos.php?idSuc=" +$.JsonUsuario.idsucursal;
	}
	
});
$('#btnIngresoDineroExtra').click(function () {
	$('#txtModalMontoIngresoCaja').val('0.00');
	$('#txtModalRazonIngresoCaja').val('');
	$('.modal-IngresarDineroExtra').modal('show');
});
$('#btnIngresarDineroSuma').click(function () {
	//console.log($.JsonUsuario);
	var idSucu=$.JsonUsuario.idsucursal;
	if(idSucu==3){idSucu=$('#cmbOficinasTotal').val();}

	$.ajax({url: 'php/ingresarDineroEntrada.php', type: 'POST', data:{valor: $('#txtModalMontoIngresoCaja').val(), mensaje: $('#txtModalRazonIngresoCaja').val(), idUser: $.JsonUsuario.idUsuario, idSucursal: idSucu }}).done(function (resp) { //console.log(resp)
		if(parseInt(resp)>0){
			$('.modal-IngresarDineroExtra').modal('hide');
			$('#pDatoGuardadoInfo').text('S/. '+parseFloat($('#txtModalMontoIngresoCaja').val()).toFixed(2)+' por «'+$('#txtModalRazonIngresoCaja').val()+'»');
			$('.modal-datoGuardado').modal('show');
			//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
		}
	});
});
$('#btnEgresoDineroExtra').click(function () {
	$('#txtModalMontoEgresoCaja').val('0.00');
	$('#txtModalRazonEgresoCaja').val('');
	$('.modal-EgresarDineroExtra').modal('show');
});
$('#btnEgresarDineroSuma').click(function () {
	//console.log($.JsonUsuario);
	var idSucu=$.JsonUsuario.idsucursal;
	if(idSucu==3){idSucu=$('#cmbOficinasTotal').val();}

	$.ajax({url: 'php/ingresarDineroSalida.php', type: 'POST', data:{valor: $('#txtModalMontoEgresoCaja').val(), mensaje: $('#txtModalRazonEgresoCaja').val(), idUser: $.JsonUsuario.idUsuario, idSucursal: idSucu }}).done(function (resp) { //console.log(resp)
		if(parseInt(resp)>0){
			$('.modal-EgresarDineroExtra').modal('hide');
			$('#pDatoGuardadoInfo').text('Retiro S/. '+parseFloat($('#txtModalMontoIngresoCaja').val()).toFixed(2)+' por «'+$('#txtModalRazonIngresoCaja').val()+'»');
			$('.modal-datoGuardado').modal('show');
			//$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
		}
	});
});
$('.btnSoloPrint').click(function () { 	window.print(); });
/*$(window).on('beforeunload', function (e) {
	mensaje = 'Estas seguro de salir, ¿es correcto?';
	e.returnValue = mensaje;
	return mensaje;
});*/
/*$('#btn-imprimirHojaControl').printPage({
	url: "hojaControl.php?idProd="+<?php if( isset ($_GET['idprod']) ){echo $_GET['idprod'];}else{echo 0;}?>,
	attr: "href",
	message:"Tu documento está siendo generado"
});*/
$('#btn-imprimirHojaControl').click(function () {
	loadPrintDocument(this,{
		url: "hojaControl.php?idProd="+<?php if( isset ($_GET['idprod']) ){echo $_GET['idprod'];}else{echo 0;}?>,
		attr: "href",
		message:"Tu documento está siendo creado"
	});
});
$('#btn-EliminarDB').click(function () {
	$('.modal-eliminarProducto').modal('show');
});
$('#btnModalEliminarDB').click(function () {
	$.ajax({url:'php/eliminarProductoBD.php', type: 'POST', data:{idProd: <?php if( isset ($_GET['idprod']) ){echo $_GET['idprod'];}else{echo 0;} ?> }}).done(function (resp) {
		//console.log(resp)
		window.location.href = "aplicativo.php";
	});
});
$('#btn-DesembolsoFijo').click(function () {
	$('#spanModalDesembolsoProducto').text($('#spanProducto').text());
	$('#spanCapitalBase').text($('#spanSrCapital').text());
	$('.modal-desembolsoCapital').modal('show');
});
$('#btnModalAgregarDesembolso').click(function () {
	var montoNuevo= parseFloat($('#txtModalDesembolsarPrestamo').val());
	var comentExtr= $('#txtComentarioextraDesembolso').val();// console.log(montoNuevo)
	if(montoNuevo>0 ){
		if( comentExtr!='' ){
			comentExtr+='<br>'
		}
		$('#pErrorDesembolso').addClass('hidden');
		
		$.ajax({url: 'php/updateDesembolsar.php', type: 'POST', data: { idProd: <?php if( isset ($_GET['idprod']) ){echo $_GET['idprod'];}else{echo 0;} ?>, nuevoDesembolso: montoNuevo, idUser: $.JsonUsuario.idUsuario , idSuc: <?php echo $_SESSION['idSucursal']; ?>, comentExtra: comentExtr }}).done(function (resp) {
			$.ajax({url: 'http://localhost/perucash/soloAbrirCaja.php', type: 'POST'});
			$.ajax({url: 'http://localhost/perucash/printTicketDesembolso.php', type: 'POST', data: {
				hora : moment().format('h:mm a dddd DD MMMM YYYY'),
				cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
				articulo: $('#rowWellFijo #spanProducto').text(),
				codArt: $('#spanCodProd').text(),
				monto: $('#spanSrCapital').text(),
				desemb: montoNuevo.toFixed(2),
				nuevoCap: parseFloat(montoNuevo+parseFloat($('#spanSrCapital').text())).toFixed(2),
				usuario: $.JsonUsuario.usunombres }}).done(function (resp) {
					// body...
				});
			if(resp>0){
				location.reload();
			}
		})
	}else{ $('#pErrorDesembolso').removeClass('hidden').text('No se puede agregar montos negativos o ceros'); }
});
$('#btn-imprimirRetiro').click(function () {
	var contenedor=$('.divContUnPrestamo tr').last();
	/*console.log(contenedor.children()[1]);*/

	fechaRetiro=$(contenedor.children()[1]).text();
	pagado=$(contenedor.children()[2]).text();
	usuario=$(contenedor.children()[3]).text();
	/*console.log(pagado)*/

	$.ajax({url: 'http://localhost/perucash/printTicketFinalizado2.php', type: 'POST', data: {
			codArt: $('#spanCodProd').text(),
			cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
			articulo: $('#rowWellFijo #spanProducto').text(),
			monto: pagado,
			hora : fechaRetiro,
			usuario: usuario
		}}).done(function(resp){console.log(resp);});
});
</script>
</body>
</html>
<?php	
} else{
	echo '<script> window.location="php/desconectar.php"; </script>';
}
?>