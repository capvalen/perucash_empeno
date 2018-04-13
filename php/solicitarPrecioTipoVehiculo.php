<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$sql=  "SELECT `vehPrecio` FROM `tipovehiculo` WHERE `idTipoVehiculo`={$_POST['idTipo']};";
//echo $sql;
$llamadoSQL = $conection->query($sql); //Ejecución mas compleja con retorno de dato de sql del procedure.

// obtener el array de objetos 
$resultado = $llamadoSQL->fetch_row();
echo $resultado[0]; //Retorna los resultados via post del procedure

// liberar el conjunto de resultados
$llamadoSQL->close();



 ?>