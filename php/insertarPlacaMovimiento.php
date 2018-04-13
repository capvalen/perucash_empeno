<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

//Comprobar si la placa existe

$sqlPlaca= mysqli_query($conection, "SELECT * FROM `placas` where placaSerie='".$_POST['placa']."';");

$placas = mysqli_fetch_array($sqlPlaca, MYSQLI_ASSOC);
$numPlaca = mysqli_num_rows($sqlPlaca);
if( $numPlaca==1){
	//si existe registrado ya la placa, solo registrar el movimiento
	$idPlaca = $placas['idPlaca'];
	
	$sqlCochera =  "call insertarCocheraMovimiento({$idPlaca}, {$_POST['precio']}, {$_POST['fecha']}, {$_COOKIE['ckidUsuario']})";
	echo $sqlCochera;
}

if( $numPlaca==0){
	//NO existe registrado ya la placa, solo registrar el movimiento
}

?>