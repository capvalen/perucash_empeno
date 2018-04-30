<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call actualizarDatosCliente('".$_POST['appe']."', '".$_POST['nnomb']."', 
	'".$_POST['ddni']."', '".$_POST['ddirecion']."', '".$_POST['eemail']."', '".$_POST['ccelular']."', '".$_POST['ttelf']."', ".$_POST['iid']."  );");

/* cerrar la conexión */
mysqli_close($conection);

echo 1;
?>