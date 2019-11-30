<?php session_start();
if( !isset($_SESSION['access_token'])){header('Location: index.php');}else{
	if( $_COOKIE['ckPower']=="7"){ header('Location: bienvenido.php'); } }

$dias = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom' ];
$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$finBucle = false;
$saltoLinea= false;
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Calendario: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>

<style>
.btnAgregarAlmacen{padding: 2px;}
#txtAlmacenCodProducto{font-size:24px;}
.pProdAlmacen{padding: 6px 5px;}
.pProdAlmacen:hover{background-color: #f5f5f5;}
.aRemoveProductAlmacen{color: #d50000;}
.circle{
	background: #6d4cc3;
	border-radius: 30px;
	color: white;
	height: 30px;
	font-weight: bold;
	width: 30px;
	display: inline-block;
}
.circle>.sDia{
	padding-top: 6px;
	padding-left: 6px;
}
.sDia{display: inline-block;}
.btnAddCalendar{float: right; color: #8d62ff; }
.btnAddCalendar:hover{color: #6042b1;
    background-color: #ffffff;
    border-color: #d6d6d6;}
h3{color: #ab47bc;}
.btnEvento{display: block;}
.popover-title{background-color: #ffffff;}
.spanFlecha{cursor:pointer;}
.divContenidos {
    margin-top: 6px;
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
			<?php if(!isset($_GET['fecha']) ){ $fecha = new DateTime( date('Y-m').'-01' ); }else{
				$fecha = new DateTime( $_GET['fecha']. '-01');
			}
			$fDisplay = $fecha->format('Y-m-d');
			 ?>
			<h2 class="purple-text text-lighten-1">Calendario </h2><hr>
			<h3 class="text-center"> <span class="spanFlecha" onclick="restarAnio('<?= $fDisplay; ?>')"><i class="icofont icofont-rounded-left"></i></span> <?= $fecha->format('Y'); ?> <span class="spanFlecha" onclick="sumarAnio('<?= $fDisplay; ?>')"><i class="icofont icofont-rounded-right"></i></span> </h3>
			<h3 class="text-center"> <span class="spanFlecha" onclick="restarMes('<?= $fDisplay; ?>')"><i class="icofont icofont-rounded-left"></i></span> <?= $meses[$fecha->format('n')-1]; ?> <span class="spanFlecha" onclick="sumarMes('<?= $fDisplay; ?>')"><i class="icofont icofont-rounded-right"></i></span> </h3>
			<?php $dia=1;
			$i=0;
			$maximoDias= $fecha->format("t"); //echo $fecha->format('w'); ?></p>
			<table class="table table-bordered">
			<thead>
				<tr>
					<th>Dom</th>
					<th>Lun</th>
					<th>Mar</th>
					<th>Mie</th>
					<th>Jue</th>
					<th>Vie</th>
					<th>Sab</th>
				</tr>
			</thead>
			<tbody>
			<?php while($dia <= $maximoDias) {
				if($saltoLinea ==true){ $i=0; $saltoLinea=false; }
				
				while ($i < 7 && $dia <= $maximoDias) {
					if($i==0){ echo "<tr>"; $saltoLinea=true; }
					if($i == $fecha->format('w') ){ 
						if(date('Y-m-d')==$fecha->format('Y-m-d')){
							echo "<td data-fecha='{$fecha->format('j')}' id='tdHoy'><div class='circle'><div class='sDia'>". $fecha->format('j')."</div></div> <button class='btn btn-default btn-sm btnAddCalendar invisible' onclick='agregarEvento(`{$fecha->format('Y-m-d')}`)'><i class='icofont icofont-ui-add'></i></button> <div class='divContenidos'></div></td>";
						}else{
							echo "<td data-fecha='{$fecha->format('j')}' > <div class='sDia'>". $fecha->format('j')."</div> <button class='btn btn-default btn-sm btnAddCalendar invisible' onclick='agregarEvento(`{$fecha->format('Y-m-d')}`)'><i class='icofont icofont-ui-add'></i></button> <div class='divContenidos'></div></td>";
						}
						
						$fecha->add(new DateInterval('P1D')); $dia++; 
					} // $fecha->format('j')." |  "
					else{ echo "<td></td>"; }
					if($i==6){ echo " </tr>"; $saltoLinea ==true; }
					$i++;
				}
				 ?>
				
				
			
			<?php  } ?>
			</tbody>
			</table>
			
				
			<!-- Fin de contenido principal -->
		</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
    </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<!-- Modal para decir que hay una observación  -->
<div class="modal fade " id='modalAsignarEvento' tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-indigo	">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-ui-calendar"></i> Nuevo evento</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<p>Estas ingresando un evento nuevo en: <span id="spanFechaElegida"></span></p>
			<input class="form-control" id="txtTituloNueva" type="text" placeholder='Título del evento'>
			<textarea class="form-control" id="txtDescripcionNueva" rows="3" placeholder='Descripción del evento (Opcional)'></textarea>
			<select class="form-control" id="slpProceso">
				<?php include 'php/optProcesosCalendario.php'; ?>
			</select>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-indigo btn-outline" id="modalGuardarEvento" ><i class="icofont icofont-save"></i> Insertar evento</button>
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
datosUsuario();
ocultar();

$(document).ready(function(){
	listarEventos()

	
	$('td').hover(function(){
		$(this).find('.btnAddCalendar').removeClass('invisible');
	});
	$('td').mouseleave(function(){
		$(this).find('.btnAddCalendar').addClass('invisible');
	});
});
function listarEventos(){
	$.post('php/listarEventosCalendarioMensual.php', {mensual: '<?= $fDisplay; ?>'}).done(function(resp){
		console.log(resp);
		$.each(JSON.parse(resp), function(index, elem){
			console.log(elem)
			var dia = moment(elem.calFecha).format('D');
			console.log(dia)
			$(`td[data-fecha="${dia}"] .divContenidos`).append(`<p class='text-capitalize btnEvento'><a href='#!'>${elem.calTitulo}</a></p>`);
		});
		$('.btnEvento').popover();
	})
}
function agregarEvento(fecha){
	moment.locale('es')
	var fechaNueva = moment(fecha);
	
	$('#spanFechaElegida').text(fechaNueva.format('dddd[,] DD MMMM [de] YYYY'))
	$('#modalAsignarEvento').modal('show');

	$('#modalGuardarEvento').click(function () {
		pantallaOver(true);
		$.ajax({url: 'php/insertarCalendarioEvento.php', type:'POST', data: {
			fecha: fecha, titulo: $('#txtTituloNueva').val(), descipcion: $('#txtDescripcionNueva').val(), proceso:$('#slpProceso').val()
		}}).done(function (resp) {
			console.log(resp);
			if(resp=='todo ok'){
				location.reload();
			}
			pantallaOver(false);
		})
	});
}
function sumarMes(objeto) {
	var fechaO = moment(objeto).add(1, 'M').format('YYYY-MM-DD');
	console.log(fechaO);
	location.href = 'calendario.php?fecha='+fechaO;
}
function sumarAnio(objeto) {
	var fechaO = moment(objeto).add(1, 'y').format('YYYY-MM-DD');
	console.log(fechaO);
	location.href = 'calendario.php?fecha='+fechaO;
}
function restarMes(objeto) {
	var fechaO = moment(objeto).subtract(1, 'M').format('YYYY-MM-DD');
	console.log(fechaO);
	location.href = 'calendario.php?fecha='+fechaO;
}
function restarAnio(objeto) {
	var fechaO = moment(objeto).subtract(1, 'y').format('YYYY-MM-DD');
	console.log(fechaO);
	location.href = 'calendario.php?fecha='+fechaO;
}

</script>
<?php } ?>
</body>

</html>
<!--  -->