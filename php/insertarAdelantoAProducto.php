<?php 
session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call insertarAdelantoAProducto(".$_POST['idProd'].", ".$_POST['monto'].",' ".$_SESSION['Atiende']."' );");
//echo "call insertarAdelantoAProducto(".$_POST['idProd'].", ".$_POST['monto'].",' ".$_SESSION['Atiende']."' );";

/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>