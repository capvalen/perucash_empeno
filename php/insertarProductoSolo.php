<?php 
session_start();
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$sql= "call insertarProductoSolo ('".$_POST['productoNombre']."', ".$_POST['montoentregado'].", ".$_POST['interes'].", ".$_POST['montopagar'].", '".$_POST['fechainicial']."', '".$_POST['feachavencimiento']."', '".$_POST['observaciones']."' , ".$_SESSION['idUsuario'].", ".$_POST['idCl']." , ".$_SESSION['idSucursal'].")";
//echo $sql;
if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	while ($resultado = $llamadoSQL->fetch_row()) {
		echo $resultado[0]; //Retorna los resultados via post del procedure
	}
	/* liberar el conjunto de resultados */
	$llamadoSQL->close();
}else{echo mysql_error( $conection);}
 ?>