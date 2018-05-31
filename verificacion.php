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
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.5" >
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.3">
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.47" >
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
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			
			<div class="logoEmpresa ocultar-mostrar-menu">
				<img class="img-responsive" src="images/empresa.png?version=1.1" alt="">
			</div>
			<li>
					<a href="#!"><i class="icofont icofont-home"></i> Inicio</a>
			</li>
			<li>
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
			<li class="active">
					<a href="verificacion.php"><i class="icofont icofont-medal"></i> Verificación</a>
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
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Área de verificación </h2><hr>
			<h4 class="purple-text text-lighten-1">Actividades sin aprobar</h4>
			<p>Los siguientes códigos son para verificar, sea responsable por favor</p>
			<table class="table table-hover">
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
			<table class="table table-hover">
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
	$.ajax({url: 'php/updateAprobarTicket.php', type: 'POST', data: {idTick: $('#aproTckId2').text() }}).done(function (resp) {
		if(resp){
			location.reload();
		}else{
			$('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: \n' + resp);
			$('.modal-GuardadoError').modal('show');
		}
	});
});
</script>
<?php } ?>
</body>

</html>