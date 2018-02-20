<?php 
session_start();
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");


$sql= "call dejarMensaje ('".$_POST['mensaje']."',".$_POST['idProducto'].",".$_POST['idUser'].")";


if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	 echo 1;

}else{echo null;}


?>