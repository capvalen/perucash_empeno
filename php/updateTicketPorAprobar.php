<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

//echo "call updateTicketPorAprobar(".$_POST['idTick'].", {$_POST['monto']}, '{$_POST["obs"]}');";

$filas=array();
if( mysqli_query($conection,"call updateTicketPorAprobar(".$_POST['idTick'].", {$_POST['monto']}, '{$_POST["obs"]}');")){
	echo true;
}else{
	echo false;
}

/* cerrar la conexión */
mysqli_close($conection);

?>