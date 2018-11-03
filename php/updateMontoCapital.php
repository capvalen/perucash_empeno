<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
if( mysqli_query($conection,"UPDATE `prestamo_producto` SET `preCapital`={$_POST['monto']} WHERE `idProducto`={$_POST['idProd']};")){
   echo true;
}else{
   echo false;
}
//echo "call updateMovimientoAceptar(".$_POST['idRepo'].", '".$_SESSION['Atiende']."' );";
/* cerrar la conexión */
mysqli_close($conection);


?>