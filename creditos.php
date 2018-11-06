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
			<h3 class="purple-text text-lighten-1">Créditos sólo con DNI</h3><hr>
		<?php if ( isset($_GET['credito'])){ 
			if($llamadoCre=$conection->query($sqlCre)){
				$rowCr=$llamadoCre->fetch_assoc();
			} ?>
			<div class="container-fluid">
				<p><strong>Datos de crédito</strong></p>
				<div class="row">
					<div class="col-sm-2"><label for="">Fecha préstamo</label><p><?php $fechaAut= new DateTime($rowCr['preFechaInicio']); echo $fechaAut->format('j/m/Y H:m a'); ?></p></div>
					<div class="col-sm-2"><label for="">Desembolso</label><p>S/ <?= number_format($rowCr['preCapital'],2); ?></p></div>
					<div class="col-sm-2"><label for="">Periodo</label><p><?= $rowCr['modDescripcion']; ?></p></div>
					<div class="col-sm-2"><label for="">Analista</label><p><?= $rowCr['usuNombres']; ?></p></div>
				</div>	
			</div>

			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Fecha Pago</th>
						<th>Estado</th>
						<th>Observaciones</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if($llamadoSQL=$cadena->query($sql)){
					$i=1;
					while ($row = $llamadoSQL->fetch_assoc()) { ?>
					<tr>
						<td><?= $i; ?></td>
						<td><?php $fechaP= new DateTime($row['cuotFechaPago']); echo $fechaP->format('d/m/Y'); ?></td>
						<td><?= $row['tipoDescripcion']; ?></td>
						<td><?= $row['cuotObservaciones']; ?></td>
					</tr>
					<?php $i++;	}
					$llamadoSQL->close();
				}
				?>
				</tbody>
			</table>
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
	
});

</script>
<?php } ?>
</body>

</html>