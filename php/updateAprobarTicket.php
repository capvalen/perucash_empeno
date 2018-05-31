<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

//echo "call updateAprobarTicket(".$_POST['idTick'].", {$_COOKIE['ckidUsuario']} );";
$filas=array();
if( mysqli_query($conection,"call updateAprobarTicket(".$_POST['idTick'].", {$_COOKIE['ckidUsuario']} );")){
	echo true;
}else{
	echo false;
}

/* cerrar la conexión */
mysqli_close($conection);

?>