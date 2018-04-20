<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$productos= $_POST['jsonProductos'];
//echo count($productos);
for ($i=0; $i < count($productos) ; $i++) { 
	$sql= "call insertarCompraSoloV3 ('".$productos[$i]['nombre']."', ".$productos[$i]['montoDado'].", '".$productos[$i]['fechaIngreso']."', '".$productos[$i]['observaciones']."' , ".$_COOKIE['ckidUsuario'].", '".$productos[$i]['fechaRegistro']."', ".$productos[$i]['tipoProducto'].")";
	//echo $sql;
	if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
		// obtener el array de objetos 
		while ($resultado = $llamadoSQL->fetch_row()) {
			echo $resultado[0]; //Retorna los resultados via post del procedure
		}
		// liberar el conjunto de resultados
		$llamadoSQL->close();
	}else{echo mysql_error( $conection);}
}

 ?>