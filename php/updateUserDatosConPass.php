<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"UPDATE `usuario` SET `usuNombres`='".$_POST['nombre']."',`usuApellido`='".$_POST['apellido']."',`usuPass`=md5('".$_POST['pass']."')
WHERE idUsuario = '".$_POST['idUser']."'");

/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>