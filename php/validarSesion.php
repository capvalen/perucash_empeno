<?php 
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
include 'variablesGlobales.php';
//session_start();
//header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
$clavePrivada= 'Es sencillo hacer que las cosas sean complicadas, pero difícil hacer que sean sencillas. Friedrich Nietzsche';



//$log = mysqli_query($conection,"SELECT * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where idFacebook = '".$_POST['idFace']."' and usuActivo =1; ");
$sentencia = "SELECT * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where usuNick = '{$_POST['user']}' and usuPass=md5('{$_POST['pws']}') and usuActivo=1; ";
//echo $sentencia;die();
$log = mysqli_query($conection,$sentencia);

$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
if ($row['idUsuario']>=1){
	// $_SESSION['idSucursal']=$row['idSucursal'];
	// $_SESSION['Sucursal']=$row['sucLugar'];
	// $_SESSION['Atiende']=$row['usuNombres'];
	// $_SESSION['nomCompleto']=$row['usuNombres'].', '.$row['usuApellido'];
	// $_SESSION['Power']=$row['usuPoder'];
	// $_SESSION['idUsuario']=$row['idUsuario'];
	// $_SESSION['oficina']=$_POST['offi'];

	if( $row['usuActivo']=='1' ){

		$local='/';
		$expira=0; //time()+60*60*8 //cookie para 8 horas
		setcookie('ckidSucursal', $row['idSucursal'], $expira, $local);
		setcookie('ckSucursal', $row['sucLugar'], $expira, $local);
		setcookie('ckAtiende', $row['usuNombres'], $expira, $local);
		setcookie('cknomCompleto', $row['usuNombres'].', '.$row['usuApellido'], $expira, $local);
		setcookie('ckPower', $row['usuPoder'], $expira, $local);
		setcookie('ckidUsuario', $row['idUsuario'], $expira, $local);
		setcookie('ckoficina', 'algo', $expira, $local); // $_POST['offi']
		setcookie('ckCorreo', $row['usuEMail'], $expira, $local);
		
		//$sqlConf = mysqli_query( $conection,  "SELECT * FROM `configuraciones`");
		//$rowConf = mysqli_fetch_array($sqlConf, MYSQLI_ASSOC);
		//setcookie('ckInventario', $rowConf['inventarioActivo'], $expira, $local);
		//setcookie('ckSucursal', $rowConf['local'], $expira, $local);
		echo 'concedido';
	}else{
		echo 'inhabilitado';
	}

	

	//echo $row['idUsuario'];
	
}else{
	echo 'nada';
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexión */
mysqli_close($conection);

?>