<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

if( $_POST["obs"] ==''){
	$obs ='';
}else{
	$obs = $_COOKIE['ckAtiende'].' dice: '.$_POST["obs"];
}

// Averiguamos qué se esta pagando.
$sqlPre= "SELECT idTipoProceso FROM `tickets` where idTicket={$_POST['idTick']};";
echo $sqlPre;
$consulta = $conection->prepare($sqlPre);
$consulta->execute();
$resultado = $consulta->get_result();
$numLineas=$resultado->num_rows;
$row = $resultado->fetch_array(MYSQLI_ASSOC);
$ticket .='#'.$row['idTicket'].'<br />';
$consulta->fetch();
$consulta->close();






//echo "call updateCobrarTicket(".$_POST['idTick'].", {$_COOKIE['ckidUsuario']}, '{$obs}');";
/*$filas=array();
if( mysqli_query($conection,"call updateCobrarTicket(".$_POST['idTick'].", {$_COOKIE['ckidUsuario']}, '{$obs}');")){
	echo true;
}else{
	echo false;
}*/

/* cerrar la conexión */
mysqli_close($conection);

?>