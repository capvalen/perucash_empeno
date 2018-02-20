<?php 
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");

$sql= "call cambiarEstadoProducto (".$_POST['estado'].", ".$_POST['idProd'].", '".$_POST['usuario']."', '".$_POST['comentario']."')";
echo $sql;


if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	 echo 1;

}else{echo null;}


?>