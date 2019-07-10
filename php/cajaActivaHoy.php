<?php 
require("conkarl.php");
require "variablesGlobales.php";
date_default_timezone_set('America/Lima');

$existeCajaU= require_once("comprobarCajaHoy.php");
$filas=array();
if (! isset($_GET['fecha'])) { //si existe lista fecha requerida
	$_GET['fecha']=date('Y-m-d');
}
if(isset($_GET['cuadre'])){
	$sql= mysqli_query($conection,"SELECT cu.*, u.usuNombres FROM `cuadre` cu
	inner join usuario u on u.idUsuario = cu.idUsuario
	where cu.idCuadre = {$_GET['cuadre']}");
}else{
	$sql = mysqli_query($conection,"call cajaActivaHoy();"); // $_GET['fecha']
}
$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

$fechaHoy = new DateTime();
$fechaReporte = new DateTime($row['fechaInicio']);

if($existeCajaU>0  ){ 
	if( !isset($_GET['cuadre']) ){ //$existeCajaU==$row['idCuadre'] &&  ?>
	<div class="container-fluid row ">
		<div class="col-xs-12 col-md-8" >
				<div class='divTopLinea'></div>
				<div class="alert alert-success-degradado container-fluid" role="alert">
					<div class="col-sm-2 col-xs-3 ">
						<div class="divLargoCircular">
						<h3><i class="icofont icofont-tick-mark"></i> <span class="hidden-xs"></span></h3>
						</div>
					</div>
					<div class="col-xs-8">
						<h4 class="h3Title">Caja abierta!</h4> Cajero <strong class="mayuscula"><?php echo $row['usuNombres']; ?></strong> aperturó <strong> <a href="caja.php?fecha=<?php $fechaN= new DateTime($row['fechaInicio']); echo $fechaN->format('Y-m-d'); ?>&cuadre=<?= $existeCajaU;?>"><i class="icofont icofont-dotted-right"></i>  <?= $fechaN->format('j/m/Y g:i a'); ?></a></strong>
					</div>
				</div>
		
		</div>
		<div class="col-xs-12 col-md-4">
		
		</div>
	</div>
	<?php } //fin de if get cuadre
}else{ if( !isset($_GET['cuadre']) ){
	?>
	<div class="container-fluid row ">
		<div class="col-xs-12 col-md-7 "> <!--  -->
			<div class="alert alert-morado container-fluid" role="alert">
				<div class="col-xs-4 col-sm-2 col-md-3">
					<img src="images/ghost.png" alt="img-responsive" width="100%">
				</div>
				<div class="col-xs-8">
					<strong>Alerta</strong> <p>No se encuentra ninguna caja aperturada.</p>
				<?php $sqlLast="SELECT idCuadre, date_format(fechaInicio, '%Y-%m-%d') as fechaInicio, date_format(fechaInicio, '%d/%m/%Y') as fechaInicioC FROM `cuadre` order by idCuadre desc limit 1";
				$resultadoLast=$esclavo->query($sqlLast);
				$rowLast=$resultadoLast->fetch_assoc();
				 ?>
					<p>La última caja se encuentra en <a href="caja.php?fecha=<?= $rowLast['fechaInicio']; ?>&cuadre=<?= $rowLast['idCuadre'];?>"><i class="icofont icofont-dotted-right"></i> <?= $rowLast['fechaInicioC']; ?></a></p>
					<?php if( in_array($_COOKIE['ckPower'], $soloCaja) && date('Y-m-d')== $_GET['fecha'] ){ ?>
					<span>También puedes: </span><button class="btn btn-morado btn-outline btn-xs" id="btnCajaAbrir"><i class="icofont icofont-coins"></i> Aperturar caja nueva</button>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-5">
					
				</div>
	</div>
	<?php
} }


if( isset($_GET['cuadre']) ){ ?>

<div class="col-xs-12 text-center purple-text text-lighten-1">
	<h4 class="mayuscula"><strong>Cajero: </strong> <?php echo $row['usuNombres'];  if($fechaReporte->format('j/n/Y')== $fechaReporte->format('j/n/Y') && $existeCajaU==$_GET['cuadre'] && ( in_array($_COOKIE['ckPower'], $soloCaja) )  ){
		?> <button class="btn btn-infocat btn-outline btn-xs" id="btnCajaCerrar"><i class="icofont icofont-tea"></i> Cerrar caja</button> <?php
	} ?></h4>
</div>
<div class="col-xs-12 col-sm-6 text-center purple-text text-lighten-1">
	<h4>Apertura: <? if( in_array($_COOKIE['ckPower'], $soloDios) ): ?> <button class="btn btn-infocat btn-outline btn-xs" id="btnCambiarApertura"><i class="icofont icofont-cube"></i> Cambiar</button> <? endif;?> </h4>
	<h4><strong >S/ <span id="spanApertura"><?= str_replace(",", '', number_format($row['cuaApertura'],2)); ?></span></strong></h4>
	<p><?php $fechaN= new DateTime($row['fechaInicio']); echo $fechaN->format('j/n/Y g:i a'); ?></p>
	<p><strong>Obs.</strong> <span class="mayuscula"><? if($row['cuaObs']==''){echo '-'; }else{echo $row['cuaObs'];} ?></span></p>
</div>
<div class="col-xs-12 col-sm-6 text-center purple-text text-lighten-1">
	<h4>Cierre: <? if($row['fechaFin']<>'0000-00-00 00:00:00'): if( in_array($_COOKIE['ckPower'], $soloDios) ): ?> <button class="btn btn-infocat btn-outline btn-xs" id="btnCambiarCierre"><i class="icofont icofont-cube"></i> Cambiar</button> <? endif; endif;?></h4>
	<h4><strong>S/ <span id="spanCierrev3"><?= str_replace(",", '', number_format($row['cuaCierre'],2)); ?></span></strong></h4>
	<p><?php if($row['fechaFin']=='0000-00-00 00:00:00'){ echo 'Sin cerrar aún';}else{ $fechaN= new DateTime($row['fechaFin']); echo $fechaN->format('j/n/Y g:i a'); } ?></p>
	<p><strong>Obs.</strong> <span class="mayuscula"><? if($row['cuaObsCierre']==''){echo '-'; }else{echo $row['cuaObsCierre'];} ?></span></p>
</div>
<?php if($_COOKIE['ckidUsuario']==$row['idUsuario'] && $row['cuaVigente']==1  ){ ?>
<div class="col-xs-12 col-sm-6 text-center">
	
</div>

<?php } }



// if (!$sql) { ////codigo para ver donde esta el error
//     printf("Error: %s\n", mysqli_error($conection));
//     exit();
// }

/*while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}*/
mysqli_close($conection); //desconectamos la base de datos

?>
