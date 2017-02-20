<?php 
session_start();
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$sql= "call insertarSucursalNueva ('".$_POST['nombre']."','".$_POST['direccion']."' )";

//echo $sql;
$conection->query($sql);
	/* obtener el array de objetos */
	
		echo 1; //Retorna los resultados via post del procedure
	
	/* liberar el conjunto de resultados */

 ?>