<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call updateMovimientoRetirar(".$_POST['idRepo'].", '".$_POST['idUser']."' );");
//echo "call updateMovimientoAceptar(".$_POST['idRepo'].", '".$_SESSION['Atiende']."' );";
/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>