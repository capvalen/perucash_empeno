<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$log="UPDATE `usuario` SET `usuActivo` = b'{$_POST['estado']}' WHERE `usuario`.`idUsuario` = {$_POST['idUser']};";
//echo $log;
if(mysqli_query($conection,$log)){
   echo true;
/* cerrar la conexión */
}else{
   echo false;
}
mysqli_close($conection);
?>