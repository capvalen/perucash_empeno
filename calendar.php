<?php session_start();
if( !isset($_SESSION['access_token'])){header('Location: index.php');}else{
	if( $_COOKIE['ckPower']=="7"){ header('Location: bienvenido.php'); } }
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Créditos: PeruCash</title>
	<?php include "header.php"; ?>
	<link href='fullcalendar/core/main.css' rel='stylesheet' />
	<link href='fullcalendar/daygrid/main.css' rel='stylesheet' />
</head>

<body>

<style>
.fc-day-header, .fc-content{
	text-transform: capitalize;
}
.close {
	color: #755e5e;
}
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
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Calendario <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<button class="btn btn-default" id="btnPrueba">Prueba</button>
			<div id='calendar'></div>

				
			<!-- Fin de contenido principal -->
			</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
   </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<!-- Modal para actualizarInfo  -->
<div class="modal fade" id="modalDetalleCalendar" tabindex="-1" role="dialog" aria-labelledby="">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-body">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4><i class="icofont icofont-ui-calendar"></i> Detalle de evento</h4>
			<div class="container-fluid">
			<div class="row text-right">
				<div class="text-left">
					<label for="">Título</label>
					<input type="text" class="form-control" id="txtTituloEvento">
					<label for="">Descripción</label>
					<input type="text" class="form-control" id="txtTituloEvento">
					<label for="">Fecha de inicio</label>
					<input type="date" class="form-control" id="txtTituloEvento">
					<label for="">Fecha final</label>
					<input type="date" class="form-control" id="txtTituloEvento">
				</div>
				<img src="images/anotacion171511.jpg?ver=1.1" class="img-responsive" alt="">
				<p class="text-center orange-text text-lighten-1 hidden"><strong>Tu información fue guardada:</strong></p>
				<div class="text-center orange-text text-lighten-1"><span id="spanMyAlerta"></span><h3 class="text-center orange-text text-lighten-1" id="h1MyAlerta"></h3></div>
				<button class="btn btn-danger btn-outline" data-dismiss="modal" ><i class="icofont icofont-ui-delete"></i> Borrar</button>
				<button class="btn btn-warning btn-outline" data-dismiss="modal" ><i class="icofont icofont-refresh"></i> Actualizar</button>
			</div>
		</div>

	</div>
	</div>
</div>
</div>

<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>
<script src='fullcalendar/core/main.js?version=1.0'></script>
<script src='fullcalendar/interaction/main.js'></script>
<script src='fullcalendar/core/locales/es.js'></script>
<script src='fullcalendar/daygrid/main.js'></script>
		

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>

document.addEventListener('DOMContentLoaded', function() {
	var calendarEl = document.getElementById('calendar');

	var calendar = new FullCalendar.Calendar(calendarEl, {
		plugins: [ 'dayGrid', 'interaction' ],
		locale: 'es',
		droppable: true,
		editable: true,
		eventLimit: true, // cuando hay muchos eventos en el día, los visualiza en un popover
		header: {
			left: 'title'
		},
		events: [{
			id: 265,
			title: "hola mundo",
			contenido: 'Lectura nueva por comprar',
			usuario: 'Carlos',
			estado: 'Pendiente',
			allDay: true,
			start: '2019-11-01',
			end: '2019-11-03',
			backgroundColor: '#942da7',
			borderColor: '#942da7',
			textColor: '#fff'
		}],
		eventClick: function(info) {
			console.log('Evento: ' + info.event.title + " " + info.event.id +"\nDescripcion: " + info.event.extendedProps.contenido +"\n" + 'Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY +"\n"+ 'View: ' + info.view.type)
			// cambiando el color del borde por ejm:
			//info.el.style.borderColor = 'red';
			$('#modalDetalleCalendar').modal('show');
		},
		eventDrop: function(info) {
			alert(info.event.title + " fue soltado en " + info.event.start.toISOString());

			if (!confirm("¿Seguro que deseas mover el evento?")) {
				info.revert();
			}
		},
		eventResize: function(info) {
			alert(info.event.title + " finaliza en" + info.event.end.toISOString());

			if (!confirm("is this okay?")) {
				info.revert();
			}
		}
	});

	calendar.render();


	calendar.on('dateClick', function(info) {
		console.log('clicked on ' + info.dateStr);
	});
});
$('#btnPrueba').click(function(){
	calendar( 'addEventSource', [{
		title: "hola mundo",
		allDay: true,
		start: '2019-11-01',
		end: '2019-11-03',
		backgroundColor: '#942da7',
		borderColor: '#942da7',
		textColor: '#fff'
	}] );
})
</script>
<?php } ?>
</body>

</html>