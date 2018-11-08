<?php 
//echo $_POST['jdata'][0]['descripProd'];

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$idCliente='';
$log = mysqli_query($conection,"SELECT idCliente from Cliente where cliDNI ='".$_POST['jCliente'][0]['dniCli']."';");
$row = mysqli_fetch_array($log, MYSQLI_ASSOC);

// Primero creamos o verificamos si el cliente ya se encuentra en las BD;
if( count($row)===1 ){
	$idCliente=$row['idCliente'];
}else{
	$newCliente= "INSERT INTO `cliente`(`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`, `cliFijo`, `cliCalificacion`) VALUES (null,'".$_POST['jCliente'][0]['apellidosCli']."','".$_POST['jCliente'][0]['nombreCli']."','".$_POST['jCliente'][0]['dniCli']."','".$_POST['jCliente'][0]['direccionCli']."','".$_POST['jCliente'][0]['correoCli']."','".$_POST['jCliente'][0]['celularCli']."','".$_POST['jCliente'][0]['fijoCli']."',0)";
	$conection->query($newCliente);
	
	$log2 = mysqli_query($conection,"SELECT idCliente from Cliente where cliDNI ='".$_POST['jCliente'][0]['dniCli']."';");
	$row2 = mysqli_fetch_array($log2, MYSQLI_ASSOC);
	$idCliente=$row2['idCliente'];
}

// Abrimos un nuevo préstamo para ésta operación
$sql= "call inicializarPrestamoV3 (".$idCliente.", '".$_POST['capital']."' , now(), 0, ".$_POST['jusuario']['idUsuario']." )";
if ($llamadoSQL = $conection->query($sql)) {
	while ($resultado = $llamadoSQL->fetch_row()) {
		$idPrestamo= $resultado[0]; 
		// Luego de crear el préstamo base, empezaremos con el ingreso de préstamo Vs Productos
		for ($i=0; $i < count($_POST['jdata']) ; $i++) {
			//echo "\n". $_POST['jdata'][$i]['descripProd'];
			$sqlProd.= "call insertarProductov3 ('".$_POST['jdata'][$i]['descripProd']."', ".$_POST['jdata'][$i]['capitalProd'].", ".$_POST['jdata'][$i]['intereProd'].", '".$_POST['jdata'][$i]['fechaIngProd']."', '".$_POST['jdata'][$i]['extraProd']."', {$idCliente}, ".$_POST['jusuario']['idUsuario'].", {$idPrestamo}, ".$_POST['jdata'][$i]['cantProd']." );";
			//echo $sqlProd;
			if ($i==count($_POST['jdata'])-1){
//				echo $sqlProd;
				while(mysqli_more_results($conection)) // Le dice que agergue más espacio al mysqli
					{ mysqli_next_result($conection); }
				if(!mysqli_multi_query($conection,$sqlProd)){ echo mysqli_error($conection) /*Imprime error*/;}
				
			}
		}
		
	}
	//$llamadoSQL->close();
}
echo $idCliente;
//header('Location: );

 ?>

 