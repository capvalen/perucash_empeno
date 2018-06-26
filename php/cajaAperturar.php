<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

if($_POST['obs']<>''){
	$obs = '<p>'.$_COOKIE['ckAtiende'].' dice '.$_POST['obs'].'</p>';
}else{$obs ='';}

$sql= "call cajaAperturar (".$_COOKIE['ckidUsuario'].", ".$_POST['monto'].", '".$_POST['obs']."' )";
echo $sql;
if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	// obtener el array de objetos 
	echo true;
	
}else{echo mysql_error( $conection);}


 ?>