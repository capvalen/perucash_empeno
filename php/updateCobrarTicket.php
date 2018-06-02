<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

if( $_POST["obs"] ==''){
	$obs ='';
}else{
	$obs = $_COOKIE['ckAtiende'].' dice: '.$_POST["obs"];
}

//echo "call updateCobrarTicket(".$_POST['idTick'].", {$_COOKIE['ckidUsuario']}, '{$obs}');";
$filas=array();
if( mysqli_query($conection,"call updateCobrarTicket(".$_POST['idTick'].", {$_COOKIE['ckidUsuario']}, '{$obs}');")){
	echo true;
}else{
	echo false;
}

/* cerrar la conexión */
mysqli_close($conection);

?>