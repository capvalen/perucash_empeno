<?php 
require("conkarl.php");
$filas=array();
if (! isset($_GET['fecha'])) { //si existe lista fecha requerida
	$_GET['fecha']=date('Y-m-d');
}
$sql = mysqli_query($conection,"call cajaActivaHoy('".$_GET['fecha']."');"); // $_GET['fecha']

// if (!$sql) { ////codigo para ver donde esta el error
//     printf("Error: %s\n", mysqli_error($conection));
//     exit();
// }
$numRow = mysqli_num_rows($sql);
$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

if($numRow>=1){ ?>
<div class="col-xs-12 col-sm-6 text-center">
	<p><strong>Sessión activa de: </strong> <?php echo $row['usuNombres']; ?></p>
	<p><strong>Aperturó con:</strong> S/ <span id="spanApertura" ><?= str_replace(",", '', number_format($row['cuaApertura'],2)); ?></span></p>
	<p><strong>Hora:</strong> <?php echo $row['fechaInicio'],2; ?></p>
</div>
<?php if($_COOKIE['ckidUsuario']==$row['idUsuario'] ){ ?>
<div class="col-xs-12 col-sm-6 text-center">
	<button class="btn btn-warning btn-outline btn-lg" id="btnCajaCerrar"><i class="icofont icofont-money-bag"></i> Cerrar caja</button>
</div>
<?php } }else{ 
	if( $_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 ){?>
	<div class="col-xs-12 col-sm-6 text-center">
	<?php if( date('Y-m-d')== $_GET['fecha'] ){ ?>
		<button class="btn btn-azul btn-outline btn-lg" id="btnCajaAbrir"><i class="icofont icofont-coins"></i> Aperturar Caja</button>
	<?php } ?>
	</div>
	<div class="col-xs-12 col-sm-6 ">
	
	</div>
<?php } }
/*while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}*/
mysqli_close($conection); //desconectamos la base de datos

?>
