<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

//Comprobar si la placa existe

$sqlPlaca= mysqli_query($conection, "SELECT * FROM `cocheraregistros` where movPlaca='".$_POST['placa']."' and idProceso= 27 and movActivo=1 ;");

$placas = mysqli_fetch_array($sqlPlaca, MYSQLI_ASSOC);
$numPlaca = mysqli_num_rows($sqlPlaca);

if( $numPlaca>=1){
	//si existe registrado ya la placa, solo registrar el movimiento
	$idPlaca = $placas['idPlaca'];
	
	//$sqlCochera =  "call insertarCocheraMovimiento({$idPlaca}, {$_POST['precio']}, {$_POST['fecha']}, {$_COOKIE['ckidUsuario']})";
	echo "Ya registrado";
}

if( $numPlaca==0){
	//NO esta registrado, solo registrar el movimiento
	$sqlRegistro="INSERT INTO `cocheraregistros`(`idCocheraReg`, `idVehiculo`, `movPlaca`, `movPrecio`, `movFecha`, `idUser`, `idProceso`, `movActivo`) 
	select null, tv.idTipoVehiculo , '{$_POST['placa']}',0,'{$_POST['fecha']}', {$_COOKIE['ckidUsuario']},27,1
	from `tipovehiculo` tv where vehDescripcion = '{$_POST['vehiculo']}'	;";
	$resultadoRegistro=$cadena->query($sqlRegistro);
	echo "Cochera lista";
}

?>