<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Verificación: PeruCash</title>

		<!-- Bootstrap Core CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Custom CSS -->
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.7" >
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

</style>
<div id="wrapper">

	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable ">
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Área de verificación </h2><hr>
			<h4 class="purple-text text-lighten-1">Actividades sin aprobar</h4>
			<p>Los siguientes códigos son para verificar, sea responsable por favor</p>
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>N° Ticket</th>
						<th>Usuario</th>
						<th>Tipo de transacción</th>
						<th>Monto</th>
						<th>Producto</th>
						<th>Observaciones</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
				
			<?php
				if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==9 ){
					include "php/listarTicketsSinAprobar.php";
				}else{
					echo '<p>No tienes permmiso para estar husmeando acá.</p>';
				}
			?>
				</tbody>
			</table>
			<h4 class="purple-text text-lighten-1">Actividades rechazadas</h4>
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>N° Ticket</th>
						<th>Usuario</th>
						<th>Tipo de transacción</th>
						<th>Monto</th>
						<th>Producto</th>
						<th>Observaciones</th>
					</tr>
				</thead>
				<tbody>
					<?php include "php/listarTicketsNoAprobado.php"; ?>
				</tbody>
			</table>

			
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<!-- Modal para verificar cambio en ticket  -->
<div class="modal fade modal-aceptPagoTicket" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-primary">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Aprobar solicitud</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row"><span class="hidden" id="pagoId2"></span>
			<p>Operación (<strong id="pagoModif"></strong>) del ticket <strong>#<span id="pagoNum2"></span></strong> son <strong>S/. <span id="pagoMoney"></span></strong></p>
			<p>¿Está realmente seguro?</p>
			<p>Puedes agregar algún comentario</p>
			<input type="text mayuscula" id="txtMotivoPago" class="form-control" autocomplete="off">
			</div>
		</div>
			
		<div class="modal-footer">
			<button class="btn btn-default btn-outline" data-dismiss="modal" ><i class="icofont icofont-warning-alt"></i> No</button>
			<button class="btn btn-azul btn-outline" id="btnAceptarPago" ><i class="icofont icofont-money"></i> Sí</button>
		</div>
	</div>
	</div>
</div>
</div>


<!-- Modal para verificar cambio en ticket  -->
<div class="modal fade modal-modifyTicket" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-danger">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Aprobar solicitud</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row"><span class="hidden" id="tckId2"></span>
			<p>Está por <strong id="stroTicktModif"></strong> el ticket a «<strong id="tckNum2"></strong>»</p>
			<p>¿Está realmente seguro?</p>
			<p>Puedes agregar algún comentario</p>
			<input type="text" id="txtMotivoDeny" class="form-control" autocomplete="off">
			</div>
		</div>
			
		<div class="modal-footer">
			<button class="btn btn-default btn-outline" data-dismiss="modal" ><i class="icofont icofont-warning-alt"></i> No</button>
			<button class="btn btn-danger btn-outline" id="btnAceptarAnular" ><i class="icofont icofont-warning-alt"></i> Sí</button>
		</div>
	</div>
	</div>
</div>
</div>

<!-- Modal para modificar ticket  -->
<div class="modal fade modal-modificarTicket" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-warning">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Modificar ticket</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
			<p>Se está modificando el ticket:</p><span class="hidden" id="tckId"></span>
			<p><strong>N° de Ticket:</strong> <span id="tckNum"></span></p>
			<p><strong>Transacción:</strong> <span id="tckTransac"></span></p>
			<p><strong>Producto:</strong> <span class="mayuscula" id="tckProd"></span></p>
			<p><strong>Observaciones:</strong> <div id="tckObs"></div></p>
			<p><strong>Monto: S/. </strong> <input class="form-control input-lg text-center esDecimal" type="number" id="txtTckMonto"></p>
			<p><strong>Otras Observaciones: S/. </strong> <input class="form-control input-lg" type="text" id="txtTckOtraObs"></p>
			</div>
		</div>
			
		<div class="modal-footer">
			<button class="btn btn-warning btn-outline" id="btnEditTicketModal" ><i class="icofont icofont-warning-alt"></i> Editar ticket</button>
		</div>
	</div>
	</div>
</div>
</div>

<!-- Modal para aprobar ticket  -->
<div class="modal fade modal-aprobarTicket" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-primary">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Modificar ticket</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
			<p>¿Deseas aprobar?</p><span class="hidden" id="aproTckId"></span>
			<p><strong>N° de Ticket:</strong> # <span id="aproTckId2"></span></p>
			<p><strong>Monto: S/. </strong> S/. <span  id="aproTckMonto"></span></p>
			<p><strong>Transacción:</strong> <span id="aproTckTransac"></span></p>
			<p><strong>Producto:</strong> <span class="mayuscula" id="aproTckProd"></span></p>
			<p><strong>Observaciones:</strong> <div id="aproTckObs"></div></p>
			</div>
		</div>
			
		<div class="modal-footer">
			<button class="btn btn-default btn-outline" data-dismiss="modal" ><i class="icofont icofont-warning-alt"></i> Aún  no</button>
			<button class="btn btn-azul btn-outline" id="btnAcceptTicketModal" ><i class="icofont icofont-like"></i> Sí, aprobar</button>
		</div>
	</div>
	</div>
