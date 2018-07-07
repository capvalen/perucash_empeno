<?php   ?>
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
		<link rel="stylesheet" href="css/sidebarDeslizable.css?version=1.1.6" >
		<link rel="stylesheet" href="css/cssBarraTop.css?version=1.0.5">
		<link rel="stylesheet" href="css/estilosElementosv3.css?version=3.0.51" >
		<link rel="stylesheet" href="css/colorsmaterial.css">
		<link rel="stylesheet" href="css/icofont.css"> <!-- iconos extraidos de: http://icofont.com/-->
		<link rel="stylesheet" href="css/bootstrap-datepicker3.css">
		<link rel="stylesheet" href="css/bootstrap-select.min.css?version=0.2" >
		<link rel="stylesheet" href="css/animate.css" >
		
</head>

<body>

<style>
.btnAgregarAlmacen{padding: 2px;}
#txtAlmacenCodProducto{font-size:24px;}
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
			<h2 class="purple-text text-lighten-1">Almacén <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="row">
			<div class="col-md-6" id="divCmbEstantes">
				<select class="selectpicker mayuscula" id="cmbEstantes" data-live-search="true" data-width="100%" title="Mostradores">
					<?php require 'php/listarEstantesOPT.php'; ?>
				</select>
			</div>
			</div>
			<br>
			<?php 
			if( isset($_GET['estanteAmarillo']) || isset($_GET['estanteExibicion']) || isset($_GET['estanteRojo']) ){
				$estante = array (
					array(1, 2, 3, 4),//pisos
					array('A','B','C'),//seccion
				);
			}
			else if( isset($_GET['estanteNegro']) || isset($_GET['estantePlateado']) ){
				$estante = array (
					array(1, 2, 3, 4, 5),//pisos
					array('A','B','C'),//seccion
				);
			}
			else if( isset($_GET['estanteLaptops']) ){
				$estante = array (
					array(1, 2, 3, 4),//pisos
					array('A','B','C', 'D', 'E', 'F'),//seccion
				);
			}
			else {
				$_GET['almacen']=0;
				$estante = array (
					array(1),//pisos
					array('A'),//seccion
				);
			}
			//var_dump($estante[1]);
			// foreach ($estante[0] as $piso ) {
			// 	echo "<br>".$piso;
			// 	foreach ($estante[1] as $seccion) {
			// 		echo ' '.$seccion;
			// 	}
			// }
			?>
			<div class="row container-fluid table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center" >Piso / Sección</th>
						<?php 
						foreach ($estante[1] as $seccionn  ) : ?>
							<th class="text-center" ><? echo $seccionn ?></th>
						<?php endforeach;
						 ?>
						</tr>
					</thead>
					<tbody>
					<?php 
					foreach ($estante[0] as $pisoo) : ?>
						<tr>
							<th class="text-center"><?= $pisoo; ?></th>
					<?php foreach ($estante[1] as $estantee ) : ?>
							<td dCol="<?= $pisoo; ?>" dRow="<?= $estantee ?>"><button class="btn btn-azul btn-outline btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
					<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					
					</tbody>
				</table>
			</div>

				
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<!-- Modal para insertar productos a almacén -->
<div class="modal fade modal-almacenInsertar" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header-primary">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Insertar producto a almacén</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid"> <span id="spanCubicaje" ></span>
					<p>Rellene cuidadosamente la siguiente información</p>
					<label for="">Código del producto</label>
					<input type="number" class="form-control input-lg mayuscula text-center " id="txtAlmacenCodProducto" val="0.00" autocomplete="off">
					<label for="">¿Observaciones?</label>
					<input type="text" class="form-control input-lg mayuscula" id="txtAlmacenObs" autocomplete="off">
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-azul btn-outline" id="btnInsertAlmacenProd" ><i class="icofont icofont-bubble-down"></i> Insertar proceso</button>
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
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.selectpicker').selectpicker('refresh');
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
	$.ajax({url: 'php/contenidoAlmacen.php', type: 'POST', data: { almacen: <?= $_GET['almacen']; ?>}}).done((resp)=> {
		$.each( JSON.parse(resp), function (i, dato) { console.log(dato)
			$("td[dCol='"+dato.numPiso+"'][dRow='"+dato.zonaDescripcion+"']" ).prepend(`<p><a href="productos.php?idProducto=${dato.idProducto}">${dato.idProducto}</a></p>`);
			//console.log(dato.numPiso +' '+dato.zonaDescripcion);
		});
	});
});
$('#cmbEstantes').on('changed.bs.select', function (e) {
	var id= $('#divCmbEstantes').find('.selected a').attr('data-tokens');
	switch (id) {
		case '1':
			window.location.href = 'almacen.php?estanteNingun&almacen='+id; break;
		case '2':
			window.location.href = 'almacen.php?estanteExibicion&almacen='+id; break;
		case '3':
			window.location.href = 'almacen.php?estanteLaptops&almacen='+id; break;
		case '4':
			window.location.href = 'almacen.php?estantePlateado&almacen='+id; break;
		case '4':
			window.location.href = 'almacen.php?estanteAmarillo&almacen='+id; break;
		case '5':
			window.location.href = 'almacen.php?estanteRojo&almacen='+id; break;
		case '6':
			window.location.href = 'almacen.php?estanteNegro&almacen='+id; break;
		default:
			break;
	}
	
});
$('.btnAgregarAlmacen').click(function() {
	$('#spanCubicaje').attr('dcol', $(this).parent().attr('dcol'));
	$('#spanCubicaje').attr('drow', $(this).parent().attr('drow'));
	$('.modal-almacenInsertar').modal('show');
});
$('#btnInsertAlmacenProd').click(function() {
	var piso= $('#spanCubicaje').attr('dcol');
	var zona= $('#spanCubicaje').attr('drow');
	console.log( zona );
	$.ajax({url: 'php/insertAlmacenv3.php', type: 'POST', data: {
		idProducto : $('#txtAlmacenCodProducto').val(),
		estante: <?= $_GET['almacen']; ?>,
		piso: piso,
		zona: zona,
		obs: $('#txtAlmacenObs').val()
	 }}).done(function(resp) {
		console.log(resp)
	});
});
</script>
<?php } ?>
</body>

</html>