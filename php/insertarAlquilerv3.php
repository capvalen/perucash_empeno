<?php
session_start();

if( !isset($_COOKIE['ckidUsuario'])){header('HTTP/1.1 Error de protección a la BD'); die('err1');}else{
	$existeCajaAntes = require_once("comprobarCajaHoy.php");
	if($existeCajaAntes==0){ header('HTTP/1.1 Error de protección a la BD'); die('err3'); }else{
		require('conkarl.php');
		header('Content-Type: text/html; charset=utf8');

		/** Reportes de error */
		/* error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('America/Lima'); */

		$cliente= $_POST['jsonCliente'];

		//comprobar si existe el cliente en la BD con su dni
		$sqlClienteExiste="SELECT idCliente FROM `Cliente` where cliDni='{$cliente[0]['dniCli']}';";

		$llamadoCliente = $conection->query($sqlClienteExiste);
		$resCliente = $llamadoCliente->fetch_row();
		$numRow = $llamadoCliente->num_rows;
		if( $numRow>0){
			$idCliente=$resCliente[0];
			$llamadoCliente->close();
			//insertarProductos($idCliente, $conection );
			//echo 'si existe su id es: '.$idCliente;
			$sqlUpdDatos="UPDATE `Cliente` SET `cliApellidos`=trim('{$cliente[0]['apellidoCli']}'),`cliNombres`=trim('{$cliente[0]['nombresCli']}'), `cliDireccion`='{$cliente[0]['direccionCli']}',`cliCorreo`= '{$cliente[0]['celularCli']}',`cliCelular`='{$cliente[0]['celularCli']}',`cliFijo`='{$cliente[0]['fijoCli']}' WHERE `idCliente` = {$idCliente} ;";
			$resultadoUpdDatos=$cadena->query($sqlUpdDatos);
			

			
		}else{
			//No existe insertar cliente nuevo
			$sqlCliente="call insertClienteV3(trim('{$cliente[0]['apellidoCli']}'), trim('{$cliente[0]['nombresCli']}'), '{$cliente[0]['dniCli']}', '{$cliente[0]['direccionCli']}', '{$cliente[0]['correoCli']}', '{$cliente[0]['celularCli']}', '{$cliente[0]['fijoCli']}' );";
			
			$llamadoClienteNew = $conection->prepare($sqlCliente);
			$llamadoClienteNew->execute();
			$resultado3 = $llamadoClienteNew->get_result();
			$numRowCli = $resultado3->num_rows;
			$resClienteNew = $resultado3->fetch_array(MYSQLI_BOTH);
			//print_r($resClienteNew);
			if( $numRowCli>0){
				$idCliente=$resClienteNew[0];
				$llamadoClienteNew -> close();
				//insertarProductos($idCliente, $conection);
			}
		}

		//echo "hola 7. \n";
			$sqlPre= "call inicializarPrestamoV3({$idCliente}, '{$_POST['total']}', '".date('Y-m-d H:i')."', 28, {$_COOKIE['ckidUsuario']} );";
			//echo $sqlPre;
			// Iniciamos préstamo
			$consulta = $conection->prepare($sqlPre);
			$consulta->execute();
			$resultado = $consulta->get_result();
			$numLineas=$resultado->num_rows;
			$row = $resultado->fetch_array(MYSQLI_ASSOC);
			$idPrestamo =$row['idnuevoPrestamo'];
			//$consulta->free();
			$consulta->fetch();
			$consulta->close();
			//echo $idPrestamo;


			//Insertar préstamo_producto y desembolso
			$productos= $_POST['jsonProductos'];
			
		for ($i=0; $i < count($productos) ; $i++) {
			$tipo= $productos[$i]['tipoProducto'];
			if($tipo=='1' || $tipo=='11' || $tipo=='42' ){
				$placa = ' Placa: '.$productos[$i]['placa'];
			}else{
				$placa='';
			}
			
			$sqlProducto= "call insertarProductov3 ('".$productos[$i]['nombre'].$placa."', '".$productos[$i]['montoDado']."', ".$productos[$i]['interes'].", '".$productos[$i]['fechaRegistro']."', '".$productos[$i]['observaciones']."', ".$idCliente." , ".$_COOKIE['ckidUsuario'].", ".$idPrestamo.", ".$productos[$i]['cantidad'].",".$productos[$i]['tipoProducto'].");";
			//echo $sqlProducto;

			$consultaProd = $conection->prepare($sqlProducto);
			$consultaProd->execute();
			$resultado4 = $consultaProd->get_result();
			$numLineas=$resultado4->num_rows;
			$rowN = $resultado4->fetch_array(MYSQLI_ASSOC);
			$idProd=$rowN['idProd'];
			$consultaProd->fetch();
			$consultaProd->close();
			
			if($tipo=='1' || $tipo=='11' || $tipo=='42' ){
				$sqlCochera ="call insertarPlaca('".$productos[$i]['placa']."', {$tipo}, {$idProd});";
				
				$consultaCoche = $conection->prepare($sqlCochera);
				$consultaCoche->execute();
				$resultadoCoche = $consultaCoche->get_result();
				//$rowCoche = $consultaCoche->fetch_array(MYSQLI_ASSOC);
				//print_r($row);
				//$consultaCoche->fetch();
				$consultaCoche->fetch();
				$consultaCoche->close();
			}

		}

		echo $idCliente;


		function insertarProductos($idCliente, $cadena){

		}
} } //fin de else que valida si hay tocken de facebook y si hay caja
?>