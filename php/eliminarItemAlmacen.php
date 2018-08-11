<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

//echo "call salidaAlmacen ( ".$_POST["idProd"].", ".$_COOKIE["ckidUsuario"].", '".$_POST["obs"]."' , ".$_POST["cubo"].");";

if( mysqli_query($conection,"call salidaAlmacen ( ".$_POST["idProd"].", ".$_COOKIE["ckidUsuario"].", '".$_POST["obs"]."' , ".$_POST["cubo"].");")){
	echo true;
}else{
	echo false;
}

/* cerrar la conexión */
mysqli_close($conection);

?>