<?php 
//session_start();
date_default_timezone_set('America/Lima');
require("php/conkarl.php");
if ( isset($_GET['credito'])){
	$sqlCre= "SELECT pe.*, mp.modDescripcion, lower(concat(c.cliApellidos,', ', c.cliNombres)) as `cliNombres`, u.usuNombres FROM `prestamo` pe inner join modoPrestamo mp on mp.idModoPrestamo = pe.idModo inner join Cliente c on c.idCliente = pe.idCliente inner join usuario u on u.idUsuario = pe.idUsuario where idPrestamo= {$_GET['credito']} and preIdEstado=78";

	$sql="SELECT `idCuota`, `idPrestamo`, `cuotFechaPago`, `cuotCuota`, `cuotFechaCancelacion`, `cuotPago`, `cuotObservaciones`, tp.tipoDescripcion FROM `prestamo_cuotas` pc
	inner join tipoProceso tp on tp.idTipoProceso = pc.idTipoPrestamo
	where idPrestamo = {$_GET['credito']}
	order by cuotFechaPago asc;";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Créditos: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>

<style>
#contenedorCreditosFluid label{font-weight: 500;}
#contenedorCreditosFluid p, #contenedorCreditosFluid td {color: #a35bb4;}
.modal p{color: #333;}
</style>
<div id="wrapper">
	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
	<!-- /#sidebar-wrapper -->
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable " id="contenedorCreditosFluid">
			<!-- Empieza a meter contenido principal -->
			<h3 class="purple-text text-lighten-1">Créditos online / sólo con DNI</h3><hr>
		<?php if ( isset($_GET['credito'])){ 
			if($llamadoCre=$conection->query($sqlCre)){
				
				$rowCr=$llamadoCre->fetch_assoc();
			}
			$numRow=$llamadoCre->num_rows;
			if( $numRow>=1): ?> 
			<h3 class="purple-text text-lighten-1">CR-<?= $_GET['credito']; ?></h3>
			<div class="container-fluid">
				<p><strong>Datos de crédito</strong></p>
				<div class="row">
					<div class="col-sm-2"><label for="">Fecha préstamo</label><p><?php $fechaAut= new DateTime($rowCr['preFechaInicio']); echo $fechaAut->format('j/m/Y h:m a'); ?></p></div>
					<div class="col-sm-2"><label for="">Desembolso</label><p>S/ <?= number_format($rowCr['preCapital'],2); ?></p></div>
					<div class="col-sm-2"><label for="">Periodo</label><p><?= $rowCr['modDescripcion']; ?></p></div>
					<div class="col-sm-2 mayuscula"><label for="">Solicitante</label><p><a href="cliente.php?idCliente=<?= $rowCr['idCliente']; ?>"><?= $rowCr['cliNombres']; ?></a></p></div>
					<div class="col-sm-2"><label for="">Analista</label><p><?= $rowCr['usuNombres']; ?></p></div>
				</div>	
			</div>
			<br>
			<p><strong>Cuotas planificadas</strong></p>
			<table class="table table-hover" >
				<thead>
					<tr>
						<th>N°</th>
						<th>Fecha programada</th>
						<th>Cuota</th>
						<th>Cancelación</th>
						<th>Pago</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if($llamadoSQL=$cadena->query($sql)){
					$i=1;
					while ($row = $llamadoSQL->fetch_assoc()) { ?>
					<tr>
						<td><?= $i; ?></td>
						<td><?php $fechaCu= new DateTime($row['cuotFechaPago']); echo $fechaCu->format('d/m/Y'); ?></td>
						<td><?= number_format($row['cuotCuota'],2); ?></td>
						<td><?php if(is_null($row['cuotFechaCancelacion'])): echo 'Pendiente'; else: echo $row['cuotFechaCancelacion']; endif;  ?></td>
						<td><?= number_format($row['cuotPago'],2); ?></td>
						<td><?php if($row['cuotPago']=='0.00'): ?> <button class="btn btn-primary btn-outline btn-sm btnPagarCuota"><i class="icofont icofont-money"></i> Pagar</button> <?php endif;?> </td>
					</tr>
					<?php $i++;	}
					$llamadoSQL->close();
				}
			  endif;
			  if($numRow==0){
				 echo '<p>El código solicitado no está asociado a ningún crédito, revise el código o comuníquelo al área responsable. </p> ';
			  }
				?>
				</tbody>
			</table>
		<?php } /* fin de if de credito */ else{ ?>
			<div class="panel panel-default">
				<div class="panel-body">
				<p><strong>Filtro de créditos:</strong></p>
					<div class="row">
						<div class="col-xs-6 col-sm-3">
							<input type="text" id="txtBuscarCredito" class="form-control">
						</div>
						<div class="col-xs-3">
							<button class="btn btn-primary btn-outline" id="btnBuscarCreditoSin"><i class="icofont icofont-search"></i> Buscar</button>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
				
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();

$(document).ready(function(){

$('#btnBuscarCreditoSin').click(function() {
	if( $('#txtBuscarCredito').val()!='' && $('#txtBuscarCredito').val().toUpperCase().indexOf('CR-')==0 ){
		window.location.href = 'creditos.php?credito='+$('#txtBuscarCredito').val().toUpperCase().replace('CR-', '');
	/* $.ajax({url: 'php/buscarCredito.php', type: 'POST', data: { idCred: $('#txtBuscarCredito').val().toUpperCase().replace('CR-', '') }}).done(function(resp) {
		
	});*/
	} 
});

});

</script>
<?php } ?>
</body>

</html>