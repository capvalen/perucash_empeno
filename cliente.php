<?php // session_start();
require("php/conkarl.php");
?>
<!DOCTYPE html>
<html lang="es">

<?php 
if( isset($_GET['idCliente'])){
	$sql = mysqli_query($conection,"SELECT * FROM `Cliente` where idCliente = '".$_GET['idCliente']."';");
	$rowCliente = mysqli_fetch_array($sql, MYSQLI_ASSOC);
}
?>
<head>
	<title>Cliente: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>
<style>
	.paPrestamo{
		margin: 0;
		padding: 15px;
		border-top: 1px solid #e3e3e3;
		/*border-radius: 6px;*/
		transition: all 0.2s ease-in-out;
		cursor: pointer;
	}
	.paPrestamo:hover{
		background-color: #f5f5f5;
		transition: all 0.2s ease-in-out;
	}
	.h3Nombres{margin-top: 0px;}
	.rate i{padding-left: 3px; cursor: pointer;}
	.divBotonesPrestamo{display: inline-block;}
	.divBotonesPrestamo .dropdown-menu>li>a{color: #0078D7;}
	.divBotonesPrestamo .dropdown-menu>li>a:focus, .divBotonesPrestamo .dropdown-menu>li>a:hover {
	    text-decoration: none;
	    background-color: #f5f5f5;
	}.divPrestamo h4{color: #c5c5c5;display: inline-block;}
	.divPrestamo .vigente{color: #050506;}
	.spanIcoProd {
    font-size: 18px;
    margin-left: 10px;
}
	/* .contenedorPres{margin-bottom: 20px;} */
</style>
<div class="" id="wrapper">
<?php include 'menu-wrapper.php' ?>

<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid">				 
		<div class="row contenedorDeslizable">
			<div class="container-fluid">
				<h2 class="purple-text text-lighten-1"><i class="icofont icofont-puzzle"></i> Reporte de cliente</h2>
			</div>
			<div class="col-xs-12 col-md-3 contenedorDatosCliente text-center">
			<!-- Empieza a meter contenido 2.1 -->
				<span><img src="images/user.png" class="img-responsive" style="margin: 0 auto;"></span>
				<h3 class="h3Apellidos mayuscula"><?php echo $rowCliente['cliApellidos']; ?></h3>
				<h3 class="h3Nombres mayuscula"><?php echo $rowCliente['cliNombres']; ?> <button class="btn btn-primary btn-outline btn-sinBorde" id="spanEditarDatoClient"><i class="icofont icofont-brush"></i></button></h3>
				<span class="rate yellow-text text-darken-2" style="font-size: 18px;">
					<?php
						for ($i=0; $i <5 ; $i++) { 
							if($i<$rowCliente['cliCalificacion']){
								echo '<i class="icofont icofont-ui-rating"></i>';
							}else{echo '<i class="icofont icofont-ui-rate-blank"></i>';}
						}
					 ?></span>
				<h5 class="grey-text text-darken-1"><i class="icofont icofont-ui-v-card"></i> <span class="cliDni"><?php echo $rowCliente['cliDni']; ?></span></h5>
				<h5 class="grey-text text-darken-1 mayuscula"><i class="icofont icofont-home"></i> <span class="cliDireccion"><?php echo $rowCliente['cliDireccion']; ?></span></h5>
				<h5 class="grey-text text-darken-1"><i class="icofont icofont-phone"></i> <span class="cliCelular"><?php if($rowCliente['cliCelular']==''){ echo 'Sin datos';}else{ echo $rowCliente['cliCelular'];} ?></span></h5>
				<h5 class="grey-text text-darken-1"><i class="icofont icofont-phone"></i><span class="cliTlf"><?php if($rowCliente['cliFijo']==''){ echo 'Sin datos';}else{ echo $rowCliente['cliFijo'];} ?></span></h5>
				<h5 class="grey-text text-darken-1"><i class="icofont icofont-envelope"></i><span class="cliCorreo"><?php if($rowCliente['cliCorreo']==''){ echo 'Sin datos';}else{ echo $rowCliente['cliCorreo'];} ?></span></h5>
			<!-- Fin de contenido 2.1 -->
			</div>
			<div class="col-xs-12 col-md-8 contenedorDatosCliente">
			<!-- Empieza a meter contenido 2.2 -->
				<div class="divPrestamo">
				<?php
				$i=0;
				if( isset($_GET['idCliente'])){
					$sql = mysqli_query($conection,"SELECT p.idProducto, p.prodNombre, pp.preCapital, p.prodActivo, pp.desFechaContarInteres, tp.tipoDescripcion, tp.tipColorMaterial
					FROM `prestamo_producto` pp
					inner join producto p on p.idProducto= pp.idProducto
					inner join tipoProceso tp on tp.idTipoProceso = pp.presidTipoProceso
					where p.idCliente = ".$_GET['idCliente']."
					order by p.prodActivo desc, p.idProducto desc;");
					while($rowPrestamos = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
						?>
						<div class="contenedorPres">
							<div class=' paPrestamo'><div>
							<div class='row <? if( $rowPrestamos['prodActivo']==1 ){ echo $rowPrestamos['tipColorMaterial'];} else{ echo 'grey-text text-darken-3'; } ?>'>
								<div class='hidden codRegistro'><?= $rowPrestamos['idProducto']; ?></div>
								<div class='col-xs-6 mayuscula'> <i class="icofont icofont-social-slack"></i> <span><?= $rowPrestamos['idProducto']; ?></span> <span class='spanIcoProd'><i class='icofont icofont-gift'></i></span> <?= $rowPrestamos['prodNombre'];?> </div>
								<div class='col-xs-2'>S/. <?= number_format($rowPrestamos['preCapital'],2); ?></div>
								<div class='col-xs-2 hastaHoy mayuscula' data-toggle="tooltip" data-placement="top" > <?= $rowPrestamos['desFechaContarInteres']; ?></div>
								<div class='col-xs-2'><? if( $rowPrestamos['prodActivo']==1 ){ echo 'Pendiente'; }else{ echo 'Inactivo'; } ?> <span class='pull-right purple-text text-lighten-1'><i class='icofont icofont-rounded-right'></i></span></div>
							</div>
						</div></div>
						</div>
						<?php 
					// 	$i++;
					// 	$j=0;
					// 	$sqlNue = mysqli_query($conection,"SELECT p.*,  tp.tipoDescripcion, tp.tipColorMaterial FROM `prestamo_producto` pp inner join `producto` p on pp.idProducto=p.idProducto inner join tipoProceso tp on tp.idTipoProceso=pp.presidTipoProceso where pp.idPrestamo= {$rowPrestamos['idPrestamo']};");
					// 	while($rowPrestamosProductos = mysqli_fetch_array($sqlNue, MYSQLI_ASSOC)){
					// 		echo "<div class=' paPrestamo'><div>
					// 	<div class='row {$rowPrestamosProductos['tipColorMaterial']}'>
					// 		<div class='hidden codRegistro'>{$rowPrestamosProductos['idProducto']}</div>
					// 		<div class='col-xs-5 mayuscula'>{$rowPrestamosProductos['prodNombre']}</div>
					// 		<div class='col-xs-3'>S/. ".number_format($rowPrestamosProductos['prodMontoEntregado'],2)."</div>
					// 		<div class='col-xs-4'><i class='icofont icofont-info-circle'></i> {$rowPrestamosProductos['tipoDescripcion']} <span class='pull-right purple-text text-lighten-1'><i class='icofont icofont-rounded-right'></i></span></div>
					// 	</div>
					// </div></div>";
					// 		$j++;
					// 	}
					
					}
					
				}
				?>
					<!-- <div class=" paPrestamo"><strong>
						<div class="row yellow-text text-darken-2">
							<div class="hidden codRegistro">850</div>
							<div class="col-xs-5">Producto 002</div>
							<div class="col-xs-3">S/. 68.00</div>
							<div class="col-xs-4"><i class="icofont icofont-info-circle"></i> Vendido <span class="pull-right grey-text"><i class="icofont icofont-rounded-right"></i></span></div>
						</div>
					</strong></div><div class=" paPrestamo"><strong>
						<div class="row amber-text text-darken-3">
							<div class="hidden codRegistro">850</div>
							<div class="col-xs-5">Producto 003</div>
							<div class="col-xs-3">S/. 80.00</div>
							<div class="col-xs-4"><i class="icofont icofont-info-circle"></i> En venta <span class="pull-right grey-text"><i class="icofont icofont-rounded-right"></i></span></div>
						</div>
					</strong></div>
									</div> -->
			<!-- Fin de contenido 2.2 -->
			</div>
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


</div>

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
				<div class="col-xs-8"><label for="">Tipo de producto <span class="txtObligatorio">*</span></label> <span class="hidden" id="deQuePrestamoViene"></span>
					<div  id="divSelectProductoListado">
						<select class="selectpicker mayuscula" id="sltProductoListado" title="Tipo de producto..."  data-width="100%" data-live-search="true" data-size="15">
							<?php require 'php/listarProductosTipos.php'; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-4"><label for="">Cantidad <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center" id="txtQProduc" autocomplete="off"></div>
			</div>
			<div class="row ">
				<div class="col-xs-8"><label for="">Nombre del artículo y características <span class="txtObligatorio">*</span></label> <input type="text" class="form-control mayuscula" id="txtNameProduc" placeholder="Sea específico con las características" autocomplete="off"></div>
			</div>
			<div class="row">
				<div class="col-xs-4"><label for="">Capital total S/. <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center txtNumeroDecimal" id="txtCapitalProduc" value="0.00" autocomplete="off"></div>
				<div class="col-xs-4"><label for="">Interés Semanal % <span class="txtObligatorio">*</span></label> <input type="number" class="form-control text-center" id="txtInteresProduc" value="4" autocomplete="off"></div>
				<div class="col-xs-4"><label for="">Fecha de ingreso <span class="txtObligatorio">*</span></label>
					<div class="sandbox-container"><input id="dtpFechaInicio" type="text" class="form-control text-center" autocomplete="off"></div>	
				</div>
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

<!-- Modal para editar los datos del cliente3  -->
<div class="modal fade modal-editarDatosCliente" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-default">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Editar datos cliente</h4>
		</div>
		<div class="modal-body">
		<div class="container-fluid">
			<label for="">Apellidos:</label>
			<input type="text" class="form-control mayuscula text-center" id="txtEditApellidos">
			<label for="">Nombres:</label>
			<input type="text" class="form-control mayuscula text-center" id="txtEditNombres">
			<label for="">D.N.I:</label>
			<input type="text" class="form-control mayuscula text-center" id="txtEditDni">
			<label for="">Dirección:</label>
			<input type="text" class="form-control mayuscula text-center" id="txtEditDireccion">
			<label for="">Celular:</label>
			<input type="text" class="form-control mayuscula text-center" id="txtEditCelu">
			<label for="">Teléfono:</label>
			<input type="text" class="form-control mayuscula text-center" id="txtEditTlf">
			<label for="">Correo electrónico:</label>
			<input type="text" class="form-control text-center" id="txtEditCorreo">
		</div>
			
		<div class="modal-footer">
			<button class="btn btn-default btn-outline" id='btnEditarClientDatos' data-dismiss="modal" ><div class="fa-spin sr-only"><i class="icofont icofont-spinner "></i> </div><i class="icofont icofont-refresh"></i> Actualizar</button>
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

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
	//$('.hastaHoy').tooltip();
	moment.locale('es');
	$.each( $('.hastaHoy') , function(i, objeto){
		var texto=  moment($(objeto).text());
		$(objeto).text(texto.fromNow());
		$(objeto).attr('title', texto.format('DD/MM/YYYY hh:mm a') ).tooltip();
	});
});
$('#aaccionesCaja').click(function () {
	$('.modal-accionesCaja').modal('show');
});
$('.paPrestamo').click(function () {
	var codigo=$(this).find('.codRegistro').text();
	window.location='productos.php?idProducto='+codigo;
});
$('.rate i').click(function () {
	var calificacion=$(this).index()+1;
	$.ajax({url:'php/puntarCliente.php', type: 'POST', data: {estrellas: calificacion , idCli: <?php echo $_GET['idCliente']; ?> }}).done(function (resp) {
		console.log(resp);
	});
	$('.rate').children().remove();
	for (var i = 0; i < 5; i++) {
		if(i<calificacion){
			$('.rate').append('<i class="icofont icofont-ui-rating"></i>');
		}else{
			$('.rate').append('<i class="icofont icofont-ui-rate-blank"></i>');
		}
	}
});
$('.btnNuevoItemProdPrestamo').click(function () {
	$('#deQuePrestamoViene').text( $(this).parent().parent().parent().parent().parent().find('.h4Prestamo').text() );
	$('.modal-nuevoProductoLista').modal('show');
});
$('#spanEditarDatoClient').click(function () {
	$('#txtEditApellidos').val($('.h3Apellidos').text());
	$('#txtEditNombres').val($('.h3Nombres').text());
	$('#txtEditDni').val($('.cliDni').text());
	$('#txtEditDireccion').val($('.cliDireccion').text());
	$('#txtEditCelu').val($('.cliCelular').text());
	$('#txtEditTlf').val($('.cliTlf').text());
	$('#txtEditCorreo').val($('.cliCorreo').text());
	$('.modal-editarDatosCliente').modal('show');
});
$('#btnEditarClientDatos').click(function () {
	$.ajax({url: 'php/actualizarDatosCliente.php', type: 'POST', data: {
		appe: $('#txtEditApellidos').val(),
		nnomb: $('#txtEditNombres').val(),
		ddni: $('#txtEditDni').val(),
		ddirecion: $('#txtEditDireccion').val(),
		eemail: $('#txtEditCorreo').val(),
		ccelular: $('#txtEditCelu').val(),
		ttelf: $('#txtEditTlf').val(),
		iid: <?= $_GET['idCliente']; ?>
	}, beforeSend: function () {
		$('.modal-nuevoProductoLista .fa-spin').removeClass('sr-only');
		$('.modal-nuevoProductoLista .icofont-refresh').addClass('sr-only');
	}, success: function (resp) {
		// body...
	}
	});
});
</script>
<?php } ?>
</body>
</html>
