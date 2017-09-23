<?php 
session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call updateFinalizarPrestamo(".$_POST['idProd'].", ".$_POST['monto']." , ".$_POST['idUser'].", ".$_POST['valor'].", ".$_POST['idSuc'].", '".$_POST['usuario']."', ".$_POST['paga']."  );");
//echo "call updateFinalizarPrestamo(".$_POST['idProd'].", ".$_POST['monto']." , ".$_POST['idUser'].", ".$_POST['valor'].", ".$_POST['idSuc'].", '".$_POST['usuario']."' );";

/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>