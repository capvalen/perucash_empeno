<?php
//session_start();
unset($_COOKIE['ckidUsuario']);
unset($_COOKIE['ckidSucursal']);
unset($_COOKIE['ckSucursal']);
unset($_COOKIE['ckAtiende']);
unset($_COOKIE['cknomCompleto']);
unset($_COOKIE['ckPower']);
unset($_COOKIE['ckoficina']);

setcookie("ckidUsuario", "", time() - 3600, '/');
setcookie("ckidSucursal", "", time() - 3600, '/');
setcookie("ckSucursal", "", time() - 3600, '/');
setcookie("ckAtiende", "", time() - 3600, '/');
setcookie("cknomCompleto", "", time() - 3600, '/');
setcookie("ckPower", "", time() - 3600, '/');
setcookie("ckoficina", "", time() - 3600, '/');
if ($_SESSION['Sucursal']) {
	session_destroy();
	
}
header("location:..\index.php");
?>