<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$sql= "call eliminarProductoBD (".$_POST['idProd']." )";

//echo $sql;
$conection->query($sql); //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* liberar el conjunto de resultados */
// $llamadoSQL->close();

 ?>