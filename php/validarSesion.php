<?php 
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
//session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$log = mysqli_query($conection,"select * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where usuNick = '".$_POST['user']."' and usuPass=md5('".$_POST['pws']."') and usuActivo=1;");
//echo "select * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where usuNick = '".$_POST['user']."' and usuPass=md5('".$_POST['pws']."') and usuActivo=1;";
$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
if ($row['idUsuario']>=1){
/*	$_SESSION['idSucursal']=$row['idSucursal'];
	$_SESSION['Sucursal']=$row['sucLugar'];
	$_SESSION['Atiende']=$row['usuNombres'];
	$_SESSION['nomCompleto']=$row['usuNombres'].', '.$row['usuApellido'];
	$_SESSION['Power']=$row['usuPoder'];
	$_SESSION['idUsuario']=$row['idUsuario'];
	$_SESSION['oficina']=$row['offi'];*/

	
	setcookie('ckidSucursal', $row['idSucursal'], time()+60*60*24, '/');
	setcookie('ckSucursal', $row['sucLugar'], time()+60*60*24, '/');
	setcookie('ckAtiende', $row['usuNombres'], time()+60*60*24, '/');
	setcookie('cknomCompleto', $row['usuNombres'].', '.$row['usuApellido'], time()+60*60*24, '/');
	setcookie('ckPower', $row['usuPoder'], time()+60*60*24, '/');
	setcookie('ckidUsuario', $row['idUsuario'], time()+60*60*24, '/');
	setcookie('ckoficina', $row['offi'], time()+60*60*24, '/');
	setcookie('ckcliente', $_POST['user'], time()+60*60*24, '/');
	setcookie('ckllave', md5($_POST['pws']), time()+60*60*24, '/');
	//echo "Welcome guy!";
	echo $row['idUsuario'];
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexión */
mysqli_close($conection);

 ?>