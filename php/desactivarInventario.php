<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$log = mysqli_query($conection,"UPDATE `configuraciones` SET `inventarioActivo`=0 WHERE 1");

echo 1;
/* cerrar la conexión */
mysqli_close($conection);

?>