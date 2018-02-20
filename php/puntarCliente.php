<?php 
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");

$sql= "call puntarCliente (".$_POST['estrellas'].",".$_POST['idCli'].")";


if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	 echo 1;

}else{echo null;}


?>