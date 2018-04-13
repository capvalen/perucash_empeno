<?php
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$productos= $_POST['jsonProductos'];
$cliente= $_POST['jsonCliente'];

//comprobar si existe el cliente en la BD con su dni
$sqlClienteExiste="SELECT idCliente FROM `Cliente` where cliDni='{$cliente[0]['dniCli']}';";

$llamadoCliente = $conection->query($sqlClienteExiste);
$resCliente = $llamadoCliente->fetch_row();
$numRow = $llamadoCliente->num_rows;
if( $numRow>0){
	$idCiente=$resCliente[0];
	insertarProductos();
	//echo 'si existe su id es: '.$idCiente;
}else{
	//No existe insertar cliente nuevo
	$sqlCliente="call insertClienteV3('{$cliente[0]['apellidoCli']}', '{$cliente[0]['nombresCli']}', '{$cliente[0]['dniCli']}', '{$cliente[0]['direccionCli']}', '{$cliente[0]['correoCli']}', '{$cliente[0]['celularCli']}', '{$cliente[0]['fijoCli']}' );";
	$llamadoClienteNew = $conection->query($sqlCliente);
	$resClienteNew = $llamadoClienteNew->fetch_row();
	$numRowCli = $llamadoClienteNew->num_rows;
	//print_r($resClienteNew);
	if( $numRowCli>0){
		$idCiente=$resClienteNew[0];
		insertarProductos();
	}
	
}



function insertarProductos(){
	//Insertar préstamo_producto y desembolso
for ($i=0; $i < count($productos) ; $i++) { 
	$sqlProducto= "call insertarProductov3 ('".$productos[$i]['nombre']."', ".$productos[$i]['montoDado'].", ".$productos[$i]['interes'].", '".$productos[$i]['fechaRegistro']."', '".$productos[$i]['observaciones']."', ".$idCiente." , ".$_COOKIE['ckidUsuario'].", '".$productos[$i]['fechaRegistro']."', ".$productos[$i]['tipoProducto'].")";
	//echo $sqlProducto;
	/*if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
		// obtener el array de objetos 
		while ($resultado = $llamadoSQL->fetch_row()) {
			echo $resultado[0]; //Retorna los resultados via post del procedure
		}
		// liberar el conjunto de resultados
		$llamadoSQL->close();
	}else{echo mysql_error( $conection);}*/
}
}

 ?>