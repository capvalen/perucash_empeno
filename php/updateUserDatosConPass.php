<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call updateUserDatosConPass('".$_POST['nombre']."', '".$_POST['apellido']."' , '".$_POST['nick']."' , '".$_POST['pass']."' , '".$_POST['poder']."' , ".$_POST['sucursal'].") , '".$_POST['idUser']."' ;");

/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>