</div>
</div>


<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
$.interesGlobal=4;
datosUsuario();

$(document).ready(function(){
	$("button[data-toggle='tooltip']").tooltip();
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});
$('.btnApproveTicket').click(function () {
	var padre = $(this).parent().parent();
	var idTick= padre.attr('data-id');

	$('#aproTckId').text(idTick);
	$('#aproTckId2').text(idTick);
	$('#aproTckMonto').text(padre.find('.tdValor').text());
	$('#aproTckTransac').text(padre.find('.tdDescip').text());
	$('#aproTckProd').html(padre.find('.tdNombre').html());
	$('#aproTckObs').html(padre.find('.tdObser').html());

	$('.modal-aprobarTicket').modal('show');
});
$('.btnEditTicket').click(function () {
	var padre = $(this).parent().parent();
	var idTick= padre.attr('data-id');


	$('#tckId').text(idTick);
	$('#tckNum').text(padre.find('.tkNum').text());
	$('#tckTransac').text(padre.find('.tdDescip').text());
	$('#tckProd').html(padre.find('.tdNombre').html());
	$('#tckObs').html(padre.find('.tdObser').html());
	$('#txtTckMonto').val(padre.find('.tdValor').text());

	$('.modal-modificarTicket').modal('show');
});
$('#btnEditTicketModal').click(function () {
	var observa='';
	if($('#txtTckOtraObs').val()!==''){
		observa='<p><?php echo $_COOKIE['ckAtiende']; ?> dice: '+$('#txtTckOtraObs').val()+'</p>'
	}else{
		observa='';
	}
	if( $('#txtTckMonto').val()>0 ){
		$.ajax({url: 'php/updateTicketPorAprobar.php', type: 'POST', data: {idTick: $('#tckId').text(), monto: $('#txtTckMonto').val(), obs:  observa }}).done(function (resp) {
			if(resp){
				location.reload();
			}else{
				$('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: \n' + resp);
				$('.modal-GuardadoError').modal('show');
			}
		});
	}
});
$('.btnDenyTicket').click(function () {
	var padre = $(this).parent().parent();
	var idTick= padre.attr('data-id');
	$('#stroTicktModif').text(' anular ');
	$('#tckId2').text(idTick);
	$('#tckNum2').text('#'+idTick);
	$('.modal-modifyTicket').modal('show');
});
$('#btnAceptarAnular').click(function () {
	if($('#txtMotivoDeny').val()!==''){
		observa='<p><?php echo $_COOKIE['ckAtiende']; ?> dice: '+$('#txtMotivoDeny').val()+'</p>';
	}else{
		observa='';
	}
	$.ajax({url: 'php/updateAnularTicket.php', type: 'POST', data: {idTick: $('#tckId2').text(), obs:  observa }}).done(function (resp) {
		//console.log(resp)
		if(resp){
			location.reload();
		}else{
			$('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: \n' + resp);
			$('.modal-GuardadoError').modal('show');
		}
	});
});
$('#btnAcceptTicketModal').click(function () {
	$.ajax({url: 'php/updateAprobarTicket.php', type: 'POST', data: {idTick: $('#aproTckId2').text() }}).done(function (resp) { console.log(resp)
		if(resp){
			location.reload();
		}else{
			$('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: \n' + resp);
			$('.modal-GuardadoError').modal('show');
		}
	});
});
$('.btnCobrarTicket').click(function() {
	var padre = $(this).parent().parent();
	var idTick= padre.attr('data-id');

	$('#pagoId2').text(idTick);
	$('#pagoNum2').text(idTick);
	$('#pagoMoney').text(padre.find('.tdValor').text());
	$('#pagoModif').text(padre.find('.tdDescip').text());
	$('.modal-aceptPagoTicket').modal('show');
});
$('#btnAceptarPago').click( ()=> {
	var obs = $('#txtMotivoPago').val();
	$.ajax({url: 'php/updateCobrarTicket.php', type: 'POST', data: {idTick: $('#pagoId2').text(), obs: obs }}).done((resp)=>{ console.info(resp);
		if(resp){
			$('tr[data-id ='+$('#pagoId2').text()+']').find('.botonesTd').children().remove();
			$('tr[data-id ='+$('#pagoId2').text()+']').find('.botonesTd').append('<h5 class="light-green-text"><i class="icofont icofont-check-circled"></i></h5>');
			$('.modal-aceptPagoTicket').modal('hide');
			$('.modal-GuardadoCorrecto #spanBien').text('Pago realizado');
			$('.modal-GuardadoCorrecto').modal('show');
		}else{

		}
	});
});
</script>
<?php } ?>
</body>

</html>