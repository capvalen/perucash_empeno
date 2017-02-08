<?php 
session_start();
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$sql= "call insertarUsuario ('".$_POST['nombre']."','".$_POST['apellido']."','".$_POST['nick']."','".$_POST['passw']."', ".$_POST['nivel']." , ".$_POST['sucursal'].")";

//echo $sql;
$conection->query($sql);
	/* obtener el array de objetos */
	
		echo 1; //Retorna los resultados via post del procedure
	
	/* liberar el conjunto de resultados */

 ?>