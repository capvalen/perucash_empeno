<?php 
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");
$sql= "call insertarUsuario ('".$_POST['nombres']."', '".$_POST['apellidos']."', '".$_POST['nick']."', '".$_POST['pass']."', ".$_POST['poder']." );";
//echo $sql;
if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	while ($resultado = $llamadoSQL->fetch_row()) {
		echo $resultado[0]; //Retorna los resultados via post del procedure
	}
	/* liberar el conjunto de resultados */
	$llamadoSQL->close();
}else{echo '0';}
?>