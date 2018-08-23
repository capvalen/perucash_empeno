<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$log="UPDATE `usuario` SET `usuPoder` = '{$_POST['poder']}' WHERE `usuario`.`idUsuario` = {$_POST['idUser']};";
//echo $log;
if(mysqli_query($conection,$log)){
   echo true;
/* cerrar la conexión */
}else{
   echo false;
}
mysqli_close($conection);
?>