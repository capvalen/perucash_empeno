<?php
session_start();
unset($_COOKIE['ckidUsuario']);
unset($_COOKIE['ckidSucursal']);
unset($_COOKIE['ckSucursal']);
unset($_COOKIE['ckAtiende']);
unset($_COOKIE['cknomCompleto']);
unset($_COOKIE['ckPower']);
unset($_COOKIE['ckoficina']);

$ruta = '/huancavelica';

setcookie("ckidUsuario", "", time() - 3600, $ruta);
setcookie("ckidSucursal", "", time() - 3600, $ruta);
setcookie("ckSucursal", "", time() - 3600, $ruta);
setcookie("ckAtiende", "", time() - 3600, $ruta);
setcookie("cknomCompleto", "", time() - 3600, $ruta);
setcookie("ckPower", "", time() - 3600, $ruta);
setcookie("ckoficina", "", time() - 3600, $ruta);
setcookie("ckInventario", "", time() - 3600, $ruta);
setcookie("ckCorreo", "", time() - 3600, $ruta);

if ($_SESSION['access_token']) {
	session_destroy();
	
}
//header("location:..\index.php");
header('Location: https://perucash.com'.$ruta.'/index.php');
?>