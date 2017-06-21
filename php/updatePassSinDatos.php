<?php 
session_start();
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');



$sql = "call updatePassSinDatos( '".$_POST['texto']."',".$_SESSION['idUsuario'].");";



if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	while ($resultado = $llamadoSQL->fetch_row()) {
		echo $resultado[0]; //Retorna los resultados via post del procedure
	}
	/* liberar el conjunto de resultados */
	$llamadoSQL->close();
}else{echo mysql_error( $conection);}



?>