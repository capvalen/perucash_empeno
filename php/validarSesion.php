<?php 
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
//session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
$clavePrivada= 'Es sencillo hacer que las cosas sean complicadas, pero difícil hacer que sean sencillas. Friedrich Nietzsche';


$log = mysqli_query($conection,"select * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where usuNick = '".$_POST['user']."' and usuPass='".md5($_POST['pws'])."' and usuActivo=1;");

$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
if ($row['idUsuario']>=1){
	// $_SESSION['idSucursal']=$row['idSucursal'];
	// $_SESSION['Sucursal']=$row['sucLugar'];
	// $_SESSION['Atiende']=$row['usuNombres'];
	// $_SESSION['nomCompleto']=$row['usuNombres'].', '.$row['usuApellido'];
	// $_SESSION['Power']=$row['usuPoder'];
	// $_SESSION['idUsuario']=$row['idUsuario'];
	// $_SESSION['oficina']=$_POST['offi'];

	$expira=time()+60*60*12;
	setcookie('ckidSucursal', $row['idSucursal'], $expira, '/');
	setcookie('ckSucursal', $row['sucLugar'], $expira, '/');
	setcookie('ckAtiende', $row['usuNombres'], $expira, '/');
	setcookie('cknomCompleto', $row['usuNombres'].', '.$row['usuApellido'], $expira, '/');
	setcookie('ckPower', $row['usuPoder'], $expira, '/');
	setcookie('ckidUsuario', $row['idUsuario'], $expira, '/');
	setcookie('ckoficina', $_POST['offi'], $expira, '/');

	
	$sqlConf = mysqli_query( $conection,  "SELECT * FROM `configuraciones`");
	$rowConf = mysqli_fetch_array($sqlConf, MYSQLI_ASSOC);
	setcookie('ckInventario', $rowConf['inventarioActivo'], $expira, '/');
	setcookie('ckSucursal', $rowConf['local'], $expira, '/');

	echo $row['idUsuario'];
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexión */
mysqli_close($conection);

?>