<?php 
require("conkarl.php");
date_default_timezone_set('America/Lima');

$hayCaja= require_once("comprobarCajaHoy.php");
$filas=array();
if (! isset($_GET['fecha'])) { //si existe lista fecha requerida
	$_GET['fecha']=date('Y-m-d');
}
if(isset($_GET['cuadre'])){
	$sql= mysqli_query($conection,"SELECT cu.*, u.usuNombres FROM `cuadre` cu
	inner join usuario u on u.idUsuario = cu.idUsuario
	where cu.idCuadre = {$_GET['cuadre']}");
}else{
	$sql = mysqli_query($conection,"call cajaActivaHoy('".$_GET['fecha']."');"); // $_GET['fecha']
}
$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
if($hayCaja==true){
	
	?>
	<div class="container-fluid row ">
		<div class="col-xs-12 col-md-8" >
				<div class='divTopLinea'></div>
				<div class="alert alert-success-degradado container-fluid" role="alert">
					<div class="col-xs-4">
						<div class="divLargoCircular">
						<h3><i class="icofont icofont-tick-mark"></i> <span class="hidden-xs">EXCELENTE</span></h3>
						</div>
					</div>
					<div class="col-xs-8">
						<h4 class="h3Title">¡Caja abierta!</h4> De la sesión <strong><?php echo $row['usuNombres']; ?></strong> aperturada <strong>«<?php $fechaN= new DateTime($row['fechaInicio']); echo $fechaN->format('j/n/Y g:i a'); ?>»</strong>
					</div>
				</div>
		<?php
			
			$fechaHoy = new DateTime();
			$fechaReporte = new DateTime($row['fechaInicio']);
		?>
		</div>
		<div class="col-xs-12 col-md-4">
		<?php 
		if( $fechaReporte->format('j/n/Y')== $fechaReporte->format('j/n/Y') && ( $_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 || $_COOKIE['ckPower']==4 )  ){
			?> <button class="btn btn-warning btn-outline btn-lg" id="btnCajaCerrar"><i class="icofont icofont-money-bag"></i> Cerrar caja</button> <?php
		}
		?>
		</div>
	</div>
	<?php
}else{
	?>
	<div class="container-fluid row ">
		<div class="col-xs-12 col-md-7">
			<div class="alert alert-morado container-fluid" role="alert">
				<div class="col-xs-4 col-sm-2 col-md-3">
					<img src="images/ghost.png" alt="img-responsive" width="100%">
				</div>
				<div class="col-xs-8">
					<strong>Alerta</strong> <p>No se encuentra ninguna caja aperturada.</p>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-5">
					<?php 
					if( $_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 || $_COOKIE['ckPower']==4 ){
						if( date('Y-m-d')== $_GET['fecha'] ){
							?>
							<div class="text-center">
							<button class="btn btn-morado btn-outline btn-lg" id="btnCajaAbrir"><i class="icofont icofont-coins"></i> Aperturar Caja</button>
							</div>
							<?php
						}
					} ?>
				</div>
	</div>
	<?php
}


if( isset($_GET['cuadre']) ){ ?>
<div class="col-xs-12 col-sm-6 text-center">
	<p><strong>Sessión de: </strong> <?php echo $row['usuNombres']; ?></p>
	<p><strong>Apertura:</strong> S/ <span id="spanApertura" ><?= str_replace(",", '', number_format($row['cuaApertura'],2)); ?></span></p>
	<p><strong>Fecha & Hora:</strong> <?php $fechaN= new DateTime($row['fechaInicio']); echo $fechaN->format('j/n/Y g:i a'); ?></p>
</div>
<div class="col-xs-12 col-sm-6 text-center">
	<p></p>
	<p><strong>Cierre con:</strong> S/ <span id="spanCierrev3" ><?= str_replace(",", '', number_format($row['cuaCierre'],2)); ?></span></p>
	<p><strong>Fecha & Hora:</strong> <?php if($row['fechaFin']=='0000-00-00 00:00:00'){ echo 'Sin cerrar aún';}else{ $fechaN= new DateTime($row['fechaFin']); echo $fechaN->format('j/n/Y g:i a'); } ?></p>
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
