<?php 
//session_start();
date_default_timezone_set('America/Lima');
require("php/conkarl.php");
require 'php/variablesGlobales.php';
$hayCaja= require_once("php/comprobarCajaHoy.php");
if ( isset($_GET['credito'])){
	$sqlCre= "SELECT pe.*, mp.modDescripcion, lower(concat(c.cliApellidos,', ', c.cliNombres)) as `cliNombres`, u.usuNombres FROM `prestamo` pe inner join modoPrestamo mp on mp.idModoPrestamo = pe.idModo inner join Cliente c on c.idCliente = pe.idCliente inner join usuario u on u.idUsuario = pe.idUsuario where idPrestamo= {$_GET['credito']} and preIdEstado in (78,82)";

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
			<div class="panel panel-default">
				<div class="panel-body">
				<p><strong>Filtro de créditos:</strong></p>
					<div class="row">
						<div class="col-xs-6 col-sm-3">
							<input type="text" id="txtBuscarCredito" class="form-control">
						</div>
						<div class="col-xs-3">
							<button class="btn btn-primary btn-outline" id="btnBuscarCreditoSin"><i class="icofont icofont-search"></i> Buscar</button>
							<button class="btn btn-azul btn-outline miToolTip btnSinBorde"  id="btnReporteNewCliente" data-toggle="tooltip" title="Lista clientes colocados"><i class="icofont 
							icofont-users-alt-1"></i></button>
							<a href="creditos.php?recordHoy" class="btn btn-azul btn-outline miToolTip btnSinBorde"  id="btnReporteNewCliente" data-toggle="tooltip" title="Reporte de Morosos (hoy)"><i class="icofont icofont-paper"></i></a>
							<a href="creditos.php?recordLibre" class="btn btn-azul btn-outline miToolTip btnSinBorde"  id="btnReporteNewCliente" data-toggle="tooltip" title="Reporte de cobros libres (hoy)"><i class="icofont icofont-newspaper"></i></a>
						</div>
					</div>
					<div class="row container-fluid hidden" id="rowReporteNewCliente">
						<div class="col-xs-6">
							<label for="">Elija la fecha para generar el reporte</label>
							<input type="text" class="form-control" id="dtpFechaCliente">
						</div>
					</div>
				</div>
			</div>
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
						<td><?php if(is_null($row['cuotFechaCancelacion'])): echo 'Pendiente'; else: $fechaNce= new DateTime($rowCr['cuotFechaCancelacion']); echo $fechaNce->format('j/m/Y h:m a'); endif; ?></td>
						<td> <?= number_format($row['cuotPago'],2); ?></td>
						<td><?php if($row['cuotPago']=='0.00'):
							if($hayCaja): ?> <button class="btn btn-azul btn-outline btn-sm btnPagarCuota" data-id="<?= $row['idCuota'];?>"><i class="icofont icofont-money"></i> Pagar</button> <?php 
							else:?>
							<p>No hay caja abierta</p>
						<?php endif; else:
						?><span><i class='icofont icofont-ui-check light-green-text text-darken-1'></i> </span> <?php 
					 endif;?> </td>
					</tr>
					<?php $i++;	}
					$llamadoSQL->close();
				}
			  endif;
			  if($numRow==0){
				 echo '<p>El código solicitado no está asociado a ningún <strong>crédito activo</strong>, revise el código o comuníquelo al área responsable. </p> ';
			  }
				?>
				</tbody>
			</table>
		<?php } /* fin de if de credito */ 
		if( isset($_GET['nuevos']) ){ ?>
			<h4 class="purple-text text-lighten-1">Lista de clientes nuevos por fecha</h4>
			<table class="table table-hover">
			<thead>
				<tr>
					<th>DNI</th>
					<th>Apellidos y Nombres</th>
					<th>Saldo</th>
					<th>Plazo</th>
					<th>Zona</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			$sumaNow=0;
			$sqlCliNow="SELECT idPrestamo, pre.idCliente, c.cliDni , preCapital, lower(concat( c.cliApellidos, ', ', c.cliNombres)) as cliNombres, c.cliDireccion, modDescripcion
			FROM `prestamo` pre
			inner join Cliente c on c.idCliente = pre.idCliente
			inner join `modoPrestamo` mp on mp.idModoPrestamo = pre.idModo
			where preIdEstado=78 and date_format(preFechaInicio, '%Y-%m-%d') = '{$_GET['nuevos']}';";
//			echo $sqlCliNow;
			$resultadoCliNow=$cadena->query($sqlCliNow);
			$rowsCliNow = $resultadoCliNow->num_rows;
			if( $rowsCliNow==0){ ?>
				<tr><td>No hay datos en ésta fecha</td></tr> <?php
			}else{
				while($rowCliNow=$resultadoCliNow->fetch_assoc()){ $sumaNow+=floatval($rowCliNow['preCapital']); ?>
					<tr>
						<td><a href="creditos.php?credito=<?= $rowCliNow['idPrestamo'];?>">CR-<?= $rowCliNow['idPrestamo'];?></a></td>
						<td><?= $rowCliNow['cliDni'];?></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=<?= $rowCliNow['idCliente']?>"><?= $rowCliNow['cliNombres'];?></a></td>
						<td><?= number_format($rowCliNow['preCapital'],2);?></td>
						<td><?= $rowCliNow['modDescripcion'];?></td>
						<td class="mayuscula"><?= $rowCliNow['cliDireccion'];?></td>
					</tr>		
		<?php }?>
				<tr><td></td> <td><strong>Total:</strong></td> <td><strong>S/ <?= number_format($sumaNow,2); ?></strong></td></tr>
		<?php
			}
			?>
			</tbody>
			</table>
		<?php } /*Fin de if get nuevos*/ 
		if( isset($_GET['recordHoy'])){?>
			<h4 class="purple-text text-lighten-1">Reporte de cuotas vencidas a la fecha</h4>
			<table class="table table-hover" id="tblRecordCobro">
			<thead>
				<tr>
					<th data-sort="int">Código <i class="icofont icofont-expand-alt"></i></th>
					<th data-sort="string">Apellidos y Nombres <i class="icofont icofont-expand-alt"></i></th>
					<th data-sort="string">Zona <i class="icofont icofont-expand-alt"></i></th>
					<th data-sort="float">Capital <i class="icofont icofont-expand-alt"></i></th>
					<th data-sort="string">Plazo <i class="icofont icofont-expand-alt"></i></th>
					<th data-sort="float">Debe <i class="icofont icofont-expand-alt"></i></th>
					<th data-sort="float">Días venc. <i class="icofont icofont-expand-alt"></i></th>
					<th data-sort="float">Mora <i class="icofont icofont-expand-alt"></i></th>
					<th>@</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			$sumaNow=0; $sumaMora=0; $sumMonto=0;
			$sqlCliNow="SELECT pre.idPrestamo, pre.idCliente, c.cliDni , preCapital, lower(concat( c.cliApellidos, ', ', c.cliNombres)) as cliNombres, c.cliDireccion, modDescripcion,
			sum(datediff( curdate() , prc.cuotFechaPago )) as diasDuda, round(sum(cuotCuota-cuotPago),2) as cuotNormal, count(idCuota) as cuotasDebe
					FROM `prestamo` pre
					inner join prestamo_cuotas prc on prc.idPrestamo = pre.idPrestamo
					inner join Cliente c on c.idCliente = pre.idCliente
					inner join `modoPrestamo` mp on mp.idModoPrestamo = pre.idModo
					where prc.idTipoPrestamo in (79, 33) and prc.cuotFechaPago<=curdate() 
					group by  pre.idPrestamo, pre.idCliente, c.cliDni , preCapital, c.cliApellidos,  c.cliNombres, c.cliDireccion, modDescripcion;";
//			echo $sqlCliNow;
			$resultadoCliNow=$cadena->query($sqlCliNow);
			$rowsCliNow = $resultadoCliNow->num_rows;
			if( $rowsCliNow==0){ ?>
				<tr><td>No hay datos en ésta fecha</td></tr> <?php
			}else{
				while($rowCliNow=$resultadoCliNow->fetch_assoc()){
					if($rowCliNow['diasDuda']>0){
					$sumaNow+=floatval($rowCliNow['cuotNormal']);
					$sumaMora+=floatval($rowCliNow['cuotasDebe']);
					$sumMonto+=floatval($rowCliNow['preCapital']); ?>
					<tr>
						<td class="tdCobrarId" data-sort-value="<?= $rowCliNow['idPrestamo'];?>"><a href="creditos.php?credito=<?= $rowCliNow['idPrestamo'];?>">CR-<?= $rowCliNow['idPrestamo'];?></a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=<?= $rowCliNow['idCliente']?>"><?= $rowCliNow['cliNombres'];?></a></td>
						<td class="mayuscula"><?= $rowCliNow['cliDireccion'];?></td>
						<td><?= number_format($rowCliNow['preCapital'],2);?></td>
						<td><?= $rowCliNow['modDescripcion'];?></td>
						<td class="tdCuotaDebe"><?= number_format($rowCliNow['cuotNormal'],2);?></td>
						<td><?= $rowCliNow['cuotasDebe'];?></td>
						<td class="tdMoraDebe"><?= number_format($rowCliNow['cuotasDebe']*2,2);?></td>
						<td><button class="btn btn-danger btn-outline btnSinBorde miToolTip btnSubsanar" data-toggle="tooltip" title="Subsanar crédito"><i class="icofont icofont-warning-alt"></i></button></td>
					</tr>		
		<?php }} ?>
				<tfoot>
					<tr><td></td> <td></td> <td><strong>Total:</strong></td> <td><strong>S/ <?= number_format($sumMonto,2)?></strong></td> <td></td> <td><strong>S/ <?= number_format($sumaNow,2); ?></strong></td> <td><strong>Mota total:</strong></td> <td><strong>S/ <?= number_format($sumaMora*2,2);?></strong></td> </tr>
				</tfoot>
		<?php 
			}
			?>
			</tbody>
			</table>
		<?php } //fin de if Get record Hoy

		if( isset($_GET['recordLibre'])){?>
			<h4 class="purple-text text-lighten-1">Reporte de cobranzas para hoy</h4>
			<table class="table table-hover" id="tblRecordCobro">
			<thead>
				<tr>
					<th data-sort="int">Código</th>
					<th data-sort="string">Apellidos y Nombres</th>
					<th data-sort="string">Zona</th>
					<th data-sort="string">Plazo</th>
					<th data-sort="float">Debe</th>
					<th data-sort="float">Capital</th>
					<th>@</th>
				</tr>
			</thead>
			<tbody>
			
			<?php
			$sumaNow=0; $sumLibre=0;
			$sqlCliNow="SELECT pre.idPrestamo, pre.idCliente, c.cliDni , preCapital, lower(concat( c.cliApellidos, ', ', c.cliNombres)) as cliNombres, c.cliDireccion, modDescripcion,
			sum(datediff( curdate() , prc.cuotFechaPago )) as diasDuda, round(sum(cuotCuota-cuotPago),2) as cuotNormal
					FROM `prestamo` pre
					inner join prestamo_cuotas prc on prc.idPrestamo = pre.idPrestamo
					inner join Cliente c on c.idCliente = pre.idCliente
					inner join `modoPrestamo` mp on mp.idModoPrestamo = pre.idModo
					where prc.idTipoPrestamo in (79, 33) and prc.cuotFechaPago<=curdate() 
					group by  pre.idPrestamo, pre.idCliente, c.cliDni , preCapital, c.cliApellidos,  c.cliNombres, c.cliDireccion, modDescripcion;";
		//			echo $sqlCliNow;
			$resultadoCliNow=$cadena->query($sqlCliNow);
			$rowsCliNow = $resultadoCliNow->num_rows;
			if( $rowsCliNow==0){ ?>
				<tr><td>No hay datos en ésta fecha</td></tr> <?php
			}else{
				while($rowCliNow=$resultadoCliNow->fetch_assoc()){
					if($rowCliNow['diasDuda']==0){
						$sumaNow+=floatval($rowCliNow['cuotNormal']); $sumLibre+=floatval($rowCliNow['preCapital']); ?>
					<tr>
						<td class="tdCobrarId" data-sort-value="<?= $rowCliNow['idPrestamo'];?>"><a href="creditos.php?credito=<?= $rowCliNow['idPrestamo'];?>">CR-<?= $rowCliNow['idPrestamo'];?></a></td>
						<td class="mayuscula"><a href="cliente.php?idCliente=<?= $rowCliNow['idCliente']?>"><?= $rowCliNow['cliNombres'];?></a></td>
						<td class="mayuscula"><?= $rowCliNow['cliDireccion'];?></td>
						<td><?= $rowCliNow['modDescripcion'];?></td>
						<td class="tdDebe" data-sort-value="<?= $rowCliNow['cuotNormal'];?>" data-debe="<?= $rowCliNow['cuotNormal'];?>"><?= number_format($rowCliNow['cuotNormal'],2);?></td>
						<td data-sort-value="<?= $rowCliNow['preCapital'];?>"><?= number_format($rowCliNow['preCapital'],2);?></td>
						<td><button class="btn btn-negro btn-outline btnSinBorde miToolTip btnCobrarCuota" title="Pre-pago" ><i class="icofont icofont-win-trophy"></i></button></td>
					</tr>		
		<?php }}?>
				<tfoot>
					<tr><td></td> <td></td> <td></td> <td><strong>Total:</strong></td> <td><strong>S/ <?= number_format($sumaNow,2); ?></strong></td> 
					<td><strong>S/ <?= number_format($sumLibre,2);?></strong></td></tr>
				</tfoot>
		<?php
			}
			?>
			</tbody>
			</table>
		<?php } //fin de if Get record Hoy
		?>
				
			<!-- Fin de contenido principal -->
			</div>
		</div>
    </div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->



