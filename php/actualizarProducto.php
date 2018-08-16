<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$log="call actualizarProducto('".$_POST['idProd']."', '".$_POST['nombre']."', ".$_POST['monto'].", ".$_POST['intereses'].", ".$_POST['idCLi'].", ".$_POST['activo'].", ".$_POST['cantidad']."  );";
//echo $log;
if(mysqli_query($conection,$log)){
   echo true;
/* cerrar la conexión */
}else{
   echo false;
}
mysqli_close($conection);
?>