<?php 
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
//session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$log = mysqli_query($conection,"select * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where usuNick = '".$_POST['user']."' and usuPass='".md5($_POST['pws'])."' and u.idSucursal= ".$_POST['offi']." and usuPoder= ".$_POST['modalidad']." and usuActivo=1;");

$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
if ($row['idUsuario']>=1){
	$_SESSION['idSucursal']=$row['idSucursal'];
	$_SESSION['Sucursal']=$row['sucLugar'];
	$_SESSION['Atiende']=$row['usuNombres'];
	$_SESSION['nomCompleto']=$row['usuNombres'].', '.$row['usuApellido'];
	$_SESSION['Power']=$row['usuPoder'];
	$_SESSION['idUsuario']=$row['idUsuario'];
	$_SESSION['oficina']=$_POST['offi'];

	$expira=time()+60*60*24;
	setcookie('ckidSucursal', $row['idSucursal']);
	setcookie('ckSucursal', $row['sucLugar']);
	setcookie('ckAtiende', $row['usuNombres']);
	setcookie('cknomCompleto', $row['usuNombres'].', '.$row['usuApellido']);
	setcookie('ckPower', $row['usuPoder']);
	setcookie('ckidUsuario', $row['idUsuario']);
	setcookie('ckoficina', $_POST['offi']);
	echo "Welcome guy!";
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexión */
mysqli_close($conection);

 ?>