<?php if(isset($_GET['recordLibre']) || isset($_GET['recordHoy'])){?>
<!-- Modal para decir pagosw puntuales  -->
<div class="modal fade modalPagarPuntual" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-primary">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Pago de cuota</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
			<p>Deuda del cliente es:</p>
			<p><strong>S/ <span id="spanMonedaCliente"></span></strong></p>
			<p>¿Cuánto pagó el cliente?</p>
			<input type="number" class="form-control esMoneda text-center inputGrande" value="0.00" id="txtPagaClienteFijo">
			</div>
		</div>
			
		<div class="modal-footer">
			<button class="btn btn-primary btn-outline" data-dismiss="modal" id="btnPagarPuntual" ><i class="icofont icofont-save"></i> Ingresar pago</button>
		</div>
	</div>
	</div>
</div>
</div>

<!-- Modal para decir pagosw de mora  -->
<div class="modal fade modalPagarAtras" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-danger">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-animal-cat-alt-4"></i> Pago de cuota</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
			<p>Deuda del cliente es:</p>
			<p>Interés: <strong>S/ <span id="spanMonedaClienteAtras"></span></strong></p>
			<p>Mora: <strong>S/ <span id="spanMonedaMoraAtras"></span></strong></p>
			<p class="spanTotal">Total: <strong>S/ <span id="spanMonedaTotalAtras"></span></strong></p>
			<p>¿Cuánto pagó el cliente?</p>
			<input type="number" class="form-control esMoneda text-center inputGrande" value="0.00" id="txtPagaClienteAtras">
			</div>
		</div>
			
		<div class="modal-footer">
			<button class="btn btn-danger btn-outline" data-dismiss="modal" id="btnPagarAtras" ><i class="icofont icofont-save"></i> Ingresar pago</button>
		</div>
	</div>
	</div>
</div>
</div>
<?php } //end recordLibre ?>

