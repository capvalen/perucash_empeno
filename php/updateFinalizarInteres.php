<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call updateFinalizarInteres(".$_POST['idProd'].", ".$_POST['monto']." );");
//echo "call updateFinalizarInteres(".$_POST['idProd'].", ".$_POST['monto']." );";

/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>