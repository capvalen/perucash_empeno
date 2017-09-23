<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$sql= "call insertarAmortizacionTodo (".$_POST['idDese'].", ".$_POST['montInicial'].", ".$_POST['montInteres']." , ".$_POST['montPago'].", ".$_POST['idUser'].", ".$_POST['idProd'].", '".$_POST['usuario']."', ".$_POST['idSuc']." )";
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