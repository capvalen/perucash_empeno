<?php 
include 'conkarl.php';
// if( $_POST["obs"] ==''){
// 	$obs ='';
// }else{
// 	$obs = $_COOKIE['ckAtiende'].' dice: '.$_POST["obs"];
// }

$sql= "CALL insertAlmacenv3(".$_POST['idProducto'].", ".$_POST['estante'].", ".$_POST['piso'].", '".$_POST['zona']."', ".$_COOKIE['ckidUsuario'].", '".$obs."' );";
$consultaDepos = $conection->prepare($sql);
$consultaDepos ->execute();
$resultadoDepos = $consultaDepos->get_result();
$numLineaDeposs=$resultadoDepos->num_rows;
$rowDepos = $resultadoDepos->fetch_array(MYSQLI_ASSOC);
echo $rowDepos['idCubicaje'] ;
$consultaDepos->fetch();
$consultaDepos->close();
 ?>