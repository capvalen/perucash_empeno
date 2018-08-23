<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$log="call actualizarCaja(".$_POST['idCaj'].", ".$_POST['pproceso'].", '".$_POST['ffecha']."', ".$_POST['vvalor'].", '".$_POST['oobs']."', ".$_POST['mmoneda'].", ".$_POST['aactivo']."  );";
//echo $log;
if(mysqli_query($conection,$log)){
   echo true;
/* cerrar la conexión */
}else{
   echo false;
}
mysqli_close($conection);
?>