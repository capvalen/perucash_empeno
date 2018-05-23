<?php
require('conkarl.php');
header('Content-Type: text/html; charset=utf8');


$cliente= $_POST['jsonCliente'];

//comprobar si existe el cliente en la BD con su dni
$sqlClienteExiste="SELECT idCliente FROM `Cliente` where cliDni='{$cliente[0]['dniCli']}';";

 $llamadoCliente = $conection->query($sqlClienteExiste);
 $resCliente = $llamadoCliente->fetch_row();
 $numRow = $llamadoCliente->num_rows;
if( $numRow>0){
	$idCliente=$resCliente[0];
	$llamadoCliente->close();
	insertarProductos($idCliente, $conection );
	//echo 'si existe su id es: '.$idCliente;
}else{
	//No existe insertar cliente nuevo
	$sqlCliente="call insertClienteV3('{$cliente[0]['apellidoCli']}', '{$cliente[0]['nombresCli']}', '{$cliente[0]['dniCli']}', '{$cliente[0]['direccionCli']}', '{$cliente[0]['correoCli']}', '{$cliente[0]['celularCli']}', '{$cliente[0]['fijoCli']}' );";
	
	$llamadoClienteNew = $conection->query($sqlCliente);
	$resClienteNew = $llamadoClienteNew->fetch_row();
	$numRowCli = $llamadoClienteNew->num_rows;
	//print_r($resClienteNew);
	if( $numRowCli>0){
		$idCliente=$resClienteNew[0];
		$llamadoClienteNew -> close();
		insertarProductos($idCliente, $conection);
	}
}


function insertarProductos($idCliente, $conn){
	$sqlPre= "call inicializarPrestamoV3({$idCliente}, '{$_POST['total']}', '".date('Y-m-d H:i')."', 4, {$_COOKIE['ckidUsuario']} );";
	echo $sqlPre;
	// Iniciamos préstamo
	$consulta = $conn->query($sqlPre);
	/*$consulta->execute();
	$resultado = $consulta->get_result();*/
	$numLineas=$resultado->num_rows;
	$row = $resultado->fetch_array(MYSQLI_ASSOC);
	$idPrestamo =$row['idnuevoPrestamo'];
	$consulta->fetch();
	$consulta->close();


	//Insertar préstamo_producto y desembolso
	$productos= $_POST['jsonProductos'];
	
for ($i=0; $i < count($productos) ; $i++) {
	$tipo= $productos[$i]['tipoProducto'];
	if($tipo=='1' || $tipo=='11' || $tipo=='42' ){
		$placa = ' Placa: '.$productos[$i]['placa'];
	}else{
		$placa='';
	}
	

	$sqlProducto= "call insertarProductov3 ('".$productos[$i]['nombre'].$placa."', ".$productos[$i]['montoDado'].", ".$productos[$i]['interes'].", '".$productos[$i]['fechaRegistro']."', '".$productos[$i]['observaciones']."', ".$idCliente." , ".$_COOKIE['ckidUsuario'].", ".$idPrestamo.", ".$productos[$i]['cantidad'].",".$productos[$i]['tipoProducto'].");";
	//echo $sqlProducto;

	$consultaProd = $conn->prepare($sqlProducto);
	$consultaProd->execute();
	$resultadoProd = $consultaProd->get_result();
	$numLineas=$resultadoProd->num_rows;
	$row = $resultadoProd->fetch_array(MYSQLI_ASSOC);
	$idProd=$row['idProd'];
	$consultaProd->fetch();
	$consultaProd->close();


	
	if($tipo=='1' || $tipo=='11' || $tipo=='42' ){
		$sqlCochera ="call insertarPlaca('".$productos[$i]['placa']."', {$tipo}, {$idProd});";
		
		$consultaCoche = $conn->prepare($sqlCochera);
		$consultaCoche->execute();
		$resultadoCoche = $consultaCoche->get_result();
		$rowCoche = $resultadoCoche->fetch_array(MYSQLI_ASSOC);
		//print_r($row);
		$consultaCoche->fetch();
		$consultaCoche->close();
	}

}

echo $idCliente;
}

?>