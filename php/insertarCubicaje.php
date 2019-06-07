<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

if( $_POST["obs"] ==''){
	$obs ='';
}else{
	$obs = $_COOKIE['ckAtiende'].' dice: '.$_POST["obs"];
}

$sqlAnt="UPDATE `cubicaje` SET `cuaVigente` = '0' WHERE idProducto={$_POST['idProducto']};";

$cadena->query($sqlAnt);

$sql= "CALL insertarCubicaje(".$_POST['idProducto'].", ".$_POST['proceso'].", ".$_COOKIE['ckidUsuario']." , '".$obs."', ".$_POST['estante'].", ".$_POST['piso'].", ".$_POST['seccion']." );";
//echo $sql;
$consultaDepos = $conection->prepare($sql);
$consultaDepos ->execute();
$resultadoDepos = $consultaDepos->get_result();
$numLineaDeposs=$resultadoDepos->num_rows;
$rowDepos = $resultadoDepos->fetch_array(MYSQLI_ASSOC);
echo $rowDepos['idCubicaje'] ;
$consultaDepos->fetch();
$consultaDepos->close();
 ?>