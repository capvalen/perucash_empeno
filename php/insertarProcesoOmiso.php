<?php 
session_start();

if( !isset($_SESSION['access_token'])){header('HTTP/1.1 Error de protección a la BD'); die('err1');}else{
	$existeCajaAntes = require_once("comprobarCajaHoy.php");
	if($existeCajaAntes==0){ header('HTTP/1.1 Error de protección a la BD'); die('err3'); }else{
		include 'conkarl.php';
		header('Content-Type: text/html; charset=utf8');

		if( $_POST['tipo']==74){
			$sql= "call insertarProcesoOmiso (0, ".$_POST['tipo'].", ".$_POST['valor'].", '".$_POST['obs']."', ".$_COOKIE['ckidUsuario'].", ".$_POST['moneda'].", {$_POST['porInteres']} );";
		}else{
			$sql= "call insertarProcesoOmiso (0, ".$_POST['tipo'].", ".$_POST['valor'].", '".$_POST['obs']."', ".$_COOKIE['ckidUsuario'].", ".$_POST['moneda'].", 0 );";

		}
		//echo $sql;
		if ($conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.

			echo true;
		}else{echo mysql_error( $conection);}

} } //fin de else que valida si hay tocken de facebook
?>