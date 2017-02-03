<?php
session_start();
if (@!$_SESSION['Sucursal']){
	header("Location:index.html");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Administración de préstamos y empeños - PerúCash</title>
	<meta charset="UTF-8">

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="shortcut icon" href="images/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">
	<link rel="stylesheet" type="text/css" href="css/anatsunamun.css">
	<link rel="stylesheet" type="text/css" href="css/icofont.css">
</head>
<body>


<div class="container formulario">
<div class="text-center">
<img src="images/logo.png">
<h3>PerúCa$h<small> Sistema de préstamos y empeños</small></h3></div>

<div class="panel with-nav-tabs panel-warning">
	<div class="panel-heading">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tabRegistro" data-toggle="tab">Registro</a></li>
			<li><a href="#tabBusqueda" data-toggle="tab">Búsqueda</a></li>
			<li><a href="#tabDetalle" data-toggle="tab">Productos Vencidos <span class="badge"> <?php require('php/contarVencidos.php'); ?></span></a></li>
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
		<label>Objeto que se ingresa:</label> <input type="text" class="form-control mayuscula" id="txtNombreProducto" placeholder="Detalle el nombre y características del objeto.">
		<div class="col-sm-6"><label>Monto entregado (S/.):</label> <input type="number" id="txtMontoEntregado" class="form-control" placeholder="Monto S/." min ="0" step="1"></div>
		<div class="col-sm-6"><label>Interés (%):</label> <input type="number" id="txtMontoInteres" class="form-control" placeholder="% de Interés" value="4" min ="0" step="1"></div>
		<div class="col-sm-6"><label>Fecha de ingreso:</label> <div class="sandbox-container"><input  id="dtpFechaInicio" type="text" class="form-control"></div></div>
		<div class="col-sm-6"><label>Vencimiento:</label> <div class="sandbox-container"><input  id="dtpFechaVencimiento" type="text" class="form-control"></div></div>
		<div class="col-sm-6"><label>Observaciones o datos extras:</label> <textarea  class="form-control mayuscula" id="txtObservaciones" rows="2" placeholder="¿Alguna observación o dato extra que desees recordar luego?"></textarea></div>
		<div class="col-sm-6"><label>Calculado:</label> <p>Periodo: <span id="spanPeriodo">1 día</span></p> <p>Intereses: <span id="spanInteres">S/. 0.00</span></p> <p>Monto total a pagar: S/.  <span id="spanMonto">0.00</span></p></div>
		<br>

	</div>
	<hr>
	<div class="pull-left"><button class="btn btn-success" id="btnLimpiarDatos"><i class="icofont icofont-eraser"></i> Limpiar datos</button></div>
	<div class="text-center"><button class="btn btn-primary" id="btnGuardarDatos"><i class="icofont icofont-diskette"></i> Guardar datos</button></div>
	
	<!-- fin de tab pane 1 -->
	</div>
	<div class="tab-pane fade  " id="tabBusqueda">
		<div class="row"> <label>Búsqueda del producto:</label>
			<input type="text" class="form-control" id="txtBuscarPersona" placeholder="Ingrese Nombre o Dni del cliente">
		</div><br>
		<div class="divResultadosPorPersona">
		<div class="row well">
			<p><strong><i class="icofont icofont-user-alt-2"></i> Datos del cliente</strong></p>
			<div class="col-sm-6"><label>Apellidos y Nombres: </label> <span class="text-primary mayuscula" id="spanNombre"></span></div>
			<div class="col-sm-6"><label>Documento de Identidad: </label> <span class="text-primary mayuscula"  id="spanDni"></span></div>
			<div class="col-sm-6"><label>Dirección domiciliaria: </label> <span class="text-primary mayuscula"  id="spanDireccion"></span></div>
			<div class="col-sm-6"><label>Correo electrónico: </label> <span class="text-primary mayuscula"  id="spanCorreo"></span></div>
			<div class="col-sm-6"><label>Celular(es): </label> <span class="text-primary mayuscula"  id="spanCelular"></span></div>
		</div>
		<p><strong><i class="icofont icofont-cube"></i> Listado de productos adquiridos por el cliente:</strong></p>
		
		<div class="row well hidden" id="rowWellFijo">
			<span class="hidden" id="lblIdProductosEnc"><?php echo $_GET['idprod']; ?></span>
			<div class="col-sm-6"><label><i class="icofont icofont-cube"></i> Producto: </label> <span class="text-success mayuscula" id="spanProducto"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-ui-tag"></i> Interés: </label> <span class="text-success"><span id="spanPorcent">4</span>%</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de inicio: </label> <span class="text-success" id="spanFechaInicio"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de límite de pago: </label> <span class="text-success" id="spanFechaFin"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Período entre las fechas: </label> <span class="text-success" id="spanPeriodo2"></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Monto entregado: </label> <span class="text-success">S/. <span id="spanMontoDado">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Intereses generados: </label> <span class="text-success">S/. <span id="spanIntGenerado">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Monto a pagar por parte del cliente: </label> <span class="text-success">S/. <span id="spanPagar">0.00</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Observaciones: </label> <em><span class="text-success mayuscula" id="spanObservacion">Ninguna</span></em></div>
			<div class="col-sm-8 col-sm-offset-2">
				<button class="btn btn-morado btn-outline" id="btn-imprimirTicketFijo"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
				<button class="btn btn-morado btn-outline" id="btn-imprimirImpresoraFijo"><i class="icofont icofont-print"></i> Voucher en impresora</button>
				<button class="btn btn-success btn-outline" id="btn-FinalizarPrestamoFijo"><i class="icofont icofont-rocket"></i> Finalizar préstamo</button>
			</div>
		</div>
		<div id="rowWellCambiante">
		</div>
	</div>
	
	</div>
	<div class="tab-pane fade  " id="tabDetalle"><p>Se encontraron <strong> <?php require('php/contarVencidos.php'); ?></strong> productos de fecha límite.</p>
		<div class="row hidden-xs"><strong>
			<div class="col-sm-4">Nombre del producto</div>
			<div class="col-sm-1">Monto S/.</div>
			<div class="col-sm-4">Dueño</div>
			<div class="col-sm-2">Fecha</div></strong>
		</div>
		<div class="row" id="divProdAVencerse">
			<p>Sin elementos que mostrar</p>
		</div>
	</div>

  </div>
  <div class="pull-right">User actual: <em class="mayuscula"><?php echo $_SESSION['Sucursal'].' - '; ?><span id="spanUsuario"><?php echo $_SESSION['Atiende'] ?></span></em></div>
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
<script>
$(document).ready(function () {
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('#dtpFechaVencimiento').val(moment().add(1, 'days').format('DD/MM/YYYY'));
	var idNew = <?php if (isset($_GET["idprod"])) { echo $_GET["idprod"]; }else{ echo 0;} ?>;
	
	//console.log('usuario ' + <?php echo $_SESSION['idUsuario']; ?>)
	if(idNew==0){ console.log('nada')}
	else {
		$('#rowWellFijo').removeClass('hidden');
		$('#rowWellCambiante').addClass('hidden');

		console.log('solcitar producto con id: ' + idNew);
		$.ajax({url: 'php/solicitarProductoPorId.php',type:'POST', data: {idProd: idNew}}). done(function (resp) {
			var dato = JSON.parse(resp);			
			
			$('#spanNombre').text(dato[0].cliApellidos+', '+dato[0].cliNombres);
			$('#spanDni').text(dato[0].cliDni);
			$('#spanDireccion').text(dato[0].cliDireccion);
			$('#spanCorreo').text(dato[0].cliCorreo);
			$('#spanCelular').text(dato[0].cliCelular);
			if(dato[0].prodObservaciones ==''){$('#spanObservacion').text('Ninguna');}else{$('#spanObservacion').text(dato[0].prodObservaciones);}

			var hoy= moment();
			var fechaIni =moment(dato[0].prodFechaInicial);
			var fechaFin=moment(dato[0].prodFechaVencimiento);
			var respu; console.log(dato[0]);
			if(hoy.diff(fechaFin, 'days')<=0){//diferencia entre dias, cuando es negativo quiere recir que faltan días para que venza el producto, si es positivo, ya venció su fecha límite
				respu=calculoIntereses(fechaIni, fechaFin, dato[0].prodMontoEntregado, dato[0].prodInteres);
			}
			else{//seccion cuando el producto ya paso su fecha límite
				respu=calculoIntereses(fechaIni, hoy, dato[0].prodMontoEntregado, dato[0].prodInteres);
				$('#spanObservacion').append('<p class="text-danger">Se exedió en ' + hoy.diff(fechaFin, 'days')+ ' días</p>');
			}
			
			$('#spanPeriodo2').text(respu[0].periodo);
			$('#spanPagar').text(parseFloat(respu[1].monto).toFixed(2));
			$('#spanIntGenerado').text(parseFloat(parseFloat(respu[1].monto).toFixed(2)-parseFloat(dato[0].prodMontoDado)).toFixed(2));

			moment.locale('es');
			$('#spanProducto').text(dato[0].prodNombre);
			$('#spanPorcent').text(dato[0].prodInteres);
			$('#spanFechaInicio').text(moment(dato[0].prodFechaInicial).format('dddd[,] DD MMMM YYYY'));
			$('#spanFechaFin').text(moment(dato[0].prodFechaVencimiento).format('dddd[,] DD MMMM YYYY'));
			
			$('#spanMontoDado').text(dato[0].prodMontoEntregado);
			
			if(dato[0].prodActivo==0){$('#btn-FinalizarPrestamoFijo').addClass('hidden');}
	
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
	var diferenciaDias, diferenciaSemanas, diferenciaRestoDias, montoaPagar;
	diferenciaDias = fechaVencimiento.diff(fechaAnterior, 'days');
		
	
	
	if(diferenciaDias<=6){diferenciaSemanas=0;diferenciaRestoDias=1; resultado.push({periodo: diferenciaDias + ' días'})}//$('#spanPeriodo2').text() }
	else{diferenciaSemanas =  parseInt(diferenciaDias/7); diferenciaRestoDias= diferenciaDias%7; resultado.push({periodo: diferenciaSemanas + ' semanas y ' +diferenciaRestoDias + ' días'})}//$('#spanPeriodo2').text() }
	


	if(diferenciaRestoDias>0 ){
		diferenciaSemanas+=1
	}

	montoaPagar = parseFloat(montoDado)+(parseFloat(montoDado)*parseInt(montoInteres)/100)*(diferenciaSemanas);
	resultado.push({monto:parseFloat(montoaPagar).toFixed(2) })
	
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
			if(diferenciaDias<=6){diferenciaSemanas=0;diferenciaRestoDias=1; $('#spanPeriodo').text(diferenciaDias + ' días'); }
			else{diferenciaSemanas =  parseInt(diferenciaDias/7); diferenciaRestoDias= diferenciaDias%7; $('#spanPeriodo').text(diferenciaSemanas + ' semanas y ' +diferenciaRestoDias + ' días') }
		}
		if($('#txtMontoEntregado').val()==''){montoDado =0; } else{montoDado= parseFloat($('#txtMontoEntregado').val());}
		if($('#txtMontoInteres').val()==''){montoInteres =0; }else{montoInteres = parseFloat($('#txtMontoInteres').val()/100);}
		
		

		if(diferenciaRestoDias>0 ){
			diferenciaSemanas+=1
			//console.log('semana de impuesto: ' + parseInt(diferenciaSemanas+1) + ' Monto a pagar')
		}

		montoaPagar = montoDado+(montoDado*montoInteres)*(diferenciaSemanas);
		/*console.log('semana de impuesto: ' + parseInt(diferenciaSemanas))
		console.log('dado: '+montoDado+' interes '+montoInteres+' semana: '+ parseInt(diferenciaSemanas))
		console.log('a pagar ' + montoaPagar);*/
		$('#spanInteres').text('S/. '+ parseFloat(montoDado*montoInteres* diferenciaSemanas).toFixed(2))
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
				interes: $('#txtMontoInteres').val(),
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
				interes: $('#txtMontoInteres').val(),
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
	  rellenarTabBusqueda();
	 }
	 if(target=='#tabDetalle'){
	 	$('#divProdAVencerse').children().remove();
	 	$.ajax({url: ' php/listarProductosVencidos.php', type: 'POST' }). done(function (resp) {
	 		
	 		var dato = JSON.parse(resp);
	 		
	 		$.each(dato, function (i, elem) { moment.locale('es')
	 			var limite = moment(elem.prodFechaVencimiento);
	 			//console.log(elem)
	 			$('#divProdAVencerse').append(`<div class="row">
			<div class="col-sm-4"><strong class="visible-xs-block">Producto: </strong>${$('#divProdAVencerse .row').length+1}. ${elem.prodNombre}</div>
			<div class="col-sm-1"><strong class="visible-xs-block">Monto: </strong>${parseFloat(elem.prodMontoPagar).toFixed(2)}</div>
			<div class="col-sm-4 mayuscula"><strong class="visible-xs-block">Dueño: </strong>${elem.propietario}</div>
			<div class="col-sm-2 mayuscula"><strong class="visible-xs-block">Fecha: </strong>${limite.startOf('day').fromNow()}</div>
			<span class="col-sm-1 push-right"> <a class="btn btn-negro btn-outline btnIcono" href="aplicativo.php?idprod=${elem.idproducto}"><i class="icofont icofont-social-aim"></i></a> </span>
		</div>`)

	 		})
	 		// body...
	 	})
	 }
});
function rellenarTabBusqueda(){

}

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
			})
			
			$('.modal-mostrarResultadosCliente').modal('show');
		})
		
		
	}
});
$('#rowUsuarioEncontrado').on('click', '.btnSelectUser', function () {
	//console.log($(this).attr('id'));
	var indice=$(this).parent().parent().index();
	$('#spanNombre').text( $('.rowEnc').eq(indice).find('.eleNom').text() );
	$('#spanDni').text( $('.rowEnc').eq(indice).find('.eleDni').text());
	$('#spanDireccion').text( $('.rowEnc').eq(indice).find('.eleDire').text());
	$('#spanCorreo').text( $('.rowEnc').eq(indice).find('.eleCorr').text());
	$('#spanCelular').text( $('.rowEnc').eq(indice).find('.eleCel').text());

	$('#rowWellFijo').addClass('hidden');
	$('#rowWellCambiante').removeClass('hidden');
	$('#rowWellCambiante').children().remove();

	$.ajax({ url: 'php/solicitarProductoPorCliente.php', type: 'POST', data: {idCli: $('.rowEnc').eq(indice).find('.eleIdCli').text() }}). done(function (resp) {
		console.log(JSON.parse(resp))
		$.each(JSON.parse(resp), function (i, dato) {
			var respu= calculoIntereses(moment(dato.prodFechaInicial), moment(dato.prodFechaVencimiento), dato.prodMontoDado, dato.prodInteres )

			var obsDin='';
			var intge=parseFloat(parseFloat(respu[1].monto).toFixed(2)-parseFloat(dato.prodMontoDado)).toFixed(2)

			if(dato.prodObservaciones==''){ obsDin='Ninguna'} else{ obsDin= dato.prodObservaciones}
			
			$('#spanPeriodo2').text(respu[0].periodo);
			$('#spanPagar').text(parseFloat(respu[1].monto).toFixed(2));
			$('#spanIntGenerado').text(parseFloat(parseFloat(respu[1].monto).toFixed(2)-parseFloat(dato.prodMontoDado)).toFixed(2));
			var botonAgregar='';
			if(parseInt(dato.prodActivo) ==1){
				botonAgregar = `<div class="col-sm-8 col-sm-offset-2">
				<button class="btn btn-morado btn-outline btn-imprimirTicketMovil"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
				<button class="btn btn-morado btn-outline btn-imprimirImpresoraMovil"><i class="icofont icofont-print"></i> Voucher en impresora</button>
				<button class="btn btn-success btn-outline btn-FinalizarPrestamoMovil"><i class="icofont icofont-rocket"></i> Finalizar préstamo</button>
			</div>`;
			}else{
			botonAgregar = `<div class="col-sm-8 col-sm-offset-2">
				<button class="btn btn-morado btn-outline btn-imprimirTicketMovil"><i class="icofont icofont-price"></i> Voucher en ticketera</button>
				<button class="btn btn-morado btn-outline btn-imprimirImpresoraMovil"><i class="icofont icofont-print"></i> Voucher en impresora</button>
			</div>`;}

			$('#rowWellCambiante').append(`<div class="row well" >
			<span class="hidden" id="lblIdProductosEnc">${dato.idProducto}</span>
			<div class="col-sm-6"><label><i class="icofont icofont-cube"></i> Producto: </label> <span class="text-success mayuscula" id="spanProducto">${dato.prodNombre}</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-ui-tag"></i> Interés: </label> <span class="text-success"><span id="spanPorcent">${dato.prodInteres}</span>%</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de inicio: </label> <span class="text-success" id="spanFechaInicio">${dato.prodFechaInicial}</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Fecha de límite de pago: </label> <span class="text-success" id="spanFechaFin">${dato.prodFechaVencimiento}</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-tasks-alt"></i> Período entre las fechas: </label> <span class="text-success" id="spanPeriodo2">${respu[0].periodo}</span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Monto entregado: </label> <span class="text-success">S/. <span id="spanMontoDado">${parseFloat(dato.prodMontoDado).toFixed(2)}</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Intereses generados: </label> <span class="text-success">S/. <span id="spanIntGenerado">${intge}</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-pie-chart"></i> Monto a pagar por parte del cliente: </label> <span class="text-success">S/. <span id="spanPagar">${parseFloat(dato.prodMontoPagar).toFixed(2)}</span></span></div>
			<div class="col-sm-6"><label><i class="icofont icofont-chat"></i> Observaciones: </label> <em><span class="text-success mayuscula" id="spanObservacion">${obsDin}</span></em></div>
			${botonAgregar}
		</div>`)
		})
	})

	$('.modal-mostrarResultadosCliente').modal('hide');

});
$('#btn-imprimirTicketFijo').click(function () {
	moment.locale('es');
	//console.log($('#rowWellFijo #spanProducto').text())
	$.ajax({url: '//localhost/perucash/printTicket.php', type: 'POST', data: {
		cliente: $('#spanNombre').text(),
		articulo: $('#rowWellFijo #spanProducto').text(),
		monto: parseFloat($('#rowWellFijo #spanMontoDado').text()).toFixed(2),
		obs: $('#rowWellFijo #spanObservacion').text(),
		hora : moment().format('h:mm a dddd DD MMMM YYYY'),
		usuario: $('#spanUsuario').text()
	}}).done(function(resp){console.log(resp);});
})
$('#btn-FinalizarPrestamoFijo').click(function () {
	var iProd=$('#rowWellFijo #lblIdProductosEnc').text();
	$.ajax({url:'php/updateFinalizarEstado.php', type: "POST", data: {idProd: iProd }}).done(function (resp) { console.log(resp)
		if(parseInt(resp)==1){$('#btn-FinalizarPrestamoFijo').addClass('hide'); }
		// body...
	});
	
});
$('#rowWellCambiante').on('click', '.btn-imprimirTicketMovil', function () {
	var contenedor = $(this).parent().parent();
	// console.log('nombre: '+ contenedor.find('#spanProducto').text());

	$.ajax({url: '//localhost/perucash/printTicket.php', type: 'POST', data: {
		cliente: $('#spanNombre').text(),
		articulo: contenedor.find('#spanProducto').text(),
		monto: parseFloat(contenedor.find('#spanMontoDado').text()).toFixed(2),
		obs: contenedor.find('#spanObservacion').text(),
		hora : moment().format('h:mm a dddd DD MMMM YYYY'),
		usuario: $('#spanUsuario').text()
	}}).done(function(resp){console.log(resp);});
});
$('#rowWellCambiante').on('click', '.btn-FinalizarPrestamoMovil', function () {
	var contenedor = $(this).parent().parent();
	var indice = $(this).parent().parent().index();
	var iProd=contenedor.find('#lblIdProductosEnc').text();

	$.ajax({url:'php/updateFinalizarEstado.php', type: "POST", data: {idProd: iProd }}).done(function (resp) { console.log(resp)
	 	if(parseInt(resp)==1){$('#rowWellCambiante').eq(indice).find('.btn-FinalizarPrestamoMovil').addClass('hide'); }
 	});
});
</script>
</body>
</html>