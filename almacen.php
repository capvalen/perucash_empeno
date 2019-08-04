<?php session_start();
if( !isset($_SESSION['access_token'])){header('Location: index.php');}else{
	if( $_COOKIE['ckPower']=="7"){ header('Location: bienvenido.php'); } }
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Registro: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>

<style>
.btnAgregarAlmacen{padding: 2px;}
#txtAlmacenCodProducto{font-size:24px;}
.pProdAlmacen{padding: 6px 5px;}
.pProdAlmacen:hover{background-color: #f5f5f5;}
.aRemoveProductAlmacen{color: #d50000;}
h3{color: #ab47bc;}
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
			if( isset($_GET['estanteExhibicion'])  ){
				$estante = array (
					array(1),//pisos
					array('A','B','C', 'D'),//seccion
				);
				if( isset($_GET['estanteAmarillo'])){ }
				if( isset($_GET['estanteExhibicion'])){ echo '<h3>Almacén 1</h3>';}
				if( isset($_GET['estanteRojo'])){ echo '<h3>Estante Rojo</h3>';}
			}
			else if( isset($_GET['estanteRojo']) ){
				$estante = array (
					array(5, 4, 3, 2, 1),//pisos
					array('A','B','C'),//seccion
				);
				echo '<h3>Estante Rojo</h3>';
			}
			else if( isset($_GET['estanteAmarillo']) ){
				$estante = array (
					array(5, 4, 3, 2, 1),//pisos
					array('A','B','C'),//seccion
				);
				echo '<h3>Estante Amarillo</h3>';
			}
			else if( isset($_GET['estanteNegro']) || isset($_GET['estantePlateado']) ){
				$estante = array (
					array(5, 4, 3, 2, 1),//pisos
					array('A','B','C'),//seccion
				);
				if( isset($_GET['estanteNegro'])){ echo '<h3>Estante Negro</h3>';}
				if( isset($_GET['estantePlateado'])){ echo '<h3>Estante Plateado</h3>';}
			}
			else if( isset($_GET['estanteLaptops']) ){
				$estante = array (
					array(5, 4, 3, 2, 1),//pisos
					array('A','B','C', 'D', 'E', 'F'),//seccion
				);
				if( isset($_GET['estanteLaptops'])){ echo '<h3>Estante Negro de Laptops</h3>';}
			}
			else if( isset($_GET['cuarto2']) ){
				$estante = array (
					array(1),//pisos
					array('A', 'B', 'C', 'D'),//seccion
				);
				if( isset($_GET['cuarto2'])){ echo '<h3>Segundo almacén</h3>';}
			}
			else if( isset($_GET['televisores']) ){
				$estante = array (
					array(1),//pisos
					array('A'),//seccion
				);
				if( isset($_GET['televisores'])){ echo '<h3>Zona televisores</h3>';}
			}
			else {
				if( isset($_GET['estanteNingun'])){ echo '<h3>Sin estante</h3>';}
				$_GET['almacen']=1;
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
				 <table class="table table-bordered "> <!-- table-hover -->
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
							<td dCol="<?= $pisoo; ?>" dRow="<?= $estantee ?>"><button class="btn btn-azul btn-outline btnSinBorde btn-block btn-lg btnAgregarAlmacen"><i class="icofont icofont-inbox"></i></button></td>
					<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					
					</tbody>
				</table>
			</div>

				
			<!-- Fin de contenido principal -->
		</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
    </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
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

<!-- Modal para confirmar elminiación -->
<div class="modal fade modalRemoveAnalisis" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" >
	<div class="modal-content ">
	<div class="modal-header-danger">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Retirar de almacén</h4>
	</div>
	  <div class="modal-body">
		<p >¿Está seguro de eliminar <strong class="text-uppercase" id="strNombre"></strong>?</p><span id="idRemove"></span>
		<input type="text" class='form-control' id='txtRetirarObs' placeholder='Observaciones'>
		<div class="modal-footer d-flex justify-content-between">
			<button class="btn btn-danger btn-outline" id="btnAproveRemoveAnalisis"><i class="icofont icofont-bug"></i> Sí, retirar</button>
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
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.selectpicker').selectpicker('refresh');
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
	$.ajax({url: 'php/contenidoAlmacen.php', type: 'POST', data: { almacen: <?= $_GET['almacen']; ?>}}).done((resp)=> { console.log(resp);
		$.each( JSON.parse(resp), function (i, dato) { console.log(dato)
			$("td[dCol='"+dato.numPiso+"'][dRow='"+dato.zonaDescripcion+"']" ).prepend(`
			<p class="text-center pProdAlmacen" data-toggle="tooltip" data-placement="top" title="${dato.prodNombre.toUpperCase()}"><a href="productos.php?idProducto=${dato.idProducto}">${dato.idProducto}</a> <a href="#!" class="aRemoveProductAlmacen pull-right" data-cubicaje="${dato.idCubicaje}"><i class="icofont icofont-close" ></i></a></p>`);
			//console.log(dato.numPiso +' '+dato.zonaDescripcion);
		});
		$('[data-toggle="tooltip"]').tooltip();
	});

});
$('#cmbEstantes').on('changed.bs.select', function (e) {
	var id= $('#divCmbEstantes').find('.selected a').attr('data-tokens');
	switch (id) {
		case '1':
			window.location.href = 'almacen.php?estanteNingun&almacen='+id; break;
		case '2':
			window.location.href = 'almacen.php?estanteExhibicion&almacen='+id; break;
		case '3':
			window.location.href = 'almacen.php?estanteLaptops&almacen='+id; break;
		case '4':
			window.location.href = 'almacen.php?estantePlateado&almacen='+id; break;
		case '5':
			window.location.href = 'almacen.php?estanteAmarillo&almacen='+id; break;
		case '6':
			window.location.href = 'almacen.php?estanteRojo&almacen='+id; break;
		case '7':
			window.location.href = 'almacen.php?estanteNegro&almacen='+id; break;
		case '8':
			window.location.href = 'almacen.php?cuarto2&almacen='+id; break;
		case '9':
			window.location.href = 'almacen.php?televisores&almacen='+id; break;
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
	//console.log( zona );
	$.ajax({url: 'php/insertAlmacenv3.php', type: 'POST', data: {
		idProducto : $('#txtAlmacenCodProducto').val(),
		estante: <?= $_GET['almacen']; ?>,
		piso: piso,
		zona: zona,
		obs: $('#txtAlmacenObs').val()
	 }}).done(function(resp) { console.log( resp );
		if($.isNumeric(resp)){
			location.reload();
		}
	});
});
$('.modalRemoveAnalisis').on('shown.bs.modal', function () { 
	$('#txtRetirarObs').focus();
});
$('td').on('click', '.aRemoveProductAlmacen', function (e) {
	$('#idRemove').attr('data-producto', $(this).prev().text());
	$('#idRemove').attr('data-cubo',$(this).attr('data-cubicaje'));
	$('#strNombre').text(' con id '+$(this).attr('data-cubicaje')+' producto: '+$(this).parent().attr('data-original-title'));
	$('.modalRemoveAnalisis').modal('show');
	
});

$('#btnAproveRemoveAnalisis').click(function() {
	$.ajax({url: 'php/eliminarItemAlmacen.php', type: 'POST', data: {
		idProd: $('#idRemove').attr('data-producto'),
		cubo: $('#idRemove').attr('data-cubo'),
		obs: $('#txtRetirarObs').val()
		}}).done(function(resp) { console.log(resp)
			if(resp==true){
				location.reload();
			}
	});
});
$('.modal-almacenInsertar').on('shown.bs.modal', function () { 
	$('#txtAlmacenCodProducto').focus();
});
</script>
<?php } ?>
</body>

</html>