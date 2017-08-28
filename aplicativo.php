<?php

session_start();
require 'php/conkarl.php';
if(isset($_SESSION['Atiende'])){?>


<!DOCTYPE html>
<html lang="es">
<head>
	<title>Administración de préstamos y empeños - PerúCash</title>
	<meta charset="UTF-8">

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="shortcut icon" href="images/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">
	<link rel="stylesheet" type="text/css" href="css/anatsunamun.css?version=2.0.2">
	<link rel="stylesheet" type="text/css" href="css/icofont.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-table.css">
</head>
<body>


<div class="container formulario col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
<div class="text-center hidden-print">
<img src="images/logo.png">
<h3>PerúCa$h<small> Sistema de préstamos y empeños</small></h3></div>
<?php if ( $_SESSION['Power']== 1){ ?>
<div class="row">
	<div class="col-xs-6 col-md-offset-3"><div><label for="">Ud. está visualizando los datos de:</label> <select class="form-control" name="Achinga" id="cmbOficinasTotal">
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
			<?php if ( $_SESSION['Power']== 1){ ?>
			<li><a href="#tabCrearUsuario" data-toggle="tab"><i class="icofont icofont-badge"></i> Gestión de usuarios</a></li>
			<li><a href="#tabCrearOficina" data-toggle="tab"><i class="icofont icofont-badge"></i> Gestión de oficinas</a></li>
			<li class="sr-only"><a href="#tabAprobarFinalizados" data-toggle="tab"><i class="icofont icofont-badge"></i> Aprobar finalizados</a></li>
			<li><a href="#tabConfirmarMovimientos" data-toggle="tab"><i class="icofont icofont-badge"></i> Confirmar movimientos</a></li>
			<?php  } ?>
			</ul>
	</div>
	<div class="panel-body">
		 <div class="tab-content container-fluid">
	<div class="tab-pane fade in active" id="tabRegistro">
	
	<div class="row well">
		<p><strong>Datos de cliente:</strong></p>
		<div class="col-sm-12"><input type="number" class=" form-control hidden " id="txtIdCliente"></div>
		
		<div class="col-sm-6"><label>D.N.I.:</label><input type="number" class="form-control" id="txtDni" placeholder="Número del documento de identidad" maxlength="8" size="8"></div>
		<div class="col-sm-6"><label>Apellidos:</label><input type="text" class="form-control mayuscula" id="txtApellidos" placeholder="Apellidos completos"></div>
		<div class="col-sm-6"><label>Nombres:</label><input type="text" class="form-control mayuscula" id="txtNombres" placeholder="Nombres completos"></div>
		<div class="col-sm-6"><label>Dirección domiciliaria:</label><input type="text" class="form-control mayuscula" id="txtDireccion" placeholder="Dirección del cliente"></div>
		<div class="col-sm-6"><label>Correo electrónico:</label><input type="text" class="form-control" id="txtCorreo" placeholder="Correo electrónico del cliente"></div>
		<div class="col-sm-6"><label>Celular:</label><input type="text" class="form-control" id="txtCelular" placeholder="Número de celular(es)"></div>
	</div>

	<div class="row well well-amarillo">
		<p><strong>Datos del bien que ingresa:</strong></p>
		<div class="col-sm-12">
			<div class="container row">
				<div class="material-switch pull-left ">
					<input id="someSwitchOptionWarning" type="checkbox"/>
					<label for="someSwitchOptionWarning" class="label-success"></label>

				</div>
				<label for="someSwitchOptionWarning" id="lblSWQueEs" style="margin-left: 10px; color: #606bdc; transition: all 0.3s ease-in-out;"> Artículo con intereses</label>
		</div>
			
		</div>
		<div class="col-sm-12">
			<label>Objeto que ingresa a la tienda:</label> <input type="text" class="form-control mayuscula" id="txtNombreProducto" placeholder="Detalle el nombre y características del objeto.">
		</div>
		<div class="col-sm-6"><label>Monto entregado (S/.):</label> <input type="number" id="txtMontoEntregado" class="form-control" placeholder="Monto S/." min ="0" step="1"></div>
		<div class="col-sm-6 hidden"><label>Interés (%):</label> <input type="number" id="txtMontoInteres" class="form-control" placeholder="% de Interés" value="10" min ="0" step="1" disabled></div>
		<div class="col-sm-6"><label>Fecha de ingreso:</label> <div class="sandbox-container"><input  id="dtpFechaInicio" type="text" class="form-control"></div></div>
		<div class="col-sm-6 "><label>Vencimiento:</label> <div class="sandbox-container"><input  id="dtpFechaVencimiento" type="text" class="form-control"></div></div>
		<div class="col-sm-6"><label>Observaciones o datos extras:</label> <textarea  class="form-control mayuscula" id="txtObservaciones" rows="2" placeholder="¿Alguna observación o dato extra que desees recordar luego?"></textarea></div>
		<div class="col-sm-12 text-center" id="divSimulado"><label>Simulado:</label>
		<h4> <span ><p>Periodo: <span id="spanPeriodo"></span></p></span>
		<span ><p>Interes: <span id="spanIntGene"></span></p></span>
		<p>Intereses: <span id="spanInteres">S/. 0.00</span></p> <p>Monto total a pagar: S/.  <span id="spanMonto">0.00</span></p></h4>
		</div>
		<br>

	</div>
	<hr>
	<div class="pull-left"><button class="btn btn-success" id="btnLimpiarDatos"><i class="icofont icofont-eraser"></i> Limpiar datos</button></div>
	<div class="text-center"><button class="btn btn-primary" id="btnGuardarDatos"><i class="icofont icofont-diskette"></i> Guardar datos</button>
		<button class="btn btn-primary sr-only" id="btnGuardarCompra"><i class="icofont icofont-diskette"></i> Guardar compra</button>
	</div>
	
	<!-- fin de tab pane 1 -->
	</div>
	<div class="tab-pane fade  " id="tabBusqueda">
		<div class="row"> <label>Búsqueda por propietario del producto:</label>
			<input type="text" class="form-control" id="txtBuscarPersona" placeholder="Ingrese Nombre o Dni del cliente">
		</div><br>
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
			<div class="col-sm-6"><label><i class="icofont icofont-cube"></i> Producto: </label> <span class="text-success mayuscula" id="spanProducto"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-ui-tag"></i> Interés: </label> <span class="text-success"><span id="spanPorcent"></span>%</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de inicio: </label> <span class="text-success" id="spanFechaInicio"> <span class="sr-only" id="spanFechaInicioNum"></span></span></div>
			<div class="col-sm-6 hidden"><label><i class="icofont icofont-tasks-alt"></i> Fecha de límite de pago: </label> <span class="text-success" id="spanFechaFin"></span> <span class="sr-only" id="spanFechaFinNum"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Período hasta hoy: </label> <span class="text-success" id="spanPeriodo2"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Monto entregado: </label> <span class="text-success">S/. <span id="spanMontoDado">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Intereses generados: </label> <span class="text-success">S/. <span id="spanIntGenerado">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Total al día de hoy: </label> <span class="text-success">S/. <span id="spanPagar">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Observaciones: </label> <em><span class="text-success mayuscula" id="spanObservacion">Ninguna</span></em></div>
			<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Adelantos: </label> <span class="text-success">S/. <span id="spanAdelanto">0.00</span></span></div>
			<!-- <div class="col-sm-6 col-sm-offset-6"><label><i class="icofont icofont-chat"></i> Deuda del cliente: </label> <span class="text-success">S/. <span id="spanDeudaFinal">0.00</span></span></div> -->

			<div class="col-xs-12 hidden" id="contenedorMovimientos" >
				<div class="alert-message alert-message-warning">
					<h4>Pagos a cuenta:</h4>
					<table class="" data-toggle="table">
					<thead style="background-color: #ffcd87; color: #9a6b29;<!--  #676767; -->">
					<tr >
						<th>Fecha</th>
						<th>Capital</th>
						<th>Interés</th>
						<th>Adelanto</th>
						<th>Usuario</th>
					</tr>
					</thead>
					<tbody  style="background-color: white;">
					<tr>
						<td>
						   Cuenta 1
						</td>
						<td>526</td>
						<td>122</td>
						<td>An extended Bootstrap table with radio, checkbox</td>
						<td>Demo</td>
					</tr>
					<tr>
						<td>
							Cuenta 2
						</td>
						<td>32</td>
						<td>11</td>
						<td>Show/hide password plugin for twitter bootstrap.
						<td>Demo</td>
						</td>
					</tr>
					<tr>
						<td>
							Cuenta 3
						</td>
						<td>13</td>
						<td>4</td>
						<td>Demo</td>
						<td>my blog</td>
					</tr>
					<tr >
						<td >
							Cuenta 4
						<td>6</td>
						<td>3</td>
						<td>Demo</td>
						<td>Redmine notification tools for chrome extension.</td>
					</tr>
					</tbody>
				</table>
				</div>
			</div>
			<div class="col-xs-12 sr-only" id="contenedorVigente" >
				<div class="alert-message alert-message-success">
					<h4>Artículo vigente: <small>El artículo se encuentra dentro del plazo, pasa a posible remate en: <strong id="contDiasPosRem"></strong> días.</small></h4>
				</div>
			</div>
			<div class="col-xs-12 sr-only" id="contenedorExpiro" >
				<div class="alert-message alert-message-danger">
					<h4>Artículo expirado: <small>El artículo se encuentra más de un mes, se sugiere <strong>rematar</strong></small></h4>
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
				<button class="btn btn-morado btn-outline" id="btn-imprimirTicketFijo"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
				<button class="btn btn-morado btn-outline" id="btn-imprimirImpresoraFijo"><i class="icofont icofont-print"></i> Voucher en impresora</button>
				<button class="btn btn-indigo btn-outline" id="btn-FinalizarImpuestoFijo"><i class="icofont icofont-pie-chart"></i> Cancelar interés</button>
				<button class="btn btn-indigo btn-outline" id="btn-AdelantoPrestamoFijo"><i class="icofont icofont-rocket"></i> Adelantar pago</button>
				<button class="btn btn-indigo btn-outline" id="btn-PagoACuentaFijo"><i class="icofont icofont-pie-chart"></i> Pago a cuenta (En desarrollo)</button>
				<button class="btn btn-danger btn-outline" id="btn-FinalizarPrestamoFijo"><i class="icofont icofont-rocket"></i> Finalizar préstamo</button>
			</div>
		</div>
		<div id="rowWellCambiante">
		</div>
	</div>
	
	</div>
	<div class="tab-pane fade" id="tabDetalle"><p>Se encontraron <strong id="strFaltan"> </strong> Artículos vencidos de más de 30 días.</p>
		<div class="row hidden-xs"><strong>
			<div class="col-sm-4">Nombre del artículo</div>
			<div class="col-sm-1">Monto S/.</div>
			<div class="col-sm-1">Más interés</div>
			<div class="col-sm-3">Dueño</div>
			<div class="col-sm-2">Fecha Registro</div></strong>
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
	
	<button class="btn btn-negro btn-outline sr-only" id="btnImprimirNoFinalizados"><i class="icofont icofont-print"></i> Imprimir éste reporte</button>
		<div class="row"><strong>
			<div class="col-sm-3">Apellidos y Nombres</div>
			<div class="col-sm-2">Registro</div>
			<div class="col-sm-2 hidden">Caduca</div>
			<div class="col-sm-1">Entregado</div>
			<div class="col-sm-1">Más interés</div>
			<div class="col-sm-3">Garantía</div></strong>

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
	<?php if ( $_SESSION['Power']== 1){ ?>
	<div class="tab-pane fade" id="tabCrearUsuario">
		<div class="row well">
		<p><strong>Creación de usuarios</strong></p>
			<div class="col-xs-12 col-sm-6"><strong>Apellidos</strong> <input type="text" class="form-control mayuscula" id="txtApellUser" placeholder="Apellidos del usuario"></div>
			<div class="col-xs-12 col-sm-6"><strong>Nombres</strong> <input type="text" class="form-control mayuscula" id="txtNombrUser" placeholder="Nombres del usuario"></div>
			<div class="col-xs-12 col-sm-6"><strong>Nick</strong> <input type="text" class="form-control" id="txtNickUser" placeholder="Nick del usuario para ingresar al sistema"></div>
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
	<br><div class="pull-right"><p style="margin-top: 10px;">Sessión actual: <em class="mayuscula"><strong><?php echo $_SESSION['Sucursal'].' - '; ?><span id="spanUsuario"><?php echo $_SESSION['Atiende'] ?></span> </strong></em>
	<button class="btn btn-sm btn-morado btn-outline" id="btnCambiarMiContras"><i class="icofont icofont-key"></i> Cambiar mi contraseña</button> 
	<button class="btn btn-sm btn-morado btn-outline" id="btnCerrarSesion"><i class="icofont icofont-exit"></i> Cerrar sesión</button> </p></div>
	</div>
</div>


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
	<div class="modal-dialog" role="document">
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

 <!-- Modal para indicar que se guardó con éxito -->
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
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Pago a cuenta</h4>
			</div>
			<div class="modal-body">
				¿Cuántos S/. está adelatando el cliente?
				<span class="sr-only" id="sr-idProdv3"></span><span class="sr-only" id="sr-MontInicv3"></span><span class="sr-only" id="sr-montIntv3"></span>
				<input type="number" class="form-control text-center" id="txtPagarACuenta" min="0" value="0" step="1">
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
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Adquisición guardada</h4>
			</div>
			<div class="modal-body">
				<strong><i class="icofont icofont-social-smugmug"></i> Enhorabuena,</strong> sus datos fueron guardados correctamente.
			</div>
			<div class="modal-footer"> 
			<button class="btn btn-primary btn-outline btnAceptarGuardado" data-dismiss="modal" ><i class="icofont icofont-check"></i> Ok, aceptar</button></div>
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

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/impotem.js"></script>
<script type="text/javascript" src="js/jquery.printPage.js"></script>
<script type="text/javascript" src="js/bootstrap-table.min.js"></script>
<script>
$(document).ready(function () {
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('#dtpFechaVencimiento').val(moment().add(1, 'days').format('DD/MM/YYYY'));
	var idNew = <?php if (isset($_GET["idprod"])) { echo $_GET["idprod"]; }else{ echo 0;} ?>;
	$.idOficina=0;
	if( <?php echo $_SESSION['idSucursal']; ?> !=3){ $.idOficina =  <?php echo $_SESSION['idSucursal']; ?> }
	else{$.idOficina=$('#cmbOficinasTotal').val();}
	cambiodeOficina();
	$.JsonUsuario=[];
	$.JsonUsuario.push({'sucursal':'<?php echo $_SESSION['idSucursal']; ?>', 'nombre': '<?php echo $_SESSION['Atiende']; ?>', 'idusuario': '<?php echo $_SESSION['idUsuario']; ?>',
		'idoficina': '<?php echo $_SESSION['oficina']; ?>'
	 });
;	
	
	//console.log('usuario ' + <?php echo $_SESSION['idUsuario']; ?>)
	if(parseInt(idNew)==0){ }//console.log('nada')
	else {
		$('#rowWellFijo').removeClass('hidden');
		$('#rowWellCambiante').addClass('hidden');

		console.log('solcitar producto con id: ' + idNew);
		$.ajax({url: 'php/solicitarProductoPorId.php',type:'POST', data: {idProd: idNew}}). done(function (resp) {
			var dato = JSON.parse(resp);

			$('#spanIdCliente').text(dato[0].idCliente);
			$('#spanApellido').text(dato[0].cliApellidos);
			$('#spanNombre').text(dato[0].cliNombres);
			$('#spanDni').text(dato[0].cliDni);
			$('#spanDireccion').text(dato[0].cliDireccion);
			$('#spanCorreo').text(dato[0].cliCorreo);
			$('#spanCelular').text(dato[0].cliCelular);

			if( dato[0].idSucursal==  <?php echo $_SESSION['idSucursal'] ?> ||  <?php echo $_SESSION['Power']  ?>==1){ console.log('misma sucursal');
				
				if(dato[0].prodObservaciones ==''){$('#spanObservacion').text('Ninguna');}else{$('#spanObservacion').html(dato[0].prodObservaciones);}

				moment.locale('es');
				var hoy = moment();
				var fechaIni =moment(dato[0].prodFechaInicial);
				var fechaFin=moment(dato[0].prodFechaVencimiento);
				var differencia=hoy.diff(fechaIni, 'days');
				console.log('Tantos dias desde fecha incial a hoy: '+ differencia);
				if(parseInt(differencia)>30){
					$('#contenedorExpiro').removeClass('sr-only');
				}else{
					$('#contenedorVigente').removeClass('sr-only'); $('#contDiasPosRem').text(parseInt(31-differencia));
				}
			
				//respu=calculoIntereses(fechaIni, hoy, dato[0].prodMontoEntregado, dato[0].prodInteres);

				if(dato[0].prodActivo==0){
					respu=calculoIntereses(fechaIni, hoy, dato[0].prodMontoEntregado, 0);
				}else{
				if( hoy.diff(fechaIni, 'days') <=14){
					
					if(dato[0].prodUltimaFechaInteres==moment().format('DD/MM/YYYY')){ 
						respu=calculoIntereses(fechaIni, hoy, dato[0].prodMontoEntregado, 0);
					}
					else{respu=calculoIntereses(fechaIni, hoy, dato[0].prodMontoEntregado, 10);}

					
					//$('#spanPorcent').text(10);
				}
				else{
					respu=calculoIntereses(fechaIni, hoy, dato[0].prodMontoEntregado, dato[0].prodInteres);
					//$('#spanPorcent').text(dato[0].prodInteres);
				}
				}
				
				// var respu; console.log(dato[0]);
				// if(hoy.diff(fechaFin, 'days')<=0){//diferencia entre dias, cuando es negativo quiere recir que faltan días para que venza el producto, si es positivo, ya venció su fecha límite
				// 	respu=calculoIntereses(fechaIni, fechaFin, dato[0].prodMontoEntregado, dato[0].prodInteres);
				// }
				// else{//seccion cuando el producto ya paso su fecha límite
				// 	respu=calculoIntereses(fechaIni, hoy, dato[0].prodMontoEntregado, dato[0].prodInteres);
				// 	$('#spanObservacion').append('<p class="text-danger">Se exedió en ' + hoy.diff(fechaFin, 'days')+ ' días</p>');				}
				$('#spanPorcent').text(parseFloat(respu[1].acumulado).toFixed(0));
				$('#spanPeriodo2').text(respu[0].periodo); //(moment(fechaIni).fromNow());
				$('#spanPagar').text(parseFloat(respu[1].monto).toFixed(2));
				$('#spanIntGenerado').text(parseFloat(parseFloat(respu[1].monto)-parseFloat(dato[0].prodMontoEntregado)).toFixed(2));

				moment.locale('es');
				$('#spanProducto').text(dato[0].prodNombre);
				//$('#spanPorcent').text(dato[0].prodInteres);
				$('#spanFechaInicio').text(moment(dato[0].prodFechaInicial).format('dddd[,] DD MMMM YYYY'));
				$('#spanFechaFin').text(moment(dato[0].prodFechaVencimiento).format('dddd[,] DD MMMM YYYY'));
				$('#spanFechaInicioNum').text(dato[0].prodFechaInicial);
				$('#spanFechaFinNum').text(dato[0].prodFechaVencimiento);
				
				$('#spanMontoDado').text(parseFloat(dato[0].prodMontoEntregado).toFixed(2));
				$('#spanAdelanto').text(parseFloat(dato[0].prodAdelanto).toFixed(2));
				$('#spanDeudaFinal').text( parseFloat($('#spanPagar').text()- parseFloat( $('#spanAdelanto').text())).toFixed(2));

				if(dato[0].prodActivo==0){
					$('#divBotonesFijos').addClass('hidden');
					$('#btn-imprimirTicketFijo').addClass('hidden');
					$('#btn-imprimirImpresoraFijo').addClass('hidden');
					$('#btn-AdelantoPrestamoFijo').addClass('hidden');
					$('#btn-FinalizarImpuestoFijo').addClass('hidden');
					$('#btn-FinalizarPrestamoFijo').addClass('hidden');
					$('#contenedorFinalizados').removeClass('sr-only');
					$.ajax({url: 'php/listarMovimientoFinal.php', type: 'POST', data: {idProd: idNew, idSuc: $.JsonUsuario[0].sucursal }}).done(function (resp) {
						dato2=JSON.parse(resp); 
						console.log(dato2)
						$('#QuienFinalizoFin').text(dato2[0].repoUsuario);
						$('#estadoAprobacionFin').text(dato2[0].prodQuienFinaliza);
						$('#QuienApruebaFin').text(dato2[0].repoQuienConfirma);
						$('#estadoAprobacionFin').text(dato2[0].prodQuienFinaliza);
						$('#estadoAprobacionFin').text(dato2[0].estadoConfirmacion);
						$('#finalizaMonto').text(parseFloat(dato2[0].repoValorMonetario).toFixed(2));
						$('#FechaFinalizo').text(moment(dato2[0].prodFechaFinaliza).format('dddd[,] DD MMMM YYYY [a las] hh:mm a'));
					});
					console.log(moment().diff(fechaIni, 'days'))
					
				}
				else{$('#divBotonesFijos').removeClass('hidden');}
			}
			else{console.log('otra sucrusal');
			$('#rowWellFijo').html(`<div class="alert alert-danger ">
				<i class="icofont icofont-animal-cat-alt-4" style="font-size:24px"></i> <strong>¡Oh lo sentimos!</strong> Éste producto está asignado a la oficina «${dato[0].sucNombre}».
						</div>`);
		}

		})
		$('.nav-tabs li').eq(1).children('a').click();

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
		$('#spanMonto').text(montoaPagar.toFixed(2))
		
	}
	else{
		$('#spanPeriodo').text('Fecha inválida');
	}
}
$('#btnGuardarDatos').click(function () { //console.log($('#txtNombreProducto').val())
	calcularPeriodo();
	if(verificarTodoRellenado()){

		if($('#txtIdCliente').val().length>0){//guardar sólo el producto
			$.ajax({url: 'php/insertarProductoSolo.php', type: 'POST', data:{
				productoNombre: $('#txtNombreProducto').val(),
				montoentregado: $('#txtMontoEntregado').val(),
				interes: 4, //$('#txtMontoInteres').val(),
				montopagar: $('#spanMonto').text(),
				fechainicial: moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				feachavencimiento: moment($('#dtpFechaVencimiento').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				observaciones: $('#txtObservaciones').val(),
				idCl: $('#txtIdCliente').val()
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', resp);
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
				observaciones: $('#txtObservaciones').val()
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', resp);
				limpiarCasillas();
			}
			}).error(function () { console.log('error');
				// body...
			});
		}
		
	}//<- fin de if verificarTodoRellenado
	
});
$('.btnAceptarGuardado').click(function () {
	var id= $(this).attr('id');
	window.location.href = "aplicativo.php?idprod=" +id;
})
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
	var estado = false;
	if ( $('#txtApellidos').val()=='' ||  $('#txtNombres').val()=='' ||   $('#txtDni').val()=='' ||  $('#txtDireccion').val()=='' ||  $('#txtCelular').val()=='' ){
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
			var dato = JSON.parse(resp);
			$('#cuadroPanelVencidos').find('.spandia').text(moment().format('DD/MM/YYYY'));
			$('#cuadroPanelVencidos').find('.spanCuadrProd').text(dato.length);

			$.each(dato, function (i, elem) { moment.locale('es');
				var limite = moment(elem.prodFechainicial);
				var hoy = moment();
				var fechaIni =moment(elem.prodFechainicial);
				respu=calculoIntereses(fechaIni, hoy, elem.prodMontoEntregado, 4);
				//console.log(respu)
				montSimpleAcum+=parseFloat(elem.prodMontoEntregado);
				montIntAcum+=parseFloat(respu[1].monto)

				$('#divProdAVencerse').append(`<div class="row">
			<div class="col-sm-4 mayuscula"><strong class="visible-xs-block">Producto: </strong>${$('#divProdAVencerse .row').length+1}. ${elem.prodNombre}</div>
			<div class="col-sm-1"><strong class="visible-xs-block">Monto: </strong>${parseFloat(elem.prodMontoEntregado).toFixed(2)}</div>
			<div class="col-sm-1"><strong class="visible-xs-block">Monto: </strong>${parseFloat(respu[1].monto).toFixed(2)}</div>
			<div class="col-sm-3 mayuscula"><strong class="visible-xs-block">Dueño: </strong>${elem.propietario}</div>
			<div class="col-sm-2 mayuscula"><strong class="visible-xs-block">Fecha: </strong>${limite.format('DD MMMM YYYY')}</div>
			<span class="col-sm-1 push-right"> <a class="btn btn-negro btn-outline btnIcono" href="aplicativo.php?idprod=${elem.idproducto}"><i class="icofont icofont-eye"></i></a> </span>
			</div>`);

			});
			$('#cuadroPanelVencidos').find('.spanCuadrSumaMont').text('S/. '+ parseFloat( montSimpleAcum).toFixed(2));
			$('#cuadroPanelVencidos').find('.spanCuadrSumaInt').text('S/. '+ parseFloat( montIntAcum).toFixed(2));
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

			$.each(JSON.parse(resp), function (i, dato) {
				var hoy = moment();
				var fechaIni =moment(dato.prodFechaInicial);
				respu=calculoIntereses(fechaIni, hoy, dato.prodMontoEntregado, dato.prodInteres);
				//console.log(respu);
				montSimpleAcum+=parseFloat(dato.prodMontoEntregado);
				montIntAcum+=parseFloat(respu[1].monto)
;
				$('#divNoFinalizados').append(`<div class="row"><div class="col-xs-3 mayuscula">${i+1}. ${dato.cliApellidos}, ${dato.cliNombres}</div>
			<div class="col-xs-2">${moment(dato.prodFechaInicial).format('DD MMMM YYYY')}</div>
			<div class="col-xs-2 hidden">${moment(dato.prodFechaVencimiento).format('DD MMMM YYYY')}</div>
			<div class="col-xs-1 sr-only">${dato.cliCelular}</div>
			<div class="col-xs-1">${parseFloat(dato.prodMontoEntregado).toFixed(2)}</div>
			<div class="col-xs-1">${parseFloat(respu[1].monto).toFixed(2)}</div>
			<div class="col-xs-3 mayuscula">${dato.prodNombre} <a class="btn btn-negro btn-outline btnIcono pull-right" href="aplicativo.php?idprod=${dato.idProducto}"><i class="icofont icofont-eye"></i></a></div></div>`);
			});
			$('#cuadroPanelNoFinalizados').find('.spanCuadrSumaMont').text('S/. '+ parseFloat( montSimpleAcum).toFixed(2));
			$('#cuadroPanelNoFinalizados').find('.spanCuadrSumaInt').text('S/. '+ parseFloat( montIntAcum).toFixed(2));
			
			
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
					boton=`<button class="btn btn-success btn-outline btnAceptarReporteGuar" style="padding: 6px 6px;"><i class="icofont icofont-check"></i> Aceptar</button> `
				}
				if(elem.idDetalleReporte==3){
					boton=`<button class="btn btn-morado btn-outline btnRetirarReporteGuar" style="padding: 6px 3px;"><i class="icofont icofont-exchange"></i> Retirado</button>
						<button class="btn btn-danger btn-outline btnRematarReporteGuar" style="padding: 6px 3px;"><i class="icofont icofont-sale-discount"></i> Rematado</button>`;
				}
				$('#divListaPorConfirmar').append(`
					<div class="row">
					<div class="idRowReporte sr-only">${elem.idReporte}</div>
					<div class="col-sm-3 mayuscula nomProd">${elem.prodNombre}</div>
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
	$.ajax({url:'php/updateMovimientoAceptar.php', type:'POST', data:{idRepo: idCamb, idUser: $.JsonUsuario[0].nombre }}).done(function (resp) {
	 if(resp==1){
		$('.modal-EstaSeguroAceptarMovimiento').modal('hide');
		$('#divListaPorConfirmar .idRowReporte').each(function (i, elem ) { //console.log(elem)
			if($(elem).text()==idCamb){
				$(this).parent().find('.botones').children().remove();
				$(this).parent().find('.botones').append('<p>Se aceptó el movimiento por <strong>'+$.JsonUsuario[0].nombre +'</strong> </p>' );
			}
		})
	 }
	});
});
$('#btnRematarMovimientoModal').click(function () {
	var idCamb= $(this).parent().parent().find('.idSelecRow').text();
	$.ajax({url:'php/updateMovimientoRematar.php', type:'POST', data:{idRepo: idCamb, idUser: $.JsonUsuario[0].nombre }}).done(function (resp) {
	 if(resp==1){
		$('.modal-EstaSeguroRematarMovimiento').modal('hide');
		$('#divListaPorConfirmar .idRowReporte').each(function (i, elem ) { console.log(elem)
			if($(elem).text()==idCamb){
				$(this).parent().find('.botones').children().remove();
				$(this).parent().find('.botones').append('<p>Se registró como rematado por <strong>'+$.JsonUsuario[0].nombre +'</strong> </p>' );
			}
		})
	 }
	});
});
$('#btnRetirarMovimientoModal').click(function () {
	var idCamb= $(this).parent().parent().find('.idSelecRow').text();
	$.ajax({url:'php/updateMovimientoRetirar.php', type:'POST', data:{idRepo: idCamb, idUser: $.JsonUsuario[0].nombre }}).done(function (resp) {
	 if(resp==1){
		$('.modal-EstaSeguroRetirarMovimiento').modal('hide');
		$('#divListaPorConfirmar .idRowReporte').each(function (i, elem ) { console.log(elem)
			if($(elem).text()==idCamb){
				$(this).parent().find('.botones').children().remove();
				$(this).parent().find('.botones').append('<p>Producto retirado por <strong>'+$.JsonUsuario[0].nombre +'</strong> </p>' );
			}
		})
	 }
	});
});

//, , btnAceptarMovimientoModal

$('#divListaPorFinalizar').on('click','.btnBotonPorConcluir',  function () {
	$('.modal-EstaSeguroFinalizar').modal('show');
});


$('#txtBuscarPersona').keyup(function (e) {var code = e.which;
	if(code==13 && $('#txtBuscarPersona').val()!=''  ){	e.preventDefault();
		//console.log('enter')
		$('#rowUsuarioEncontrado').children().remove();
		$.ajax({url: 'php/ubicarPersonaProductos.php', type: 'POST', data: {campo:$('#txtBuscarPersona').val() }}).done(function (resp) {
			dato = JSON.parse(resp);// console.log(dato)
			$.each(dato, function(i, elem){
				$('#rowUsuarioEncontrado').append(`<div class="rowEnc"><div class="col-xs-6 mayuscula eleNom">${elem.cliApellidos}, ${elem.cliNombres}</div>
					<div class="hidden eleIdCli" >${elem.idCliente}</div>
					<div class="hidden eleDire">${elem.cliDireccion}</div>
					<div class="hidden eleCorr">${elem.cliCorreo}</div>
					<div class="col-xs-3 eleDni">${elem.cliDni}</div>
					<div class="col-xs-2 eleCel">${elem.cliCelular}</div>
					<div class="col-xs-1"><button class="btn btn-negro btn-outline btnSelectUser" id="${elem.idCliente}"><i class="icofont icofont-tick-mark"></i></button></div></div>`)
			});
			
			$('.modal-mostrarResultadosCliente').modal('show');
		});
		
		
	}
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
		console.log(JSON.parse(resp))
		moment.locale('es');
		$.each(JSON.parse(resp), function (i, dato) {
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
					<button class="btn btn-morado btn-outline btn-imprimirImpresoraMovil"><i class="icofont icofont-print"></i> Voucher en impresora</button>
					<button class="btn btn-indigo btn-outline btn-FinalizarImpuestoMovil"><i class="icofont icofont-pie-chart"></i> Cancelar interés</button>
					<button class="btn btn-indigo btn-outline btn-AdelantoPrestamoMovil"><i class="icofont icofont-rocket"></i> Adelantar pago</button>
					<button class="btn btn-danger btn-outline btn-FinalizarPrestamoMovil"><i class="icofont icofont-rocket"></i> Finalizar préstamo</button>
				</div>`;
				}else{
					botonAgregar = `
					<div class="col-xs-12" >
						<div class="alert-message alert-message-morado">
								<h4>Producto finalizado</h4>
								<p>por: <strong><span class="mayuscula" id="QuienFinalizo">${dato.prodQuienFinaliza}</span></strong>, el día: <strong><span id="FechaFinalizo">${moment(dato.prodFechaFinaliza).format('')}</span></strong>, por el monto: S/. <span id="finalizaMonto">${parseFloat(dato.prodCuantoFinaliza).toFixed(2)}</span> estado de aprobación: <strong><span id="QuienAprueba">Todavía sin aprobación</span></strong>.</p>
						</div>
					</div>
					<div class="col-sm-8 col-sm-offset-2">
					<button class="btn btn-morado btn-outline btn-imprimirTicketMovil"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
					<button class="btn btn-morado btn-outline btn-imprimirImpresoraMovil"><i class="icofont icofont-print"></i> Voucher en impresora</button>
				</div>`;}

				$('#rowWellCambiante').append(`<div class="row well" >
				<span class="hidden" id="lblIdProductosEnc">${dato.idProducto}</span>
				<div class="col-sm-6"><label><i class="icofont icofont-cube"></i> Producto: </label> <span class="text-success mayuscula" id="spanProducto">${dato.prodNombre}</span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-ui-tag"></i> Interés: </label> <span class="text-success"><span id="spanPorcent">${parseFloat(respu[1].acumulado).toFixed(0)}</span>%</span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de inicio: </label> <span class="text-success" id="spanFechaInicio">${moment(dato.prodFechaInicial).format('dddd[,] DD MMMM YYYY')}</span> <span class="sr-only" id="spanFechaInicioNum">${dato.prodFechaInicial}</span></div>
				<div class="col-sm-6 hidden"><label><i class="icofont icofont-tasks-alt"></i> Fecha de límite de pago: </label> <span class="text-success" id="spanFechaFin">${moment(dato.prodFechaVencimiento).format('dddd[,] DD MMMM YYYY')}</span> <span class="sr-only" id="spanFechaFinNum">${dato.prodFechaVencimiento}</span></div>
				<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Período hasta hoy: </label> <span class="text-success" id="spanPeriodo2">${respu[0].periodo}</span></div>
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
	//console.log($('#rowWellFijo #spanProducto').text())
	$.ajax({url: 'http://localhost/perucash/printTicket.php', type: 'POST', data: {
		cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
		articulo: $('#rowWellFijo #spanProducto').text(),
		monto: parseFloat($('#rowWellFijo #spanMontoDado').text()).toFixed(2),
		obs: $('#rowWellFijo #spanObservacion').text(),
		hora : moment().format('h:mm a dddd DD MMMM YYYY'),
		usuario: $.JsonUsuario[0].nombre
	}}).done(function(resp){console.log(resp);});
});
$('#btn-FinalizarPrestamoFijo').click(function () {
	var iProd=$('#rowWellFijo #lblIdProductosEnc').text();
	$('#idEstaseguro').text(iProd);
	$('#montoSeguro').text($('#rowWellFijo #spanPagar').text());
	$('.modal-EstaSeguro').modal('show');


	
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
$('#btnSeguroFinalizar').click(function () {
	var iProd=$('#idEstaseguro').text();
	var valor=parseFloat($('#montoSeguro').text());

	console.log(iProd)
	
	$.ajax({url:'php/updateFinalizarEstado.php', type: "POST", data: {idProd: iProd, monto: valor }}).done(function (resp) { console.log(resp)
		if(parseInt(resp)==1){
			//$('#btn-FinalizarPrestamoFijo').addClass('hide');
			$('.modal-EstaSeguro').modal('hide');
	}
		// body...
	});

	if( !$('#rowWellFijo').hasClass('hidden')){ console.log( 'Buscar Datos en fijo: '+ $('#rowWellFijo #spanProducto').text());
		$.ajax({url: 'http://localhost/perucash/printTicketFinalizado.php', type: 'POST', data: {
			cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
			articulo: $('#rowWellFijo #spanProducto').text(),
			monto: parseFloat($('#rowWellFijo #spanPagar').text()).toFixed(2),
			obs: $('#rowWellFijo #spanObservacion').text(),
			hora : moment().format('h:mm a dddd DD MMMM YYYY'),
			usuario: $.JsonUsuario[0].nombre
		}}).done(function(resp){console.log(resp);});
		}
	if( !$('#rowWellCambiante').hasClass('hidden')){
		$.each($('#rowWellCambiante #lblIdProductosEnc'), function (i, elem) {
			if($(elem).text()==iProd){
					
				$.ajax({url: 'http://localhost/perucash/printTicketFinalizado.php', type: 'POST', data: {
					cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
					articulo: $(elem).parent().find('#spanProducto').text(),
					monto: parseFloat( $(elem).parent().find('#spanPagar').text() ).toFixed(2),
					obs: $(elem).parent().find('#spanObservacion').text(),
					hora : moment().format('h:mm a dddd DD MMMM YYYY'),
					usuario: $.JsonUsuario[0].nombre
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
	cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
	articulo: articuloCalc,
	monto: montoCalc,
	hora : moment().format('h:mm a dddd DD MMMM YYYY'),
	usuario: $.JsonUsuario[0].nombre
}}).done(function(resp){console.log(resp);});

	window.location.href = "aplicativo.php?idprod=" +iProd;
});

$('#rowWellCambiante').on('click', '.btn-imprimirTicketMovil', function () {
	var contenedor = $(this).parent().parent();
	// console.log('nombre: '+ contenedor.find('#spanProducto').text());

	$.ajax({url: 'http://localhost/perucash/printTicket.php', type: 'POST', data: {
		cliente: $('#spanApellido').text()+', '+$('#spanNombre').text(),
		articulo: contenedor.find('#spanProducto').text(),
		monto: parseFloat(contenedor.find('#spanMontoDado').text()).toFixed(2),
		obs: contenedor.find('#spanObservacion').text(),
		hora : moment().format('h:mm a dddd DD MMMM YYYY'),
		usuario: $.JsonUsuario[0].nombre
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
		$('#lblSWQueEs').text('Producto es una compra sin intereses').css('color', '#5CB85C');
		$('#dtpFechaVencimiento').attr("disabled", 'true');
		$('#divSimulado').addClass('sr-only');
		$('#btnGuardarDatos').addClass('sr-only');
		$('#btnGuardarCompra').removeClass('sr-only');


	}else{//false
		$('#lblSWQueEs').text('Artículo con intereses').css('color', '#606bdc');
		$('#dtpFechaVencimiento').removeAttr("disabled");
		$('#divSimulado').removeClass('sr-only');
		$('#btnGuardarDatos').removeClass('sr-only');
		$('#btnGuardarCompra').addClass('sr-only');
	}
	$('#txtNombreProducto').focus();
});
$('#btnGuardarCompra').click(function () { //console.log($('#txtNombreProducto').val())
	if(verificarTodoRellenado()){
		if($('#txtIdCliente').val().length>0){//guardar sólo el producto
			$.ajax({url: 'php/insertarCompraSolo.php', type: 'POST', data:{
				productoNombre: $('#txtNombreProducto').val(),
				montoentregado: $('#txtMontoEntregado').val(),
				fechainicial: moment($('#dtpFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
				observaciones: $('#txtObservaciones').val(),
				idCl: $('#txtIdCliente').val()
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', resp);
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
				observaciones: $('#txtObservaciones').val()
				}
			}).done(function (resp) { console.log(resp) 
				if(parseInt(resp)>=1){ $('.modal-ventaGuardada').modal('show'); $('.modal-ventaGuardada').find('.btnAceptarGuardado').attr('id', resp);
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

	console.log(contenedor.find('#spanProducto').text());
	console.log($('#spanApellido').text()+', '+$('#spanNombre').text());

	$('#sr-idProdv3').text(iProd);
	$('#sr-MontInicv3').text(contenedor.find('#spanMontoDado').text());
	$('#sr-montIntv3').text(contenedor.find('#spanIntGenerado').text());
	$('.modal-PagoACuenta').modal('show');
});
$('#btnPagarACuenta').click(function () {
	if( parseInt($('#txtPagarACuenta').val())>0 || $('#txtPagarACuenta').val()!='' ){
		console.log( 'guardar '+ $('#txtPagarACuenta').val())
		var interes= $('#sr-montIntv3').text();
		if(interes<= $('#txtPagarACuenta').val() ){}
	}
});
</script>
</body>
</html>
<?php	
} else{
	echo '<script> window.location="php/desconectar.php"; </script>';
}
?>