<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>
<script src="js/bootstrap-material-datetimepicker.js"></script>
<script src="js/stupidtable.min.js"></script>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>

<script>
datosUsuario();

$(document).ready(function(){
$('.miToolTip').tooltip();
$('#dtpFechaCliente').bootstrapMaterialDatePicker({	format: 'DD/MM/YYYY',
	lang: 'es',
	time: false,
	weekStart: 1,
	cancelText : 'Cerrar',
	nowButton : true,
	switchOnClick : true,
	okText: 'Aceptar', nowText: 'Hoy'
});

$('#dtpFechaCliente').bootstrapMaterialDatePicker('setDate', moment(<?php if(isset($_GET['nuevos'])): echo "'".$_GET['nuevos']."'"; endif;?>));
$('#dtpFechaCliente').change(function () {
	window.location.href = 'creditos.php?nuevos=' + moment( $('#dtpFechaCliente').val() , 'DD/MM/YYYY').format('YYYY-MM-DD');
});
$('#btnBuscarCreditoSin').click(function() {
	if( $('#txtBuscarCredito').val()!='' && $('#txtBuscarCredito').val().toUpperCase().indexOf('CR-')==0 ){
		window.location.href = 'creditos.php?credito='+$('#txtBuscarCredito').val().toUpperCase().replace('CR-', '');
	/* $.ajax({url: 'php/buscarCredito.php', type: 'POST', data: { idCred: $('#txtBuscarCredito').val().toUpperCase().replace('CR-', '') }}).done(function(resp) {
		
	});*/
	} 
});
<?php if(isset($_GET['recordLibre']) || isset($_GET['recordHoy'])){ ?>
$("#tblRecordCobro").stupidtable();
$('.btnCobrarCuota').click(function () {
	var padre= $(this).parent().parent();
	$('#btnPagarPuntual').attr('data-id', padre.find('.tdCobrarId').attr('data-sort-value'));
	$('#txtPagaClienteFijo').val(padre.find('.tdDebe').attr('data-debe'));
	$('#spanMonedaCliente').text(padre.find('.tdDebe').attr('data-debe'));
	$('.modalPagarPuntual').modal('show');
});
$('#btnPagarPuntual').click(function() {
	var idPre= $(this).attr('data-id');
	if( $('#txtPagaClienteFijo').val()>0 ){
		$.ajax({url: 'php/pagoCuasiCompleto.php', type: 'POST', data: { idPre: idPre, dinero: $('#txtPagaClienteFijo').val()  }}).done(function(resp) {
			console.log(resp)
		});
	}
});
$('.btnSubsanar').click(function() {
	var padre = $(this).parent().parent();
	$('#spanMonedaClienteAtras').text(padre.find('.tdCuotaDebe').text());
	$('#spanMonedaMoraAtras').text(padre.find('.tdMoraDebe').text());
	$('#spanMonedaTotalAtras').text( parseFloat(parseFloat($('#spanMonedaClienteAtras').text()) + parseFloat($('#spanMonedaMoraAtras').text())).toFixed(2) );
	$('#btnPagarAtras').attr('data-id', padre.find('.tdCobrarId').attr('data-sort-value'));
	$('.modalPagarAtras').modal('show');
});
<?php } ?>
$('#txtBuscarCredito').keypress(function (e) { 
	if(e.keyCode == 13){ 
		$('#btnBuscarCreditoSin').click();
	}
});
<?php if(isset($_GET['credito'])): ?>//in_array($_COOKIE['ckPower'],$soloAdmis) && ?>
$('.btnPagarCuota').click(function() {
	id=$(this).attr('data-id');
	$.ajax({url: 'php/cancelarCuotaOnline.php', type: 'POST', data: { idCuo: id, idPre: <?= $_GET['credito'];?> }}).done(function(resp) {
		console.log(resp)
		if(resp == true){
			location.reload();
		}
	});
});
<?php endif; ?>
$('#btnReporteNewCliente').click(function() {
	$('#rowReporteNewCliente').removeClass('hidden');
});
});

</script>
<?php } ?>
</body>

</html>