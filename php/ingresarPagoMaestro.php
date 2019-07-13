<?php 
session_start();

if( !isset($_SESSION['access_token'])){header('HTTP/1.1 Error de protección a la BD'); die('err1');}else{
	$existeCajaAntes = require_once("comprobarCajaHoy.php");
	if($existeCajaAntes==0){ header('HTTP/1.1 Error de protección a la BD'); die('err3'); }else{

	include 'conkarl.php';
	header('Content-Type: text/html; charset=utf8');

	$cant=0;
	if ( isset($_POST['cantidad']) ){
		$cant=$_POST['cantidad'];
	}
	//Cambiar el SP de ingresarPagoMaestro, nuevo campo cant INT
	$sql= "call ingresarPagoMaestro (".$_POST['idProd'].", ".$_POST['quePago'].", ".$_POST['cuanto']." , '".$_POST['fecha']."', ".$_COOKIE['ckidUsuario'].", '".$_POST['obs']."', ".$_POST['moneda'].", {$cant} )";
	//echo $sql;
	if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
		/* obtener el array de objetos */
		while ($resultado = $llamadoSQL->fetch_row()) {
			echo $resultado[0]; //Retorna los resultados via post del procedure
		}
		/* liberar el conjunto de resultados */
		$llamadoSQL->close();
	}else{echo mysql_error( $conection);}
} } //fin de else que valida si hay tocken de facebook y si hay caja
 ?>