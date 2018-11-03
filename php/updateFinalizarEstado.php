<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call updateFinalizarEstado(".$_POST['idProd'].", '".$_POST['nombreUser']."' , ".$_POST['monto'].");");
//echo "call updateFinalizarEstado(".$_POST['idProd'].", '".$_SESSION['Atiende']."' , ".$_POST['monto'].");";

/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>